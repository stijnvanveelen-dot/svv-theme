<?php
/**
 * Formulieren: aanvraag (chat), bakkie (kennismaken), nieuwsbrief.
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
 * Formulier-HTML. $type: aanvraag | bakkie | nieuwsbrief
 */
function svv_form( $type = 'aanvraag', $args = array() ) {
	$args = wp_parse_args( $args, array(
		'button'      => 'Versturen',
		'placeholder' => 'Vraag Stijn iets over je advertenties…',
		'chat'        => false,
		'topic'       => '',
	) );
	$sent = isset( $_GET['verzonden'] ) && sanitize_key( wp_unslash( $_GET['verzonden'] ) ) === $type;
	ob_start();
	if ( $sent ) {
		if ( 'nieuwsbrief' === $type ) {
			$ok = 'Je bent aangemeld. De eerste mail komt zodra er nieuwe testresultaten zijn.';
		} elseif ( 'bakkie' === $type ) {
			$ok = 'Top. Binnen een werkdag krijg je een paar tijden voorgesteld.';
		} else {
			$ok = 'Dank je. Je hoort binnen een werkdag van me.';
		}
		echo '<p class="svv-form__ok" role="status">' . esc_html( $ok );
		$cal = svv_opt( 'svv_calendar', '' );
		if ( 'bakkie' === $type && $cal ) {
			echo ' <a href="' . esc_url( $cal ) . '" rel="noopener">Of kies zelf een moment in de agenda →</a>';
		}
		echo '</p>';
	}
	$action = esc_url( admin_url( 'admin-post.php' ) );
	$uid    = wp_unique_id( 'svvf' );
	?>
	<form class="svv-form svv-form--<?php echo esc_attr( $type ); ?><?php echo $args['chat'] ? ' svv-prompt' : ''; ?>" method="post" action="<?php echo $action; ?>">
		<input type="hidden" name="action" value="svv_lead">
		<input type="hidden" name="svv_type" value="<?php echo esc_attr( $type ); ?>">
		<input type="hidden" name="svv_transcript" value="">
		<input type="hidden" name="svv_page" value="<?php echo esc_url( get_permalink() ?: home_url( '/' ) ); ?>">
		<input type="hidden" name="svv_t" value="">
		<div class="svv-hp" aria-hidden="true"><label>Laat leeg<input type="text" name="svv_website" tabindex="-1" autocomplete="off"></label></div>
		<?php if ( 'nieuwsbrief' === $type ) : ?>
			<label class="svv-sr" for="<?php echo esc_attr( $uid ); ?>-mail">Je e-mailadres</label>
			<input id="<?php echo esc_attr( $uid ); ?>-mail" class="svv-input svv-input--pill" type="email" name="svv_email" required placeholder="je e-mailadres" autocomplete="email">
			<button class="svv-btn" type="submit"><?php echo esc_html( $args['button'] ); ?></button>
		<?php elseif ( 'bakkie' === $type ) : ?>
			<fieldset class="svv-choice">
				<legend class="svv-sr">Waar drinken we koffie?</legend>
				<label><input type="radio" name="svv_where" value="digitaal" checked> Digitaal</label>
				<label><input type="radio" name="svv_where" value="aan tafel"> Aan tafel</label>
			</fieldset>
			<label class="svv-sr" for="<?php echo esc_attr( $uid ); ?>-msg">Waar wil je het over hebben?</label>
			<textarea id="<?php echo esc_attr( $uid ); ?>-msg" class="svv-input" name="svv_message" rows="2" placeholder="<?php echo esc_attr( $args['topic'] ? 'Waar wil je het over hebben? Bijvoorbeeld ' . $args['topic'] . '.' : 'Waar wil je het over hebben? Mag ook kort.' ); ?>"></textarea>
			<label class="svv-sr" for="<?php echo esc_attr( $uid ); ?>-mail">Je zakelijke e-mailadres</label>
			<input id="<?php echo esc_attr( $uid ); ?>-mail" class="svv-input" type="email" name="svv_email" required placeholder="je zakelijke e-mailadres" autocomplete="email">
			<label class="svv-sr" for="<?php echo esc_attr( $uid ); ?>-tel">Telefoonnummer (optioneel)</label>
			<input id="<?php echo esc_attr( $uid ); ?>-tel" class="svv-input" type="tel" name="svv_phone" placeholder="telefoonnummer (optioneel)" autocomplete="tel">
			<button class="svv-btn" type="submit">Bakkie plannen</button>
		<?php else : ?>
			<label class="svv-sr" for="<?php echo esc_attr( $uid ); ?>-msg">Je vraag</label>
			<textarea id="<?php echo esc_attr( $uid ); ?>-msg" name="svv_message" rows="2" required placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"></textarea>
			<div class="svv-prompt__row">
				<label class="svv-prompt__hint" for="<?php echo esc_attr( $uid ); ?>-mail">Waar mag Stijn het antwoord naartoe sturen?</label>
				<input id="<?php echo esc_attr( $uid ); ?>-mail" type="email" name="svv_email" required placeholder="je zakelijke e-mailadres" autocomplete="email">
				<button class="svv-send" type="submit" aria-label="<?php echo esc_attr( $args['button'] ); ?>"><svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="6 11 12 5 18 11"/></svg></button>
			</div>
		<?php endif; ?>
	</form>
	<?php
	return ob_get_clean();
}

