<?php
defined( 'ABSPATH' ) || exit;
get_header();
?>
<div class="svv-page"><div class="svv-narrow">
	<header class="svv-page__head">
		<h1 class="svv-h1">Nieuws</h1>
		<p class="svv-muted">Wat er verandert in ChatGPT Ads, Google Ads en Meta, en wat je ermee moet.</p>
	</header>
	<?php if ( have_posts() ) : ?>
		<ul class="svv-list svv-list--nieuws">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<li>
					<div>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						<?php if ( has_excerpt() ) : ?>
							<p class="svv-muted"><?php echo esc_html( get_the_excerpt() ); ?></p>
						<?php endif; ?>
					</div>
					<span class="svv-muted"><?php echo esc_html( get_the_date() ); ?></span>
				</li>
			<?php endwhile; ?>
		</ul>
		<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>
	<?php else : ?>
		<p>Nog geen nieuws.</p>
	<?php endif; ?>
</div></div>
<?php
get_footer();
