<?php
defined( 'ABSPATH' ) || exit;
get_header();

$hour     = (int) current_time( 'G' );
$greeting = $hour < 6 ? 'Goedenacht.' : ( $hour < 12 ? 'Goedemorgen.' : ( $hour < 18 ? 'Goedemiddag.' : 'Goedenavond.' ) );

$faqs = array(
	array(
		'q'    => 'Kan ik adverteren in ChatGPT?',
		'a'    => 'Ja, sinds eind augustus ook in Nederland. Ik heb toegang en test deze maand op mijn eigen platforms. Wil je mee in een pilot? Vaste opzet, jouw budget, en na ' . svv_opt( 'svv_weeks_pilot', '[x]' ) . ' weken een eerlijk verslag.',
		'link' => array( 'Adverteren in ChatGPT', svv_service_url( 'chatgpt-adverteren' ) ),
	),
	array(
		'q'    => 'Google zet mijn campagnes om naar AI Max. Wat nu?',
		'a'    => 'Google zet dynamische zoekadvertenties en brede instellingen automatisch om naar AI Max. Zonder merkuitsluitingen en goede uitsluitingszoekwoorden betaal je dan vaak meer per aanvraag. Ik richt het in en houd toezicht.',
		'link' => array( 'Google Ads & AI Max', svv_service_url( 'sea-specialist-google-ads' ) ),
	),
	array(
		'q'    => 'Kloppen mijn cijfers in GA4 eigenlijk wel?',
		'a'    => 'Vaak niet helemaal. Dubbel geteld verkeer, betalingen die als eigen bron tellen of bezoekers zonder toestemming vertekenen het beeld. De Meetscan laat zien waar je verkeer echt vandaan komt en welke conversies kloppen.',
		'link' => array( 'Meetscan', home_url( '/#modellen' ) ),
	),
	array(
		'q'    => 'Word ik gevonden in ChatGPT?',
		'a'    => 'Dat hangt af van hoe je site in elkaar zit en wat er over je bedrijf online staat. Met SEO en GEO zorg ik dat je gevonden wordt in Google én genoemd in AI-antwoorden.',
		'link' => array( 'SEO & GEO', svv_service_url( 'seo-specialist-inhuren-op-freelance-basis' ) ),
	),
);
$photo = get_template_directory_uri() . '/assets/img/stijn.jpg';
?>

<section class="svv-hero">
	<div class="svv-narrow">
		<div class="svv-hero__head">
			<img class="svv-portrait" src="<?php echo esc_url( $photo ); ?>" alt="Stijn van Veelen" width="140" height="140" fetchpriority="high">
			<div>
				<p class="svv-greet"><span class="svv-grad"><?php echo esc_html( $greeting ); ?></span></p>
				<h1 class="svv-h1">Ik ben Stijn. AI zet je advertenties in, ik zorg dat ze iets opleveren.</h1>
			</div>
		</div>
		<p class="svv-intro">Twintig jaar Google Ads. Nu met de AI-laag erbovenop: adverteren in ChatGPT, AI Max in Google en Advantage+ in Meta. Geen chatbot, gewoon ik.</p>

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

		<div class="svv-chips">
			<?php foreach ( $faqs as $i => $f ) : ?>
				<button type="button" class="svv-chip" data-ask="<?php echo (int) $i; ?>"><?php echo esc_html( $f['q'] ); ?></button>
			<?php endforeach; ?>
		</div>
		<p class="svv-disclaimer">Stijn kan fouten maken. Daarom test hij alles eerst met eigen budget.</p>
	</div>
</section>

<?php get_template_part( 'template-parts/modellen' ); ?>
<?php get_template_part( 'template-parts/nieuwsbrief' ); ?>

<section class="svv-section svv-section--line" id="over">
	<div class="svv-wrap">
		<div class="svv-prose">
			<h2 class="svv-h2">Geen AI-goeroe. Een adverteerder met twintig jaar ervaring.</h2>
			<p>Ik adverteer al sinds Google Ads nog AdWords heette: bij bureaus, bij Sneleentaxi en als marketingmanager en MT-lid bij Dialog (Visma). Butlaroo vond me via mijn eigen advertentie en is nu ruim twee jaar klant.</p>
			<p class="svv-soft">Onder New Road Digital bouw en vermarkt ik mijn eigen platforms, zoals Autobaak en Therapievergelijk. Daar test ik elk nieuw AI-advertentiekanaal eerst met mijn eigen geld, voordat ik het een klant aanraad.</p>
		</div>
	</div>
</section>

<?php
get_footer();
