<?php
/**
 * Ads-testlab: aanmeldingen van het nieuwsbriefformulier naar Brevo.
 *
 * - Met een DOI-sjabloon: Brevo stuurt een bevestigingsmail (dubbele opt-in);
 *   pas na de klik staat het adres op de lijst. Zo is aan te tonen dat iemand
 *   zich zelf heeft aangemeld (AVG).
 * - Zonder sjabloon: het adres gaat direct op de lijst.
 * Aanmelding blijft daarnaast als Aanvraag in WordPress staan en komt per mail binnen.
 * Instellingen → Ads-testlab (Brevo).
 */

defined( 'ABSPATH' ) || exit;

function svv_brevo_settings() {
	return wp_parse_args( get_option( 'svv_brevo', array() ), array(
		'list'     => 0,
		'template' => 0,
	) );
}

function svv_brevo_key() {
	return (string) get_option( 'svv_brevo_key', '' );
}

/** Dubbele opt-in actief? (sleutel, lijst en sjabloon ingevuld) */
function svv_brevo_doi() {
	$s = svv_brevo_settings();
	return svv_brevo_key() && (int) $s['list'] && (int) $s['template'];
}

/**
 * Verzoek naar de Brevo-API. Geeft array( code, body ) terug, of WP_Error.
 */
function svv_brevo_request( $method, $path, $body = null ) {
	$args = array(
		'method'  => $method,
		'timeout' => 10,
		'headers' => array(
			'api-key'      => svv_brevo_key(),
			'accept'       => 'application/json',
			'content-type' => 'application/json',
		),
	);
	if ( null !== $body ) {
		$args['body'] = wp_json_encode( $body );
	}
	$res = wp_remote_request( 'https://api.brevo.com/v3' . $path, $args );
	if ( is_wp_error( $res ) ) {
		return $res;
	}
	return array( (int) wp_remote_retrieve_response_code( $res ), json_decode( wp_remote_retrieve_body( $res ), true ) );
}

/** Nieuwe nieuwsbrief-aanmelding → Brevo. */
add_action( 'svv_lead_created', function ( $lead_id, $data ) {
	if ( 'nieuwsbrief' !== ( $data['type'] ?? '' ) || ! svv_brevo_key() ) {
		return;
	}
	$s    = svv_brevo_settings();
	$list = (int) $s['list'];
	if ( ! $list ) {
		return;
	}
	$email = (string) $data['email'];
	if ( (int) $s['template'] ) {
		$r = svv_brevo_request( 'POST', '/contacts/doubleOptinConfirmation', array(
			'email'          => $email,
			'includeListIds' => array( $list ),
			'templateId'     => (int) $s['template'],
			'redirectionUrl' => home_url( '/?aangemeld=bevestigd#nieuwsbrief' ),
		) );
	} else {
		$r = svv_brevo_request( 'POST', '/contacts', array(
			'email'         => $email,
			'listIds'       => array( $list ),
			'updateEnabled' => true,
		) );
	}
	if ( is_wp_error( $r ) ) {
		$status = 'fout: ' . $r->get_error_message();
	} elseif ( $r[0] >= 200 && $r[0] < 300 ) {
		$status = (int) $s['template'] ? 'bevestigingsmail verstuurd' : 'op de lijst gezet';
	} elseif ( 400 === $r[0] && 'duplicate_parameter' === ( $r[1]['code'] ?? '' ) ) {
		$status = 'stond al op de lijst';
	} else {
		$status = 'fout ' . $r[0] . ': ' . ( $r[1]['message'] ?? '' );
	}
	if ( $lead_id ) {
		update_post_meta( $lead_id, 'svv_brevo', $status );
	}
	if ( 0 === strpos( $status, 'fout' ) ) {
		update_option( 'svv_brevo_last_error', current_time( 'mysql' ) . ' · ' . $email . ' · ' . $status, false );
	}
}, 10, 2 );

/** Melding op de site na de klik in de bevestigingsmail. */
add_filter( 'svv_form_ok_text', function ( $text, $type ) {
	if ( 'nieuwsbrief' === $type && svv_brevo_doi() ) {
		return 'Bijna klaar. Check je mail en bevestig je aanmelding.';
	}
	return $text;
}, 10, 2 );

/* -------- Beheer: Instellingen → Ads-testlab (Brevo) -------- */

add_action( 'admin_menu', function () {
	add_options_page( 'Ads-testlab (Brevo)', 'Ads-testlab (Brevo)', 'manage_options', 'svv-brevo', 'svv_brevo_admin_page' );
} );

add_action( 'admin_post_svv_brevo_save', function () {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Geen toegang.' );
	}
	check_admin_referer( 'svv_brevo_save' );
	$key = trim( sanitize_text_field( wp_unslash( $_POST['key'] ?? '' ) ) );
	if ( ! empty( $_POST['key_clear'] ) ) {
		delete_option( 'svv_brevo_key' );
	} elseif ( '' !== $key ) {
		update_option( 'svv_brevo_key', $key, false );
	}
	update_option( 'svv_brevo', array(
		'list'     => absint( $_POST['list'] ?? 0 ),
		'template' => absint( $_POST['template'] ?? 0 ),
	), false );
	wp_safe_redirect( admin_url( 'options-general.php?page=svv-brevo&opgeslagen=1' ) );
	exit;
} );

