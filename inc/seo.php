<?php
/**
 * SEO (audit 25-11-2026): titels, meta descriptions, schema en positionering.
 *
 * - svv_seo_pages(): per slug een nieuwe paginatitel (H1, eenmalig in de database via content-fixes),
 *   een eigen SEO-titel en/of een meta description. De meta's hier gaan voor wat in Yoast staat.
 * - Titels: het achtervoegsel " - Stijn van Veelen" valt weg als de titel daardoor langer dan 60 tekens wordt.
 * - Nieuws zonder eigen meta: de samenvatting.
 * - Schema: NewsArticle bij nieuws, Person (Growth Marketing Manager) op de homepage en bij elke auteur.
 * - Oude/dubbele URL's: svv_seo_redirects().
 */

defined( 'ABSPATH' ) || exit;

define( 'SVV_SUFFIX', ' – Stijn van Veelen' );

/**
 * Per slug: title = nieuwe paginatitel (H1), seo = titel in Google, desc = meta description.
 *
 * @return array<string,array<string,string>>
 */
function svv_seo_pages() {
	return array(
		// Pagina's en archieven.
		'blog'                => array( 'desc' => 'Artikelen over Google Ads, Meta, LinkedIn, SEO en analytics. Praktische uitleg en handleidingen van growth marketing manager Stijn van Veelen.' ),
		'privacy-statement'   => array(
			'seo'  => 'Privacyverklaring – Stijn van Veelen',
			'desc' => 'Welke gegevens stijnvanveelen.nl gebruikt, waarvoor en hoe lang, welke cookies er zijn en welke rechten je hebt.',
		),
		'algemene-voorwaarden-voor-websites-ontwikkeld-door-new-road-digital' => array(
			'desc' => 'De algemene voorwaarden van New Road Digital voor websites die wij ontwikkelen: licenties, eigendom, aansprakelijkheid en toepasselijk recht.',
		),

		// Google Ads en Shopping.
		'adverteren-op-google-adwords-handleiding' => array(
			'title' => 'Adverteren op Google: handleiding voor Google Ads',
			'desc'  => 'Stap voor stap adverteren op Google: account aanmaken, campagne opzetten, zoekwoorden kiezen en je budget bewaken. Handleiding voor Google Ads (voorheen AdWords).',
		),
		'google-adverteren-adwords-kosten' => array(
			'title' => 'Wat kost adverteren in Google Ads (AdWords)?',
			'desc'  => 'Wat kost adverteren in Google Ads? Klikprijzen, dagbudget en wat je per lead of verkoop mag betalen, met rekenvoorbeelden om je eigen budget te bepalen.',
		),
		'aanmelden-google-shopping' => array(
			'title' => 'Aanmelden bij Google Shopping',
			'desc'  => 'Zo meld je je webshop aan bij Google Shopping: Merchant Center instellen, je productfeed koppelen aan Google Ads en je eerste producten live krijgen.',
		),
		'labels-toevoegen-in-google-merchant-center' => array(
			'title' => 'Labels toevoegen in Google Merchant Center',
			'seo'   => 'Labels toevoegen in Google Merchant Center: stappenplan' . SVV_SUFFIX,
			'desc'  => 'Met aangepaste labels stuur je in Google Shopping op seizoen, marge en ROAS. Zo voeg je ze in een paar stappen toe in Google Merchant Center.',
		),
		'roas-berekenen' => array(
			'seo'  => 'ROAS berekenen: uitleg en gratis rekentool' . SVV_SUFFIX,
			'desc' => 'Bereken je ROAS en ROI met de gratis tool op deze pagina. Wat een gezonde ROAS is en hoe je de hele funnel doorrekent, voor e-commerce en leadgeneratie.',
		),

		// Meta Ads-serie.
		'introductie-tot-meta-ads-wat-je-moet-weten-voordat-je-begint' => array(
			'title' => 'Introductie tot Meta Ads: wat je moet weten voordat je begint',
			'desc'  => 'Wat zijn Meta Ads en werken ze voor jouw bedrijf? De basis van adverteren op Facebook en Instagram: doelgroepen, formaten, budget en meten.',
		),
		'hoe-maak-je-een-meta-ads-account-aan-stapsgewijze-handleiding' => array(
			'title' => 'Een Meta Ads-account aanmaken: stap voor stap',
			'desc'  => 'Zo maak je een advertentieaccount aan bij Meta: Business Manager, pagina en betaalmethode koppelen, en klaar voor je eerste campagne.',
		),
		'hoe-bepaal-je-doelstellingen-voor-je-meta-advertentiecampagnes' => array(
			'title' => 'Doelstellingen bepalen voor je Meta-advertentiecampagnes',
			'desc'  => 'Bekendheid, verkeer of conversies? Zo kies je de juiste campagnedoelstelling in Meta en koppel je die aan meetbare doelen voor je bedrijf.',
		),
		'de-basis-van-campagnes-instellen-in-meta-ads-manager' => array(
			'title' => 'Campagnes instellen in Meta Ads Manager: de basis',
			'desc'  => 'Je eerste campagne in Meta Ads Manager: doelstelling kiezen, doelgroep en budget instellen en een advertentie maken die opvalt op Facebook en Instagram.',
		),
		'hoe-je-een-budget-en-biedstrategie-instelt-voor-meta-ads' => array(
			'title' => 'Zo stel je budget en biedstrategie in voor Meta Ads',
			'desc'  => 'Dagbudget of totaalbudget, laagste kosten of kostenlimiet? Zo kies je een budget en biedstrategie in Meta Ads die passen bij je doel en je marge.',
		),
		'een-doelgroep-creeren-met-metas-audience-targeting-tools' => array(
			'title' => 'Een doelgroep maken met de targetingtools van Meta',
			'desc'  => 'Kerndoelgroepen, aangepaste doelgroepen en lookalikes: zo bouw je in Meta een doelgroep die past bij je doel, zonder dat je budget versnippert.',
		),
		'visuele-content-creeren-voor-meta-advertenties-tips-en-tools' => array(
			'title' => 'Visuele content maken voor Meta-advertenties: tips en tools',
			'desc'  => 'Beelden en video\'s die werken op Facebook en Instagram: formaten, de eerste seconden, tekst in beeld en tools om snel goede advertenties te maken.',
		),
		'het-schrijven-van-effectieve-advertentieteksten-voor-meta' => array(
			'title' => 'Effectieve advertentieteksten schrijven voor Meta',
			'desc'  => 'Teksten die mensen laten stoppen met scrollen: tips voor de kop, de primaire tekst en de call-to-action in je Facebook- en Instagram-advertenties.',
		),
		'dynamische-advertenties-en-catalogusbeheer-in-meta' => array(
			'title' => 'Dynamische advertenties en catalogusbeheer in Meta',
			'desc'  => 'Laat Meta automatisch de juiste producten tonen: zo zet je een productcatalogus op, koppel je je feed en richt je dynamische advertenties in.',
		),
		'het-testen-en-optimaliseren-van-meta-advertenties-a-b-testing' => array(
			'title' => 'Meta-advertenties testen en optimaliseren met A/B-tests',
			'desc'  => 'Zo zet je een A/B-test op in Meta Ads Manager: wat je test, hoe lang en met welk budget, en hoe je de winnaar herkent zonder op toeval te sturen.',
		),
		'hoe-je-de-prestaties-van-meta-advertenties-meet-en-analyseert' => array(
			'title' => 'Zo meet en analyseer je de prestaties van Meta-advertenties',
			'desc'  => 'Welke cijfers in Meta Ads Manager tellen echt? CPM, CTR, kosten per resultaat en ROAS uitgelegd, en hoe je er je campagnes mee bijstuurt.',
		),
		'conversiegericht-adverteren-met-meta-pixel-wat-is-het-en-hoe-werkt-het' => array(
			'title' => 'Meta Pixel: wat is het en hoe werkt het?',
			'desc'  => 'Wat de Meta Pixel meet, hoe je hem installeert en hoe je conversies gebruikt om je Facebook- en Instagram-advertenties beter te laten presteren.',
		),
		'retargeting-op-meta-hoe-je-ingeschakelde-gebruikers-terugwint' => array(
			'title' => 'Retargeting op Meta: zo haal je eerdere bezoekers terug',
			'desc'  => 'Bezoekers die niet kochten opnieuw bereiken op Facebook en Instagram: zo zet je retargeting op in Meta, met doelgroepen, frequentie en voorbeelden.',
		),
		'geavanceerde-targeting-lookalike-audiences-en-gedragsdata' => array(
			'title' => 'Geavanceerde targeting: lookalike audiences en gedragsdata',
			'desc'  => 'Bereik mensen die lijken op je beste klanten. Zo werken lookalike audiences en gedragsdata in Meta, en zo stel je ze in zonder budget te verspillen.',
		),
		'het-gebruik-van-automatisering-en-machine-learning-in-meta-ads' => array(
			'title' => 'Automatisering en machine learning in Meta Ads',
			'desc'  => 'Advantage+, automatische plaatsingen en biedstrategieën: zo gebruik je de automatisering van Meta Ads slim, en wanneer je beter zelf stuurt.',
		),

		// LinkedIn.
		'linkedin-ads-hoe-zet-je-deze-succesvol-in' => array(
			'desc' => 'Zo zet je LinkedIn Ads in voor B2B-leads: targeting op functie en bedrijf, de beste advertentievormen, budget en hoe je de resultaten meet.',
		),
		'linkedin-lead-gen-campagne-opzetten' => array(
			'title' => 'Een LinkedIn Lead Gen-campagne opzetten',
			'desc'  => 'Leads verzamelen zonder landingspagina: zo zet je stap voor stap een Lead Gen-campagne op in LinkedIn, met formulier, doelgroep en budget.',
		),

		// WordPress, SEO-plug-ins en techniek.
		'in-5-stappen-je-eigen-wordpress-blog-opzetten' => array(
			'title' => 'In 5 stappen je WordPress-site online zetten',
			'desc'  => 'Je eigen WordPress-website online zetten in 5 stappen: domein en hosting kiezen, WordPress installeren, een thema instellen en je eerste pagina live.',
		),
		'wordpress-installeren-handleiding' => array(
			'desc' => 'WordPress installeren bij je hostingbedrijf of lokaal op je computer: stap voor stap, van database tot eerste login. Binnen een uur aan de slag.',
		),
		'google-maps-in-wordpress-site' => array(
			'title' => 'Google Maps in je WordPress-site plaatsen',
			'desc'  => 'Zo plaats je een Google Maps-kaart op je WordPress-site: met een embedcode, een blok of een plug-in, en wat je regelt voor privacy en laadtijd.',
		),
		'website-meertalig-mogelijkheden' => array(
			'seo'  => 'Website meertalig maken? De mogelijkheden op een rij' . SVV_SUFFIX,
			'desc' => 'Een website meertalig maken: de drie mogelijkheden op een rij, met de voor- en nadelen voor onderhoud, kosten en vindbaarheid in andere talen.',
		),
		'wordpress-meertalig-multi-language-maken-met-trabslatepress' => array(
			'title' => 'WordPress meertalig maken met TranslatePress',
			'slug'  => 'wordpress-meertalig-maken-met-translatepress',
			'desc'  => 'Je WordPress-site in meerdere talen met TranslatePress: installeren, vertalen in de visuele editor, vertaalde URL\'s en wat je regelt voor SEO.',
		),
		'wordpress-meertalig-maken-met-translatepress' => array(
			'desc' => 'Je WordPress-site in meerdere talen met TranslatePress: installeren, vertalen in de visuele editor, vertaalde URL\'s en wat je regelt voor SEO.',
		),
		'clonable-website-meertalig-maken' => array(
			'desc' => 'Je website meertalig maken met Clonable: hoe het werkt, wat het kost en waar je op let bij vertaalde URL\'s, metadata en SEO in andere talen.',
		),
		'functies-van-de-yoast-seo-plug-in-voor-wordpress' => array(
			'title' => 'De beste functies van Yoast SEO voor WordPress',
			'desc'  => 'Yoast SEO kan meer dan een groen bolletje. De functies die echt helpen: titels en meta\'s, XML-sitemaps, schema, interne links en redirects.',
		),
		'hoe-gebruik-je-de-all-in-one-seo-pack-plug-in-voor-je-wordpress-site' => array(
			'title' => 'Hoe gebruik je All in One SEO voor je WordPress-site?',
			'desc'  => 'All in One SEO installeren en instellen: de setupwizard, titels en meta\'s, sitemaps en de functies die het verschil maken voor je WordPress-site.',
		),
		'een-gids-voor-het-gebruiken-van-rank-math-seo-plug-in' => array(
			'title' => 'Rank Math SEO gebruiken: een gids',
			'desc'  => 'Rank Math installeren en instellen in WordPress: de setupwizard, de belangrijkste functies en tips om er meer uit te halen dan alleen groene vinkjes.',
		),
		'waarom-kiezen-voor-de-seopress-plug-in-voor-je-wordpress-website' => array(
			'title' => 'Waarom kiezen voor SEOPress voor je WordPress-website?',
			'desc'  => 'SEOPress is een complete SEO-plug-in zonder reclame in je dashboard. Wat hij kan, hoe hij zich verhoudt tot Yoast en voor wie hij de beste keus is.',
		),
		'het-inzetten-van-the-seo-framework-plug-in-voor-optimaal-zoekmachinegebruik' => array(
			'title' => 'The SEO Framework inzetten voor een beter vindbare site',
			'desc'  => 'The SEO Framework is een lichte SEO-plug-in voor WordPress. Wat hij standaard regelt, welke instellingen ertoe doen en voor wie hij geschikt is.',
		),
		'een-overzicht-van-de-premium-seo-pack-plug-in' => array(
			'title' => 'Een overzicht van de plug-in Premium SEO Pack',
			'desc'  => 'Wat doet Premium SEO Pack voor je WordPress-site? Een overzicht van de functies, de instellingen die ertoe doen en voor wie de plug-in geschikt is.',
		),
		'hoe-je-met-wp-meta-seo-je-wordpress-site-verbeterd' => array(
			'title' => 'Zo verbeter je je WordPress-site met WP Meta SEO',
			'desc'  => 'Met WP Meta SEO pas je titels, meta\'s en alt-teksten in bulk aan. Zo zet je de plug-in in om je WordPress-site sneller beter vindbaar te maken.',
		),
		'de-voordelen-van-het-gebruiken-van-squirrly-seo-voor-wordpress' => array(
			'title' => 'De voordelen van Squirrly SEO voor WordPress',
			'desc'  => 'Squirrly SEO helpt ook zonder technische kennis je WordPress-site vindbaarder te maken. De voordelen, de nadelen en voor wie de plug-in past.',
		),
		'de-belangrijkste-functies-van-de-seo-squirrly-plug-in' => array(
			'title' => 'De belangrijkste functies van Squirrly SEO',
			'desc'  => 'Van zoekwoordonderzoek tot contentchecks: de functies van Squirrly SEO die je echt gebruikt, en hoe je ze instelt in WordPress.',
		),
		'structuur-gegevens-invoegen-met-schema-pro-voor-wordpress' => array(
			'title' => 'Gestructureerde gegevens toevoegen met Schema Pro voor WordPress',
			'desc'  => 'Rich results in Google met Schema Pro: zo voeg je gestructureerde gegevens toe aan je WordPress-pagina\'s zonder zelf JSON-LD te schrijven.',
		),
		'hoe-je-structured-data-invoegt-met-wp-seo-structured-data-schema' => array(
			'title' => 'Structured data toevoegen met WP SEO Structured Data Schema',
			'desc'  => 'Opvallen in Google met sterren, FAQ\'s of evenementen: zo voeg je met WP SEO Structured Data Schema gestructureerde gegevens toe aan WordPress.',
		),
		'waarom-je-de-broken-link-checker-plug-in-moet-gebruiken' => array(
			'title' => 'Waarom je de Broken Link Checker-plug-in moet gebruiken',
			'desc'  => 'Kapotte links kosten bezoekers en posities. Zo vind en herstel je ze met de Broken Link Checker-plug-in, zonder dat je WordPress-site trager wordt.',
		),
		'hoe-je-de-redirection-plug-in-gebruikt-voor-301-redirects' => array(
			'title' => 'Zo gebruik je de Redirection-plug-in voor 301-redirects',
			'desc'  => 'Oude URL\'s doorsturen zonder je posities in Google te verliezen: zo stel je met de Redirection-plug-in 301-redirects in en spoor je 404-fouten op.',
		),
		'optimaliseer-je-seo-met-de-seoquake-plug-in-voor-wordpress' => array(
			'title' => 'Je SEO analyseren met SEOquake',
			'desc'  => 'SEOquake geeft je in één klik een SEO-analyse van elke pagina. Zo gebruik je de extensie om je eigen site en die van concurrenten door te lichten.',
		),
		'het-belang-van-google-xml-sitemaps-voor-je-wordpress-site' => array(
			'title' => 'Het belang van een XML-sitemap voor je WordPress-site',
			'desc'  => 'Waarom een XML-sitemap Google helpt je pagina\'s te vinden, hoe je hem maakt in WordPress, aanmeldt in Search Console en welke fouten je voorkomt.',
		),
		'hoe-je-je-website-snelheid-en-seo-verbeterd-met-wp-rocket' => array(
			'title' => 'Zo verbeter je de snelheid en SEO van je website met WP Rocket',
			'desc'  => 'Een snellere WordPress-site met WP Rocket: caching, bestanden verkleinen, lazy load en de instellingen die je Core Web Vitals echt verbeteren.',
		),
		'de-beste-manieren-om-afbeeldingen-te-optimaliseren-met-seo-optimized-images' => array(
			'title' => 'Afbeeldingen optimaliseren met SEO Optimized Images',
			'desc'  => 'Snellere pagina\'s en betere vindbaarheid met geoptimaliseerde afbeeldingen: alt-teksten, bestandsnamen en compressie met SEO Optimized Images.',
		),
		'waarom-je-de-wp-review-plug-in-moet-gebruiken-voor-seo' => array(
			'title' => 'Waarom de WP Review-plug-in goed is voor je SEO',
			'desc'  => 'Reviews en sterren in Google met de WP Review-plug-in: zo verzamel en toon je beoordelingen op je WordPress-site, en wat het oplevert voor je SEO.',
		),
		'verhoog-je-website-snelheid-met-lazy-load-for-seo-plug-in' => array(
			'title' => 'Je website sneller maken met de Lazy Load for SEO-plug-in',
			'desc'  => 'Afbeeldingen pas laden als ze in beeld komen: zo maakt de Lazy Load for SEO-plug-in je WordPress-site sneller, zonder dat Google iets mist.',
		),
		'502-bad-gateway-en-andere-errors-uitgelegd' => array(
			'desc' => 'Een 502 Bad Gateway op je scherm? Wat deze fout betekent, wat je eraan doet en uitleg bij 401, 403, 404, 500 en 503.',
		),

		// Marketingmodellen.
		'swot-analyse' => array(
			'title' => 'SWOT-analyse',
			'seo'   => 'SWOT-analyse: sterktes, zwaktes, kansen en bedreigingen (met voorbeeld)',
			'desc'  => 'Zo maak je een SWOT-analyse: sterktes, zwaktes, kansen en bedreigingen (Strengths, Weaknesses, Opportunities, Threats) uitgelegd, met een voorbeeld.',
		),
		'5-krachten-model-porter' => array(
			'desc' => 'Het vijfkrachtenmodel van Porter uitgelegd: de vijf krachten die de concurrentie in je markt bepalen, hoe je ze toepast en voorbeelden uit de praktijk.',
		),
	);
}

