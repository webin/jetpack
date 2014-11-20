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

	static function render_module_names_list( $module_slugs ) {
		$module_names = array();
		foreach ( (array) $module_slugs as $module_slug ) {
			if ( 'jetpack' === $module_slug ) {
				$module_names[] = esc_html__( 'Jetpack Core', 'jetpack' );
				continue;
			}
			$module = Jetpack::get_module( $module_slug );
			$module_names[] = esc_html( $module['name'] );
		}
		echo implode( '<br />', $module_names );
	}

	function page_render() {
		$list_table = new Jetpack_Modules_List_Table;
		?>
		<div class="clouds-sm"></div>
		<?php do_action( 'jetpack_notices' ) ?>
		<div class="page-content" style="padding: 0 1em;">
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
			?>

			<h3><?php esc_html_e( 'Synced Posts', 'jetpack' ); ?></h3>
			<table class="wp-list-table widefat jetpack-sync-posts-table">
				<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Post Type', 'jetpack' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Post Status', 'jetpack' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Needed By', 'jetpack' ); ?></th>
				</tr>
				</thead>
				<tfoot>
				<tr>
					<th scope="col"><?php esc_html_e( 'Post Type', 'jetpack' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Post Status', 'jetpack' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Needed By', 'jetpack' ); ?></th>
				</tr>
				</tfoot>
				<tbody>
				<?php $alternate = 0; ?>
				<?php if ( empty( $synced['post_types'] ) ) : ?>
					<tr class="no-items">
						<th scope="row" colspan="3"><?php esc_html_e( 'None', 'jetpack' ); ?></th>
					</tr>
				<?php else : foreach ( $synced['post_types'] as $post_type => $post_type_data ) : ?>
					<?php foreach ( $post_type_data as $post_status => $module_slugs ) : ?>
						<tr class="<?php echo ( $alternate = 1 - $alternate ) ? 'alternate' : ''; ?>">
							<th scope="row"><?php echo esc_html( $post_type ); ?></th>
							<th scope="row"><?php echo esc_html( $post_status ); ?></th>
							<td><?php echo self::render_module_names_list( $module_slugs ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endforeach; endif; ?>
				</tbody>
			</table>

			<h3><?php esc_html_e( 'Synced Comments', 'jetpack' ); ?></h3>
			<table class="wp-list-table widefat jetpack-sync-comments-table">
				<thead>
				<tr>
					<th scope="col"><?php esc_html_e( 'Comment Type', 'jetpack' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Comment Status', 'jetpack' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Needed By', 'jetpack' ); ?></th>
				</tr>
				</thead>
				<tfoot>
				<tr>
					<th scope="col"><?php esc_html_e( 'Comment Type', 'jetpack' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Comment Status', 'jetpack' ); ?></th>
					<th scope="col"><?php esc_html_e( 'Needed By', 'jetpack' ); ?></th>
				</tr>
				</tfoot>
				<tbody>
				<?php $alternate = 0; ?>
				<?php if ( empty( $synced['comment_types'] ) ) : ?>
					<tr class="no-items">
						<th scope="row" colspan="3"><?php esc_html_e( 'None', 'jetpack' ); ?></th>
					</tr>
				<?php else : foreach ( $synced['comment_types'] as $comment_type => $comment_type_data ) : ?>
					<?php foreach ( $comment_type_data as $comment_status => $module_slugs ) : ?>
						<tr class="<?php echo ( $alternate = 1 - $alternate ) ? 'alternate' : ''; ?>">
							<th scope="row"><?php echo esc_html( $comment_type ); ?></th>
							<th scope="row"><?php echo esc_html( $comment_status ); ?></th>
							<td><?php echo self::render_module_names_list( $module_slugs ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php endforeach; endif; ?>
				</tbody>
			</table>

			<h3><?php esc_html_e( 'Synced Options', 'jetpack' ); ?></h3>
			<table class="wp-list-table widefat jetpack-sync-options-table" style="width: auto;">
				<thead>
					<tr>
						<th scope="col"><?php esc_html_e( 'Option Name', 'jetpack' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Needed By', 'jetpack' ); ?></th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th scope="col"><?php esc_html_e( 'Option Name', 'jetpack' ); ?></th>
						<th scope="col"><?php esc_html_e( 'Needed By', 'jetpack' ); ?></th>
					</tr>
				</tfoot>
				<tbody>
					<?php $alternate = 0; ?>
					<?php foreach ( $synced['options'] as $option_name => $module_slugs ) : ?>
					<tr class="<?php echo ( $alternate = 1 - $alternate ) ? 'alternate' : ''; ?>">
						<th scope="row"><?php echo esc_html( $option_name ); ?></th>
						<td><?php echo self::render_module_names_list( $module_slugs ); ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div><!-- /.content -->
	<?php
	}

	function page_admin_scripts() {}
}
?>
