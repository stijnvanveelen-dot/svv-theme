<?php
/**
 * Nieuws (/nieuws/): korte updates over advertentieplatforms, los van het blog.
 *
 * - Eigen berichttype 'nieuws' met archief /nieuws/ en berichten /nieuws/slug/.
 * - Per bericht een bron (naam + URL), onder de tekst getoond als "Bron: ...".
 * - Eerste bericht (ChatGPT Ads: Target CPA/CPC) wordt één keer aangemaakt en gepubliceerd.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {
	register_post_type( 'nieuws', array(
		'labels'        => array(
			'name'          => 'Nieuws',
			'singular_name' => 'Nieuwsbericht',
			'add_new'       => 'Nieuw bericht',
			'add_new_item'  => 'Nieuw nieuwsbericht',
			'edit_item'     => 'Nieuwsbericht bewerken',
			'all_items'     => 'Alle nieuwsberichten',
		),
		'public'        => true,
		'has_archive'   => 'nieuws',
		'rewrite'       => array( 'slug' => 'nieuws', 'with_front' => false ),
		'menu_position' => 6,
		'menu_icon'     => 'dashicons-megaphone',
		'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields' ),
		'show_in_rest'  => true,
	) );

	foreach ( array( 'svv_bron_naam', 'svv_bron_url' ) as $key ) {
		register_post_meta( 'nieuws', $key, array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'svv_bron_url' === $key ? 'esc_url_raw' : 'sanitize_text_field',
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
		) );
	}

	// Permalinks één keer verversen, zodat /nieuws/ direct werkt.
	if ( get_option( 'svv_nieuws_rewrite' ) !== SVV_VERSION ) {
		flush_rewrite_rules( false );
		update_option( 'svv_nieuws_rewrite', SVV_VERSION, false );
	}
} );

// Bron-velden in de editor.
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'svv_bron', 'Bron', function ( $post ) {
		wp_nonce_field( 'svv_bron', 'svv_bron_nonce' );
		printf(
			'<p><label>Naam<br><input type="text" class="widefat" name="svv_bron_naam" value="%s" placeholder="OpenAI Help Center"></label></p><p><label>URL<br><input type="url" class="widefat" name="svv_bron_url" value="%s" placeholder="https://"></label></p>',
			esc_attr( get_post_meta( $post->ID, 'svv_bron_naam', true ) ),
			esc_attr( get_post_meta( $post->ID, 'svv_bron_url', true ) )
		);
	}, 'nieuws', 'side' );
} );

add_action( 'save_post_nieuws', function ( $post_id ) {
	if ( ! isset( $_POST['svv_bron_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['svv_bron_nonce'] ) ), 'svv_bron' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, 'svv_bron_naam', sanitize_text_field( wp_unslash( $_POST['svv_bron_naam'] ?? '' ) ) );
	update_post_meta( $post_id, 'svv_bron_url', esc_url_raw( wp_unslash( $_POST['svv_bron_url'] ?? '' ) ) );
} );

/**
 * Bronregel onder een nieuwsbericht.
 */
function svv_bron( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$naam    = get_post_meta( $post_id, 'svv_bron_naam', true );
	$url     = get_post_meta( $post_id, 'svv_bron_url', true );
	if ( ! $url ) {
		return '';
	}
	return sprintf(
		'<p class="svv-bron">Bron: <a href="%s" target="_blank" rel="noopener">%s</a></p>',
		esc_url( $url ),
		esc_html( $naam ?: wp_parse_url( $url, PHP_URL_HOST ) )
	);
}

// Titel van het archief.
add_filter( 'pre_get_document_title', function ( $title ) {
	return is_post_type_archive( 'nieuws' ) ? 'Nieuws over ChatGPT Ads, Google Ads en Meta – Stijn van Veelen' : $title;
}, 25 );