/**
 * Instellingen voor de huidige pagina, of een lege array.
 */
function svv_seo_current() {
	if ( ! is_singular() && ! is_home() ) {
		return array();
	}
	$id   = is_home() ? (int) get_option( 'page_for_posts' ) : get_queried_object_id();
	$slug = $id ? (string) get_post_field( 'post_name', $id ) : '';
	$map  = svv_seo_pages();
	return $map[ $slug ] ?? array();
}

/**
 * Titel in Google: eigen SEO-titel, anders zonder achtervoegsel als hij te lang wordt.
 */
function svv_seo_title( $title ) {
	if ( is_front_page() ) {
		return $title;
	}
	$cur = svv_seo_current();
	if ( ! empty( $cur['seo'] ) ) {
		return $cur['seo'];
	}
	if ( is_category() || is_tag() ) {
		return single_term_title( '', false ) . ': artikelen en handleidingen' . SVV_SUFFIX;
	}
	$title = (string) $title;
	$title = preg_replace( '/\s+[-–|]\s*$/u', '', $title ); // Leeg achtervoegsel ("… -").
	if ( preg_match( '/^(.*?)\s+[-–|]\s+Stijn van Veelen$/u', $title, $m ) ) {
		$base  = trim( $m[1] );
		$title = mb_strlen( $base . SVV_SUFFIX ) > 60 ? $base : $base . SVV_SUFFIX;
	}
	return $title;
}
add_filter( 'wpseo_title', 'svv_seo_title', 30 );
add_filter( 'wpseo_opengraph_title', 'svv_seo_title', 30 );

