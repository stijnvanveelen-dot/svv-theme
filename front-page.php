<?php
defined( 'ABSPATH' ) || exit;
get_header();

$img   = get_template_directory_uri() . '/assets/img/';
$check = '<svg aria-hidden="true" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l4 4 10-10"/></svg>';
$email = svv_opt( 'svv_email', 'info@stijnvanveelen.nl' );

$labels = array( 'Performance marketing', 'SEO &amp; GEO', 'Data &amp; conversie', 'Automation', 'Marketing &amp; sales', 'Teamleiderschap' );

$areas = array(
	'Strategie &amp; positionering',
	'Performance &amp; acquisitie',
	'Vindbaarheid: SEO &amp; GEO',
	'Data, conversie &amp; automation',
	'Marketing &amp; sales verbonden',
	'Team &amp; leiderschap',
);

$steps = array(
	array( 'Meetbaarheid als fundament', 'Eerst de data op orde, dan pas sturen.' ),
	array( 'Groeien waar de vraag zit', 'Investeren in bewezen vraag, niet in hoop.' ),
	array( 'Kanalen en KPI\'s kiezen', 'Elk kanaal een rol, elke rol een KPI.' ),
	array( 'Schalen op bewijs', 'Budget volgt resultaat.' ),
	array( 'De optimale mix', 'Een strategie is nooit af.' ),
);

$jobs = array(
	array( 'Huidig', 'Butlaroo &amp; Steamer Specialist', 'Freelance performance marketing · B2B en e-commerce' ),
	array( 'Vorige', 'FarMedvisie', 'Commercieel Manager · MT-lid · HealthTech' ),
	array( 'Daarvoor', 'Sneleentaxi', 'Senior Online Marketing Manager' ),
	array( 'Eerder', 'Dialog (Visma)', 'Team Lead Marketing · MT-lid · B2B SaaS' ),
	array( 'Eerder', 'Biobestrijding', 'Marketing Manager · merk opgebouwd tot marktleider' ),
);
?>

<section class="svv-hero">
	<div class="svv-container svv-hero__in">
		<div class="svv-hero__text">
			<h1 class="svv-display">Marketing die stuurt op bewijs.</h1>
			<p class="svv-lead">Marketing manager met vijftien jaar ervaring in digital marketing, sales en commerciële leiding.</p>
			<div class="svv-actions">
				<a class="svv-btn svv-btn--lg" href="#contact">Kennismaken</a>
				<a class="svv-btn svv-btn--ghost svv-btn--lg" href="#over">Bekijk mijn loopbaan</a>
			</div>
		</div>
		<div class="svv-hero__visual" aria-hidden="true">
			<span class="svv-hero__circle"></span>
			<span class="svv-hero__dot"></span>
			<span class="svv-hero__mask"><img src="<?php echo esc_url( $img . 'stijn-portret.webp' ); ?>" alt="" width="330" height="319" fetchpriority="high"></span>
		</div>
	</div>
</section>

<div class="svv-container svv-strip-wrap">
	<ul class="svv-strip" aria-label="Kernlabels">
		<?php foreach ( $labels as $label ) : ?>
			<li><?php echo $check; // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo $label; // phpcs:ignore WordPress.Security.EscapeOutput ?></li>
		<?php endforeach; ?>
	</ul>
</div>

<section class="svv-section">
	<div class="svv-container">
		<div class="svv-center">
			<h2 class="svv-h2 svv-h2--xl">Groei is geen campagne. Groei is een systeem.</h2>
			<p class="svv-lead">Ik bouw marketing die kanalen laat samenwerken, op data stuurt en meegroeit met de business.</p>
		</div>
		<div class="svv-features">
			<div class="svv-feature">
				<span class="svv-icon" aria-hidden="true"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M4 20V10M10 20V4M16 20v-7M22 20H2"/></svg></span>
				<h3>Meetbaar vanaf dag één</h3>
				<p>Een sluitende datacollectie laat zien welk kanaal echt bijdraagt aan omzet. Wie niet meet, stuurt op gevoel.</p>
			</div>
			<div class="svv-feature">
				<span class="svv-icon" aria-hidden="true"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="6" cy="6" r="3"/><circle cx="18" cy="6" r="3"/><circle cx="12" cy="18" r="3"/><path d="M8 8l3 7M16 8l-3 7"/></svg></span>
				<h3>Kanalen als één systeem</h3>
				<p>Het ene kanaal levert bereik, het andere conversie. Samen leveren ze meer op dan los van elkaar.</p>
			</div>
			<div class="svv-feature">
				<span class="svv-icon" aria-hidden="true"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="9" cy="8" r="3.5"/><path d="M2 20c0-3.5 3-6 7-6s7 2.5 7 6M16 4.5a3.5 3.5 0 010 7M18 14c2.5.6 4 2.8 4 6"/></svg></span>
				<h3>Het team erachter</h3>
				<p>Design, data, copy en performance in één team. Strategie is zo sterk als de mensen die hem uitvoeren.</p>
			</div>
		</div>
	</div>
</section>

