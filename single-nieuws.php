<?php
defined( 'ABSPATH' ) || exit;
get_header();
while ( have_posts() ) :
	the_post();
	?>
	<article class="svv-page">
		<header class="svv-narrow svv-page__head">
			<p class="svv-muted"><a href="<?php echo esc_url( get_post_type_archive_link( 'nieuws' ) ); ?>">Nieuws</a> · <?php echo esc_html( get_the_date() ); ?></p>
			<h1 class="svv-h1"><?php the_title(); ?></h1>
		</header>
		<div class="svv-narrow svv-content">
			<?php the_content(); ?>
			<?php echo svv_bron(); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</article>
	<?php get_template_part( 'template-parts/nieuwsbrief' ); ?>
	<?php
endwhile;
get_footer();
