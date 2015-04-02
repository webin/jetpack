<script id="tmpl-connection-modal" type="text/html">
	<span id="modal-label" class="screen-reader-text"><?php _e( 'Modal window. Press escape to close.', 'jetpack' ); ?></span>
	<a href="#" class="close">&times; <span class="screen-reader-text"><?php _e( 'Close modal window', 'jetpack' ); ?></span></a>
	<div class="content-container <# if ( data.available) { #>modal-footer<# } #>">
		<div id="my-connection-content" class="content">
			<h2>Your Jetpack Connection</h2>
			<table>
				<tr>
					<th><?php _e( 'User', 'jetpack' ); ?></th>
					<th><?php _e( 'WordPress.com Username', 'jetpack' ); ?></th>
					<th><?php _e( 'Actions', 'jetpack' ); ?></th>
				</tr>
				<# if ( data.isAdmin ) { #><?php /* if user has admin privledges */ ?>
					<# if ( data.isMasterUser ) { #><?php /* if user is the owner of the primary jetpack connection */ ?>
					<tr>
						<td><strong><?php esc_html_e( 'You (primary)', 'jetpack' ); ?></strong></td>
						<td><strong>{{{ data.masterComData.login }}}</strong></td>
						<td><a class="button" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=disconnect' ), 'jetpack-disconnect' ); ?>" onclick="return confirm('<?php echo htmlspecialchars( __( 'Are you sure you want to disconnect from WordPress.com?', 'jetpack' ), ENT_QUOTES ); ?>');"><?php esc_html_e( 'Disconnect', 'jetpack' ); ?></a></td>
					</tr>
					<# } else { #><?php /* user is an admin but not the owner primary jetpack connection */ ?>
					<tr>
						<td><strong>{{{ data.masterUserLink }}}<?php esc_html_e( ' (primary)', 'jetpack' ); ?></strong></td>
						<td><strong>{{{ data.masterComData.login }}}</strong></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><?php esc_html_e( 'You', 'jetpack' ); ?></td>
						<# if ( data.isUserConnected ) { #><?php /* user is connected to Jetpack */ ?>
							<td>{{{ data.userComData.login }}}</td>
							<td><a id="set-self-as-master" class="button primary"><?php esc_html_e( 'Make primary account', 'jetpack' ); ?></a><span class="spinner"></span><a class="button" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=unlink' ), 'jetpack-unlink' ); ?>"><?php esc_html_e( ' Disconnect', 'jetpack' ); ?></a></td>
						<# } else { #><?php /* user isn't connected to Jetpack at all and should see a connection prompt */ ?>
							<td>&nbsp;</td>
							<td><a href="<?php echo Jetpack::init()->build_connect_url() ?>" class="button"><?php esc_html_e( 'Link your account', 'jetpack' ); ?></a></td>
						<# } #><?php /* end data.isUserConnected */ ?>
					</tr>
					<# } #><?php /* end data.isMasterUser */ ?>
				<# } else { #><?php /* User doesn't have admin privledges */ ?>
				<tr>
					<td><?php _e( 'You', 'jetpack' ); ?></td>
					<# if ( data.isUserConnected ) { #><?php /* user is connected to Jetpack */ ?>
						<td><strong>{{{ data.userComData.login }}}</strong></td>
						<td><a class="button" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=unlink' ), 'jetpack-unlink' ); ?>"><?php esc_html_e( ' Disconnect', 'jetpack' ); ?></a></td>
					<# } else { #><?php /* user isn't connected to Jetpack at all and should see a connection prompt */ ?>
						<td>&nbsp;</td>
						<td><a href="<?php echo Jetpack::init()->build_connect_url() ?>" class="button"><?php esc_html_e( 'Link your account', 'jetpack' ); ?></a></td>
					<# } #><?php /* end data.isUserConnected */ ?>
				</tr>
				<# } #><?php /* end data.isAdmin */ ?>
			</table>
		</div>
	</div>
</script>