<section class="svv-section svv-section--stone" id="werkgebieden">
	<div class="svv-container svv-split">
		<div class="svv-split__text">
			<p class="svv-eyebrow">Werkgebieden</p>
			<h2 class="svv-h2">Marketing met een commerciële bril.</h2>
			<p class="svv-lead">Van positionering tot pipeline: de gebieden waarop ik strategie bepaal, teams aanstuur en zelf de diepte in kan.</p>
			<ul class="svv-areas">
				<?php foreach ( $areas as $area ) : ?>
					<li><?php echo $area; // phpcs:ignore WordPress.Security.EscapeOutput ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<div class="svv-dash" role="img" aria-label="Illustratie van een marketingdashboard met kanaalmix, trend en funnel">
			<span class="svv-dash__sun" aria-hidden="true"></span>
			<span class="svv-dash__ring" aria-hidden="true"></span>
			<div class="svv-dash__card" aria-hidden="true">
				<div class="svv-dash__head"><p>Groeidashboard</p><span>Afgelopen 90 dagen</span></div>
				<div class="svv-dash__kpis">
					<div class="is-blue"><span>ROAS</span><strong>▲ stijgend</strong></div>
					<div class="is-yellow"><span>CPL</span><strong>▼ dalend</strong></div>
					<div class="is-green"><span>Leads</span><strong>▲ groeiend</strong></div>
				</div>
				<div class="svv-dash__row">
					<div class="svv-dash__bars"><span>Trend per week</span><div><i style="height:34%"></i><i style="height:42%"></i><i style="height:38%"></i><i class="m" style="height:55%"></i><i class="m" style="height:63%"></i><i class="b" style="height:72%"></i><i class="b" style="height:88%"></i><i class="y" style="height:100%"></i></div></div>
					<div class="svv-dash__donut"><span>Kanaalmix</span><b></b></div>
				</div>
				<div class="svv-dash__tags"><em class="b">Google Ads</em><em class="y">SEO &amp; GEO</em><em class="m">Meta</em><em class="g">LinkedIn</em></div>
			</div>
		</div>
	</div>
</section>

<section class="svv-section" id="werkwijze">
	<div class="svv-container">
		<p class="svv-eyebrow">Werkwijze</p>
		<h2 class="svv-h2">Meten, kiezen, schalen, optimaliseren.</h2>
		<ol class="svv-steps">
			<?php foreach ( $steps as $i => $step ) : ?>
				<li><span><?php echo (int) $i + 1; ?></span><h3><?php echo esc_html( $step[0] ); ?></h3><p><?php echo esc_html( $step[1] ); ?></p></li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<section class="svv-section svv-section--stone" id="platforms">
	<div class="svv-container">
		<p class="svv-eyebrow">Eigen platforms</p>
		<h2 class="svv-h2">Mijn proeftuin voor nieuwe kanalen en techniek.</h2>
		<div class="svv-platforms">
			<a class="svv-platform" href="https://www.autobaak.nl/" rel="noopener">
				<span class="svv-platform__visual is-yellow"><span class="svv-platform__logo is-left"><img src="<?php echo esc_url( $img . 'autobaak-logo.webp' ); ?>" alt="Autobaak – dé automotive site van NL" width="300" height="76" loading="lazy"></span></span>
				<span class="svv-platform__body"><strong>Autobaak</strong><span>Dé automotive site van Nederland, met vacatures, APK-stations en een kentekencheck. Honderden bezoekers per dag, en ik ontwikkel het platform technisch verder.</span></span>
			</a>
			<a class="svv-platform" href="https://therapievergelijk.nl/" rel="noopener">
				<span class="svv-platform__visual is-mint"><span class="svv-platform__logo is-right"><img src="<?php echo esc_url( $img . 'therapievergelijk-logo.svg' ); ?>" alt="Therapievergelijk" width="320" height="77" loading="lazy"></span></span>
				<span class="svv-platform__body"><strong>Therapievergelijk</strong><span>Een vergelijkingsplatform voor therapeuten en praktijken, dat ik technisch verder ontwikkel. Automations zijn de rode draad: van leadopvolging tot rapportage.</span></span>
			</a>
		</div>
	</div>
</section>

<section class="svv-section" id="over">
	<div class="svv-container">
		<p class="svv-eyebrow">Over mij</p>
		<h2 class="svv-h2">Van e-commerce tot SaaS.</h2>
		<div class="svv-about">
			<div class="svv-about__text">
				<p>Mijn loopbaan begon in digital marketing en sales en verschoof steeds verder richting commerciële verantwoordelijkheid. De rode draad: data, commercie, structuur en mensen met elkaar verbinden.</p>
				<p>In de loop der jaren heb ik met verschillende founders en managers gewerkt, ieder met een eigen visie. Wat ze opvallend vaak gemeen hadden: niet eindeloos analyseren, maar in beweging komen. Beslissen, uitvoeren, leren en het de volgende keer beter doen. Die combinatie is ook mijn manier van werken geworden.</p>
			</div>
			<div class="svv-jobs">
				<span class="svv-jobs__line" aria-hidden="true"></span>
				<span class="svv-jobs__dot" aria-hidden="true"></span>
				<ol class="svv-jobs__card">
					<?php foreach ( $jobs as $i => $job ) : ?>
						<li><span class="<?php echo 0 === $i ? 'is-now' : ''; ?>"><?php echo esc_html( $job[0] ); ?></span><strong><?php echo $job[1]; // phpcs:ignore WordPress.Security.EscapeOutput ?></strong><em><?php echo esc_html( $job[2] ); ?></em></li>
					<?php endforeach; ?>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="svv-section svv-section--tight" id="contact">
	<div class="svv-container">
		<div class="svv-contact">
			<div class="svv-contact__text">
				<h2 class="svv-h2">Kennismaken?</h2>
				<p>Op zoek naar een marketing manager die strategie en uitvoering verbindt? Ik maak graag kennis, online of bij een kop koffie.</p>
				<p class="svv-contact__links"><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a> · <a href="<?php echo esc_url( svv_opt( 'svv_linkedin', 'https://www.linkedin.com/in/stijn-van-veelen' ) ); ?>" rel="noopener">LinkedIn</a></p>
			</div>
			<div class="svv-cvbox" id="cv">
				<h3>CV opvragen</h3>
				<p>Laat je gegevens achter, dan stuur ik je mijn CV persoonlijk toe.</p>
				<?php echo svv_form( 'cv' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
