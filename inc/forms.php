<?php
/**
 * Formulieren: contact/aanvraag, Meetscan, nieuwsbrief.
 * Versie 1: mail naar beheerder + opslaan als privé-bericht (type svv_lead).
 * Bigin-koppeling volgt in inc/bigin.php.
 */

defined( 'ABSPATH' ) || exit;

add_action( 'init', function () {
	register_post_type( 'svv_lead', array(
		'label'        => 'Aanvragen',
		'public'       => false,
		'show_ui'      => true,
		'menu_icon'    => 'dashicons-email-alt',
		'supports'     => array( 'title', 'editor', 'custom-fields' ),
		'capabilities' => array( 'create_posts' => 'do_not_allow' ),
		'map_meta_cap' => true,
	) );
} );

/**
 * Formulier-HTML. $type: aanvraag | meetscan | nieuwsbrief
 */
function svv_form( $type = 'aanvraag', $args = array() ) {
	$args = wp_parse_args( $args, array(
		'button'      => 'Versturen',
		'placeholder' => 'Vraag Stijn iets over je advertenties…',
		'chat'        => false,
	) );
	$sent = isset( $_GET['verzonden'] ) && sanitize_key( wp_unslash( $_GET['verzonden'] ) ) === $type;
	ob_start();
	if ( $sent ) {
		echo '<p class="svv-form__ok" role="status">' . ( 'nieuwsbrief' === $type ? 'Je bent aangemeld. Je eerste mail komt deze week.' : 'Dank je. Je hoort binnen een werkdag van me.' ) . '</p>';
	}
	$action = esc_url( admin_url( 'admin-post.php' ) );
	$uid    = wp_unique_id( 'svvf' );
	?>
	<form class="svv-form svv-form--<?php echo esc_attr( $type ); ?><?php echo $args['chat'] ? ' svv-prompt' : ''; ?>" method="post" action="<?php echo $action; ?>">
		<input type="hidden" name="action" value="svv_lead">
		<input type="hidden" name="svv_type" value="<?php echo esc_attr( $type ); ?>">
		<input type="hidden" name="svv_page" value="<?php echo esc_url( get_permalink() ?: home_url( '/' ) ); ?>">
		<?php wp_nonce_field( 'svv_lead', 'svv_nonce' ); ?>
		<div class="svv-hp" aria-hidden="true"><label>Laat leeg<input type="text" name="svv_website" tabindex="-1" autocomplete="off"></label></div>
		<?php if ( 'nieuwsbrief' === $type ) : ?>
			<label class="svv-sr" for="<?php echo esc_attr( $uid ); ?>-mail">Je e-mailadres</label>
			<input id="<?php echo esc_attr( $uid ); ?>-mail" class="svv-input svv-input--pill" type="email" name="svv_email" required placeholder="je e-mailadres" autocomplete="email">
			<button class="svv-btn" type="submit"><?php echo esc_html( $args['button'] ); ?></button>
		<?php else : ?>
			<label class="svv-sr" for="<?php echo esc_attr( $uid ); ?>-msg">Je vraag</label>
			<textarea id="<?php echo esc_attr( $uid ); ?>-msg" name="svv_message" rows="2" required placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"></textarea>
			<div class="svv-prompt__row">
				<label class="svv-sr" for="<?php echo esc_attr( $uid ); ?>-mail">Je zakelijke e-mailadres</label>
				<input id="<?php echo esc_attr( $uid ); ?>-mail" type="email" name="svv_email" required placeholder="je zakelijke e-mailadres" autocomplete="email">
				<button class="svv-send" type="submit" aria-label="<?php echo esc_attr( $args['button'] ); ?>"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="6 11 12 5 18 11"/></svg></button>
			</div>
		<?php endif; ?>
	</form>
	<?php
	return ob_get_clean();
}

function svv_handle_lead() {
	$back = wp_get_referer() ?: home_url( '/' );
	if ( ! isset( $_POST['svv_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['svv_nonce'] ) ), 'svv_lead' ) ) {
		wp_safe_redirect( $back );
		exit;
	}
	// Honeypot: bots vullen dit in.
	if ( ! empty( $_POST['svv_website'] ) ) {
		wp_safe_redirect( $back );
		exit;
	}
	// Eenvoudige rate limit per IP-hash (5 per uur).
	$ip_key = 'svv_rl_' . md5( (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) );
	$count  = (int) get_transient( $ip_key );
	if ( $count >= 5 ) {
		wp_safe_redirect( $back );
		exit;
	}
	set_transient( $ip_key, $count + 1, HOUR_IN_SECONDS );

	$type    = sanitize_key( wp_unslash( $_POST['svv_type'] ?? 'aanvraag' ) );
	$type    = in_array( $type, array( 'aanvraag', 'meetscan', 'nieuwsbrief' ), true ) ? $type : 'aanvraag';
	$email   = sanitize_email( wp_unslash( $_POST['svv_email'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['svv_message'] ?? '' ) );
	$page    = esc_url_raw( wp_unslash( $_POST['svv_page'] ?? '' ) );

	if ( ! is_email( $email ) ) {
		wp_safe_redirect( $back );
		exit;
	}

	$lead_id = wp_insert_post( array(
		'post_type'    => 'svv_lead',
		'post_status'  => 'private',
		'post_title'   => ucfirst( $type ) . ' — ' . $email,
		'post_content' => $message,
		'meta_input'   => array(
			'svv_type'  => $type,
			'svv_email' => $email,
			'svv_page'  => $page,
		),
	) );

	$subject = '[stijnvanveelen.nl] ' . ucfirst( $type ) . ' van ' . $email;
	$body    = "Type: {$type}\nE-mail: {$email}\nPagina: {$page}\n\n{$message}";
	wp_mail( get_option( 'admin_email' ), $subject, $body, array( 'Reply-To: ' . $email ) );

	/**
	 * Hook voor CRM-koppeling (Bigin).
	 */
	do_action( 'svv_lead_created', $lead_id, compact( 'type', 'email', 'message', 'page' ) );

	wp_safe_redirect( add_query_arg( 'verzonden', $type, remove_query_arg( 'verzonden', $back ) ) . '#' . ( 'nieuwsbrief' === $type ? 'nieuwsbrief' : 'vraag' ) );
	exit;
}
add_action( 'admin_post_svv_lead', 'svv_handle_lead' );
add_action( 'admin_post_nopriv_svv_lead', 'svv_handle_lead' );
