<?php
/**
 * Plugin Name: Display Recent WordPress Posts Widget
 * Description: Displays recent posts from a WordPress.com or Jetpack-enabled self-hosted WordPress site.
 * Version: 1.0
 * Author: Brad Angelcyk, Kathryn Presner, Justin Shreve, Carolyn Sonnek
 * Author URI: http://automattic.com
 * License: GPL2
 */
add_action( 'widgets_init', 'jetpack_display_posts_widget' );
function jetpack_display_posts_widget() {
	 register_widget( 'Jetpack_Display_Posts_Widget' );
}

/*
 * Display a list of recent posts from a WordPress.com or Jetpack-enabled blog.
 */
class Jetpack_Display_Posts_Widget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			// internal id
			'jetpack_display_posts_widget',
			// wp-admin title
			apply_filters( 'jetpack_widget_name', __( 'Display WordPress Posts', 'jetpack' ) ),
			array(
				'description' => __( 'Displays a list of recent posts from another WordPress.com or Jetpack-enabled blog.', 'jetpack' ),
			)
		);
	}

	/**
	 * Expiring transients have a name length maximum of 45 characters,
	 * so this function returns an abbreviated MD5 hash to use instead of
	 * the full URI.
	 */
	public function get_site_hash( $site ) {
		return substr( md5( $site ), 0, 21 );
	}

	public function get_site_info( $site ) {
		$site_hash = $this->get_site_hash( $site );
		$data_from_cache = get_transient( 'display_posts_site_info_' . $site_hash );
		if ( false === $data_from_cache ) {
			$response = wp_remote_get( sprintf( 'https://public-api.wordpress.com/rest/v1/sites/%s', urlencode( $site ) ) );
		} else {
			$response = $data_from_cache;
		}

		if ( is_wp_error( $response ) ) {
			error_log( 'Display WordPress Posts Error: ' . print_r( $response, 1 ) );
			return false;
		}

		$site_info = json_decode( $response ['body'] );
		if ( ! isset( $site_info->ID ) ) {
			error_log( 'Display WordPress Posts Error: ' . print_r( $site_info, 1 ) );
			return false;
		}

		// If we've made it this far without any errors, let's write the results
		// to the DB, but only if they are new or don't exist yet.
		$site_info_from_db = get_option( 'display_posts_site_info_' . $site_hash );
		if ( ! $site_info_from_db || $site_info_from_db !== $site_info ) {
			update_option( 'display_posts_site_info_' . $site_hash, $site_info );
		}

		// Set transient when we know we have good data with no errors
//		set_transient( 'display_posts_site_info_' . $site_hash, $response, 1 * MINUTE_IN_SECONDS );

		return $site_info;
	}

	/*
	 * Set up the widget display on the front end
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$site_hash = $this->get_site_hash( $instance['url'] );

		// All of the back up DB options we're storing.
		$site_info_from_db = get_option( 'display_posts_site_info_' . $site_hash );
		$response_from_db = get_option( 'display_posts_response_info_' . $site_hash );
		$posts_from_db = get_option( 'display_posts_post_info_' . $site_hash );

		wp_enqueue_style( 'jetpack_display_posts_widget', plugins_url( 'wordpress-post-widget/style.css', __FILE__ ) );

		$site_info = $this->get_site_info( $instance['url'] );

		echo $args['before_widget'];

		if ( false === $site_info ) {
			// Before we fail, let's check our DB for any back-up data of the $site_info_from_db
			if ( $site_info_from_db ) {
				$site_info = $site_info_from_db;
			} else {
				printf( '<p>' . __( 'Currently having trouble retrieving the site data for %s. This usually clears itself up after a few minutes, please try again later.', 'jetpack' ) . '</p>', $instance['url'] );
				echo $args['after_widget'];
				return;
			}
		}

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . esc_html( $title . ': ' . $site_info->name ) . $args['after_title'];
		} else {
			echo $args['before_title'] . esc_html( $site_info->name ) . $args['after_title'];
		}

		$data_from_cache = get_transient( 'display_posts_post_info_' . $site_hash );
		if ( false === $data_from_cache ) {
			$response = wp_remote_get( sprintf( 'https://public-api.wordpress.com/rest/v1/sites/%d/posts/', $site_info->ID ) );
		} else {
			$response = $data_from_cache;
		}

		if ( is_wp_error( $response ) ) {
			// Before we fail, let's check our DB for any back-up data we have
			if ( $response_from_db ) {
				$response = $response_from_db;
			} else {
				printf( '<p>' . __( '1 - Currently having trouble retrieving posts from %s. This usually clears itself up after a few minutes, please try again later.', 'jetpack' ) . '</p>', $instance['url'] );
				echo $args['after_widget'];
				return;
			}
		}

		$posts_info = json_decode( wp_remote_retrieve_body( $response ) );

		echo '<div class="jetpack-display-remote-posts">';

		if ( isset( $posts_info->error ) && 'jetpack_error' == $posts_info->error ) {
			// Before we fail, let's check our DB for any back-up data
			if ( $posts_from_db ) {
				$posts_info = $posts_from_db;
			} else {
				printf( '<p>' . __( '2- Currently having trouble retrieving posts from %s. This usually clears itself up after a few minutes, please try again later.', 'jetpack' ) . '</p>', $instance['url'] );
				echo '</div><!-- .jetpack-display-remote-posts -->';
				echo $args['after_widget'];

				return;
			}
		}

		// If we've made it this far without any errors, let's write the results
		// to the DB, but only if they are new or don't exist yet.
		if ( ! $posts_from_db || $posts_from_db !== $posts_info ) {
			update_option( 'display_posts_post_info_' . $site_hash, $posts_info );
		}

		if ( ! $response_from_db || $response_from_db !== $response ) {
			update_option( 'display_posts_response_info_' . $site_hash, $response );
		}

		// Set transient when we know we have good data with no errors
//		set_transient( 'display_posts_post_info_' . $site_hash, $response, 1 * MINUTE_IN_SECONDS );

		$number_of_posts = min( $instance['number_of_posts'], count( $posts_info->posts ) );

		for ( $i = 0; $i < $number_of_posts; $i++ ) {
			$single_post = $posts_info->posts[$i];
			$post_title = ( $single_post->title ) ? $single_post->title : '( No Title )';

			echo '<h4><a href="' . esc_url( $single_post->URL ) . '">' . esc_html( $post_title ) . '</a></h4>' . "\n";
			if ( ( $instance['featured_image'] == true ) && ( ! empty ( $single_post->featured_image) ) ) {
				$featured_image = ( $single_post->featured_image ) ? $single_post->featured_image  : '';
				echo '<a title="' . esc_attr( $post_title ) . '" href="' . esc_url( $single_post->URL ) . '"><img src="' . $featured_image . '" alt="' . esc_attr( $post_title ) . '"/></a>';
			}

			if ( $instance['show_excerpts'] == true ) {
				$post_excerpt = ( $single_post->excerpt ) ? $single_post->excerpt  : '';
				echo $post_excerpt;
			}
		}

		echo '</div><!-- .jetpack-display-remote-posts -->';
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		} else {
			$title = __( 'Recent Posts', 'jetpack' );
		}

		if ( isset( $instance[ 'url' ] ) ) {
			$url = $instance[ 'url' ];
		} else {
			$url = '';
		}

		if ( isset( $instance[ 'number_of_posts' ] ) ) {
			$number_of_posts = $instance[ 'number_of_posts' ];
		} else {
			$number_of_posts = 5;
		}

		if ( isset( $instance[ 'featured_image'] ) ) {
			$featured_image = $instance[ 'featured_image'];
		} else {
			$featured_image = false;
		}

		if ( isset( $instance[ 'show_excerpts'] ) ) {
			$show_excerpts = $instance[ 'show_excerpts'];
		} else {
			$show_excerpts = false;
		}

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'jetpack' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'Blog URL:', 'jetpack' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo esc_attr( $url ); ?>" />
			<p>
			<?php _e( "Enter a WordPress.com or Jetpack WordPress site URL.", 'jetpack' ); ?>
			</p>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'number_of_posts' ); ?>"><?php _e( 'Number of Posts to Display:', 'jetpack' ); ?></label>
			<select name="<?php echo $this->get_field_name( 'number_of_posts' ); ?>">
				<?php
					for ($i = 1; $i <= 10; $i++) {
					echo '<option value="' . $i . '" '.selected( $number_of_posts, $i ).'>' . $i . '</option>';
					}
				?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'featured_image' ); ?>"><?php _e( 'Show Featured Image:', 'jetpack' ); ?></label>
			<input type="checkbox" name="<?php echo $this->get_field_name( 'featured_image' ); ?>" <?php checked( $featured_image, 1 ); ?> />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'show_excerpts' ); ?>"><?php _e( 'Show Excerpts:', 'jetpack' ); ?></label>
			<input type="checkbox" name="<?php echo $this->get_field_name( 'show_excerpts' ); ?>" <?php checked( $show_excerpts, 1 ); ?> />
		</p>

		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['url'] = ( ! empty( $new_instance['url'] ) ) ? strip_tags( $new_instance['url'] ) : '';
		$instance['url'] = str_replace( "http://", "", $instance['url'] );
		$instance['url'] = untrailingslashit( $instance['url'] );

		// Normalize www.
		$site_info = $this->get_site_info( $instance['url'] );
		if ( ! $site_info && 'www.' === substr( $instance['url'], 0, 4 ) ) {
			$site_info = $this->get_site_info( substr( $instance['url'], 4 ) );
			if ( $site_info ) {
				$instance['url'] = substr( $instance['url'], 4 );
			}
		}

		$instance['number_of_posts'] = ( ! empty( $new_instance['number_of_posts'] ) ) ? intval( $new_instance['number_of_posts'] ) : '';
		$instance['featured_image'] = ( ! empty( $new_instance['featured_image'] ) ) ? true : '';
		$instance['show_excerpts'] = ( ! empty( $new_instance['show_excerpts'] ) ) ? true : '';
		return $instance;
	}
}
