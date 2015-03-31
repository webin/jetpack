<script id="tmpl-connection-modal" type="text/html">
	<span id="modal-label" class="screen-reader-text"><?php _e( 'Modal window. Press escape to close.', 'jetpack' ); ?></span>
	<a href="#" class="close">&times; <span class="screen-reader-text"><?php _e( 'Close modal window', 'jetpack' ); ?></span></a>
	<div class="content-container <# if ( data.available) { #>modal-footer<# } #>">
		<div id="my-connection-content" class="content">
			<h2>Your Jetpack Connection</h2>
			<# if ( data.isMasterUser && data.isAdmin ) { #>
				<p>You are the master user</p>
				<a href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=disconnect' ), 'jetpack-disconnect' ); ?>" onclick="return confirm('<?php echo htmlspecialchars( __('Are you sure you want to disconnect from WordPress.com?', 'jetpack'), ENT_QUOTES ); ?>');"><?php esc_html_e( 'Disconnect from WordPress.com', 'jetpack' ); ?></a>
			<# } else { #>
				<p>You are not the master user, {{{ data.masterUserLink }}} is.</p>
				<# if ( data.isUserConnected && data.isAdmin && data.isActive ) { #>
					<p>You are connected to WordPress.com</p>
					<a id="set-self-as-master" class="button primary">set yourself as master</a><span class="spinner"></span><br /><br />
					<a href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=unlink' ), 'jetpack-unlink' ); ?>"><?php esc_html_e( 'Unlink your user account', 'jetpack' ); ?></a>
				<# } else { #>
					<p>Your user account is not linked to WordPress.com</p>
					<a href="<?php echo Jetpack::init()->build_connect_url() ?>" class="download-jetpack"><?php esc_html_e( 'Link your account to WordPress.com', 'jetpack' ); ?></a>
				<# } #>
			<# } #>
		</div>
	</div>
</script>
