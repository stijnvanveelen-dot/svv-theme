<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
<div class="svv-narrow svv-page">
	<header class="svv-page__head">
		<p class="svv-greet"><span class="svv-grad">Hmm.</span></p>
		<h1 class="svv-h1">Deze pagina bestaat niet (meer).</h1>
		<p class="svv-intro">Misschien zoek je een van deze:</p>
	</header>
	<ul class="svv-list">
		<?php foreach ( svv_services() as $slug => $label ) : ?>
			<li><a href="<?php echo esc_url( svv_service_url( $slug ) ); ?>"><?php echo esc_html( $label ); ?></a></li>
		<?php endforeach; ?>
	</ul>
</div>
<?php
get_footer();
