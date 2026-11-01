<?php defined( 'ABSPATH' ) || exit; ?>
<section class="svv-section svv-section--deep" id="modellen">
	<div class="svv-wrap">
		<h2 class="svv-h2">Kies je model</h2>
		<p class="svv-lead">Begin met kloppende cijfers. Schaal daarna op wat werkt.</p>
		<div class="svv-cards">
			<article class="svv-card">
				<h3>Meetscan</h3>
				<p class="svv-muted">GA4 en Search Console, eenmalig</p>
				<p>Waar komt je verkeer echt vandaan, en kloppen je conversies? Binnen <?php echo esc_html( svv_opt( 'svv_days_scan', '[x]' ) ); ?> werkdagen een overzicht van wat je meting vertekent en wat je moet aanpassen.</p>
				<p class="svv-price">€ <?php echo esc_html( svv_opt( 'svv_price_scan', '[prijs]' ) ); ?> eenmalig</p>
			</article>
			<article class="svv-card svv-card--hl">
				<h3><span class="svv-grad">ChatGPT Ads-pilot</span></h3>
				<p class="svv-muted">Nieuw in Nederland</p>
				<p>Opzet, meting en <?php echo esc_html( svv_opt( 'svv_weeks_pilot', '[x]' ) ); ?> weken testen met jouw budget. Daarna een eerlijk verslag: werkt dit kanaal voor jou, ja of nee.</p>
				<p class="svv-price">€ <?php echo esc_html( svv_opt( 'svv_price_pilot', '[prijs]' ) ); ?> + mediabudget</p>
			</article>
			<article class="svv-card">
				<h3>Beheer</h3>
				<p class="svv-muted">Doorlopend, per maand</p>
				<p>Google, Microsoft, Meta en ChatGPT onder één hand. Wekelijks bijsturen, maandelijks een kort overzicht van kosten en aanvragen.</p>
				<p class="svv-price">vanaf € <?php echo esc_html( svv_opt( 'svv_price_beheer', '[prijs]' ) ); ?> p/m</p>
			</article>
		</div>
	</div>
</section>
