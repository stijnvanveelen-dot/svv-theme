<?php
/**
 * Eigen cookiemelding (zelfde opzet als NRD Core), vervangt Real Cookie Banner.
 *
 * - Google Consent Mode v2: standaard alles "denied".
 * - Alleen statistieken: GA4 rechtstreeks (zonder GTM).
 * - Marketing (of Accepteren): Google Tag Manager laadt. In die container zitten ook
 *   eigen HTML-tags (o.a. Meta-pixel) die zelf geen toestemming checken; daarom laadt
 *   GTM pas na toestemming voor marketing.
 * - Keuze 12 maanden in de browser (localStorage), dus werkt met paginacache.
 * - dataLayer-event `svv_consent` met svv_analytics / svv_marketing (0/1).
 */

defined( 'ABSPATH' ) || exit;

define( 'SVV_GTM_ID', 'GTM-5MM8R23' );
define( 'SVV_GA4_ID', 'G-HH1K99SC2M' );

add_action( 'wp_head', function () {
	if ( is_admin() ) {
		return;
	}
	?>
<script>
window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}
gtag('consent','default',{ad_storage:'denied',ad_user_data:'denied',ad_personalization:'denied',analytics_storage:'denied',functionality_storage:'granted',security_storage:'granted',wait_for_update:500});
gtag('set','ads_data_redaction',true);gtag('set','url_passthrough',true);
window.svvConsent=(function(){
 var GTM=<?php echo wp_json_encode( SVV_GTM_ID ); ?>,GA4=<?php echo wp_json_encode( SVV_GA4_ID ); ?>,loaded={};
 function load(src){if(loaded[src])return;loaded[src]=1;var s=document.createElement('script');s.async=true;s.src=src;document.head.appendChild(s);}
 function apply(c){
  gtag('consent','update',{analytics_storage:c.a?'granted':'denied',ad_storage:c.m?'granted':'denied',ad_user_data:c.m?'granted':'denied',ad_personalization:c.m?'granted':'denied'});
  dataLayer.push({event:'svv_consent',svv_analytics:c.a?1:0,svv_marketing:c.m?1:0});
  if(c.m){dataLayer.push({'gtm.start':Date.now(),event:'gtm.js'});load('https://www.googletagmanager.com/gtm.js?id='+GTM);}
  else if(c.a){load('https://www.googletagmanager.com/gtag/js?id='+GA4);gtag('js',new Date());gtag('config',GA4);}
 }
 function get(){try{var c=JSON.parse(localStorage.getItem('svv_consent')||'null');return c&&c.t&&(Date.now()-c.t)<31536000000?c:null;}catch(e){return null;}}
 var c=get();if(c){apply(c);}
 return {get:get,apply:apply};
})();
</script>
	<?php
}, 2 );

add_action( 'wp_footer', function () {
	if ( is_admin() ) {
		return;
	}
	$cookie_page = get_page_by_path( 'cookies' );
	$cookie_url  = $cookie_page && 'publish' === $cookie_page->post_status ? get_permalink( $cookie_page ) : get_privacy_policy_url();
	?>
<div id="svv-cc" class="svv-cc" role="dialog" aria-modal="false" aria-labelledby="svv-cc-t" hidden>
	<div class="svv-cc__box">
		<p class="svv-cc__title" id="svv-cc-t">Cookies, mag dat?</p>
		<p class="svv-cc__text">Met je toestemming meet deze site het gebruik (Google Analytics) en de resultaten van advertenties (Google Ads, Meta en LinkedIn). Noodzakelijke cookies staan altijd aan.<?php echo $cookie_url ? ' <a href="' . esc_url( $cookie_url ) . '">Meer informatie</a>' : ''; ?></p>
		<div class="svv-cc__prefs" id="svv-cc-prefs" hidden>
			<label><input type="checkbox" checked disabled> Noodzakelijk <span>altijd aan</span></label>
			<label><input type="checkbox" id="svv-cc-a"> Statistieken <span>Google Analytics</span></label>
			<label><input type="checkbox" id="svv-cc-m"> Marketing <span>Google Ads, Meta, LinkedIn</span></label>
		</div>
		<div class="svv-cc__btns">
			<button type="button" class="svv-cc__btn svv-cc__btn--yes" data-cc="all">Accepteren</button>
			<button type="button" class="svv-cc__btn svv-cc__btn--yes" data-cc="save" hidden>Keuze opslaan</button>
			<button type="button" class="svv-cc__btn svv-cc__btn--no" data-cc="none">Weigeren</button>
			<button type="button" class="svv-cc__link" data-cc="prefs">Voorkeuren</button>
		</div>
	</div>
</div>
<script>
(function(){
var el=document.getElementById('svv-cc');if(!el||!window.svvConsent)return;
function save(a,m){var c={a:a?1:0,m:m?1:0,t:Date.now(),v:1};try{localStorage.setItem('svv_consent',JSON.stringify(c));}catch(e){}window.svvConsent.apply(c);el.hidden=true;}
function open(prefs){var c=window.svvConsent.get()||{};document.getElementById('svv-cc-a').checked=!!c.a;document.getElementById('svv-cc-m').checked=!!c.m;
 document.getElementById('svv-cc-prefs').hidden=!prefs;el.querySelector('[data-cc=save]').hidden=!prefs;el.querySelector('[data-cc=all]').hidden=!!prefs;el.querySelector('[data-cc=prefs]').hidden=!!prefs;el.hidden=false;}
if(!window.svvConsent.get()){open(false);}
el.addEventListener('click',function(e){var b=e.target.closest('[data-cc]');if(!b)return;var k=b.getAttribute('data-cc');
 if(k==='all')save(1,1);else if(k==='none')save(0,0);else if(k==='prefs')open(true);
 else if(k==='save')save(document.getElementById('svv-cc-a').checked,document.getElementById('svv-cc-m').checked);});
document.addEventListener('click',function(e){var o=e.target.closest('[data-svv-consent-open]');if(o){e.preventDefault();open(true);}});
})();
</script>
	<?php
}, 50 );
