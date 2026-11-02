<?php defined( 'ABSPATH' ) || exit; ?>
	</main>
	<footer class="svv-footer">
		<div class="svv-wrap svv-footer__in">
			<span>Stijn van Veelen · <?php echo esc_html( SVV_TAGLINE ); ?> · KvK <?php echo esc_html( svv_opt( 'svv_kvk', '70585245' ) ); ?></span>
			<nav aria-label="Voettekst">
				<a href="<?php echo esc_url( home_url( '/privacy/' ) ); ?>">Privacy</a>
				<a href="<?php echo esc_url( home_url( '/cookies/' ) ); ?>">Cookies</a>
				<a href="<?php echo esc_url( svv_opt( 'svv_linkedin', 'https://www.linkedin.com/in/stijn-van-veelen' ) ); ?>" rel="noopener">LinkedIn</a>
				<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a>
			</nav>
		</div>
	</footer>
</div><!-- .svv-main -->
</div><!-- .svv-shell -->
<?php wp_footer(); ?>
</body>
</html>
