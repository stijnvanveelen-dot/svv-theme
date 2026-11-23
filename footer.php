<?php defined( 'ABSPATH' ) || exit; ?>
</main>
<footer class="svv-footer">
	<div class="svv-container svv-footer__in">
		<span>Stijn van Veelen · KvK <?php echo esc_html( svv_opt( 'svv_kvk', '70585245' ) ); ?></span>
		<nav aria-label="Voettekst">
			<a href="<?php echo esc_url( home_url( '/ads-testlab/' ) ); ?>">Voor ondernemers: ads-scan &amp; testlab</a>
			<a href="<?php echo esc_url( svv_opt( 'svv_linkedin', 'https://www.linkedin.com/in/stijn-van-veelen' ) ); ?>" rel="noopener">LinkedIn</a>
			<a href="<?php echo esc_url( home_url( '/privacy-statement/' ) ); ?>">Privacy</a>
			<a href="#" data-svv-consent-open>Cookie-instellingen</a>
		</nav>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