/**
 * Tellen hoeveel spam er is tegengehouden (zichtbaar onder Instellingen → AI-assistent).
 */
function svv_spam_count( $reason ) {
	$c = get_option( 'svv_spam', array() );
	$c = is_array( $c ) ? $c : array();
	$c[ $reason ] = (int) ( $c[ $reason ] ?? 0 ) + 1;
	update_option( 'svv_spam', $c, false );
}

function svv_handle_lead() {
	$back = wp_get_referer() ?: home_url( '/' );
	/*
	 * Spamfilter. Geen WordPress-nonce: die verloopt binnen een dag, terwijl de cache
	 * pagina's een week bewaart (formulieren zouden dan stil weigeren). In plaats daarvan:
	 * 1. JavaScript vult svv_t met de tijd op de pagina (bots zonder JS vallen af);
	 * 2. minimaal 3 seconden op de pagina (te snel = bot);
	 * 3. honeypot-veld; 4. max. 5 berichten per uur per IP; 5. max. 2 links in het bericht.
	 */
	$t = sanitize_text_field( wp_unslash( $_POST['svv_t'] ?? '' ) );
	if ( ! preg_match( '/^js-(\d+)$/', $t, $m ) || (int) $m[1] < 3000 ) {
		svv_spam_count( 'tijd' );
		wp_safe_redirect( $back );
		exit;
	}
	// Honeypot: bots vullen dit in.
	if ( ! empty( $_POST['svv_website'] ) ) {
		svv_spam_count( 'honeypot' );
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
	$type    = in_array( $type, array( 'aanvraag', 'bakkie', 'nieuwsbrief' ), true ) ? $type : 'aanvraag';
	$email   = sanitize_email( wp_unslash( $_POST['svv_email'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['svv_message'] ?? '' ) );
	$page    = esc_url_raw( wp_unslash( $_POST['svv_page'] ?? '' ) );
	$phone   = sanitize_text_field( wp_unslash( $_POST['svv_phone'] ?? '' ) );
	$where   = sanitize_text_field( wp_unslash( $_POST['svv_where'] ?? '' ) );
	$where   = in_array( $where, array( 'digitaal', 'aan tafel' ), true ) ? $where : '';
	$transcript = sanitize_textarea_field( wp_unslash( $_POST['svv_transcript'] ?? '' ) );
	if ( $transcript ) {
		$message = trim( $message . "\n\n--- Gesprek met de AI-assistent ---\n" . mb_substr( $transcript, 0, 8000 ) );
	}
	if ( 'bakkie' === $type ) {
		$message = trim( 'Voorkeur: ' . ( $where ?: 'onbekend' ) . ( $phone ? "\nTelefoon: {$phone}" : '' ) . "\n\n" . $message );
	}

	if ( ! is_email( $email ) ) {
		wp_safe_redirect( $back );
		exit;
	}
	if ( preg_match_all( '#https?://|www\.#i', (string) wp_unslash( $_POST['svv_message'] ?? '' ) ) > 2 ) {
		svv_spam_count( 'links' );
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
			'svv_phone' => $phone,
			'svv_where' => $where,
		),
	) );

	$subject = '[stijnvanveelen.nl] ' . ucfirst( $type ) . ' van ' . $email;
	$body    = "Type: {$type}\nE-mail: {$email}\nPagina: {$page}\n\n{$message}";
	wp_mail( get_option( 'admin_email' ), $subject, $body, array( 'Reply-To: ' . $email ) );

	/**
	 * Hook voor CRM-koppeling (Bigin).
	 */
	do_action( 'svv_lead_created', $lead_id, compact( 'type', 'email', 'message', 'page', 'phone', 'where' ) );

	wp_safe_redirect( add_query_arg( 'verzonden', $type, remove_query_arg( 'verzonden', $back ) ) . '#' . ( 'aanvraag' === $type ? 'vraag' : $type ) );
	exit;
}
add_action( 'admin_post_svv_lead', 'svv_handle_lead' );
add_action( 'admin_post_nopriv_svv_lead', 'svv_handle_lead' );
