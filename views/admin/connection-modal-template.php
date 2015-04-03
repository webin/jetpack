<script id="tmpl-connection-modal" type="text/html">
	<span id="modal-label" class="screen-reader-text"><?php _e( 'Modal window. Press escape to close.', 'jetpack' ); ?></span>
	<a href="#" class="close">&times; <span class="screen-reader-text"><?php _e( 'Close modal window', 'jetpack' ); ?></span></a>
	<div class="content-container <# if ( data.available) { #>modal-footer<# } #>">
		<div id="my-connection-content" class="content">
			<h2>Your Jetpack Connection</h2>
			<div class="connection-details">
				<# if ( data.isAdmin ) { #><?php /* if user has admin privledges */ ?>
					<# if ( data.connectionLogic.isMasterUser ) { #><?php /* if user is the owner of the primary jetpack connection */ ?>
						<div class="j-col j-lrg-4 j-md-4 j-sm-12 jp-user">
							<h3 title="<?php _e( 'User', 'jetpack' ); ?>"><?php _e( 'User', 'jetpack' ); ?></h3>
							<div class="user-01"><span><?php esc_html_e( ' You (primary)', 'jetpack' ); ?></span></div>
						</div><!-- // jp-user -->
						<div class="j-col j-lrg-4 j-md-4 j-sm-12 wp-user">
							<h3 title="<?php _e( 'WordPress.com Username', 'jetpack' ); ?>"><?php _e( 'WordPress.com Username', 'jetpack' ); ?></h3>
							<div class="wpuser-02">{{{ data.userComData.login }}}</div>
						</div><!-- // wp-user -->
						<div class="j-col j-lrg-4 j-md-4 j-sm-12 jp-actions">
							<h3 title="<?php _e( 'Actions', 'jetpack' ); ?>"><?php _e( 'Actions', 'jetpack' ); ?></h3>
							<div class="action-01">
								<a class="button" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=disconnect' ), 'jetpack-disconnect' ); ?>" onclick="return confirm('<?php echo htmlspecialchars( __( 'Are you sure you want to disconnect from WordPress.com?', 'jetpack' ), ENT_QUOTES ); ?>');"><?php esc_html_e( 'Disconnect Site', 'jetpack' ); ?></a>
							</div>
						</div>
					<# } else { #><?php /* user is an admin but not the owner primary jetpack connection */ ?>
						<div class="j-col j-lrg-4 j-md-4 j-sm-12 jp-user">
							<h3 title="<?php _e( 'User', 'jetpack' ); ?>"><?php _e( 'User', 'jetpack' ); ?></h3>
							<div class="user-01"><span>{{{ data.connectionLogic.masterUserLink }}}<?php esc_html_e( ' (primary)', 'jetpack' ); ?></span></div>
							<div class="user-02"><span><?php esc_html_e( 'You', 'jetpack' ); ?><span></div>
						</div><!-- // jp-user -->

						<div class="j-col j-lrg-4 j-md-4 j-sm-12 wp-user">
							<h3 title="<?php _e( 'WordPress.com Username', 'jetpack' ); ?>"><?php _e( 'WordPress.com Username', 'jetpack' ); ?></h3>
							<div class="wpuser-01">{{{ data.masterComData.login }}}</div>
							<div class="wpuser-02">{{{ data.userComData.login }}}</div>
						</div><!-- // wp-user -->

						<# if ( data.connectionLogic.isUserConnected ) { #><?php /* user is connected to Jetpack */ ?>
							<div class="j-col j-lrg-4 j-md-4 j-sm-12 jp-actions">
								<h3 title="<?php _e( 'Actions', 'jetpack' ); ?>"><?php _e( 'Actions', 'jetpack' ); ?></h3>
								<div class="action-01">
									<a class="button" id="set-self-as-master"><?php esc_html_e( 'Make ', 'jetpack' ); ?><span class="ifmobile">{{{ data.userComData.login }}}</span><?php esc_html_e( ' Primary', 'jetpack' ); ?></a>
								</div>
								<div class="action-02">
									<a class="button" title="Disconnect your WordPress.com account from Jetpack" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=unlink' ), 'jetpack-unlink' ); ?>"><?php esc_html_e( 'Disconnect ', 'jetpack' ); ?><span class="ifmobile">{{{ data.userComData.login }}}</span></a>
								</div>
							</div>
						<# } else { #><?php /* user isn't connected to Jetpack at all and should see a connection prompt */ ?>
							<div class="j-col j-lrg-4 j-md-4 j-sm-12 jp-actions">
								<h3 title="<?php _e( 'Actions', 'jetpack' ); ?>"><?php _e( 'Actions', 'jetpack' ); ?></h3>
								<div class="action-01"></div>
								<div class="action-02">
									<a href="<?php echo Jetpack::init()->build_connect_url() ?>" class="button"><?php esc_html_e( 'Link your account', 'jetpack' ); ?></a>
								</div>
							</div>
						<# } #><?php /* end data.connectionLogic.isUserConnected */ ?>
					<# } #><?php /* end data.connectionLogic.isMasterUser */ ?>
				<# } else { #><?php /* User doesn't have admin privledges */ ?>
					<# if ( data.connectionLogic.isUserConnected ) { #><?php /* user is connected to Jetpack */ ?>
						<div class="j-col j-lrg-4 j-md-4 j-sm-12 jp-user">
							<h3 title="<?php _e( 'User', 'jetpack' ); ?>"><?php _e( 'User', 'jetpack' ); ?></h3>
							<div class="user-01"><span><?php esc_html_e( ' You', 'jetpack' ); ?></span></div>
						</div><!-- // jp-user -->
						<div class="j-col j-lrg-4 j-md-4 j-sm-12 wp-user">
							<h3 title="<?php _e( 'WordPress.com Username', 'jetpack' ); ?>"><?php _e( 'WordPress.com Username', 'jetpack' ); ?></h3>
							<div class="wpuser-02">{{{ data.userComData.login }}}</div>
						</div><!-- // wp-user -->
						<div class="j-col j-lrg-4 j-md-4 j-sm-12 jp-actions">
							<h3 title="<?php _e( 'Actions', 'jetpack' ); ?>"><?php _e( 'Actions', 'jetpack' ); ?></h3>
							<div class="action-01">
								<a class="button" title="Disconnect your WordPress.com account from Jetpack" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=unlink' ), 'jetpack-unlink' ); ?>"><?php esc_html_e( 'Disconnect ', 'jetpack' ); ?><span class="ifmobile">{{{ data.userComData.login }}}</span></a>
							</div>
						</div>
					<# } else { #><?php /* user isn't connected to Jetpack at all and should see a connection prompt */ ?>
						<a href="<?php echo Jetpack::init()->build_connect_url() ?>" class="button"><?php esc_html_e( 'Link your account to WordPress.com', 'jetpack' ); ?></a>
					<# } #><?php /* end data.connectionLogic.isUserConnected */ ?>
				<# } #><?php /* end data.isAdmin */ ?>
			</div>
		</div>
	</div>
</script>

<script id="tmpl-connection-modal-loading" type="text/html">
<p>Loading...</p>
</script>
