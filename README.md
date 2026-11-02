# svv-theme — stijnvanveelen.nl

Eigen WordPress-thema voor stijnvanveelen.nl (Freelance AI Ads specialist).

- Donker ontwerp in de kleuren van het LinkedIn-profiel, logo A (ring met sparkle).
- Lettertypes lokaal (Inter, Source Serif 4) — geen Google Fonts-verzoeken.
- Uitrol: Hostinger Git → `public_html/wp-content/themes/svv-theme` (eerst staging).

## Instellen na activeren
1. Weergave → Customizer → **Stijn van Veelen**: beschikbaarheid, agendalink voor een bakkie (optioneel), KvK, LinkedIn. Geen prijzen op de site; tarieven gaan telefonisch.
2. Instellingen → Lezen: statische voorpagina (een lege pagina "Home").
3. Dienstpagina's: slugs staan in `svv_services()` in `functions.php`. Bestaande slugs behouden.
4. Samenvatting (excerpt) van een dienstpagina = de intro onder de titel.

## Formulieren
`inc/forms.php`: aanvraag (chat), bakkie (kennismaken: digitaal of aan tafel, e-mail, optioneel telefoon) en nieuwsbrief (Ads-testlab) → mail aan beheerder + privé-bericht "Aanvragen" in wp-admin.
Hook `svv_lead_created` voor de Bigin-koppeling. Honeypot + nonce + max. 5 per uur per IP.

## Meetpunten (dataLayer)
- `svv_chip` (svv_question) — klik op vraagknop
- `svv_lead_submit` (svv_type) — formulier verstuurd
