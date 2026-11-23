<?php
/**
 * Eenmalige inhoudscorrecties. Elke correctie draait één keer (vlag in een optie)
 * en leegt daarna de LiteSpeed-cache, zodat bezoekers direct de nieuwe tekst zien.
 * Nieuwe correctie: voeg een sleutel toe aan svv_content_fixes().
 */

defined( 'ABSPATH' ) || exit;

function svv_content_fixes() {
	return array(
		// Profielsite (nov 2026): overal vijftien jaar ervaring in plaats van twintig.
		'2026-11-ervaring-15' => function () {
			$page = get_page_by_path( 'sea-specialist-google-ads' );
			if ( ! $page ) {
				return;
			}
			$from = array( 'Twintig jaar', 'twintig jaar' );
			$to   = array( 'Vijftien jaar', 'vijftien jaar' );
			// Direct in de database: wp_update_post() zou zonder ingelogde gebruiker
			// de HTML door kses halen en zo blokken of embeds kunnen beschadigen.
			global $wpdb;
			$wpdb->update(
				$wpdb->posts,
				array(
					'post_content' => str_replace( $from, $to, $page->post_content ),
					'post_excerpt' => str_replace( $from, $to, $page->post_excerpt ),
				),
				array( 'ID' => $page->ID )
			);
			clean_post_cache( $page->ID );
		},
	);
}

add_action( 'init', function () {
	$done = (array) get_option( 'svv_content_fixes_done', array() );
	$ran  = false;
	foreach ( svv_content_fixes() as $key => $fix ) {
		if ( in_array( $key, $done, true ) ) {
			continue;
		}
		$fix();
		$done[] = $key;
		$ran    = true;
	}
	if ( $ran ) {
		update_option( 'svv_content_fixes_done', $done, false );
		do_action( 'litespeed_purge_all' );
	}
}, 40 );

/**
 * Na elke uitrol (gewijzigde themabestanden) de LiteSpeed-cache één keer legen,
 * zodat pagina's niet naar oude CSS, favicons of teksten blijven verwijzen.
 */
add_action( 'init', function () {
	$dir   = get_template_directory();
	$stamp = 0;
	foreach ( array( 'functions.php', 'header.php', 'footer.php', 'front-page.php', 'assets/css/main.css', 'assets/js/main.js' ) as $file ) {
		$stamp = max( $stamp, (int) @filemtime( $dir . '/' . $file ) ); // phpcs:ignore WordPress.PHP.NoSilencedErrors
	}
	if ( $stamp && (int) get_option( 'svv_deploy_stamp' ) !== $stamp ) {
		update_option( 'svv_deploy_stamp', $stamp, false );
		do_action( 'litespeed_purge_all' );
	}
}, 41 );
