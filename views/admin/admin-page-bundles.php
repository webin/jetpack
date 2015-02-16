<div class="masthead <?php if ( ! $data['is_connected'] || ! $data['is_user_connected'] ) echo 'hasbutton'; ?>">

	<?php Jetpack::init()->load_view( 'admin/network-activated-notice.php' ); ?>

	<?php do_action( 'jetpack_notices' ) ?>

	<h1><?php esc_html_e( 'Supercharge your self-hosted site with a suite of the most powerful WordPress.com features.', 'jetpack' ); ?></h1>

	<?php if ( ! $data['is_connected'] && current_user_can( 'jetpack_connect' ) ) : ?>
		<a href="<?php echo Jetpack::init()->build_connect_url() ?>" class="download-jetpack"><?php esc_html_e( 'Connect to Get Started', 'jetpack' ); ?></a>
	<?php elseif ( $data['is_connected'] && ! $data['is_user_connected'] && current_user_can( 'jetpack_connect_user' ) ) : ?>
		<a href="<?php echo Jetpack::init()->build_connect_url() ?>" class="download-jetpack"><?php esc_html_e( 'Link your account to WordPress.com', 'jetpack' ); ?></a>
	<?php endif; ?>

	<?php /* @TODO make miguel fly again
	<div class="flyby">
		<svg class="flyer" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="87px" viewBox="0 0 80 87" enable-background="new 0 0 80 87" xml:space="preserve">
					<polygon class="eye" fill="#518d2a" points="41.187,17.081 46.769,11.292 50.984,15.306"/>
			<path class="body" fill="#518d2a" d="M38.032,47.3l4.973-5.157l7.597,1.996l0.878-0.91l0.761-0.789l-0.688-2.838l-0.972-0.926l-1.858,1.926 l-2.206-2.1l3.803-3.944l0.09-3.872L80,0L61.201,10.382L60.2,15.976l-5.674,1.145l-8.09-7.702L34.282,22.024l8.828-1.109 l2.068,2.929l-4.996,0.655l-3.467,3.595l0.166-4.469l-4.486,0.355L21.248,35.539l-0.441,4.206l-2.282,2.366l-2.04,6.961 L27.69,37.453l4.693,1.442l-2.223,2.306l-4.912,0.095l-7.39,22.292l-8.06,3.848l-2.408,9.811l-3.343-0.739L0,86.739l30.601-31.733 l8.867,2.507l-7.782,8.07l-1.496-0.616l-0.317-2.623l-7.197,7.463l11.445-2.604l16.413-7.999L38.032,47.3z M42.774,16.143 l3.774-3.914l2.85,2.713L42.774,16.143z"/>
				</svg>
				<svg class="flyer" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="87px" viewBox="0 0 80 87" enable-background="new 0 0 80 87" xml:space="preserve">
					<polygon class="eye" fill="#518d2a" points="41.187,17.081 46.769,11.292 50.984,15.306   "/>
					<path class="body" fill="#518d2a" d="M38.032,47.3l4.973-5.157l7.597,1.996l0.878-0.91l0.761-0.789l-0.688-2.838l-0.972-0.926l-1.858,1.926 l-2.206-2.1l3.803-3.944l0.09-3.872L80,0L61.201,10.382L60.2,15.976l-5.674,1.145l-8.09-7.702L34.282,22.024l8.828-1.109 l2.068,2.929l-4.996,0.655l-3.467,3.595l0.166-4.469l-4.486,0.355L21.248,35.539l-0.441,4.206l-2.282,2.366l-2.04,6.961 L27.69,37.453l4.693,1.442l-2.223,2.306l-4.912,0.095l-7.39,22.292l-8.06,3.848l-2.408,9.811l-3.343-0.739L0,86.739l30.601-31.733 l8.867,2.507l-7.782,8.07l-1.496-0.616l-0.317-2.623l-7.197,7.463l11.445-2.604l16.413-7.999L38.032,47.3z M42.774,16.143 l3.774-3.914l2.85,2.713L42.774,16.143z"/>
				</svg>
				<svg class="flyer" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="80px" height="87px" viewBox="0 0 80 87" enable-background="new 0 0 80 87" xml:space="preserve">
					<polygon class="eye" fill="#518d2a" points="41.187,17.081 46.769,11.292 50.984,15.306   "/>
					<path class="body" fill="#518d2a" d="M38.032,47.3l4.973-5.157l7.597,1.996l0.878-0.91l0.761-0.789l-0.688-2.838l-0.972-0.926l-1.858,1.926 l-2.206-2.1l3.803-3.944l0.09-3.872L80,0L61.201,10.382L60.2,15.976l-5.674,1.145l-8.09-7.702L34.282,22.024l8.828-1.109 l2.068,2.929l-4.996,0.655l-3.467,3.595l0.166-4.469l-4.486,0.355L21.248,35.539l-0.441,4.206l-2.282,2.366l-2.04,6.961 L27.69,37.453l4.693,1.442l-2.223,2.306l-4.912,0.095l-7.39,22.292l-8.06,3.848l-2.408,9.811l-3.343-0.739L0,86.739l30.601-31.733 l8.867,2.507l-7.782,8.07l-1.496-0.616l-0.317-2.623l-7.197,7.463l11.445-2.604l16.413-7.999L38.032,47.3z M42.774,16.143 l3.774-3.914l2.85,2.713L42.774,16.143z"/>
				</svg>
	</div>
 */ ?>

	<div class="subhead">
		<?php if ( Jetpack::is_development_mode() ) : ?>
			<h2><?php _e('Jetpack is in local development mode.', 'jetpack' ); ?></h2>
		<?php elseif ( $data['is_connected'] ) : ?>
			<h2><?php _e("You're successfully connected to Jetpack!", 'jetpack' ); ?></h2>
			<?php
			if ( Jetpack::init()->can_display_jetpack_manage_notice() ) {
				Jetpack::init()->opt_in_jetpack_manage_notice();
			}
			?>
		<?php else : ?>
			<h2><?php _e('Once you’ve connected Jetpack, you’ll get access to all the delightful features below.', 'jetpack' ); ?></h2>
		<?php endif; ?>
	</div>
</div><!-- .masthead -->

<div class="page-content about">
	<div class="module-grid">

		<div class="bundle-settings-filter" id="bundle-filters">
			<a href="#" id="customization" data-filter="customization" class="selected"><?php esc_html_e( 'Customization', 'jetpack' ); ?></a>
			<a href="#" id="content-creation" data-filter="content-creation" class="selected"><?php esc_html_e( 'Content Creation', 'jetpack' ); ?></a>
			<a href="#" id="traffic" data-filter="traffic" class="selected"><?php esc_html_e( 'Traffic and Engagement', 'jetpack' ); ?></a>
			<a href="#" id="security" data-filter="security" class="selected"><?php esc_html_e( 'Security', 'jetpack' ); ?></a>
			<a href="#" id="performance" data-filter="performance" class="selected"><?php esc_html_e( 'Performance', 'jetpack' ); ?></a>
			<a href="#" id="development" data-filter="development" class="selected"><?php esc_html_e( 'Development', 'jetpack' ); ?></a>
		</div>

	</div><!-- .module-grid -->
</div><!-- .page -->