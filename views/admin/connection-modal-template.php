<script id="tmpl-connection-modal" type="text/html">
	<span id="modal-label" class="screen-reader-text"><?php _e( 'Modal window. Press escape to close.', 'jetpack' ); ?></span>
	<a href="#" class="close">&times; <span class="screen-reader-text"><?php _e( 'Close modal window', 'jetpack' ); ?></span></a>
	<div class="content-container <# if ( data.available) { #>modal-footer<# } #>">
		<div id="my-connection-content" class="content">
			<h2>Your Jetpack Connection</h2>
			<# if ( data.isMasterUser ) { #>
				<p>You are the master user</p>
			<# } else { #>
				<p>You are not the master user, {{{ data.masterUserLink }}} is.</p>
				<# if ( data.isUserConnected ) { #>
					<p>You are connected to WordPress.com</p>
					<a id="set-self-as-master" class="button primary">set yourself as master</a>
					<span class="spinner"></span>
				<# } else { #>
					<p>Your user account is not linked to WordPress.com</p>
					<a href="<?php echo Jetpack::init()->build_connect_url() ?>" class="download-jetpack"><?php esc_html_e( 'Link your account to WordPress.com', 'jetpack' ); ?></a>
				<# } #>
			<# } #>
		</div>
	</div>
</script>
