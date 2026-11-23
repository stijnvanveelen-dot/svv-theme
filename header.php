<?php defined( 'ABSPATH' ) || exit; ?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="svv-skip" href="#inhoud">Naar de inhoud</a>
<?php $svv_home = home_url( '/' ); ?>
<header class="svv-header">
	<div class="svv-container svv-header__in">
		<a class="svv-brand" href="<?php echo esc_url( $svv_home ); ?>" aria-label="Stijn van Veelen, <?php echo esc_attr( SVV_TAGLINE ); ?> — naar de homepage">
			<?php echo svv_logo( 40 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</a>
		<button class="svv-menu-toggle" type="button" aria-expanded="false" aria-controls="svv-nav">
			<span class="svv-sr">Menu</span>
			<svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg>
		</button>
		<nav class="svv-nav" id="svv-nav" aria-label="Hoofdmenu">
			<a href="<?php echo esc_url( $svv_home . '#werkgebieden' ); ?>">Werkgebieden</a>
			<a href="<?php echo esc_url( $svv_home . '#werkwijze' ); ?>">Werkwijze</a>
			<a href="<?php echo esc_url( $svv_home . '#over' ); ?>">Over mij</a>
			<a href="<?php echo esc_url( home_url( '/nieuws/' ) ); ?>"<?php echo ( is_post_type_archive( 'nieuws' ) || is_singular( 'nieuws' ) ) ? ' aria-current="page"' : ''; ?>>Nieuws</a>
			<a class="svv-btn svv-btn--ghost svv-btn--sm" href="<?php echo esc_url( $svv_home . '#contact' ); ?>">Contact</a>
			<a class="svv-btn svv-btn--sm" href="<?php echo esc_url( $svv_home . '#cv' ); ?>">CV opvragen</a>
		</nav>
	</div>
</header>
<main id="inhoud">