function svv_brevo_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s     = svv_brevo_settings();
	$key   = svv_brevo_key();
	$lists = array();
	$acct  = '';
	$conn  = '';
	if ( $key ) {
		$a = svv_brevo_request( 'GET', '/account' );
		if ( is_wp_error( $a ) ) {
			$conn = 'Geen verbinding: ' . $a->get_error_message();
		} elseif ( 200 !== $a[0] ) {
			$conn = 'Sleutel werkt niet (HTTP ' . $a[0] . ': ' . ( $a[1]['message'] ?? '' ) . ')';
		} else {
			$acct = trim( ( $a[1]['companyName'] ?? '' ) . ' · ' . ( $a[1]['email'] ?? '' ), ' ·' );
			$l    = svv_brevo_request( 'GET', '/contacts/lists?limit=50' );
			if ( ! is_wp_error( $l ) && 200 === $l[0] ) {
				$lists = (array) ( $l[1]['lists'] ?? array() );
			}
		}
	}
	$err   = get_option( 'svv_brevo_last_error', '' );
	$leads = get_posts( array(
		'post_type'   => 'svv_lead',
		'post_status' => 'private',
		'numberposts' => 10,
		'meta_key'    => 'svv_type', // phpcs:ignore WordPress.DB.SlowDBQuery
		'meta_value'  => 'nieuwsbrief', // phpcs:ignore WordPress.DB.SlowDBQuery
	) );
	?>
	<div class="wrap">
		<h1>Ads-testlab (Brevo)</h1>
		<p>Aanmeldingen van het nieuwsbriefformulier gaan naar een lijst in Brevo. Met een bevestigingssjabloon (dubbele opt-in) stuurt Brevo eerst een mail; pas na de klik staat iemand op de lijst. Aanmeldingen blijven ook onder Aanvragen staan en komen per mail binnen.</p>
		<?php if ( isset( $_GET['opgeslagen'] ) ) : // phpcs:ignore ?>
			<div class="notice notice-success"><p>Opgeslagen.</p></div>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="svv_brevo_save">
			<?php wp_nonce_field( 'svv_brevo_save' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="svv-brevo-key">API-sleutel</label></th>
					<td>
						<input id="svv-brevo-key" type="password" name="key" class="regular-text" autocomplete="off" placeholder="<?php echo $key ? esc_attr( 'ingesteld (…' . substr( $key, -4 ) . ')' ) : 'xkeysib-…'; ?>">
						<?php if ( $key ) : ?>
							<label><input type="checkbox" name="key_clear" value="1"> sleutel wissen</label>
						<?php endif; ?>
						<p class="description">Brevo → rechtsboven je naam → SMTP &amp; API → API-sleutels → Nieuwe sleutel. Leeg laten = niet wijzigen.</p>
						<?php if ( $acct ) : ?>
							<p><strong>Verbonden:</strong> <?php echo esc_html( $acct ); ?></p>
						<?php elseif ( $conn ) : ?>
							<p style="color:#b32d2e"><strong><?php echo esc_html( $conn ); ?></strong></p>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="svv-brevo-list">Lijst</label></th>
					<td>
						<?php if ( $lists ) : ?>
							<select id="svv-brevo-list" name="list">
								<option value="0">— kies een lijst —</option>
								<?php foreach ( $lists as $li ) : ?>
									<option value="<?php echo (int) $li['id']; ?>" <?php selected( (int) $s['list'], (int) $li['id'] ); ?>><?php echo esc_html( $li['name'] . ' (#' . $li['id'] . ', ' . (int) ( $li['uniqueSubscribers'] ?? $li['totalSubscribers'] ?? 0 ) . ' contacten)' ); ?></option>
								<?php endforeach; ?>
							</select>
						<?php else : ?>
							<input id="svv-brevo-list" type="number" name="list" min="0" value="<?php echo (int) $s['list']; ?>" class="small-text">
						<?php endif; ?>
						<p class="description">Maak in Brevo onder Contacten → Lijsten een lijst "Ads-testlab" aan. Na het opslaan van de sleutel verschijnen je lijsten hier.</p>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="svv-brevo-tpl">Bevestigingssjabloon (ID)</label></th>
					<td>
						<input id="svv-brevo-tpl" type="number" name="template" min="0" value="<?php echo (int) $s['template']; ?>" class="small-text">
						<p class="description">ID van een sjabloon met de tag <code>optin</code> en de link <code>{{ doubleoptin }}</code> (Brevo → Campagnes → Sjablonen). Leeg = direct op de lijst, zonder bevestigingsmail.</p>
					</td>
				</tr>
			</table>
			<?php submit_button( 'Opslaan' ); ?>
		</form>
		<?php if ( $err ) : ?>
			<p style="color:#b32d2e"><strong>Laatste fout:</strong> <?php echo esc_html( $err ); ?></p>
		<?php endif; ?>
		<h2>Laatste aanmeldingen</h2>
		<?php if ( $leads ) : ?>
			<table class="widefat striped" style="max-width:720px"><tbody>
			<?php foreach ( $leads as $p ) : ?>
				<tr><td><?php echo esc_html( get_the_date( 'd-m-Y H:i', $p ) ); ?></td><td><?php echo esc_html( get_post_meta( $p->ID, 'svv_email', true ) ); ?></td><td><?php echo esc_html( get_post_meta( $p->ID, 'svv_brevo', true ) ?: 'nog niet naar Brevo' ); ?></td></tr>
			<?php endforeach; ?>
			</tbody></table>
		<?php else : ?>
			<p>Nog geen aanmeldingen.</p>
		<?php endif; ?>
	</div>
	<?php
}
