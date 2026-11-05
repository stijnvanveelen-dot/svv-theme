<?php
/**
 * Landingspagina Ads-testlab. Bestemmings-URL voor advertenties op aanmeldingen:
 * /ads-testlab/?utm_source=chatgpt_ads&utm_medium=cpc&utm_campaign=ads_testlab#aanmelden
 */
defined( 'ABSPATH' ) || exit;
get_header();

$photo = get_template_directory_uri() . '/assets/img/stijn.jpg';
?>

<section class="svv-hero">
	<div class="svv-narrow">
		<div class="svv-hero__head">
			<img class="svv-portrait" src="<?php echo esc_url( $photo ); ?>" alt="Stijn van Veelen" width="140" height="140" fetchpriority="high">
			<div>
				<p class="svv-eyebrow"><span class="svv-grad">Ads-testlab</span></p>
				<h1 class="svv-h1">ChatGPT Ads getest met echt budget. De cijfers komen in je mail.</h1>
			</div>
		</div>
		<p class="svv-intro">Elk nieuw advertentiekanaal draait eerst op eigen platforms, zoals <?php echo svv_platform_links(); // phpcs:ignore WordPress.Security.EscapeOutput ?>. Wat een test kost, wat hij oplevert en welke instelling het verschil maakte, lees je in het Ads-testlab.</p>
	</div>
</section>

<section class="svv-section" id="aanmelden">
	<div class="svv-wrap">
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
	<div class="svv-wrap">
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
		<p class="svv-muted" style="margin-top:22px">Eigen campagnes die beter kunnen: de <a href="<?php echo esc_url( home_url( '/#bakkie' ) ); ?>">gratis scan met een bakkie</a> staat ook open.</p>
	</div>
</section>

<?php
get_footer();
