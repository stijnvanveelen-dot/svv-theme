<?php
/**
 * SVV theme — stijnvanveelen.nl
 */

defined( 'ABSPATH' ) || exit;

define( 'SVV_VERSION', '0.2.0' );
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
	wp_enqueue_style( 'svv', $uri . '/assets/css/main.css', array(), SVV_VERSION );
	wp_enqueue_script( 'svv', $uri . '/assets/js/main.js', array(), SVV_VERSION, array( 'in_footer' => true, 'strategy' => 'defer' ) );
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
	return is_front_page() ? 'Adverteren in ChatGPT, Google Ads met AI Max, Microsoft Ads en social ads. Twintig jaar ervaring, elk nieuw kanaal eerst getest met eigen budget.' : $desc;
} );

// Rommel uit de head.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
add_filter( 'xmlrpc_enabled', '__return_false' );

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
		'svv_kvk'           => array( 'KvK-nummer', '[nummer]' ),
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
