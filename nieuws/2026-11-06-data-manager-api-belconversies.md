---
title: Data Manager API ondersteunt nu belconversies voor Google Ads
slug: data-manager-api-belconversies
excerpt: Sinds de release van 4 november 2026 kun je via de Data Manager API ook telefoongesprekken als conversie naar Google Ads sturen.
bron_naam: Google Ads Developer Blog
bron_url: https://ads-developers.googleblog.com/2026/11/data-manager-api-now-supports-google.html
---
Google heeft op 4 november 2026 een nieuwe versie van de Data Manager API uitgebracht. Daarmee kunnen adverteerders voortaan ook belconversies uit Google Ads via deze API aanleveren. Tegelijk komen er betere foutmeldingen bij het uploaden en een nieuw veld voor lead-ID's.

## Wat verandert er?

De Data Manager API is de koppeling waarmee je eigen data, zoals conversies uit je CRM, naar Google-producten stuurt. Belconversies vielen daar tot nu toe buiten. Vanaf deze release stuur je een gesprek mee via het nieuwe veld `call_info`, met het starttijdstip van het gesprek en een gehasht telefoonnummer.

Daarnaast laat de API nu per foutmelding of waarschuwing zien welke regels in je upload het probleem veroorzaken. Je hoeft dus niet meer de hele batch na te lopen om één fout record te vinden.

Ook is er een veld `lead_id` toegevoegd. Google meldt er wel bij dat attributie op basis van `lead_id` en `match_id` nog niet beschikbaar is. Het veld bestaat dus al, maar doet voor de meting nog niets.

Google noemt geen landen of regio's in de aankondiging. Het gaat om een technische API-release, dus er is geen reden om aan te nemen dat Nederland is uitgezonderd, maar dat staat er niet expliciet.

## Wat moet je nu doen?

- Komen veel van je leads binnen via de telefoon en verwerk je die nu handmatig of via een omweg, laat je developer of tracking-partij kijken of belconversies via de Data Manager API kunnen lopen. De [handleiding voor belconversies](https://developers.google.com/data-manager/api/devguides/events/google-ads/call) beschrijft de velden.
- Gebruik je de API al voor conversie-uploads, dan helpen de nieuwe foutindexen om mislukte records gericht te herstellen en opnieuw te versturen.
- Bouw nog niet op `lead_id` voor attributie. Wacht tot Google meldt dat dit werkt.
