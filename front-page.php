<?php
defined( 'ABSPATH' ) || exit;
get_header();

$faqs  = svv_faqs();
$photo = get_template_directory_uri() . '/assets/img/stijn.jpg';
?>

<section class="svv-hero">
	<div class="svv-narrow">
		<div class="svv-hero__head">
			<img class="svv-portrait" src="<?php echo esc_url( $photo ); ?>" alt="Stijn van Veelen" width="140" height="140" fetchpriority="high">
			<div>
				<p class="svv-greet"><span class="svv-grad" id="svv-greet">Hallo.</span></p>
				<script>(function(){var h=new Date().getHours(),g=h<6?'Goedenacht.':h<12?'Goedemorgen.':h<18?'Goedemiddag.':'Goedenavond.';document.getElementById('svv-greet').textContent=g;})();</script>
				<h1 class="svv-h1">Stijn hier. AI stuurt je advertenties, ik zorg dat ze converteren.</h1>
			</div>
		</div>
		<p class="svv-intro">Twintig jaar Google Ads. Nu met de AI-laag erbovenop: adverteren in ChatGPT, AI Max in Google en Advantage+ in Meta. AI mag fantaseren, je ROAS niet.</p>

		<div class="svv-chat" aria-live="polite">
			<?php foreach ( $faqs as $i => $f ) : ?>
				<div class="svv-turn" data-turn="<?php echo (int) $i; ?>" hidden>
					<p class="svv-q"><?php echo esc_html( $f['q'] ); ?></p>
					<div class="svv-a">
						<img src="<?php echo esc_url( $photo ); ?>" alt="" width="30" height="30" loading="lazy">
						<div>
							<p><?php echo esc_html( $f['a'] ); ?></p>
							<a href="<?php echo esc_url( $f['link'][1] ); ?>"><?php echo esc_html( $f['link'][0] ); ?> →</a>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div id="vraag">
			<?php echo svv_form( 'aanvraag', array( 'chat' => true ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>

		<div class="svv-actions" hidden>
			<a class="svv-chip" href="#bakkie">Bakkie doen?</a>
			<button type="button" class="svv-chip" data-handoff>Laat Stijn zelf reageren</button>
		</div>

		<div class="svv-chips">
			<?php foreach ( $faqs as $i => $f ) : ?>
				<button type="button" class="svv-chip" data-ask="<?php echo (int) $i; ?>"><?php echo esc_html( $f['q'] ); ?></button>
			<?php endforeach; ?>
		</div>
		<p class="svv-disclaimer"><?php echo svv_bot_enabled() ? 'Antwoorden komen van een AI-assistent en kunnen fouten bevatten. Twijfel? Stijn reageert zelf binnen een werkdag.' : 'Stijn reageert zelf, binnen een werkdag.'; ?></p>
		<template id="svv-bot-avatar"><?php echo svv_logo( 30, '#1F252B' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></template>
	</div>
</section>

<?php get_template_part( 'template-parts/bakkie' ); ?>
<?php get_template_part( 'template-parts/nieuwsbrief' ); ?>

<section class="svv-section svv-section--line" id="over">
	<div class="svv-wrap">
		<div class="svv-prose">
			<h2 class="svv-h2">Geen AI-goeroe. Een adverteerder met twintig jaar ervaring.</h2>
			<p>Adverteren doe ik al sinds Google Ads nog AdWords heette: bij bureaus, bij Sneleentaxi en als marketingmanager en MT-lid bij Dialog (Visma). Butlaroo vond me via mijn eigen advertentie en is nu ruim twee jaar klant.</p>
			<p class="svv-soft">Onder New Road Digital bouw en vermarkt ik eigen platforms, zoals <?php echo svv_platform_links(); // phpcs:ignore WordPress.Security.EscapeOutput ?>. Elk nieuw AI-advertentiekanaal gaat daar eerst langs, voordat een klant het aangeraden krijgt.</p>
		</div>
	</div>
</section>

<?php
get_footer();
