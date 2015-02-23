<?php
include_once( 'class.jetpack-admin-page.php' );
include_once( JETPACK__PLUGIN_DIR . 'class.jetpack-modules-list-table.php' );

// Builds the settings page and its menu
class Jetpack_Quick_Configure extends Jetpack_Admin_Page {
	// Show the settings page only when Jetpack is connected or in dev mode
	protected $dont_show_if_not_active = true;
	public function add_page_actions( $hook ) {
		add_action( 'admin_enqueue_scripts', array( $this, 'jetpack_quickconfig_ajax_enqueue' ) );
	}

	// Enqueue and localize ajax Javascript
	function jetpack_quickconfig_ajax_enqueue( $hook ) {
		if ( 'jetpack_page_jetpack_quick_configure' !== $hook )
			return;

		wp_enqueue_script( 'jetpack-quickconfig-ajax', plugins_url( '_inc/jetpack-admin-ajax.js', JETPACK__PLUGIN_FILE ), array( 'jquery' ) );

		wp_localize_script(
			'jetpack-quickconfig-ajax',
			'jetpack_quickconfig_ajax_object',
			array(
				'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
				'jetpack_quickconfig_nonce' => wp_create_nonce( 'jetpack-quickconfig-nonce' )
			)
		);
	}

	// Adds the Settings sub menu
	function get_page_hook() {
		return add_submenu_page( 'jetpack', __( 'Jetpack Quick Configure', 'jetpack' ), __( 'Quick Configure', 'jetpack' ), 'jetpack_manage_modules', 'jetpack_quick_configure', array( $this, 'render' ) );
	}
	// Declare var to hold value of current benefits tab
	public $navtotab;

