<?php
/**
 * Landingspagina Ads-testlab (/ads-testlab/) voor advertenties gericht op aanmeldingen.
 *
 * - De pagina wordt één keer automatisch aangemaakt (slug ads-testlab), zodat Yoast,
 *   sitemap en canonical gewoon werken. Inhoud staat in page-ads-testlab.php.
 * - Titel en metabeschrijving worden hier gezet, los van oude Yoast-velden.
 */

defined( 'ABSPATH' ) || exit;

define( 'SVV_TESTLAB_SLUG', 'ads-testlab' );

add_action( 'init', function () {
	if ( get_option( 'svv_testlab_page' ) ) {
		return;
	}
	$page = get_page_by_path( SVV_TESTLAB_SLUG );
	if ( ! $page ) {
		$id = wp_insert_post( array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_title'   => 'Ads-testlab',
			'post_name'    => SVV_TESTLAB_SLUG,
			'post_content' => '',
		) );
	} else {
		$id = $page->ID;
	}
	if ( $id && ! is_wp_error( $id ) ) {
		update_option( 'svv_testlab_page', (int) $id, false );
	}
}, 20 );

function svv_is_testlab() {
	return is_page( SVV_TESTLAB_SLUG );
}

function svv_testlab_title() {
	return 'Ads-testlab: testresultaten van ChatGPT Ads – Stijn van Veelen';
}

function svv_testlab_desc() {
	return 'Testresultaten van ChatGPT Ads, AI Max en Advantage+, gedraaid met echt budget op eigen platforms. Cijfers en instellingen in je mail, zodra er iets te melden is.';
}

add_filter( 'pre_get_document_title', function ( $title ) {
	return svv_is_testlab() ? svv_testlab_title() : $title;
}, 25 );
foreach ( array( 'wpseo_title', 'wpseo_opengraph_title' ) as $svv_hook ) {
	add_filter( $svv_hook, function ( $title ) {
		return svv_is_testlab() ? svv_testlab_title() : $title;
	}, 25 );
}
foreach ( array( 'wpseo_metadesc', 'wpseo_opengraph_desc' ) as $svv_hook ) {
	add_filter( $svv_hook, function ( $desc ) {
		return svv_is_testlab() ? svv_testlab_desc() : $desc;
	}, 25 );
}
