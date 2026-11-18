---
title: Google tag wordt een Tag Manager-container, afwijkende installaties volgen in januari 2027
slug: google-tag-wordt-tag-manager-container
excerpt: Sinds 17 november 2026 is elke Google tag ook een Tag Manager-container; installaties die Google niet ondersteunt krijgen de upgrade in januari 2027.
bron_naam: Google Tag Manager Help, release notes
bron_url: https://support.google.com/tagmanager/answer/4620708?hl=en
---
Google voegt de Google tag (gtag.js) en Google Tag Manager samen. In de release notes van **17 november 2026** staat dat elke Google tag vanaf nu een Tag Manager-container is. Wie alleen de Google tag op de site heeft staan, voor bijvoorbeeld Google Ads-conversies of Google Analytics, kan daardoor de functies van Tag Manager gaan gebruiken. Voor installaties die Google niet ondersteunt volgt in **januari 2027** een automatische upgrade.

## Wat verandert er?

Google vat het zelf samen als: "We're bringing all the capabilities of Google Tag Manager to the Google tag." De belangrijkste punten uit de release notes:

- Een site met alleen de Google tag krijgt toegang tot tags beheren via de interface, debuggen en versiebeheer zoals in Tag Manager. Dat gebruik je als je wilt.
- Hoe de tag op de pagina werkt, verandert niet. Elke Google-bestemming houdt een eigen tag, dus bestaande instellingen en triggers op automatisch gemeten events blijven staan.
- Nieuwe installatiecodes zijn voortaan allemaal gelijk en bevatten het `config`-commando van gtag niet meer.
- De rechten van de Tag Manager-container gelden nu overal, ook voor wie data in Tag Assistant mag inzien.

Google noemt geen landen of regio's. Het gaat om een wijziging in de tag zelf, dus ook Nederlandse accounts krijgen ermee te maken.

## Wat moet je nu doen?

Check eerst of jouw installatie onder de niet-ondersteunde varianten valt. Google verwijst daarvoor naar een aparte [helppagina](https://support.google.com/tagmanager/answer/17231523) en raadt aan de opzet vóór januari 2027 aan te passen. Doe je dat niet, dan voert Google de upgrade zelf door en heb je minder grip op wat er verandert.

Loop daarnaast na wie toegang heeft tot je Google tag. Omdat de containerrechten nu ook voor de Google tag gelden, kan een collega of bureau ineens meer of minder zien dan je verwacht. Werk je met eigen code die op het `config`-commando leunt, test dan na een wijziging of je Google Ads-conversies nog binnenkomen. Een korte controle in Tag Assistant en in het conversieoverzicht van Google Ads is genoeg om dat te zien.
