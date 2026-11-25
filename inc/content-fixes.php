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
		// SEO-audit 25-11-2026: titels in gewoon Nederlands (geen Engelse hoofdletters), taalfouten,
		// hernoemde slug TranslatePress, dubbel WP Rocket-bericht offline, sitenaam en tagline.
		'2026-11-seo-audit' => function () {
			global $wpdb;
			foreach ( svv_seo_pages() as $slug => $cfg ) {
				if ( empty( $cfg['title'] ) && empty( $cfg['slug'] ) ) {
					continue;
				}
				$id = (int) $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_name = %s AND post_type IN ('post','page') AND post_status = 'publish' LIMIT 1", $slug ) ); // phpcs:ignore WordPress.DB
				if ( ! $id ) {
					continue;
				}
				$data = array();
				if ( ! empty( $cfg['title'] ) ) {
					$data['post_title'] = $cfg['title'];
				}
				if ( ! empty( $cfg['slug'] ) ) {
					$data['post_name'] = $cfg['slug'];
					add_post_meta( $id, '_wp_old_slug', $slug );
				}
				$wpdb->update( $wpdb->posts, $data, array( 'ID' => $id ) );
				clean_post_cache( $id );
			}
			$dup = get_page_by_path( 'hoe-je-je-website-snelheid-en-seo-verbeterd-met-wp-rocket-2', OBJECT, 'post' );
			if ( $dup ) {
				$wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $dup->ID ) );
				clean_post_cache( $dup->ID );
			}
			update_option( 'blogname', 'Stijn van Veelen' );
			update_option( 'blogdescription', SVV_TAGLINE );
			$titles = get_option( 'wpseo_titles' );
			if ( is_array( $titles ) ) {
				$titles['website_name']         = 'Stijn van Veelen';
				$titles['title-home-wpseo']     = 'Stijn van Veelen – ' . SVV_TAGLINE;
				$titles['metadesc-home-wpseo']  = 'Growth marketing manager met vijftien jaar ervaring in digital marketing, sales en commerciële leiding. Marketing die stuurt op bewijs: performance, SEO & GEO, data en automation.';
				update_option( 'wpseo_titles', $titles );
			}
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
