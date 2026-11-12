---
title: Demand Gen regelt Display vanaf 1 december alleen nog per advertentiegroep
slug: demand-gen-display-alleen-op-advertentiegroep
excerpt: Vanaf de week van 1 december 2026 kun je Display in Demand Gen-campagnes niet meer op campagneniveau uitzetten via de API; dat gebeurt dan alleen per advertentiegroep.
bron_naam: Google Ads Developer Blog
bron_url: https://ads-developers.googleblog.com/2026/11/display-targeting-changes-in-demand-gen.html
---
Google trekt de Display-instelling in Demand Gen-campagnes recht. Vanaf de week van **1 december 2026** bepaal je alleen nog op advertentiegroepniveau of een Demand Gen-campagne op het Display-netwerk draait. De schakelaar op campagneniveau verdwijnt uit de Google Ads API. Google kondigde dit op 9 november aan op de Developer Blog.

## Wat verandert er?

Tot nu toe kon je Display op twee plekken regelen: op de campagne en in de advertentiegroep. Stond Display op campagneniveau uit, dan won die instelling van wat er in de advertentiegroep stond. Google wil van die dubbele laag af en trekt de API gelijk met de Google Ads-interface en Editor.

Concreet:

- Het campagneveld voor het Display-netwerk staat bij Demand Gen standaard aan. Probeer je het via de API uit te zetten, dan krijg je een foutmelding.
- Display regel je via de kanaalinstellingen (channel controls) van de advertentiegroep.
- Bestaande campagnes zet Google automatisch om. Advertentiegroepen die Display combineren met andere kanalen krijgen Display uit. Advertentiegroepen die alleen op Display mikten in een campagne waar Display uit stond, worden **gepauzeerd**.

Volgens Google verandert er daardoor niets aan waar bestaande campagnes vertoond worden. Google noemt geen landen of regio's; de wijziging zit in de API zelf.

## Wat moet je nu doen?

Werk je alleen in de interface, dan hoef je in principe niets te doen. Gebruik je scripts, een eigen koppeling of een tool die Demand Gen-campagnes aanmaakt of bewerkt, kijk die dan vóór eind november na:

- Stop met het uitzetten van Display op campagneniveau, anders lopen je aanroepen vanaf december vast.
- Zet Display voortaan uit in de kanaalinstellingen van elke advertentiegroep.
- Loop na de omzetting je Demand Gen-campagnes langs. Controleer per advertentiegroep of de kanalen kloppen met het bereik dat je wilt, en kijk of er groepen op pauze zijn gezet.

Het campagneveld staat straks overal op "aan", dus daaraan zie je niet meer welke campagnes echt op Display draaien. De advertentiegroep is vanaf dan de enige plek om dat te checken.
