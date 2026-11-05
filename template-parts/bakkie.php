<?php
defined( 'ABSPATH' ) || exit;
$topic = isset( $args['topic'] ) ? $args['topic'] : '';
?>
<section class="svv-section svv-section--deep" id="bakkie">
	<div class="svv-wrap">
		<div class="svv-bakkie">
			<div class="svv-bakkie__text">
				<p class="svv-eyebrow"><span class="svv-grad">Kennismaken</span></p>
				<h2 class="svv-h2">Gratis scan, met een bakkie erbij</h2>
				<p class="svv-soft">Een gratis scan van je advertentieaccounts: waar lekt budget weg en waar valt meer uit te halen. De uitkomst bespreken we aan tafel of digitaal via Teams of Google Meet. Digitaal zet je zelf de koffie, aan tafel trakteer ik.</p>
				<p class="svv-muted">Hoe we daarna verder gaan en wat dat kost, spreken we in dat gesprek af.</p>
			</div>
			<?php echo svv_form( 'bakkie', array( 'topic' => $topic ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>
