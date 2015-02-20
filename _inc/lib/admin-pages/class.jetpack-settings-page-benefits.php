<?php
include_once( 'class.jetpack-admin-page.php' );
include_once( JETPACK__PLUGIN_DIR . 'class.jetpack-modules-list-table.php' );

// Builds the settings page and its menu
class Jetpack_Settings_Page_Benefits extends Jetpack_Admin_Page {
	// Show the settings page only when Jetpack is connected or in dev mode
	protected $dont_show_if_not_active = true;
	function add_page_actions( $hook ) {} // There are no page specific actions to attach to the menu

	// Adds the Settings sub menu
	function get_page_hook() {
		return add_submenu_page( 'jetpack', __( 'Jetpack Settings', 'jetpack' ), __( 'Settings (new)', 'jetpack' ), 'jetpack_manage_modules', 'jetpack_modules', array( $this, 'render' ) );
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
			<div id="classic_settings">
				<div class="frame top hide-if-no-js">
					<div class="wrap">
						<div class="manage-left">
							<table class="table table-bordered fixed-top">
								<thead>
									<tr>
										<th class="check-column"><input type="checkbox" class="checkall"></th>
										<th colspan="2">
											<?php $list_table->unprotected_display_tablenav( 'top' ); ?>
											<span class="filter-search">
												<button type="button" class="button">Filter</button>
											</span>
										</th>
									</tr>
								</thead>
							</table>
						</div>
					</div><!-- /.wrap -->
				</div><!-- /.frame -->
				<div class="frame bottom">
					<div class="wrap">
						<div class="manage-right">
							<div class="bumper">
								<form class="navbar-form" role="search">
									<input type="hidden" name="page" value="jetpack_modules" />
									<?php $list_table->search_box( __( 'Search', 'jetpack' ), 'srch-term' ); ?>
									<p><?php esc_html_e( 'View:', 'jetpack' ); ?></p>
									<div class="button-group filter-active">
										<button type="button" class="button <?php if ( empty( $_GET['activated'] ) ) echo 'active'; ?>"><?php esc_html_e( 'All', 'jetpack' ); ?></button>
										<button type="button" class="button <?php if ( ! empty( $_GET['activated'] ) && 'true' == $_GET['activated'] ) echo 'active'; ?>" data-filter-by="activated" data-filter-value="true"><?php esc_html_e( 'Active', 'jetpack' ); ?></button>
										<button type="button" class="button <?php if ( ! empty( $_GET['activated'] ) && 'false' == $_GET['activated'] ) echo 'active'; ?>" data-filter-by="activated" data-filter-value="false"><?php esc_html_e( 'Inactive', 'jetpack' ); ?></button>
									</div>
									<p><?php esc_html_e( 'Sort by:', 'jetpack' ); ?></p>
									<div class="button-group sort">
										<button type="button" class="button <?php if ( empty( $_GET['sort_by'] ) ) echo 'active'; ?>" data-sort-by="name"><?php esc_html_e( 'Alphabetical', 'jetpack' ); ?></button>
										<button type="button" class="button <?php if ( ! empty( $_GET['sort_by'] ) && 'introduced' == $_GET['sort_by'] ) echo 'active'; ?>" data-sort-by="introduced" data-sort-order="reverse"><?php esc_html_e( 'Newest', 'jetpack' ); ?></button>
										<button type="button" class="button <?php if ( ! empty( $_GET['sort_by'] ) && 'benefit_tag' == $_GET['sort_by'] ) echo 'active'; ?>" data-sort-by="benefit_tag" ><?php esc_html_e( 'Benefits', 'jetpack' ); ?></button>
										<button type="button" class="button <?php if ( ! empty( $_GET['sort_by'] ) && 'sort' == $_GET['sort_by'] ) echo 'active'; ?>" data-sort-by="sort"><?php esc_html_e( 'Popular', 'jetpack' ); ?></button>
									</div>
									<p><?php esc_html_e( 'Show:', 'jetpack' ); ?></p>
									<?php $list_table->views(); ?>
								</form>
							</div>
						</div>
						<div class="manage-left">
							<form class="jetpack-modules-list-table-form" onsubmit="return false;">
							<table class="<?php echo implode( ' ', $list_table->get_table_classes() ); ?>">
								<tbody id="the-list">
									<?php $list_table->display_rows_or_placeholder(); ?>
								</tbody>
							</table>
							</form>
						</div>
					</div><!-- /.wrap -->
				</div><!-- /.frame -->
			</div> <!-- /#classic_settings -->
			<style>
				/* temp hide message */
				#message { display: none; }
				.page-content { max-width: 950px!important; margin: 0 auto!important; }
				.nopad { padding: 0!important; }
				.hide { display: none; }
				.fixed { position: fixed; top: 0; background: #fff;  }
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
				#benefits .genericon-help, #benefits .genericon-image { font-size: 1.6em; color: #ccc; margin: 0; }
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
				<div id="j-settings-nav" class="j-col j-lrg-12">
					<ul>
						<li class="benefit-bucket active"><a href="javascript:void(0)" class="j-benefit-content"><span class="genericon genericon-edit"></span>Content</a></li>
						<li class="benefit-bucket"><a href="javascript:void(0)" class="j-benefit-engage"><span class="genericon genericon-activity"></span>Engage</a></li>
						<li class="benefit-bucket"><a href="javascript:void(0)" class="j-benefit-customize"><span class="genericon genericon-paintbrush"></span>Customize</a></li>
						<li class="benefit-bucket"><a href="javascript:void(0)" class="j-benefit-engage"><span class="genericon genericon-activity"></span>Optimize</a></li>
						<li class="benefit-bucket"><a href="javascript:void(0)" class="j-benefit-secure"><span class="genericon genericon-lock"></span>Secure</a></li>
					</ul>
				</div>
				<div id="j-benefits-content" class="j-col j-lrg-12">
					<div class="benefit-content clear" id="j-benefit-content">
						<div class="j-col j-lrg-12 j-sm-12">
							<h1>Bacon ipsum dolor amet </h1>
						</div><!-- /.j-col -->
						<div class="j-col j-lrg-5 alignleft">
							&nbsp;
						</div>
						<div class="j-col j-lrg-7 alignleft j-post-by-email">
							<h3 class="j-title alignleft">Post by email <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-post-by-email -->
						<div class="j-col j-lrg-12 alignleft j-markdown">
							<h3 class="j-title alignleft">Markdown <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-markdown -->
						<div class="j-col j-lrg-12 j-sm-12">
							<h1>Bacon ipsum dolor amet </h1>
						</div><!-- /.j-col -->
						<div class="j-col j-lrg-12 alignleft j-beautiful-math">
							<h3 class="j-title alignleft">Beautiful Math <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-beautiful-math -->
						<div class="j-col j-lrg-12 alignleft j-spelling-grammar">
							<h3 class="j-title alignleft">Spelling and Grammar <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-spelling-grammar -->
						<div class="j-col j-lrg-12 j-sm-12">
							<h1>Bacon ipsum dolor amet </h1>
						</div><!-- /.j-col -->
						<div class="j-col j-lrg-12 alignleft j-shortcode-embeds">
							<h3 class="j-title alignleft">Shortcode Embeds <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-shortcode-embeds -->
						<div class="j-col j-lrg-12 alignleft j-videopress">
							<h3 class="j-title alignleft">VideoPress <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-videopress -->
					</div><!-- /#j-benefit-content -->
					<div class="benefit-content clear" id="j-benefit-customize">
						<h2>Customize</h2>
						<p>Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. Turducken beef ribs kielbasa tenderloin, ut id jerky. Chuck id leberkas sed consectetur incididunt do pork chop commodo enim landjaeger dolore exercitation. Veniam leberkas tenderloin boudin in ut shank porchetta ad. T-bone short ribs ut meatloaf, dolore ad pork elit magna lorem tail fatback consequat kevin pig. Reprehenderit occaecat in et, ut porchetta exercitation drumstick ut.</p>
						<p>Brisket drumstick ribeye velit ut cillum. Beef exercitation laboris spare ribs. Landjaeger occaecat labore, short ribs sint officia cillum pig t-bone incididunt consectetur tongue capicola swine. Shank ullamco do irure consectetur sunt. Salami kevin labore, in deserunt boudin meatball jerky ut tenderloin shank tail eiusmod leberkas.</p>
						<p>Et ribeye non sausage boudin cupidatat nostrud occaecat nisi deserunt filet mignon andouille pariatur est elit. Esse pork loin exercitation andouille pork belly cillum ad venison enim. Pork chop sed cillum dolor tri-tip laborum. Proident sed ipsum salami adipisicing officia landjaeger.</p>
						<p>In exercitation adipisicing pork loin, ullamco porchetta nostrud. Magna in bacon, sint fugiat ullamco landjaeger pariatur. Anim cillum prosciutto irure, chuck do consectetur in drumstick esse elit kielbasa ipsum. Commodo cupim tempor ham esse swine pork loin kevin consequat strip steak. Quis tenderloin tongue proident exercitation, irure consequat ut nisi pork chop dolore ham hock frankfurter ribeye. Ad filet mignon pancetta, id ground round duis meatball esse occaecat eu tenderloin sausage tail short ribs. Andouille hamburger capicola quis.</p>
						<p>Dolore sirloin shankle, rump ut voluptate doner mollit ea. Fugiat mollit kielbasa, minim ad irure pancetta doner. Ullamco eu consectetur ribeye magna quis minim sint do aliquip id. Landjaeger doner proident duis tail.</p>
					</div><!-- /#j-benefit-customize -->
					<div class="benefit-content clear" id="j-benefit-secure">
						<h2>Secure</h2>
						<p>Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. Turducken beef ribs kielbasa tenderloin, ut id jerky. Chuck id leberkas sed consectetur incididunt do pork chop commodo enim landjaeger dolore exercitation. Veniam leberkas tenderloin boudin in ut shank porchetta ad. T-bone short ribs ut meatloaf, dolore ad pork elit magna lorem tail fatback consequat kevin pig. Reprehenderit occaecat in et, ut porchetta exercitation drumstick ut.</p>
						<p>Brisket drumstick ribeye velit ut cillum. Beef exercitation laboris spare ribs. Landjaeger occaecat labore, short ribs sint officia cillum pig t-bone incididunt consectetur tongue capicola swine. Shank ullamco do irure consectetur sunt. Salami kevin labore, in deserunt boudin meatball jerky ut tenderloin shank tail eiusmod leberkas.</p>
						<p>Et ribeye non sausage boudin cupidatat nostrud occaecat nisi deserunt filet mignon andouille pariatur est elit. Esse pork loin exercitation andouille pork belly cillum ad venison enim. Pork chop sed cillum dolor tri-tip laborum. Proident sed ipsum salami adipisicing officia landjaeger.</p>
						<p>In exercitation adipisicing pork loin, ullamco porchetta nostrud. Magna in bacon, sint fugiat ullamco landjaeger pariatur. Anim cillum prosciutto irure, chuck do consectetur in drumstick esse elit kielbasa ipsum. Commodo cupim tempor ham esse swine pork loin kevin consequat strip steak. Quis tenderloin tongue proident exercitation, irure consequat ut nisi pork chop dolore ham hock frankfurter ribeye. Ad filet mignon pancetta, id ground round duis meatball esse occaecat eu tenderloin sausage tail short ribs. Andouille hamburger capicola quis.</p>
						<p>Dolore sirloin shankle, rump ut voluptate doner mollit ea. Fugiat mollit kielbasa, minim ad irure pancetta doner. Ullamco eu consectetur ribeye magna quis minim sint do aliquip id. Landjaeger doner proident duis tail.</p>
					</div><!-- /#j-benefit-secure -->
					<div class="benefit-content clear" id="j-benefit-engage">
						<div class="j-col j-lrg-12 j-sm-12">
							<h1>Share your posts with the world</h1>
						</div><!-- /.j-col -->
						<div class="j-col j-lrg-12 alignleft j-publicize">
							<h3 class="alignleft">Publicize</h3>
							<p class="clear">Connect your networks and automatically share new posts with your friends. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a></p>
							<div class="j-col j-lrg-6 j-md-12 j-sm-12 alignleft nopad">
								<ul>
									<li class="j-social-connect clear"><span class="genericon genericon-facebook"></span><p class="alignleft">Facebook</p><button class="alignright">Connect</button></li>
									<li class="j-social-connect clear"><span class="genericon genericon-twitter"></span><p class="alignleft">Twitter</p><button class="alignright">Connect</button></li>
									<li class="j-social-connect clear"><span class="genericon genericon-linkedin-alt"></span><p class="alignleft">LinkedIn</p><button class="alignright">Connect</button></li>
								</ul>
							</div><!-- /.j-col -->
							<div class="j-col j-lrg-6 j-md-12 j-sm-12 alignleft nopad">
								<ul>
									<li class="j-social-connect clear"><span class="genericon genericon-tumblr"></span><p class="alignleft">Tumblr</p><button class="alignright">Connect</button></li>
									<li class="j-social-connect clear"><span class="genericon genericon-path"></span><p class="alignleft">Path</p><button class="alignright">Connect</button></li>
									<li class="j-social-connect clear"><span class="genericon genericon-googleplus"></span><p class="alignleft">Google+</p><button class="alignright">Connect</button></li>
								</ul>
							</div><!-- /.j-col -->
						</div><!-- /.j-publicize -->
						<div class="j-col j-lrg-12 alignleft j-enhanced-distribution">
							<h3 class="alignleft">Enhanced Distribution <small class="j-enabled-notification">(enabled)</small></h3>
							<p class="clear">Jetpack will automatically take the great published content from your blog or website and share it instantly with third party services like search engines, increasing your reach and traffic.
								<a href="javascript:void(0)"><span class="genericon genericon-help"></span></a></p>
						</div><!-- /.j-enhanced-distribution -->
						<div class="j-col j-lrg-12 alignleft j-notifications">
							<h3 class="alignleft">Notifications <small class="j-enabled-notification">(enabled)</small></h3>
							<p class="clear">Keep up with the latest happenings on all your WordPress sites and interact with other WordPress.com users.
								<a href="javascript:void(0)"><span class="genericon genericon-help"></span></a></p>
						</div><!-- /.j-notifications -->
						<div class="j-col j-lrg-12 alignleft j-verification">
							<h3 class="alignleft">Site Verifitcation</h3>
							<p class="clear">Optimise your site with Google & Bing Webmaster Tools, and improve your Pinterest profile. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a>
							</p>
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
						<div class="j-col j-lrg-12 alignleft j-likes">
							<h3 class="j-title alignleft">Likes <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Track and engage readers who really appreciate your posts by adding a like button at the bottom of your post. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-likes -->
						<div class="j-col j-lrg-12 alignleft j-subscriptions">
							<h3 class="j-title alignleft">Subsciptions <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Give readers the option to subscribe to your posts via email. They'll never miss another post. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-subscriptions -->
						<div class="j-col j-lrg-12 alignleft j-sharing">
							<h3 class="j-title alignleft">Sharing <small class="hide">(enabled)</small></h3>
							<div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Give your readers the ability to share your posts across social networks, via email and more with just one click. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-sharing -->
						<div class="j-col j-lrg-12 alignleft j-shortlinks">
							<h3 class="j-title alignleft">WP.me Shortlinks <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Make your URLs short and sweet so you can easily share them with your network. example: <?php echo bloginfo( 'url' ); ?>2014/10/hello-world becomes http://wp.me/p5sC8j-1 <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-shortlinks -->
						<div class="j-col j-lrg-12 j-sm-12">
							<h1>Engage your visitors</h1>
						</div><!-- /.j-col -->
						<div class="j-col j-lrg-12 alignleft j-related-posts">
							<h3 class="j-title alignleft">Related Posts <small class="hide">(enabled)</small></h3><div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Show readers more of your great content that is related to what they're already reading. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-related-posts -->
						<div class="j-col j-lrg-12 alignleft j-gravatar">
							<h3 class="j-title alignleft">Gravatar Hovercards <small class="hide">(enabled)</small></h3>
							<div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Reward your commenters with more exposure by showing their gravatar bio on hover. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-gravatar -->
						<div class="j-col j-lrg-12 alignleft j-comments">
							<h3 class="j-title alignleft">Comments <small class="hide">(enabled)</small></h3>
							<div class="j-enable-feature"><div class="j-toggle"><div class="j-toggle-wrap"><div class="j-toggle-off">Off</div><div class="j-toggle-on">On</div></div></div></div>
							<p class="clear">Make it easier for your readers to comment on your posts by letting them use their WordPress.com, Twitter, or Facebook accounts. <a href="javascript:void(0)"><span class="genericon genericon-help"></span></a><a href="javascript:void(0)"><span class="genericon genericon-image"></span></a></p>
						</div><!-- /.j-comments -->
					</div><!-- /#j-benefit-engage -->
				</div><!-- /#j-benefits-content -->
			</div><!-- /#benefits -->
		</div><!-- /.content -->
		<?php
	}

	// Javascript logic specific to the list table
	function page_admin_scripts() {
		wp_enqueue_script( 'jetpack-admin-js', plugins_url( '_inc/jetpack-admin.js', JETPACK__PLUGIN_FILE ), array( 'jquery' ), JETPACK__VERSION . '-20121111' );
	}
}
?>
