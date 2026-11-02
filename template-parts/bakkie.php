<?php
defined( 'ABSPATH' ) || exit;
$topic = isset( $args['topic'] ) ? $args['topic'] : '';
?>
<section class="svv-section svv-section--deep" id="bakkie">
	<div class="svv-wrap">
		<div class="svv-bakkie">
			<div class="svv-bakkie__text">
				<p class="svv-eyebrow"><span class="svv-grad">Kennismaken</span></p>
				<h2 class="svv-h2">Bakkie doen?</h2>
				<p class="svv-soft">Twintig minuten over je advertenties, zonder verkooppraatje. Aan tafel of digitaal via Teams of Google Meet. Digitaal zet je zelf de koffie, aan tafel trakteer ik.</p>
				<p class="svv-muted">Tarieven bespreken we gewoon tijdens het gesprek.</p>
			</div>
			<?php echo svv_form( 'bakkie', array( 'topic' => $topic ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>
