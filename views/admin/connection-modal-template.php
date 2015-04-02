<script id="tmpl-connection-modal" type="text/html">
	<span id="modal-label" class="screen-reader-text"><?php _e( 'Modal window. Press escape to close.', 'jetpack' ); ?></span>
	<a href="#" class="close">&times; <span class="screen-reader-text"><?php _e( 'Close modal window', 'jetpack' ); ?></span></a>
	<div class="content-container <# if ( data.available) { #>modal-footer<# } #>">
		<div id="my-connection-content" class="content">
			<h2>Your Jetpack Connection</h2>
			<table>
				<tr>
					<th>User</th>
					<th>WordPress.com Account</th>
					<th>Actions</th>
				</tr>
				<# if ( data.isMasterUser && data.isAdmin ) { #>
				<tr>
					<td><strong><?php esc_html_e('You (primary)', 'jetpack'); ?></strong></td>
					<td title="user: {{{ data.masterComData.login }}} / email: {{{ data.masterComData.email }}} "><strong>{{{ data.masterComData.login }}}</strong></td>
					<td><a class="button" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=disconnect' ), 'jetpack-disconnect' ); ?>" onclick="return confirm('<?php echo htmlspecialchars( __('Are you sure you want to disconnect from WordPress.com?', 'jetpack'), ENT_QUOTES ); ?>');"><?php esc_html_e( 'Disconnect', 'jetpack' ); ?></a></td>
				</tr>
				<# } else { #>
				<tr>
					<td><strong>{{{ data.masterUserLink }}}<?php esc_html_e(' (primary)', 'jetpack'); ?></strong></td>
					<td title="user: {{{ data.masterComData.login }}} / email: {{{ data.masterComData.email }}} "><strong>{{{ data.masterComData.login }}}</strong></td>
					<td>&nbsp;</td>
				</tr>
					<# if ( data.isActive && data.isAdmin ) { #>
						<tr>
							<td><?php esc_html_e('You', 'jetpack'); ?></td>
							<# if ( data.isUserConnected ) { #>
								<td title="user: {{{ data.userComData.login }}} / email: {{{ data.userComData.email }}} "><strong>{{{ data.userComData.login }}}</strong></td>
								<td><a id="set-self-as-master" class="button primary"><?php esc_html_e( 'Make primary account', 'jetpack' ); ?></a><span class="spinner"></span><a class="button" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=unlink' ), 'jetpack-unlink' ); ?>"><?php esc_html_e( ' Disconnect', 'jetpack' ); ?></a></td>
							<# } else { #>
								<td>&nbsp;</td>
								<td><a href="<?php echo Jetpack::init()->build_connect_url() ?>" class="button"><?php esc_html_e( 'Link your account', 'jetpack' ); ?></a></td>
							<# } #>

						</tr>
					<# } #>
				<# } #>
			</table>
		</div>
	</div>
</script>