/**
 * Meta description: uit de lijst hierboven, nieuws zonder meta → samenvatting, archieven een vaste tekst.
 */
function svv_seo_desc( $desc ) {
	$cur = svv_seo_current();
	if ( ! empty( $cur['desc'] ) ) {
		return $cur['desc'];
	}
	if ( is_singular( 'nieuws' ) && '' === trim( (string) $desc ) ) {
		$ex = (string) get_post_field( 'post_excerpt', get_queried_object_id() );
		return $ex ? wp_strip_all_tags( $ex ) : $desc;
	}
	if ( is_post_type_archive( 'nieuws' ) ) {
		return 'Kort nieuws over ChatGPT Ads, Google Ads en Meta: wat er verandert in de platforms en wat je ermee moet. Bijgehouden door Stijn van Veelen.';
	}
	if ( is_category() || is_tag() ) {
		return sprintf( 'Artikelen over %s: praktische uitleg en handleidingen van growth marketing manager Stijn van Veelen.', mb_strtolower( single_term_title( '', false ) ) );
	}
	return $desc;
}
add_filter( 'wpseo_metadesc', 'svv_seo_desc', 30 );
add_filter( 'wpseo_opengraph_desc', 'svv_seo_desc', 30 );

/**
 * Dubbele of hernoemde berichten → de juiste URL.
 *
 * @return array<string,string> pad zonder slashes => doel
 */
