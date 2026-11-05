<?php
/**
 * Nieuws-import: Markdown-bestanden in de map nieuws/ van het theme worden concepten.
 *
 * De dagelijkse nieuwscheck (geplande taak) zet een bestand als
 * nieuws/2026-11-06-chatgpt-ads-iets.md in de repo. Na de uitrol maakt dit
 * bestand er één keer een concept van onder Nieuws in wp-admin.
 *
 * Opbouw van een bestand:
 *   ---
 *   title: Titel van het bericht
 *   slug: korte-slug
 *   excerpt: Eén zin voor het overzicht.
 *   bron_naam: Google Ads & Commerce Blog
 *   bron_url: https://...
 *   ---
 *   Tekst in Markdown: alinea's, ## kopjes, - lijstjes, **vet**, *schuin*, [link](url).
 *
 * Bestanden die met _ beginnen (zoals _log.md) worden overgeslagen.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {
	$files = glob( get_template_directory() . '/nieuws/[!_]*.md' );
	if ( ! $files ) {
		return;
	}
	$done = (array) get_option( 'svv_nieuws_imported', array() );
	$new  = array_diff( array_map( 'basename', $files ), $done );
	if ( ! $new || get_transient( 'svv_nieuws_import_lock' ) ) {
		return;
	}
	set_transient( 'svv_nieuws_import_lock', 1, 60 );

	foreach ( $new as $name ) {
		$parsed = svv_nieuws_parse( (string) file_get_contents( get_template_directory() . '/nieuws/' . $name ) );
		if ( $parsed && ! empty( $parsed['meta']['title'] ) ) {
			$m  = $parsed['meta'];
			$id = wp_insert_post( array(
				'post_type'    => 'nieuws',
				'post_status'  => 'draft',
				'post_title'   => sanitize_text_field( $m['title'] ),
				'post_name'    => sanitize_title( $m['slug'] ?? $m['title'] ),
				'post_excerpt' => sanitize_text_field( $m['excerpt'] ?? '' ),
				'post_content' => svv_nieuws_blocks( $parsed['body'] ),
			) );
			if ( $id && ! is_wp_error( $id ) ) {
				update_post_meta( $id, 'svv_bron_naam', sanitize_text_field( $m['bron_naam'] ?? '' ) );
				update_post_meta( $id, 'svv_bron_url', esc_url_raw( $m['bron_url'] ?? '' ) );
				update_post_meta( $id, 'svv_import_file', $name );
			}
		}
		$done[] = $name; // Ook bij een fout: niet elke paginalading opnieuw proberen.
	}
	update_option( 'svv_nieuws_imported', array_values( array_unique( $done ) ), false );
	delete_transient( 'svv_nieuws_import_lock' );
}, 40 );

/**
 * Front matter en tekst uit elkaar halen.
 */
function svv_nieuws_parse( $raw ) {
	$raw = str_replace( "\r\n", "\n", ltrim( $raw, "\xEF\xBB\xBF" ) );
	if ( ! preg_match( '/^---\n(.*?)\n---\n(.*)$/s', $raw, $mt ) ) {
		return null;
	}
	$meta = array();
	foreach ( explode( "\n", $mt[1] ) as $line ) {
		if ( preg_match( '/^([a-z_]+):\s*(.*)$/', trim( $line ), $kv ) ) {
			$val = trim( $kv[2] );
			if ( preg_match( '/^(["\'])(.*)\1$/', $val, $q ) ) {
				$val = $q[2];
			}
			$meta[ $kv[1] ] = $val;
		}
	}
	return array( 'meta' => $meta, 'body' => trim( $mt[2] ) );
}

/**
 * Eenvoudige Markdown naar blokken: alinea's, ##/### kopjes en lijstjes.
 */
function svv_nieuws_blocks( $md ) {
	$out = '';
	foreach ( preg_split( "/\n\s*\n/", $md ) as $chunk ) {
		$chunk = trim( $chunk );
		if ( '' === $chunk ) {
			continue;
		}
		if ( preg_match( '/^(#{2,3})\s+(.+)$/', $chunk, $h ) ) {
			$lvl  = strlen( $h[1] );
			$attr = 3 === $lvl ? ' {"level":3}' : '';
			$out .= "<!-- wp:heading{$attr} -->\n<h{$lvl} class=\"wp-block-heading\">" . svv_nieuws_inline( $h[2] ) . "</h{$lvl}>\n<!-- /wp:heading -->\n\n";
		} elseif ( preg_match( '/^[-*]\s/', $chunk ) ) {
			$items = '';
			foreach ( preg_split( '/\n(?=[-*]\s)/', $chunk ) as $li ) {
				$items .= "<!-- wp:list-item -->\n<li>" . svv_nieuws_inline( preg_replace( '/^[-*]\s+/', '', trim( $li ) ) ) . "</li>\n<!-- /wp:list-item -->\n";
			}
			$out .= "<!-- wp:list -->\n<ul class=\"wp-block-list\">" . $items . "</ul>\n<!-- /wp:list -->\n\n";
		} else {
			$out .= "<!-- wp:paragraph -->\n<p>" . svv_nieuws_inline( preg_replace( '/\s*\n\s*/', ' ', $chunk ) ) . "</p>\n<!-- /wp:paragraph -->\n\n";
		}
	}
	return $out;
}

function svv_nieuws_inline( $text ) {
	$text = esc_html( $text );
	$text = preg_replace_callback( '/\[([^\]]+)\]\((https?:\/\/[^\s)]+)\)/', function ( $m ) {
		return '<a href="' . esc_url( html_entity_decode( $m[2] ) ) . '">' . $m[1] . '</a>';
	}, $text );
	$text = preg_replace( '/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text );
	$text = preg_replace( '/(?<![\*\w])\*(?!\s)(.+?)(?<!\s)\*(?![\*\w])/', '<em>$1</em>', $text );
	return $text;
}
