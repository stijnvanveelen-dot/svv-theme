# svv-theme — stijnvanveelen.nl

Eigen WordPress-thema voor stijnvanveelen.nl (AI Ads-specialist).

- Donker ontwerp in de kleuren van het LinkedIn-profiel, logo A (ring met sparkle).
- Lettertypes lokaal (Inter, Source Serif 4) — geen Google Fonts-verzoeken.
- Uitrol: Hostinger Git → `public_html/wp-content/themes/svv-theme` (eerst staging).

## Instellen na activeren
1. Weergave → Customizer → **Stijn van Veelen**: prijzen, levertijd, pilotduur, KvK, beschikbaarheid.
2. Instellingen → Lezen: statische voorpagina (een lege pagina "Home").
3. Dienstpagina's: slugs staan in `svv_services()` in `functions.php`. Bestaande slugs behouden.
4. Samenvatting (excerpt) van een dienstpagina = de intro onder de titel.

## Formulieren
`inc/forms.php`: aanvraag, meetscan en nieuwsbrief → mail aan beheerder + privé-bericht "Aanvragen" in wp-admin.
Hook `svv_lead_created` voor de Bigin-koppeling. Honeypot + nonce + max. 5 per uur per IP.

## Meetpunten (dataLayer)
- `svv_chip` (svv_question) — klik op vraagknop
- `svv_lead_submit` (svv_type) — formulier verstuurd
