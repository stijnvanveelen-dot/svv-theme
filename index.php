<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
<div class="svv-narrow svv-page">
	<header class="svv-page__head">
		<h1 class="svv-h1"><?php echo is_home() ? 'Blog' : esc_html( wp_strip_all_tags( get_the_archive_title() ) ); ?></h1>
	</header>
	<?php if ( have_posts() ) : ?>
		<ul class="svv-list">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<li>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					<span class="svv-muted"><?php echo esc_html( get_the_date() ); ?></span>
				</li>
			<?php endwhile; ?>
		</ul>
		<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>
	<?php else : ?>
		<p>Nog niets te lezen.</p>
	<?php endif; ?>
</div>
<?php
get_footer();
