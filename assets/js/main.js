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