	// Renders the module list table where you can use bulk action or row
	// actions to activate/deactivate and configure modules
	function page_render() {
		// Get the current benefits tab
		$this->navtotab = isset($_GET['t']) ? $_GET['t'] : null;

		$list_table = new Jetpack_Modules_List_Table;
		?>
		<div class="clouds-sm"></div>
		<div class="settings-view alignright">
			<a href="#" class="classic-button">classic view</a>
			<a href="#" class="benefits-button">benefits view</a>
		</div><!-- /.settings-view -->
		<?php do_action( 'jetpack_notices' ) ?>
		<div class="page-content configure">
			<style>
				/* temp hide message */
				#message { display: none; }
				.page-content { max-width: 950px!important; margin: 0 auto!important; }
				.nopad { padding: 0!important; }
				.hide { display: none; }
				.fixed { position: fixed!important; top: 20px; background: #f9f9f9; z-index: 9999; }
				#classic_settings, .benefits-button { display: none; }
				#benefits #j-settings-nav li { float: left; padding: 1%; margin: 0 2%; width: 16%; text-align: center; }
				#benefits .genericon { float: left; font-size: 2em;  margin: 0 1%; }
				#benefits h1 { margin: 0; font-style: italic; color: #81a844; }
				#benefits h2 { clear: none; margin: 0; }
				#benefits p { margin: 0; }
				#benefits .benefit-bucket { border-bottom: 3px solid #eee;  }
				#benefits .benefit-bucket a { color: inherit; }
				#benefits .benefit-bucket.active { border-bottom: 3px solid #333; }
				#benefits .benefit-content { padding: 1em 0; }
				#benefits #j-benefit-content { display: block; }
				#benefits .j-publicize .j-social-connect { padding: 1em 0; }
				#benefits .j-publicize .j-social-connect p { font-size: 1.3em; margin: 0 1em 0 .3em; }
				#benefits .j-publicize .j-social-connect button { margin: .2em 1em 0 0; }
				#benefits .genericon-help, #benefits .genericon-image { float: none; font-size: 1.6em; color: #ccc; margin: 0; }
				#benefits .j-verification { margin: 1em 0 0 0; }
				#benefits .j-verification label { display: block; margin: 1em 0 0 0; }
				#benefits .j-verification input { width: 90%; }
				#benefits .j-verification input#submit { width: 100px; }
				#benefits .j-enable-feature p { float: right; font-size: 1.1em; font-weight: 600; margin-right: 10px; }
				#benefits .j-toggle { width: 70px; height: 20px; overflow: hidden; border: 1px solid #333; float: right; border-radius: 10px; cursor: pointer; }
				#benefits .j-toggle-wrap { width: 240px; }
				#benefits .j-toggle .j-toggle-on, #benefits .j-toggle .j-toggle-off { color: #fff; width: 70px; padding: 3px; font-size: .8em; float: left; }
				#benefits .j-toggle .j-toggle-on { background: #81a844; border-right: 35px solid #fff; }
				#benefits .j-toggle .j-toggle-off { background: #333; border-left: 35px solid #fff; }
				#benefits .j-toggle-enabled { margin-left: -70px; }
				#benefits .j-feature-enabled { color: #ccc; }
			</style>
			<div id="benefits" class="j-row">
				<div id="j-settings-nav" class="j-col j-lrg-12 nopad">
					<ul>
						<li class="benefit-bucket active"><a href="javascript:void(0)" class="j-benefit-content"><span class="genericon genericon-edit"></span>Content</a></li>
						<li class="benefit-bucket"><a href="javascript:void(0)" class="j-benefit-engage"><span class="genericon genericon-rating-empty"></span>Engage</a></li>
						<li class="benefit-bucket"><a href="javascript:void(0)" class="j-benefit-customize"><span class="genericon genericon-paintbrush"></span>Customize</a></li>
						<li class="benefit-bucket"><a href="javascript:void(0)" class="j-benefit-performance"><span class="genericon genericon-activity"></span>Performance</a></li>
						<li class="benefit-bucket"><a href="javascript:void(0)" class="j-benefit-secure"><span class="genericon genericon-lock"></span>Secure</a></li>
					</ul>
				</div>
				<div id="j-benefits-content" class="j-col j-lrg-12">
					<div class="benefit-content clear" id="j-benefit-content">
						<div class="j-col j-lrg-12 j-sm-12">
							<h1>Great content deserves great tools </h1>
						</div><!-- /.j-col -->
						<div class="j-col j-lrg-6 alignleft j-post-by-email">
							<h3 class="j-title alignleft">Post by email <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-post-by-email -->
						<div class="j-col j-lrg-6 alignleft j-videopress">
							<h3 class="j-title alignleft">VideoPress <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-videopress -->
					</div><!-- /#j-benefit-content -->
					<div class="benefit-content clear" id="j-benefit-engage">
						<div class="j-col j-lrg-12 j-sm-12">
							<h1>Share your posts with the world</h1>
						</div><!-- /.j-col -->
						<div class="j-col j-lrg-12 alignleft j-publicize">
							<h3 class="j-title alignleft">Publicize</h3>
							<p class="clear">Connect your networks and automatically share new posts with your friends. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a></p>
							<div class="j-col j-lrg-6 alignleft">
								<ul>
									<li class="j-social-connect clear"><span class="genericon genericon-facebook"></span><p class="alignleft">Facebook</p><button class="alignright">Connect</button></li>
									<li class="j-social-connect clear"><span class="genericon genericon-twitter"></span><p class="alignleft">Twitter</p><button class="alignright">Connect</button></li>
									<li class="j-social-connect clear"><span class="genericon genericon-linkedin-alt"></span><p class="alignleft">LinkedIn</p><button class="alignright">Connect</button></li>
								</ul>
							</div><!-- /.j-col -->
							<div class="j-col j-lrg-6 alignleft">
								<ul>
									<li class="j-social-connect clear"><span class="genericon genericon-tumblr"></span><p class="alignleft">Tumblr</p><button class="alignright">Connect</button></li>
									<li class="j-social-connect clear"><span class="genericon genericon-path"></span><p class="alignleft">Path</p><button class="alignright">Connect</button></li>
									<li class="j-social-connect clear"><span class="genericon genericon-googleplus"></span><p class="alignleft">Google+</p><button class="alignright">Connect</button></li>
								</ul>
							</div><!-- /.j-col -->
						</div><!-- /.j-publicize -->
						<div class="j-col j-lrg-12 alignleft j-verification">
							<h3 class="j-title alignleft">Site Verifitcation</h3>
							<p class="clear">Optimise your site with Google & Bing Webmaster Tools, and improve your Pinterest profile. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a></p>
							<form>
								<div class="j-col j-lrg-6 alignleft nopad">
									<label><a href="#">Google Webmaster Tools</a></label>
									<input type="text" placeholder="<meta name='google-site-verification' content='dBw5CvburAxi537Rp9qi5uG2174Vb6JwHwIRwPSLIK8'>">
									<label><a href="#">Pinterest Site Verification</a></label>
									<input type="text" placeholder="<meta name='p:domain_verify' content='f100679e6048d45e4a0b0b92dce1efce'>">
								</div><!-- /.j-col -->
								<div class="j-col j-lrg-6 alignleft nopad">
									<label><a href="#">Bing Webmaster Tools</a></label>
									<input type="text" placeholder="<meta name='msvalidate.01' content='12C1203B5086AECE94EB3A3D9830B2E'>">
									<input type="submit" class="alignright" id="submit" value="Save Changes">
								</div><!-- /.j-col -->
							</form>
						</div><!-- /.j-verification -->
						<div class="j-col j-lrg-12 j-sm-12">
							<h1>Turn readers into fans</h1>
						</div><!-- /.j-col -->
						<div class="j-col j-lrg-6 alignleft j-likes">
							<h3 class="j-title alignleft">Likes <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Track and engage readers who really appreciate your posts by adding a like button at the bottom of your post. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-likes -->
						<div class="j-col j-lrg-6 alignleft j-subscriptions">
							<h3 class="j-title alignleft">Subsciptions <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Give readers the option to subscribe to your posts via email. They'll never miss another post. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-subscriptions -->
						<div class="j-col j-lrg-6 alignleft j-sharing">
							<h3 class="j-title alignleft">Sharing <small class="hide">(enabled)</small></h3>
							<div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Give your readers the ability to share your posts across social networks, via email and more with just one click. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-sharing -->
						<div class="j-col j-lrg-6 alignleft j-shortlinks">
							<h3 class="j-title alignleft">WP.me Shortlinks <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Make your URLs short and sweet so you can easily share them with your network. example: <?php echo bloginfo( 'url' ); ?>2014/10/hello-world becomes http://wp.me/p5sC8j-1 <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-shortlinks -->
						<div class="j-col j-lrg-12 j-sm-12">
							<h1>Engage your visitors</h1>
						</div><!-- /.j-col -->
						<div class="j-col j-lrg-6 alignleft j-related-posts">
							<h3 class="j-title alignleft">Related Posts <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Show readers more of your great content that is related to what they're already reading. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-related-posts -->
						<div class="j-col j-lrg-6 alignleft j-gravatar">
							<h3 class="j-title alignleft">Gravatar Hovercards <small class="hide">(enabled)</small></h3>
							<div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Reward your commenters with more exposure by showing their gravatar bio on hover. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-gravatar -->
						<div class="j-col j-lrg-6 alignleft j-comments">
							<h3 class="j-title alignleft">Comments <small class="hide">(enabled)</small></h3>
							<div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Make it easier for your readers to comment on your posts by letting them use their WordPress.com, Twitter, or Facebook accounts. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-comments -->
					</div><!-- /#j-benefit-engage -->
					<div class="benefit-content clear" id="j-benefit-customize">
						<div class="j-col j-lrg-12 j-sm-12">
							<h1>Customize </h1>
						</div><!-- /.j-col -->
						<div class="j-col j-lrg-6 alignleft j-mobile-theme">
							<h3 class="j-title alignleft">Mobile Theme <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-mobile-theme -->
						<div class="j-col j-lrg-6 alignleft j-infinite-scroll">
							<h3 class="j-title alignleft">Infinite Scroll <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-infinite-scroll -->
						<div class="j-col j-lrg-6 alignleft j-carousel">
							<h3 class="j-title alignleft">Carousel <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-carousel -->
						<div class="j-col j-lrg-6 alignleft j-tiled-galleries">
							<h3 class="j-title alignleft">Titled Galleries <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-tiled-galleries -->
						<div class="j-col j-lrg-6 alignleft j-site-icon">
							<h3 class="j-title alignleft">Site Icon <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-site-icon -->
					</div><!-- /#j-benefit-customize -->
					<div class="benefit-content clear" id="j-benefit-performance">
						<div class="j-col j-lrg-12 j-sm-12">
							<h1>Performance</h1>
						</div><!-- /.j-col -->
						<div class="j-col j-lrg-6 alignleft j-photon">
							<h3 class="j-title alignleft">Photon <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-photon -->
					</div><!-- /#j-benefit-performance -->
					<div class="benefit-content clear" id="j-benefit-secure">
						<div class="j-col j-lrg-12 j-sm-12">
							<h1>Secure</h1>
						</div><!-- /.j-col -->
						<div class="j-col j-lrg-6 alignleft j-single-sign-on">
							<h3 class="j-title alignleft">Single Sign On <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-single-sign-on -->
						<div class="j-col j-lrg-6 alignleft j-monitor">
							<h3 class="j-title alignleft">Monitor <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-monitor -->
						<div class="j-col j-lrg-6 alignleft j-security-scan">
							<h3 class="j-title alignleft">Security Scanning <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-security-scan -->
						<div class="j-col j-lrg-6 alignleft j-akismet">
							<h3 class="j-title alignleft">Akismet <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-akismet -->
					</div><!-- /#j-benefit-secure -->
				</div><!-- /#j-benefits-content -->
			</div><!-- /#benefits -->

			<!-- Toggle Miguel-->
			<a class="toggle-miguel button" style="cursor: pointer;"><?php echo __( 'Toggle Miguel', 'jetpack' ); ?></a>
			<span class="spinner" style="display: none;"></span>
			<div class="ajax-result-miguel">
				<?php if ( 1 == Jetpack::get_option( 'miguel_can_fly' ) ) : ?>
					<p>Miguel can fly</p>
					<div id="miguels" class="flyby">
						<svg class="miguel" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="87px" viewBox="0 0 80 87" enable-background="new 0 0 80 87" xml:space="preserve">
							<polygon class="eye" fill="#518d2a" points="41.187,17.081 46.769,11.292 50.984,15.306"/>
							<path class="body" fill="#518d2a" d="M38.032,47.3l4.973-5.157l7.597,1.996l0.878-0.91l0.761-0.789l-0.688-2.838l-0.972-0.926l-1.858,1.926 l-2.206-2.1l3.803-3.944l0.09-3.872L80,0L61.201,10.382L60.2,15.976l-5.674,1.145l-8.09-7.702L34.282,22.024l8.828-1.109 l2.068,2.929l-4.996,0.655l-3.467,3.595l0.166-4.469l-4.486,0.355L21.248,35.539l-0.441,4.206l-2.282,2.366l-2.04,6.961 L27.69,37.453l4.693,1.442l-2.223,2.306l-4.912,0.095l-7.39,22.292l-8.06,3.848l-2.408,9.811l-3.343-0.739L0,86.739l30.601-31.733 l8.867,2.507l-7.782,8.07l-1.496-0.616l-0.317-2.623l-7.197,7.463l11.445-2.604l16.413-7.999L38.032,47.3z M42.774,16.143 l3.774-3.914l2.85,2.713L42.774,16.143z"/>
						</svg>
						<svg class="miguel" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="87px" viewBox="0 0 80 87" enable-background="new 0 0 80 87" xml:space="preserve">
							<polygon class="eye" fill="#518d2a" points="41.187,17.081 46.769,11.292 50.984,15.306   "/>
							<path class="body" fill="#518d2a" d="M38.032,47.3l4.973-5.157l7.597,1.996l0.878-0.91l0.761-0.789l-0.688-2.838l-0.972-0.926l-1.858,1.926 l-2.206-2.1l3.803-3.944l0.09-3.872L80,0L61.201,10.382L60.2,15.976l-5.674,1.145l-8.09-7.702L34.282,22.024l8.828-1.109 l2.068,2.929l-4.996,0.655l-3.467,3.595l0.166-4.469l-4.486,0.355L21.248,35.539l-0.441,4.206l-2.282,2.366l-2.04,6.961 L27.69,37.453l4.693,1.442l-2.223,2.306l-4.912,0.095l-7.39,22.292l-8.06,3.848l-2.408,9.811l-3.343-0.739L0,86.739l30.601-31.733 l8.867,2.507l-7.782,8.07l-1.496-0.616l-0.317-2.623l-7.197,7.463l11.445-2.604l16.413-7.999L38.032,47.3z M42.774,16.143 l3.774-3.914l2.85,2.713L42.774,16.143z"/>
						</svg>
						<svg class="miguel" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="87px" viewBox="0 0 80 87" enable-background="new 0 0 80 87" xml:space="preserve">
							<polygon class="eye" fill="#518d2a" points="41.187,17.081 46.769,11.292 50.984,15.306   "/>
							<path class="body" fill="#518d2a" d="M38.032,47.3l4.973-5.157l7.597,1.996l0.878-0.91l0.761-0.789l-0.688-2.838l-0.972-0.926l-1.858,1.926 l-2.206-2.1l3.803-3.944l0.09-3.872L80,0L61.201,10.382L60.2,15.976l-5.674,1.145l-8.09-7.702L34.282,22.024l8.828-1.109 l2.068,2.929l-4.996,0.655l-3.467,3.595l0.166-4.469l-4.486,0.355L21.248,35.539l-0.441,4.206l-2.282,2.366l-2.04,6.961 L27.69,37.453l4.693,1.442l-2.223,2.306l-4.912,0.095l-7.39,22.292l-8.06,3.848l-2.408,9.811l-3.343-0.739L0,86.739l30.601-31.733 l8.867,2.507l-7.782,8.07l-1.496-0.616l-0.317-2.623l-7.197,7.463l11.445-2.604l16.413-7.999L38.032,47.3z M42.774,16.143 l3.774-3.914l2.85,2.713L42.774,16.143z"/>
						</svg>
					</div>
				<?php else: ?>
					<p>Miguel can't fly</p>
				<?php endif; ?>
			</div>
			<!-- End Toggle Miguel-->

		</div><!-- /.content -->
		<style>
			.miguel {
				display: none;
				position: fixed;
				bottom: -200px;
				left: 0;
				z-index: 1;
				-webkit-animation: miguel 3.4s 0s ease-in-out;
				animation: miguel 3.4s 0s ease-in-out;
			}
			.miguel:nth-child(2) {
				left: 49%;
				width: 120px;
				height: 131px;
				-webkit-animation-duration: 2.4s;
				animation-duration: 2.4s;
				-webkit-animation-delay: 0s;
				animation-delay: 0s;
			}
			.miguel:nth-child(3) {
				left: 23%;
				width: 60px;
				height: 66px;
				-webkit-animation-duration: 4.5s;
				animation-duration: 4.5s;
				-webkit-animation-delay: 0s;
				animation-delay: 0s;
			}
			@-webkit-keyframes "miguel" {
				0% {
					-webkit-transform: translate3d(0px, 0px, 0px);
					transform: translate3d(0px, 0px, 0px);
				}
				100% {
					-webkit-transform: translate3d(900px, -900px, 0px);
					transform: translate3d(900px, -900px, 0px);
				}
			}
			@keyframes "miguel" {
				0% {
					-webkit-transform: translate3d(0px, 0px, 0px);
					transform: translate3d(0px, 0px, 0px);
				}
				100% {
					-webkit-transform: translate3d(900px, -900px, 0px);
					transform: translate3d(900px, -900px, 0px);
				}
			}
		</style>
<?php
	}

	// Javascript logic specific to the list table
	function page_admin_scripts() {
		wp_enqueue_script( 'jetpack-admin-js', plugins_url( '_inc/jetpack-admin.js', JETPACK__PLUGIN_FILE ), array( 'jquery' ), JETPACK__VERSION . '-20121111' );
	}
}
?>
