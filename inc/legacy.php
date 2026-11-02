<?php
/**
 * Oude site (Divi): doorverwijzingen en opruimen.
 *
 * - Oude Divi-pagina's die niet meer passen: 301 naar de best passende nieuwe pagina,
 *   zodat bezoekers en Google niet op een kapotte pagina landen.
 * - Valt er toch nog Divi-opmaak in een pagina of bericht, dan worden alleen de
 *   [et_pb_…]-codes weggehaald en blijft de tekst staan.
 */

defined( 'ABSPATH' ) || exit;

function svv_legacy_redirects() {
	$seo  = '/seo-specialist-inhuren-op-freelance-basis/';
	$sea  = '/sea-specialist-google-ads/';
	return array(
		'commercieel-manager'          => '/',
		'home'                         => '/',
		'intro'                        => '/',
		'digital-marketing-specialist' => '/',
		'portfolio'                    => '/',
		'saas-marketing'               => '/',
		'email-marketing-optimaliseren-met-specialist' => '/',
		'contact-map'                  => '/#bakkie',
		'bedankt-saas'                 => '/',
		'bedankt-social'               => '/social-media-advertising-specialist/',
		'bedankt-email'                => '/',
		'bedankt-sea'                  => $sea,
		'bedankt-seo'                  => $seo,
		'technische-seo-analyse'       => $seo,
		'seo-specialist-optimaliseer-jouw-content-en-zichtbaarheid' => $seo,
		'zoekwoorden-vinden-in-google' => $seo,
		'google-shopping'              => $sea,
		'marketing-kennis-nieuws'      => '/blog/',
		// Oude webshop en cursus (WooCommerce bestaat niet meer).
		'wordpress-cursus-website-in-1-dag' => '/',
		'winkel'                       => '/',
		'winkelmand'                   => '/',
		'afrekenen'                    => '/',
		'mijn-account'                 => '/',
		'account'                      => '/',
		'login'                        => '/',
		'register'                     => '/',
		'password-reset'               => '/',
	);
}

add_action( 'template_redirect', function () {
	if ( ! is_page() || is_front_page() ) {
		return;
	}
	$slug = get_post_field( 'post_name', get_queried_object_id() );
	$map  = svv_legacy_redirects();
	if ( isset( $map[ $slug ] ) ) {
		wp_safe_redirect( home_url( $map[ $slug ] ), 301, 'svv-theme' );
		exit;
	}
} );

// Doorverwezen pagina's niet in de Yoast-sitemap.
add_filter( 'wpseo_exclude_from_sitemap_by_post_ids', function ( $ids ) {
	foreach ( array_keys( svv_legacy_redirects() ) as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			$ids[] = (int) $page->ID;
		}
	}
	return $ids;
} );

// Restanten van Divi-opmaak weghalen, tekst laten staan.
add_filter( 'the_content', function ( $content ) {
	if ( false === strpos( $content, '[et_pb_' ) && false === strpos( $content, '[ba_' ) ) {
		return $content;
	}
	return preg_replace( '/\[\/?(et_pb_[a-z0-9_]+|ba_[a-z0-9_]+)[^\]]*\]/i', '', $content );
}, 5 );