function svv_seo_redirects() {
	return array(
		'hoe-je-je-website-snelheid-en-seo-verbeterd-met-wp-rocket-2'   => '/hoe-je-je-website-snelheid-en-seo-verbeterd-met-wp-rocket/',
		'wordpress-meertalig-multi-language-maken-met-trabslatepress' => '/wordpress-meertalig-maken-met-translatepress/',
	);
}
add_action(
	'template_redirect',
	function () {
		if ( ! is_404() ) {
			return;
		}
		$path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
		$map  = svv_seo_redirects();
		if ( isset( $map[ $path ] ) ) {
			wp_safe_redirect( home_url( $map[ $path ] ), 301, 'svv-theme' );
			exit;
		}
	},
	5
);

/**
 * Wie Stijn is, voor schema: één Person met vaste @id.
 */
function svv_person_id() {
	return home_url( '/#/schema/person/stijn' );
}
function svv_person_piece() {
	return array(
		'@type'       => 'Person',
		'@id'         => svv_person_id(),
		'name'        => 'Stijn van Veelen',
		'jobTitle'    => SVV_TAGLINE,
		'description' => 'Growth marketing manager met vijftien jaar ervaring in digital marketing, sales en commerciële leiding.',
		'url'         => home_url( '/' ),
		'image'       => get_site_icon_url( 512 ) ? get_site_icon_url( 512 ) : null,
		'sameAs'      => array_values( array_filter( array( (string) svv_opt( 'svv_linkedin', 'https://www.linkedin.com/in/stijn-van-veelen' ) ) ) ),
		'knowsAbout'  => array( 'Growth marketing', 'Google Ads', 'Meta Ads', 'ChatGPT Ads', 'SEO', 'Marketing automation', 'Data-analyse' ),
	);
}

