<?php
include_once( 'class.jetpack-admin-page.php' );
include_once( JETPACK__PLUGIN_DIR . 'class.jetpack-modules-list-table.php' );

// Builds the My Connection page
class Jetpack_My_Connection_Page extends Jetpack_Admin_Page {
	// Show the settings page only when Jetpack is connected or in dev mode
	protected $dont_show_if_not_active = true;
	function add_page_actions( $hook ) {} // There are no page specific actions to attach to the menu

	// Adds the My Connection page, but hides it from the submenu
	function get_page_hook() {
		return add_submenu_page( null, __( 'My Connection', 'jetpack' ), __( 'My Connection', 'jetpack' ), 'jetpack_manage_modules', 'my_connection', array( $this, 'render' ) );
	}

	// Renders the view file
	function page_render() {
		Jetpack::init()->load_view( 'admin/my-connection-page.php' );
	}

	// Load up admin scripts
	function page_admin_scripts() {
//		wp_enqueue_script( 'jp-connection-js', plugins_url( '_inc/jp-connection.js', JETPACK__PLUGIN_FILE ), array( 'jquery' ), JETPACK__VERSION . 'today' );
	}
}
