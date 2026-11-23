<?php
/**
 * Landingspagina Ads-testlab. Bestemmings-URL voor advertenties op aanmeldingen:
 * /ads-testlab/?utm_source=chatgpt_ads&utm_medium=cpc&utm_campaign=ads_testlab#aanmelden
 */
defined( 'ABSPATH' ) || exit;
get_header();

?>

<section class="svv-hero svv-hero--page">
	<div class="svv-container">
		<p class="svv-eyebrow">Voor ondernemers · Google · Meta · ChatGPT</p>
		<h1 class="svv-display svv-display--md">AI stuurt je advertenties, ik zorg dat ze converteren.</h1>
		<p class="svv-lead">Vijftien jaar Google Ads-ervaring, nu met een AI-laag: ChatGPT-ads, AI Max in Google en Advantage+ in Meta. Elk nieuw kanaal draait eerst op eigen platforms, zoals <?php echo svv_platform_links(); // phpcs:ignore WordPress.Security.EscapeOutput ?>.</p>
	</div>
</section>

<?php get_template_part( 'template-parts/bakkie' ); ?>

<section class="svv-section" id="aanmelden">
	<div class="svv-container">
		<div class="svv-panel" id="nieuwsbrief">
			<div class="svv-panel__text">
				<h2 class="svv-h2">Aanmelden</h2>
				<p class="svv-soft">Een mail zodra er nieuwe resultaten zijn, zonder vaste dag. Afmelden kan met één klik.</p>
			</div>
			<?php echo svv_form( 'nieuwsbrief', array( 'button' => 'Stuur me de resultaten' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>

<section class="svv-section svv-section--line">
	<div class="svv-container">
		<h2 class="svv-h2">Wat er in een mail staat</h2>
		<div class="svv-cards">
			<div class="svv-card">
				<h3>De cijfers</h3>
				<p>Budget, vertoningen, klikken, kosten per klik en per aanmelding of verkoop. Ook als een test tegenvalt.</p>
			</div>
			<div class="svv-card">
				<h3>De instellingen</h3>
				<p>Biedstrategie, contexthints en advertentieteksten: wat er aanstond en wat er na een week is aangepast.</p>
			</div>
			<div class="svv-card">
				<h3>Wat je kunt overnemen</h3>
				<p>Per test de instellingen die het waard zijn om in je eigen account te proberen, en welke je beter laat liggen.</p>
			</div>
			<div class="svv-card">
				<h3>Meer dan ChatGPT</h3>
				<p>AI Max in Google Ads en Advantage+ in Meta komen ook langs, zodra er iets te melden valt.</p>
			</div>
		</div>
		<p class="svv-muted" style="margin-top:22px">Eigen campagnes die beter kunnen: de <a href="#bakkie">gratis scan met een bakkie</a> staat ook open.</p>
	</div>
</section>

<?php
get_footer();
