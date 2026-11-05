<?php
/**
 * SVV theme — stijnvanveelen.nl
 */

defined( 'ABSPATH' ) || exit;

define( 'SVV_VERSION', '0.5.0' );
define( 'SVV_TAGLINE', 'Freelance AI Ads specialist' );

/**
 * Diensten in het menu. Slugs van bestaande pagina's behouden (zoekwaarde).
 * Pas hier aan als een slug afwijkt; alle menu's en links volgen automatisch.
 */
function svv_services() {
	return array(
		'chatgpt-adverteren'                        => 'Adverteren in ChatGPT',
		'sea-specialist-google-ads'                 => 'Google & Microsoft Ads',
		'social-media-advertising-specialist'       => 'Social Ads',
		'seo-specialist-inhuren-op-freelance-basis' => 'SEO & GEO',
	);
}

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	remove_theme_support( 'core-block-patterns' );
} );

add_action( 'wp_enqueue_scripts', function () {
	$uri = get_template_directory_uri();
	$dir = get_template_directory();
	// Versie = tijdstip van het bestand: na elke uitrol halen browsers direct de nieuwe versie.
	wp_enqueue_style( 'svv', $uri . '/assets/css/main.css', array(), (string) filemtime( $dir . '/assets/css/main.css' ) );
	wp_enqueue_script( 'svv', $uri . '/assets/js/main.js', array(), (string) filemtime( $dir . '/assets/js/main.js' ), array( 'in_footer' => true, 'strategy' => 'defer' ) );
	// Geen blokbibliotheek-CSS op de voorkant als we die niet gebruiken.
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}, 20 );

