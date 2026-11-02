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
<div class="svv-shell">

<aside class="svv-side" id="menu">
	<div class="svv-side__top">
		<a class="svv-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="Stijn van Veelen, <?php echo esc_attr( SVV_TAGLINE ); ?> — naar de homepage">
			<?php echo svv_logo( 36 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			<span><strong>Stijn van Veelen</strong><small><?php echo esc_html( SVV_TAGLINE ); ?></small></span>
		</a>
		<button class="svv-menu-toggle" type="button" aria-expanded="false" aria-controls="svv-nav">
			<span class="svv-sr">Menu</span>
			<svg aria-hidden="true" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="4" y1="7" x2="20" y2="7"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="17" x2="20" y2="17"/></svg>
		</button>
	</div>
	<div class="svv-side__body" id="svv-nav">
		<a class="svv-new" href="<?php echo esc_url( home_url( '/' ) ); ?>" data-svv-new>
			<svg aria-hidden="true" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
			Nieuw gesprek
		</a>
		<nav aria-label="Diensten" class="svv-nav">
			<p class="svv-nav__label">Diensten</p>
			<?php foreach ( svv_services() as $slug => $label ) : ?>
				<a href="<?php echo esc_url( svv_service_url( $slug ) ); ?>"<?php echo svv_is_current( $slug ) ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $label ); ?></a>
			<?php endforeach; ?>
			<p class="svv-nav__label">Meer</p>
			<a class="svv-nav__two" href="<?php echo esc_url( home_url( '/#bakkie' ) ); ?>">Bakkie doen?<small>Zwart, met melk of via Teams</small></a>
			<a href="<?php echo esc_url( home_url( '/#nieuwsbrief' ) ); ?>">Ads-testlab</a>
		</nav>
		<p class="svv-status"><span aria-hidden="true"></span>Beschikbaar vanaf <?php echo esc_html( svv_opt( 'svv_available', 'direct' ) ); ?></p>
	</div>
</aside>

<div class="svv-main">
	<div class="svv-topbar">
		<a class="svv-btn svv-btn--sm" href="<?php echo esc_url( home_url( '/#bakkie' ) ); ?>">Bakkie doen?</a>
	</div>
	<main id="inhoud">
