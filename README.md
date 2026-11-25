# svv-theme — stijnvanveelen.nl

Eigen WordPress-thema voor stijnvanveelen.nl — profielsite (Growth Marketing Manager), met een aparte pagina voor ondernemers.

- Licht ontwerp (v1.0): wit en lichtgrijs, blauw #1F5FD6 als accent, geel #FFC34D als stip. Logo: blauwe cirkel met S en gele stip (`svv_logo()`).
- Lettertypes lokaal (Outfit voor koppen, Inter voor tekst) — geen Google Fonts-verzoeken.
- Homepage (`front-page.php`): hero met portret, werkgebieden, werkwijze, eigen platforms, over mij, contact met **CV opvragen** (opt-in, geen vrije download).
- Ondernemers (scan + Ads-testlab): `/ads-testlab/`, alleen gelinkt vanuit de footer. Dienstpagina's blijven bestaan voor vindbaarheid, maar staan niet meer in het menu.
- Uitrol: Hostinger Git → `public_html/wp-content/themes/svv-theme` vanaf `main` (er is geen staging). Grote wijzigingen gaan via een pull request; terugdraaien kan met een revert-commit op `main`.

## Instellen na activeren
1. Weergave → Customizer → **Stijn van Veelen**: agendalink voor een bakkie (optioneel), KvK, e-mailadres op de site, LinkedIn. Geen prijzen op de site; tarieven gaan telefonisch.
2. Instellingen → Lezen: statische voorpagina (een lege pagina "Home").
3. Dienstpagina's: slugs staan in `svv_services()` in `functions.php`. Bestaande slugs behouden.
4. Samenvatting (excerpt) van een dienstpagina = de intro onder de titel.

## Formulieren
`inc/forms.php`: aanvraag (chat), bakkie (kennismaken: digitaal of aan tafel, e-mail, optioneel telefoon), nieuwsbrief (Ads-testlab) en cv (naam, e-mail, organisatie, verplichte toestemming; het CV wordt nooit automatisch verstuurd) → mail aan beheerder + privé-bericht "Aanvragen" in wp-admin.
Hook `svv_lead_created` voor de Bigin-koppeling. Honeypot + nonce + max. 5 per uur per IP.

## Meetpunten (dataLayer)
- `svv_chip` (svv_question) — klik op vraagknop
- `svv_lead_submit` (svv_type) — formulier verstuurd

## AI-assistent (staat sinds v1.0 niet meer op de homepage; code blijft beschikbaar)
`inc/bot.php`. Instellingen → **AI-assistent**: aan/uit, aanbieder (Anthropic of OpenAI), model, API-sleutel (of `SVV_BOT_KEY` in wp-config.php), daglimiet.
- Kennis = dienstpagina's (titel, samenvatting, tekst) + vragen op de homepage (`svv_faqs()`) + vaste regels (geen tarieven, geen verzinsels, derde persoon).
- REST `POST /wp-json/svv/v1/chat`. Grenzen: 20 vragen per uur per bezoeker, 8 per gesprek, daglimiet.
- "Laat Stijn zelf reageren": e-mailveld verschijnt, gesprek gaat mee in de aanvraag.
- Uit of geen sleutel: het venster werkt als gewoon contactformulier.
- Meetpunten: `svv_bot_question`, `svv_bot_handoff`.

## Nieuws
`inc/nieuws.php`: berichttype **Nieuws** (wp-admin → Nieuws), archief `/nieuws/`, los van het blog. Kort nieuws over platforms; per bericht een **Bron** (naam + URL, zijbalk in de editor) die onder de tekst verschijnt. Link "Nieuws" in het menu onder Meer. Het eerste bericht (ChatGPT Ads: Target CPA/CPC) wordt na de uitrol automatisch gepubliceerd.

### Dagelijkse nieuwscheck
Een geplande taak (ma–vr ~6:10) zet nieuwe berichten als Markdown in `nieuws/` (met front matter: title, slug, excerpt, bron_naam, bron_url) en pusht naar `main`. Na de uitrol maakt `inc/nieuws-import.php` er één keer een **concept** van. `nieuws/_log.md` houdt bij wat al behandeld is.

## Snelheid
Op homepage, pagina's en nieuws laden de oude Divi-plugins (Bloom, Divi-addons, a3 Lazy Load, CF7, Kraft) niets (`svv_trim_assets()`). Blogberichten houden alles.

## Cache
Na elke uitrol leegt het thema één keer de LiteSpeed-cache (`inc/content-fixes.php`, op basis van de bestandsdatum van de themabestanden). Eenmalige inhoudscorrecties staan in hetzelfde bestand.

## SEO (25-11-2026, `inc/seo.php`)
- `svv_seo_pages()`: per slug een nieuwe paginatitel (eenmalig via content-fixes), SEO-titel en meta description. Deze meta's gaan voor Yoast.
- Titels: " – Stijn van Veelen" valt weg als de titel daarmee langer wordt dan 60 tekens. Categorieën en tags krijgen een eigen titel en meta.
- Nieuws zonder meta → samenvatting. Schema: `NewsArticle` bij nieuws, `ProfilePage` + `Person` (jobTitle = `SVV_TAGLINE`) op de homepage, jobTitle bij de auteur in Yoast.
- Doorverwijzingen `svv_seo_redirects()`: dubbel WP Rocket-bericht en de oude TranslatePress-slug.