add_action( 'wp_head', function () {
	$uri = get_template_directory_uri();
	echo '<link rel="preload" href="' . esc_url( $uri . '/assets/fonts/inter-latin-400-normal.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	echo '<link rel="preload" href="' . esc_url( $uri . '/assets/fonts/source-serif-4-latin-400-normal.woff2' ) . '" as="font" type="font/woff2" crossorigin>' . "\n";
	echo '<link rel="icon" href="' . esc_url( $uri . '/assets/img/favicon-32.png' ) . '" sizes="32x32">' . "\n";
	echo '<link rel="icon" href="' . esc_url( $uri . '/assets/img/favicon-192.png' ) . '" sizes="192x192">' . "\n";
	echo '<link rel="apple-touch-icon" href="' . esc_url( $uri . '/assets/img/favicon-192.png' ) . '">' . "\n";
	echo '<meta name="theme-color" content="#1F252B">' . "\n";
}, 1 );

// Oude WordPress-sitepictogrammen (2018) niet meer tonen: het thema levert de favicons.
remove_action( 'wp_head', 'wp_site_icon', 99 );
add_filter( 'get_site_icon_url', '__return_empty_string' );

// Titel en beschrijving van de homepage (Yoast gebruikt anders de oude paginatitel).
function svv_home_title() {
	return 'Stijn van Veelen – ' . SVV_TAGLINE;
}
add_filter( 'pre_get_document_title', function ( $title ) {
	return is_front_page() ? svv_home_title() : $title;
}, 20 );
add_filter( 'wpseo_title', function ( $title ) {
	return is_front_page() ? svv_home_title() : $title;
} );
add_filter( 'wpseo_opengraph_title', function ( $title ) {
	return is_front_page() ? svv_home_title() : $title;
} );
add_filter( 'wpseo_metadesc', function ( $desc ) {
	return is_front_page() ? 'Adverteren in ChatGPT, Google Ads met AI Max, Microsoft Ads en social ads. Twintig jaar ervaring, elk nieuw kanaal eerst getest op eigen platforms.' : $desc;
} );

/**
 * Dienstpagina's: titel en beschrijving volgen de paginatitel en samenvatting.
 * De oude Yoast-titels per pagina (uit de Divi-tijd) gelden dan niet meer.
 */
function svv_is_service_page() {
	return is_page() && array_key_exists( get_post_field( 'post_name', get_queried_object_id() ), svv_services() );
}
foreach ( array( 'wpseo_title', 'wpseo_opengraph_title' ) as $svv_hook ) {
	add_filter( $svv_hook, function ( $title ) {
		return svv_is_service_page() ? get_the_title( get_queried_object_id() ) . ' – Stijn van Veelen' : $title;
	}, 20 );
}
foreach ( array( 'wpseo_metadesc', 'wpseo_opengraph_desc' ) as $svv_hook ) {
	add_filter( $svv_hook, function ( $desc ) {
		$excerpt = svv_is_service_page() ? get_post_field( 'post_excerpt', get_queried_object_id() ) : '';
		return $excerpt ? wp_strip_all_tags( $excerpt ) : $desc;
	}, 20 );
}

/**
 * Snelheid: oude Divi-plugins laden niets op de homepage en dienstpagina's.
 * Blogberichten houden alles (oude berichten kunnen er nog op leunen).
 */
function svv_lean_page() {
	return ! is_admin() && ( is_front_page() || is_page() || is_singular( 'nieuws' ) || is_post_type_archive( 'nieuws' ) );
}
function svv_trim_assets() {
	if ( ! svv_lean_page() ) {
		return;
	}
	$block = array( '/plugins/bloom/', '/plugins/addons-for-divi/', '/plugins/a3-lazy-load/', '/plugins/contact-form-7/', '/plugins/kraft-portfolio/', 'fonts.googleapis.com' );
	foreach ( array( wp_styles(), wp_scripts() ) as $deps ) {
		foreach ( (array) $deps->queue as $handle ) {
			$src = isset( $deps->registered[ $handle ] ) ? (string) $deps->registered[ $handle ]->src : '';
			foreach ( $block as $needle ) {
				if ( '' !== $src && false !== strpos( $src, $needle ) ) {
					$deps->dequeue( $handle );
					break;
				}
			}
		}
	}
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'svv_trim_assets', 999 );
add_action( 'wp_print_styles', 'svv_trim_assets', 1 );
add_action( 'wp_print_footer_scripts', 'svv_trim_assets', 1 );
add_action( 'wp', function () {
	if ( svv_lean_page() ) {
		remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
		remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
		remove_action( 'wp_head', 'wp_print_auto_sizes_contain_css_fix', 1 );
	}
} );

// Rommel uit de head.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Voorbereide vragen op de homepage (ook kennis voor de AI-assistent).
 */
function svv_faqs() {
	return array(
		array(
			'q'    => 'Kan ik adverteren in ChatGPT?',
			'a'    => 'Ja, sinds eind augustus ook in Nederland. Op mijn eigen platforms draaien de eerste campagnes al. Zin in een pilot? Vaste opzet, jouw budget en na een paar weken een eerlijk verslag.',
			'link' => array( 'Adverteren in ChatGPT', svv_service_url( 'chatgpt-adverteren' ) ),
		),
		array(
			'q'    => 'Google zet mijn campagnes om naar AI Max. Wat nu?',
			'a'    => 'Google zet dynamische zoekadvertenties en brede instellingen automatisch om naar AI Max. Zonder merkuitsluitingen en goede uitsluitingszoekwoorden betaal je dan vaak meer per aanvraag. Goed inrichten en scherp toezicht houden: daar zit het verschil.',
			'link' => array( 'Google & Microsoft Ads', svv_service_url( 'sea-specialist-google-ads' ) ),
		),
		array(
			'q'    => 'Werkt TikTok of LinkedIn voor mijn bedrijf?',
			'a'    => 'Dat hangt af van wie je wilt bereiken. Meta en TikTok voor bereik bij consumenten, LinkedIn voor beslissers in B2B. Samen kijken we waar jouw klant zit en beginnen we klein.',
			'link' => array( 'Social Ads', svv_service_url( 'social-media-advertising-specialist' ) ),
		),
		array(
			'q'    => 'Word ik gevonden in ChatGPT?',
			'a'    => 'Dat hangt af van hoe je site in elkaar zit en wat er over je bedrijf online staat. Met SEO en GEO zorg ik dat je gevonden wordt in Google én genoemd in AI-antwoorden.',
			'link' => array( 'SEO & GEO', svv_service_url( 'seo-specialist-inhuren-op-freelance-basis' ) ),
		),
	);
}

/**
 * Eigen platforms van New Road Digital (getest op eigen platforms).
 */
function svv_platforms() {
	return array(
		'Autobaak'          => 'https://www.autobaak.nl/',
		'Therapievergelijk' => 'https://www.therapievergelijk.nl/',
	);
}

/**
 * Lijst van platforms als links: "A, B en C".
 */
function svv_platform_links() {
	$links = array();
	foreach ( svv_platforms() as $name => $url ) {
		$links[] = '<a href="' . esc_url( $url ) . '">' . esc_html( $name ) . '</a>';
	}
	$last = array_pop( $links );
	return implode( ', ', $links ) . ' en ' . $last;
}

/**
 * URL van een dienst op basis van slug (valt terug op /slug/).
 */
function svv_service_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

/**
 * Is de huidige pagina deze dienst?
 */
function svv_is_current( $slug ) {
	return is_page( $slug );
}

/**
 * Logo A (ring met sparkle) als inline SVG.
 */
function svv_logo( $size = 36, $bg = '#181D22' ) {
	static $n = 0;
	$n++;
	$id = 'svvg' . $n;
	return sprintf(
		'<svg aria-hidden="true" width="%1$d" height="%1$d" viewBox="0 0 48 48"><defs><linearGradient id="%2$s" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#7FA9FF"/><stop offset=".55" stop-color="#AE94FF"/><stop offset="1" stop-color="#F08AAE"/></linearGradient></defs><circle cx="24" cy="24" r="21" fill="none" stroke="url(#%2$s)" stroke-width="2"/><text x="24" y="31.5" text-anchor="middle" font-family="Source Serif 4, Georgia, serif" font-size="23" fill="#FFFFFF">S</text><path d="M38 4C38.3 6.9 40.1 8.7 43 9C40.1 9.3 38.3 11.1 38 14C37.7 11.1 35.9 9.3 33 9C35.9 8.7 37.7 6.9 38 4Z" fill="url(#%2$s)" stroke="%3$s" stroke-width="2.5" paint-order="stroke"/></svg>',
		(int) $size,
		esc_attr( $id ),
		esc_attr( $bg )
	);
}

/**
 * Instellingen (Customizer) voor zaken die vaak wijzigen.
 */
add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( 'svv', array( 'title' => 'Stijn van Veelen' ) );
	$fields = array(
		'svv_available'     => array( 'Beschikbaar vanaf', 'direct' ),
		'svv_calendar'      => array( 'Agendalink voor een bakkie (optioneel)', '' ),
		'svv_kvk'           => array( 'KvK-nummer', '70585245' ),
		'svv_linkedin'      => array( 'LinkedIn-URL', 'https://www.linkedin.com/in/stijn-van-veelen' ),
	);
	foreach ( $fields as $key => $f ) {
		$wp_customize->add_setting( $key, array( 'default' => $f[1], 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control( $key, array( 'label' => $f[0], 'section' => 'svv', 'type' => 'text' ) );
	}
} );

function svv_opt( $key, $fallback = '' ) {
	return get_theme_mod( $key, $fallback );
}

require get_template_directory() . '/inc/forms.php';
require get_template_directory() . '/inc/bot.php';
require get_template_directory() . '/inc/legacy.php';
require get_template_directory() . '/inc/brevo.php';
require get_template_directory() . '/inc/landing.php';
require get_template_directory() . '/inc/nieuws.php';
require get_template_directory() . '/inc/nieuws-import.php';
// Eigen cookiemelding; niet dubbel naast Real Cookie Banner.
if ( ! array_intersect( array( 'real-cookie-banner-pro/index.php', 'real-cookie-banner/index.php' ), (array) get_option( 'active_plugins', array() ) ) ) {
	require get_template_directory() . '/inc/consent.php';
}