// Eerste bericht live: stond het al als concept klaar, dan één keer publiceren.
add_action( 'init', function () {
	if ( get_option( 'svv_nieuws_live' ) || ! get_option( 'svv_nieuws_seed' ) ) {
		return;
	}
	$draft = get_posts( array(
		'post_type'   => 'nieuws',
		'name'        => 'chatgpt-ads-target-cpa-target-cpc',
		'post_status' => 'draft',
		'numberposts' => 1,
	) );
	if ( $draft ) {
		wp_publish_post( $draft[0]->ID );
	}
	update_option( 'svv_nieuws_live', 1, false );
}, 31 );

// Eerste bericht één keer aanmaken en publiceren.
add_action( 'init', function () {
	if ( get_option( 'svv_nieuws_seed' ) ) {
		return;
	}
	$p  = function ( $t ) {
		return "<!-- wp:paragraph -->\n<p>" . $t . "</p>\n<!-- /wp:paragraph -->\n\n";
	};
	$h  = function ( $t ) {
		return "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . $t . "</h2>\n<!-- /wp:heading -->\n\n";
	};
	$body = $p( 'OpenAI heeft adverteerders deze week gemaild over een flinke wijziging in het biedsysteem van ChatGPT Ads. Werk je nu met Max Bid of Max CPC, dan moet je overstappen. Op 1 januari 2027 verdwijnen beide opties.' )
		. $h( 'Wat verandert er?' )
		. $p( 'Vanaf maandag 9 november kun je in Ads Manager een kostendoel instellen, bij een nieuwe campagne of in een bestaande. Voor conversiecampagnes komt er Target CPA. Je geeft aan wat een conversie, zoals een aankoop of lead, gemiddeld mag kosten, en het systeem stuurt biedingen en levering richting dat bedrag. Target CPA vervangt Max Bid voor nieuwe conversiecampagnes.' )
		. $p( 'Voor klikcampagnes komt Target CPC in plaats van Max CPC. Max CPC is een harde grens per bod. Bij Target CPC mag OpenAI per veiling boven of onder je doel bieden, zolang de gemiddelde klikprijs eromheen blijft. Zo probeert het systeem zoveel mogelijk klikken uit je budget te halen.' )
		. $p( 'Bestaande campagnes draaien voorlopig gewoon door op Max Bid of Max CPC. Op 1 januari worden ze automatisch omgezet naar Target CPA of Target CPC. Maximize results (autobid) blijft bestaan en heet voortaan intelligent bidding.' )
		. $h( 'Wat moet je nu doen?' )
		. $p( 'Wacht niet tot januari. Een automatische omzetting met een doel dat niet past bij je echte cijfers kan een campagne flink afremmen.' )
		. $p( 'Begin bij je conversiemeting. Stuurt je Pixel of Conversions API het gekozen event echt door naar de databron die aan je campagne hangt? De Event Quality Score (EQS) in Ads Manager waarschuwt als het signaal zwak is. Los dat eerst op, want Target CPA stuurt alleen zo goed als de data die het krijgt.' )
		. $p( 'Kijk daarna naar je werkelijke CPA en CPC van de afgelopen weken en kies op basis daarvan een realistisch doel. Te krappe doelen of zwakke signalen zorgen dat advertenties minder vaak vertoond worden en budget blijft liggen.' );

	$id = wp_insert_post( array(
		'post_type'    => 'nieuws',
		'post_status'  => 'publish',
		'post_title'   => 'ChatGPT Ads stopt met Max Bid en Max CPC: zo stap je over',
		'post_name'    => 'chatgpt-ads-target-cpa-target-cpc',
		'post_excerpt' => 'Vanaf 9 november Target CPA en Target CPC in ChatGPT Ads. Max Bid en Max CPC verdwijnen op 1 januari 2027.',
		'post_content' => $body,
	) );
	if ( $id && ! is_wp_error( $id ) ) {
		update_post_meta( $id, 'svv_bron_naam', 'OpenAI, ChatGPT Ads Help Center' );
		update_post_meta( $id, 'svv_bron_url', 'https://help.openai.com/en/collections/20001223' );
	}
	update_option( 'svv_nieuws_seed', 1, false );
}, 30 );
