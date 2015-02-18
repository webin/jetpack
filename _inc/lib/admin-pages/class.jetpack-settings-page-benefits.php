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
				.page-content { max-width: 950px!important; margin: 0 auto!important; }
				.nopad { padding: 0!important; }
				#classic_settings, .benefits-button { display: none; }
				#benefits .genericon { float: left; font-size: 2em;  margin: 0 1%; }
				#benefits h1 { margin: 0; font-style: italic; color: #81a844; }
				#benefits h2 { clear: none; margin: 0; }
				#benefits p { margin: 0; }
				#benefits .benefit-bucket { border-bottom: 3px solid #eee;  }
				#benefits .benefit-bucket a { color: inherit; }
				#benefits .benefit-bucket.active { border-bottom: 3px solid #333; }
				#benefits .benefit-content { display: none; padding: 1em 0; }
				#benefits #jp-benefit-content { display: block; }
				#benefits .publicize .j-social-connect { padding: 1em 0; }
				#benefits .publicize .j-social-connect p { font-size: 1.3em; margin: 0 1em 0 .3em; }
				#benefits .publicize .j-social-connect button { margin: .2em 1em 0 0; }
				#benefits .genericon-help, #benefits .genericon-image { font-size: 1.8em; color: #ccc; float: right; }

			</style>
			<div id="benefits" class="j-row">
				<div class="j-col j-lrg-3 benefit-bucket active"><a href="javascript:void(0)" class="jp-benefit-content"><span class="genericon genericon-edit"></span><h2>Content</h2><p>Create and publish rich and engaging content</p></a></div>
				<div class="j-col j-lrg-3 benefit-bucket"><a href="javascript:void(0)" class="jp-benefit-customize"><span class="genericon genericon-paintbrush"></span><h2>Customize</h2><p>Make your WordPress site uniquely yours</p></a></div>
				<div class="j-col j-lrg-3 benefit-bucket"><a href="javascript:void(0)" class="jp-benefit-secure"><span class="genericon genericon-lock"></span><h2>Secure</h2><p>Keep your WordPress optimised, safe and protected</p></a></div>
				<div class="j-col j-lrg-3 benefit-bucket"><a href="javascript:void(0)" class="jp-benefit-engage"><span class="genericon genericon-activity"></span><h2>Engage</h2><p>Increase your traffic and keep visitors coming back</p></a></div>
				<div class="benefit-content clear" id="jp-benefit-content">
					<h2>Content</h2>
					<p>Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. Turducken beef ribs kielbasa tenderloin, ut id jerky. Chuck id leberkas sed consectetur incididunt do pork chop commodo enim landjaeger dolore exercitation. Veniam leberkas tenderloin boudin in ut shank porchetta ad. T-bone short ribs ut meatloaf, dolore ad pork elit magna lorem tail fatback consequat kevin pig. Reprehenderit occaecat in et, ut porchetta exercitation drumstick ut.</p>
					<p>Brisket drumstick ribeye velit ut cillum. Beef exercitation laboris spare ribs. Landjaeger occaecat labore, short ribs sint officia cillum pig t-bone incididunt consectetur tongue capicola swine. Shank ullamco do irure consectetur sunt. Salami kevin labore, in deserunt boudin meatball jerky ut tenderloin shank tail eiusmod leberkas.</p>
					<p>Et ribeye non sausage boudin cupidatat nostrud occaecat nisi deserunt filet mignon andouille pariatur est elit. Esse pork loin exercitation andouille pork belly cillum ad venison enim. Pork chop sed cillum dolor tri-tip laborum. Proident sed ipsum salami adipisicing officia landjaeger.</p>
					<p>In exercitation adipisicing pork loin, ullamco porchetta nostrud. Magna in bacon, sint fugiat ullamco landjaeger pariatur. Anim cillum prosciutto irure, chuck do consectetur in drumstick esse elit kielbasa ipsum. Commodo cupim tempor ham esse swine pork loin kevin consequat strip steak. Quis tenderloin tongue proident exercitation, irure consequat ut nisi pork chop dolore ham hock frankfurter ribeye. Ad filet mignon pancetta, id ground round duis meatball esse occaecat eu tenderloin sausage tail short ribs. Andouille hamburger capicola quis.</p>
					<p>Dolore sirloin shankle, rump ut voluptate doner mollit ea. Fugiat mollit kielbasa, minim ad irure pancetta doner. Ullamco eu consectetur ribeye magna quis minim sint do aliquip id. Landjaeger doner proident duis tail.</p>
				</div>
				<div class="benefit-content clear" id="jp-benefit-customize">
					<h2>Customize</h2>
					<p>Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. Turducken beef ribs kielbasa tenderloin, ut id jerky. Chuck id leberkas sed consectetur incididunt do pork chop commodo enim landjaeger dolore exercitation. Veniam leberkas tenderloin boudin in ut shank porchetta ad. T-bone short ribs ut meatloaf, dolore ad pork elit magna lorem tail fatback consequat kevin pig. Reprehenderit occaecat in et, ut porchetta exercitation drumstick ut.</p>
					<p>Brisket drumstick ribeye velit ut cillum. Beef exercitation laboris spare ribs. Landjaeger occaecat labore, short ribs sint officia cillum pig t-bone incididunt consectetur tongue capicola swine. Shank ullamco do irure consectetur sunt. Salami kevin labore, in deserunt boudin meatball jerky ut tenderloin shank tail eiusmod leberkas.</p>
					<p>Et ribeye non sausage boudin cupidatat nostrud occaecat nisi deserunt filet mignon andouille pariatur est elit. Esse pork loin exercitation andouille pork belly cillum ad venison enim. Pork chop sed cillum dolor tri-tip laborum. Proident sed ipsum salami adipisicing officia landjaeger.</p>
					<p>In exercitation adipisicing pork loin, ullamco porchetta nostrud. Magna in bacon, sint fugiat ullamco landjaeger pariatur. Anim cillum prosciutto irure, chuck do consectetur in drumstick esse elit kielbasa ipsum. Commodo cupim tempor ham esse swine pork loin kevin consequat strip steak. Quis tenderloin tongue proident exercitation, irure consequat ut nisi pork chop dolore ham hock frankfurter ribeye. Ad filet mignon pancetta, id ground round duis meatball esse occaecat eu tenderloin sausage tail short ribs. Andouille hamburger capicola quis.</p>
					<p>Dolore sirloin shankle, rump ut voluptate doner mollit ea. Fugiat mollit kielbasa, minim ad irure pancetta doner. Ullamco eu consectetur ribeye magna quis minim sint do aliquip id. Landjaeger doner proident duis tail.</p>
				</div>
				<div class="benefit-content clear" id="jp-benefit-secure">
					<h2>Secure</h2>
					<p>Bacon ipsum dolor amet nisi tongue sint mollit filet mignon lorem tail pork flank id doner pork belly brisket. Turducken beef ribs kielbasa tenderloin, ut id jerky. Chuck id leberkas sed consectetur incididunt do pork chop commodo enim landjaeger dolore exercitation. Veniam leberkas tenderloin boudin in ut shank porchetta ad. T-bone short ribs ut meatloaf, dolore ad pork elit magna lorem tail fatback consequat kevin pig. Reprehenderit occaecat in et, ut porchetta exercitation drumstick ut.</p>
					<p>Brisket drumstick ribeye velit ut cillum. Beef exercitation laboris spare ribs. Landjaeger occaecat labore, short ribs sint officia cillum pig t-bone incididunt consectetur tongue capicola swine. Shank ullamco do irure consectetur sunt. Salami kevin labore, in deserunt boudin meatball jerky ut tenderloin shank tail eiusmod leberkas.</p>
					<p>Et ribeye non sausage boudin cupidatat nostrud occaecat nisi deserunt filet mignon andouille pariatur est elit. Esse pork loin exercitation andouille pork belly cillum ad venison enim. Pork chop sed cillum dolor tri-tip laborum. Proident sed ipsum salami adipisicing officia landjaeger.</p>
					<p>In exercitation adipisicing pork loin, ullamco porchetta nostrud. Magna in bacon, sint fugiat ullamco landjaeger pariatur. Anim cillum prosciutto irure, chuck do consectetur in drumstick esse elit kielbasa ipsum. Commodo cupim tempor ham esse swine pork loin kevin consequat strip steak. Quis tenderloin tongue proident exercitation, irure consequat ut nisi pork chop dolore ham hock frankfurter ribeye. Ad filet mignon pancetta, id ground round duis meatball esse occaecat eu tenderloin sausage tail short ribs. Andouille hamburger capicola quis.</p>
					<p>Dolore sirloin shankle, rump ut voluptate doner mollit ea. Fugiat mollit kielbasa, minim ad irure pancetta doner. Ullamco eu consectetur ribeye magna quis minim sint do aliquip id. Landjaeger doner proident duis tail.</p>
				</div>
				<div class="benefit-content clear" id="jp-benefit-engage">
					<div class="j-row">
						<div class="j-col j-lrg-12 j-sm-12">
							<h1>Share your posts with the world</h1>
						</div>
						<div class="j-col j-lrg-6 j-md-8 j-sm-12 alignleft publicize">
							<h3 class="alignleft">Publicize</h3>
							<a href="javascript:void(0)"><span class="genericon genericon-help"></span></a>
							<p class="clear">Connect your networks and automatically share new posts with your friends</p>
							<div class="j-col j-lrg-6 j-sm-12 alignleft nopad">
								<ul>
									<li class="j-social-connect clear"><span class="genericon genericon-facebook"></span><p class="alignleft">Facebook</p><button class="alignright">Connect</button></li>
									<li class="j-social-connect clear"><span class="genericon genericon-twitter"></span><p class="alignleft">Twitter</p><button class="alignright">Connect</button></li>
									<li class="j-social-connect clear"><span class="genericon genericon-linkedin-alt"></span><p class="alignleft">LinkedIn</p><button class="alignright">Connect</button></li>
								</ul>
							</div>
							<div class="j-col j-lrg-6 j-sm-12 alignleft nopad">
								<ul>
									<li class="j-social-connect clear"><span class="genericon genericon-tumblr"></span><p class="alignleft">Tumblr</p><button class="alignright">Connect</button></li>
									<li class="j-social-connect clear"><span class="genericon genericon-path"></span><p class="alignleft">Path</p><button class="alignright">Connect</button></li>
									<li class="j-social-connect clear"><span class="genericon genericon-googleplus"></span><p class="alignleft">Google+</p><button class="alignright">Connect</button></li>
								</ul>
							</div>
						</div>
						<div class="j-col j-lrg-6 j-md-4 j-sm-12 alignleft ">
							<h3 class="alignleft">Enhanced Distribution <small>(enabled)</small></h3>
							<a href="javascript:void(0)"><span class="genericon genericon-help"></span></a>
							<p class="clear">Jetpack will automatically take the great published content from your blog or website and share it instantly with third party services like search engines, increasing your reach and traffic.</p>
						</div>
						<div class="j-col j-lrg-6 j-md-4 j-sm-12 alignleft ">
							<h3 class="alignleft">Notifications <small>(enabled)</small></h3>
							<a href="javascript:void(0)"><span class="genericon genericon-help"></span></a>
							<p class="clear">Keep up with the latest happenings on all your WordPress sites and interact with other WordPress.com users.</p>
						</div>
					</div>
				</div>
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
