<?php
include_once( 'class.jetpack-admin-page.php' );

class Jetpack_Privacy_Page extends Jetpack_Admin_Page {
	// Show the settings page only when Jetpack is connected or in dev mode
	protected $dont_show_if_not_active = false;
	function add_page_actions( $hook ) {} // There are no page specific actions to attach to the menu

	// Adds the Settings sub menu
	function get_page_hook() {
		return add_submenu_page( null, __( 'Jetpack Privacy', 'jetpack' ), __( 'Privacy', 'jetpack' ), 'jetpack_manage_modules', 'jetpack_privacy', array( $this, 'render' ) );
	}

	// Renders the module list table where you can use bulk action or row
	// actions to activate/deactivate and configure modules
	function page_render() {
		$list_table = new Jetpack_Modules_List_Table;
		?>
		<div class="clouds-sm"></div>
		<?php do_action( 'jetpack_notices' ) ?>
		<div class="page-content configure">
			<?php
			$sync = Jetpack::init()->sync;

			$synced = array(
				'post_types'    => array(),
				'comment_types' => array(),
				'options'       => array(),
			);

			foreach ( $sync->sync_conditions['posts'] as $module_slug => $what_is_synced ) {
				foreach ( $what_is_synced['post_types'] as $post_type ) {
					foreach ( $what_is_synced['post_stati'] as $post_status ) {
						$synced['post_types'][ $post_type ][ $post_status ][] = $module_slug;
					}
				}
			}

			foreach ( $sync->sync_conditions['comments'] as $module_slug => $what_is_synced ) {
				foreach ( $what_is_synced['comment_types'] as $comment_type ) {
					foreach ( $what_is_synced['comment_stati'] as $comment_status ) {
						$synced['comment_types'][ $comment_type ][ $comment_status ][] = $module_slug;
					}
				}
			}

			foreach ( $sync->sync_options as $module_slug => $options ) {
				foreach ( $options as $option_name ) {
					$synced['options'][ $option_name ][] = $module_slug;
				}
			}

			echo '<pre>';
			var_dump( $synced );
			echo '</pre>';
			?>
		</div><!-- /.content -->
	<?php
	}

	function page_admin_scripts() {}
}
?>