/**
 * Yoast-schema aanvullen: Person op de homepage, NewsArticle bij nieuws.
 */
add_filter(
	'wpseo_schema_graph',
	function ( $graph ) {
		if ( ! is_array( $graph ) ) {
			return $graph;
		}
		$webpage_id = '';
		foreach ( $graph as $i => $piece ) {
			$types = (array) ( $piece['@type'] ?? array() );
			if ( in_array( 'WebPage', $types, true ) || in_array( 'ProfilePage', $types, true ) || in_array( 'CollectionPage', $types, true ) ) {
				$webpage_id = (string) ( $piece['@id'] ?? '' );
				if ( is_front_page() ) {
					$graph[ $i ]['@type']      = 'ProfilePage';
					$graph[ $i ]['mainEntity'] = array( '@id' => svv_person_id() );
					$graph[ $i ]['about']      = array( '@id' => svv_person_id() );
				}
			}
		}
		if ( is_front_page() ) {
			$graph[] = array_filter( svv_person_piece() );
		}
		if ( is_singular( 'nieuws' ) ) {
			$id      = get_queried_object_id();
			$url     = (string) get_permalink( $id );
			$article = array(
				'@type'            => 'NewsArticle',
				'@id'              => $url . '#article',
				'headline'         => wp_strip_all_tags( get_the_title( $id ) ),
				'description'      => wp_strip_all_tags( (string) get_post_field( 'post_excerpt', $id ) ),
				'datePublished'    => get_the_date( 'c', $id ),
				'dateModified'     => get_the_modified_date( 'c', $id ),
				'inLanguage'       => 'nl-NL',
				'mainEntityOfPage' => array( '@id' => $webpage_id ? $webpage_id : $url ),
				'author'           => array( '@id' => svv_person_id() ),
				'publisher'        => array( '@id' => svv_person_id() ),
				'image'            => get_the_post_thumbnail_url( $id, 'full' ) ? get_the_post_thumbnail_url( $id, 'full' ) : null,
				'isBasedOn'        => get_post_meta( $id, 'svv_bron_url', true ) ? get_post_meta( $id, 'svv_bron_url', true ) : null,
			);
			$graph[] = array_filter( $article );
			$graph[] = array_filter( svv_person_piece() );
		}
		return $graph;
	},
	20
);

// Auteur in Yoast-schema (blogartikelen): zelfde functietitel.
add_filter(
	'wpseo_schema_person',
	function ( $data ) {
		if ( is_array( $data ) && ( $data['name'] ?? '' ) === 'Stijn van Veelen' ) {
			$data['jobTitle'] = SVV_TAGLINE;
			$data['url']      = home_url( '/' );
		}
		return $data;
	}
);
