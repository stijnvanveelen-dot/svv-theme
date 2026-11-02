(function () {
	'use strict';

	// Mobiel menu
	var side = document.querySelector('.svv-side');
	var toggle = document.querySelector('.svv-menu-toggle');
	if (side && toggle) {
		toggle.addEventListener('click', function () {
			var open = side.classList.toggle('is-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	}

	// Vraagknoppen: toon antwoord in het gesprek, verberg de knop
	var chat = document.querySelector('.svv-chat');
	document.querySelectorAll('.svv-chip[data-ask]').forEach(function (chip) {
		chip.addEventListener('click', function () {
			var turn = document.querySelector('.svv-turn[data-turn="' + chip.getAttribute('data-ask') + '"]');
			if (!turn || !chat) return;
			turn.hidden = false;
			chat.appendChild(turn); // in klikvolgorde
			chip.hidden = true;
			if (window.dataLayer) {
				window.dataLayer.push({ event: 'svv_chip', svv_question: chip.textContent.trim() });
			}
			var next = document.querySelector('.svv-chip:not([hidden])');
			(next || document.querySelector('#vraag textarea')).focus({ preventScroll: true });
		});
	});

	// Nieuw gesprek: werkt als in ChatGPT. Op de homepage het gesprek leegmaken zonder
	// paginasprong; elders naar de homepage en daar het invoerveld klaarzetten.
	var isHome = document.body.classList.contains('home');
	var input = document.querySelector('#vraag textarea');
	function resetChat() {
		document.querySelectorAll('.svv-turn').forEach(function (t) { t.hidden = true; });
		document.querySelectorAll('.svv-chip[data-ask]').forEach(function (c) { c.hidden = false; });
		document.querySelectorAll('#vraag .svv-form__ok').forEach(function (el) { el.remove(); });
		if (input) { input.value = ''; }
		if (/[?&]verzonden=/.test(location.search) || location.hash) {
			history.replaceState(null, '', location.pathname);
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

	// Meetpunt: formulier verzonden (vóór redirect)
	document.querySelectorAll('.svv-form').forEach(function (form) {
		form.addEventListener('submit', function () {
			var type = (form.querySelector('[name="svv_type"]') || {}).value || 'aanvraag';
			if (window.dataLayer) {
				window.dataLayer.push({ event: 'svv_lead_submit', svv_type: type });
			}
		});
	});
})();
