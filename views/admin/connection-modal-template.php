<script id="tmpl-connection-modal" type="text/html">
	<span id="modal-label" class="screen-reader-text"><?php _e( 'Modal window. Press escape to close.', 'jetpack' ); ?></span>
	<a href="#" class="close">&times; <span class="screen-reader-text"><?php _e( 'Close modal window', 'jetpack' ); ?></span></a>
	<div class="content-container <# if ( data.available) { #>modal-footer<# } #>">
		<div id="my-connection-content" class="content">
			<h2>Your Jetpack Connection</h2>
			<?php /*
			<div class="connection-details">
				<div class="j-col j-lrg-4 j-md-4 j-sm-12 jp-user">
					<h3 title="<?php _e( 'User', 'jetpack' ); ?>"><?php _e( 'User', 'jetpack' ); ?></h3>
					<div class="user-01">Jeff Golenski</div>
					<div class="user-02">Jesse Friedman</div>

				</div><!-- // jp-user -->

				<div class="j-col j-lrg-4 j-md-4 j-sm-12 wp-user">
					<h3 title="<?php _e( 'WordPress.com Username', 'jetpack' ); ?>"><?php _e( 'WordPress.com Username', 'jetpack' ); ?></h3>
					<div class="wpuser-01">Jeff Golenski wpcom</div>
					<div class="wpuser-02">Jesse Friedman wpcom</div>

				</div><!-- // wp-user -->

				<div class="j-col j-lrg-4 j-md-4 j-sm-12 jp-actions">
					<h3 title="<?php _e( 'Actions', 'jetpack' ); ?>"><?php _e( 'Actions', 'jetpack' ); ?></h3>
					<div class="action-01">
						<a class="button" title="Disconnect your WordPress.com account from Jetpack" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=unlink' ), 'jetpack-unlink' ); ?>"><?php esc_html_e( 'Disconnect ', 'jetpack' ); ?><span class="ifmobile"><?php esc_html_e( '[Username]', 'jetpack' ); ?></span><?php // esc_html_e( ' Disconnect <span class="ifmobile">Jeff Golenski</span>', 'jetpack' ); ?></a>
					</div>
					<div class="action-02">
					<a class="button" href=""><?php esc_html_e( 'Make ', 'jetpack' ); ?><span class="ifmobile"><?php esc_html_e( '[Username]', 'jetpack' ); ?></span><?php esc_html_e( ' Primary', 'jetpack' ); ?><?php // esc_html_e( ' Make <span class="ifmobile">Jeff Golenski</span> Primary', 'jetpack' ); ?></a>
				</div>
			</div>

			<?php */ // old table layout below - going to be removed once ported to updated html above ?>
			<br clear="all" />
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
							<td><a class="button" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=disconnect' ), 'jetpack-disconnect' ); ?>" onclick="return confirm('<?php echo htmlspecialchars( __( 'Are you sure you want to disconnect from WordPress.com?', 'jetpack' ), ENT_QUOTES ); ?>');"><?php esc_html_e( 'Disconnect Site', 'jetpack' ); ?></a></td>
						</tr>
					<# } else { #><?php /* user is an admin but not the owner primary jetpack connection */ ?>
						<tr>
							<td><strong>{{{ data.masterUserLink }}}<?php esc_html_e( ' (primary)', 'jetpack' ); ?></strong></td>
							<td><strong>{{{ data.masterComData.login }}}</strong></td>
							<td><a class="button" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=disconnect' ), 'jetpack-disconnect' ); ?>" onclick="return confirm('<?php echo htmlspecialchars( __( 'Are you sure you want to disconnect from WordPress.com?', 'jetpack' ), ENT_QUOTES ); ?>');"><?php esc_html_e( 'Disconnect Site', 'jetpack' ); ?></a></td>
						</tr>
						<tr>
							<td><?php esc_html_e( 'You', 'jetpack' ); ?></td>
							<# if ( data.isUserConnected ) { #><?php /* user is connected to Jetpack */ ?>
								<td>{{{ data.userComData.login }}}</td>
								<td><a id="set-self-as-master" class="button primary"><?php esc_html_e( 'Make primary account', 'jetpack' ); ?></a><span class="spinner"></span><a class="button" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=unlink' ), 'jetpack-unlink' ); ?>"><?php esc_html_e( ' Disconnect Account', 'jetpack' ); ?></a></td>
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
						<td><a class="button" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=unlink' ), 'jetpack-unlink' ); ?>"><?php esc_html_e( ' Disconnect Account', 'jetpack' ); ?></a></td>
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

<script id="tmpl-connection-modal-loading" type="text/html">
<p>Loading...</p>
</script>
