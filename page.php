<?php
defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	$slug       = get_post_field( 'post_name' );
	$is_service = array_key_exists( $slug, svv_services() );
	?>
	<article class="svv-page<?php echo $is_service ? ' svv-page--service' : ''; ?>">
		<header class="svv-narrow svv-page__head">
			<?php if ( $is_service ) : ?>
				<p class="svv-eyebrow"><span class="svv-grad"><?php echo esc_html( svv_services()[ $slug ] ); ?></span></p>
			<?php endif; ?>
			<h1 class="svv-h1"><?php the_title(); ?></h1>
			<?php if ( has_excerpt() ) : ?>
				<p class="svv-intro"><?php echo esc_html( get_the_excerpt() ); ?></p>
			<?php endif; ?>
		</header>
		<div class="svv-narrow svv-content">
			<?php the_content(); ?>
		</div>
	</article>

	<?php if ( $is_service ) : ?>
		<?php get_template_part( 'template-parts/bakkie', null, array( 'topic' => svv_services()[ $slug ] ) ); ?>
		<?php get_template_part( 'template-parts/nieuwsbrief' ); ?>
	<?php endif; ?>
	<?php
endwhile;

get_footer();
