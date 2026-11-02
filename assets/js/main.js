(function () {
	'use strict';

	function push(data) {
		if (window.dataLayer) { window.dataLayer.push(data); }
	}

	// Mobiel menu
	var side = document.querySelector('.svv-side');
	var toggle = document.querySelector('.svv-menu-toggle');
	if (side && toggle) {
		toggle.addEventListener('click', function () {
			var open = side.classList.toggle('is-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	}

	var isHome = document.body.classList.contains('home');
	var chat = document.querySelector('.svv-chat');
	var form = document.querySelector('#vraag form');
	var input = form ? form.querySelector('textarea') : null;
	var mail = form ? form.querySelector('input[type="email"]') : null;
	var sendBtn = form ? form.querySelector('.svv-send') : null;
	var actions = document.querySelector('.svv-actions');
	var cfg = window.svvBot || {};
	var bot = !!(cfg.on && cfg.url && form && input && chat);
	var history = []; // {role, content}
	var busy = false;

	// Vraagknoppen: toon het voorbereide antwoord in het gesprek, verberg de knop
	document.querySelectorAll('.svv-chip[data-ask]').forEach(function (chip) {
		chip.addEventListener('click', function () {
			var turn = document.querySelector('.svv-turn[data-turn="' + chip.getAttribute('data-ask') + '"]');
			if (!turn || !chat) return;
			turn.hidden = false;
			chat.appendChild(turn); // in klikvolgorde
			chip.hidden = true;
			var q = turn.querySelector('.svv-q');
			var a = turn.querySelector('.svv-a p');
			if (q && a) {
				history.push({ role: 'user', content: q.textContent.trim() });
				history.push({ role: 'assistant', content: a.textContent.trim() });
			}
			push({ event: 'svv_chip', svv_question: chip.textContent.trim() });
			var next = document.querySelector('.svv-chip[data-ask]:not([hidden])');
			(next || input).focus({ preventScroll: true });
		});
	});

	// --- AI-assistent ---------------------------------------------------------
	function esc(t) {
		return t.replace(/[&<>"']/g, function (c) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
		});
	}
	// Alleen links naar deze site klikbaar maken.
	function format(text) {
		var home = (cfg.home || location.origin + '/').replace(/\/$/, '');
		var html = esc(text);
		var re = new RegExp('(' + home.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + '[^\\s<)]*?)([.,;:!?]?)(?=\\s|$|<)', 'g');
		html = html.replace(re, function (m, url, punct) {
			var label = /#bakkie$/.test(url) ? 'Bakkie doen?' : /#nieuwsbrief$/.test(url) ? 'Ads-testlab' : (url.replace(home, '') || '/');
			return '<a href="' + url + '">' + label + '</a>' + punct;
		});
		return '<p>' + html.replace(/\n{2,}/g, '</p><p>').replace(/\n/g, '<br>') + '</p>';
	}
	function avatar() {
		var tpl = document.getElementById('svv-bot-avatar');
		var span = document.createElement('span');
		span.className = 'svv-avatar';
		if (tpl) { span.innerHTML = tpl.innerHTML; }
		return span;
	}
	function addTurn(question) {
		var turn = document.createElement('div');
		turn.className = 'svv-turn svv-turn--bot';
		var q = document.createElement('p');
		q.className = 'svv-q';
		q.textContent = question;
		var a = document.createElement('div');
		a.className = 'svv-a';
		var body = document.createElement('div');
		body.innerHTML = '<p class="svv-typing" aria-label="Aan het typen"><span></span><span></span><span></span></p>';
		a.appendChild(avatar());
		a.appendChild(body);
		turn.appendChild(q);
		turn.appendChild(a);
		chat.appendChild(turn);
		return body;
	}
	function setMode(handoff) {
		form.classList.toggle('is-handoff', handoff);
		if (mail) { mail.required = handoff; }
		input.required = !handoff;
		input.placeholder = handoff ? 'Nog iets toevoegen? Mag leeg blijven.' : 'Stel je vraag over ChatGPT Ads, Google, Meta of SEO…';
		if (sendBtn) { sendBtn.setAttribute('aria-label', handoff ? 'Versturen naar Stijn' : 'Vraag stellen'); }
	}
	function handoff() {
		setMode(true);
		if (actions) { actions.hidden = true; }
		push({ event: 'svv_bot_handoff' });
		if (mail) { mail.focus(); }
	}
	function transcript() {
		return history.map(function (m) {
			return (m.role === 'user' ? 'Bezoeker: ' : 'Assistent: ') + m.content;
		}).join('\n\n');
	}
	function ask(question) {
		busy = true;
		form.classList.add('is-busy');
		var body = addTurn(question);
		var msgs = history.concat([{ role: 'user', content: question }]);
		history.push({ role: 'user', content: question });
		push({ event: 'svv_bot_question' });
		fetch(cfg.url, {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			credentials: 'omit',
			body: JSON.stringify({ messages: msgs })
		}).then(function (r) {
			return r.json().then(function (j) { return { ok: r.ok, status: r.status, j: j }; });
		}).then(function (res) {
			if (!res.ok || !res.j || !res.j.answer) { throw res; }
			body.innerHTML = format(res.j.answer);
			history.push({ role: 'assistant', content: res.j.answer });
			if (actions) { actions.hidden = false; }
		}).catch(function (res) {
			var text = res && res.status === 429
				? 'Genoeg vragen voor nu. Laat je e-mailadres achter, dan reageert Stijn zelf binnen een werkdag.'
				: 'Daar kan de assistent nu even niet bij. Laat je e-mailadres achter, dan reageert Stijn zelf binnen een werkdag.';
			body.innerHTML = format(text);
			history.push({ role: 'assistant', content: text });
			handoff();
		}).then(function () {
			busy = false;
			form.classList.remove('is-busy');
			form.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
			if (!form.classList.contains('is-handoff')) { input.focus({ preventScroll: true }); }
		});
	}

	if (bot) {
		form.classList.add('is-bot');
		setMode(false);
		form.addEventListener('submit', function (e) {
			if (form.classList.contains('is-handoff')) {
				var t = form.querySelector('[name="svv_transcript"]');
				if (t) { t.value = transcript(); }
				return; // gewoon versturen naar Stijn
			}
			e.preventDefault();
			var q = input.value.trim();
			if (!q || busy) return;
			input.value = '';
			ask(q);
		});
		input.addEventListener('keydown', function (e) {
			if (e.key === 'Enter' && !e.shiftKey && !e.isComposing && !form.classList.contains('is-handoff')) {
				e.preventDefault();
				if (form.requestSubmit) { form.requestSubmit(); } else { form.dispatchEvent(new Event('submit', { cancelable: true })); }
			}
		});
		var hb = document.querySelector('[data-handoff]');
		if (hb) { hb.addEventListener('click', handoff); }
	}

	// --- Nieuw gesprek --------------------------------------------------------
	// Werkt als in ChatGPT. Op de homepage het gesprek leegmaken zonder paginasprong;
	// elders naar de homepage en daar het invoerveld klaarzetten.
	function resetChat() {
		document.querySelectorAll('.svv-turn--bot').forEach(function (t) { t.remove(); });
		document.querySelectorAll('.svv-turn').forEach(function (t) { t.hidden = true; });
		document.querySelectorAll('.svv-chip[data-ask]').forEach(function (c) { c.hidden = false; });
		document.querySelectorAll('#vraag .svv-form__ok').forEach(function (el) { el.remove(); });
		history = [];
		if (actions) { actions.hidden = true; }
		if (bot) { setMode(false); }
		if (input) { input.value = ''; }
		if (/[?&]verzonden=/.test(location.search) || location.hash) {
			window.history.replaceState(null, '', location.pathname);
		}
		if (side) { side.classList.remove('is-open'); }
		if (toggle) { toggle.setAttribute('aria-expanded', 'false'); }
		window.scrollTo({ top: 0, behavior: 'smooth' });
		if (input) { input.focus({ preventScroll: true }); }
	}
	document.querySelectorAll('[data-svv-new]').forEach(function (link) {
		link.addEventListener('click', function (e) {
			if (isHome) {
				e.preventDefault();
				resetChat();
			} else {
				try { sessionStorage.setItem('svv_new', '1'); } catch (err) {}
			}
		});
	});
	if (isHome && input) {
		var fresh = false;
		try { fresh = sessionStorage.getItem('svv_new') === '1'; sessionStorage.removeItem('svv_new'); } catch (err) {}
		if (fresh) { input.focus({ preventScroll: true }); }
	}

	// Spamfilter: tijd op de pagina meesturen (zie inc/forms.php)
	var loadedAt = Date.now();
	document.querySelectorAll('.svv-form').forEach(function (f) {
		f.addEventListener('submit', function () {
			var t = f.querySelector('[name="svv_t"]');
			if (t) { t.value = 'js-' + (Date.now() - loadedAt); }
		}, true);
	});

	// Meetpunt: formulier verzonden (vóór redirect)
	document.querySelectorAll('.svv-form').forEach(function (f) {
		f.addEventListener('submit', function (e) {
			if (e.defaultPrevented) return;
			var type = (f.querySelector('[name="svv_type"]') || {}).value || 'aanvraag';
			push({ event: 'svv_lead_submit', svv_type: type });
		});
	});
})();
