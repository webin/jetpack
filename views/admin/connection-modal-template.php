<script id="tmpl-connection-modal" type="text/html">
	<span id="modal-label" class="screen-reader-text"><?php _e( 'Modal window. Press escape to close.', 'jetpack' ); ?></span>
	<a href="#" class="close">&times; <span class="screen-reader-text"><?php _e( 'Close modal window', 'jetpack' ); ?></span></a>
	<div class="content-container <# if ( data.available) { #>modal-footer<# } #>">

		<div id="my-connection-content" class="content">
			<h2><?php _e( 'My Connection' ); ?></h2>
			<# if ( data.isAdmin ) { #><?php /* if user has admin privledges */ ?>
				<div class="connection-details">
					<# if ( !data.connectionLogic.isMasterUser ) { #><?php /* if user is an admin but not the primary account holder */ ?>
						<div class="j-row">

							<div class="j-col j-lrg-4 j-md-4 j-sm-12 jp-user">
								<h3 title="<?php _e('Username', 'jetpack' ); ?>"><?php _e( 'Site Username', 'jetpack' ); ?></h3>
								<div class="user-01">{{{ data.connectionLogic.adminUsername }}} (you)</div>
							</div><!-- // jp-user -->

							<div class="j-col j-lrg-5 j-md-5 j-sm-12 jp-user">
								<h3 title="<?php _e( 'WordPress.com Username', 'jetpack' ); ?>"><?php _e( 'WordPress.com Username', 'jetpack' ); ?></h3>
								<div class="wpuser-02">
									<# if ( !data.connectionLogic.isUserConnected ) { #><?php /* if user is not connected to wordpress.com */ ?>
										<a class="button button-primary" href="<?php echo Jetpack::init()->build_connect_url() ?>" ><?php esc_html_e( 'Link your account', 'jetpack' ); ?></a>
									<# } else { #>
										{{{ data.userComData.login }}} <a class="alignright" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=unlink' ), 'jetpack-unlink' ); ?>"  title="Disconnect your WordPress.com account from Jetpack" onclick="return confirm('<?php echo htmlspecialchars( __( 'Are you sure you want to disconnect your WordPress.com account?', 'jetpack' ), ENT_QUOTES ); ?>');" ><?php esc_html_e( '(Unlink my account)', 'jetpack' ); ?></a>
									<# } #>
								</div><!-- // wp-user -->
							</div>

							<div class="j-col j-lrg-3 j-md-3 j-sm-12 jp-user">
								<h3 title="<?php _e('Account Actions', 'jetpack' ); ?>"><?php _e( 'Account Actions', 'jetpack' ); ?></h3>
								<div class="action">
									<# if ( data.connectionLogic.isUserConnected ) { #><?php /* ONLY if user is connected, show "make primary" and "unlink account" buttons */ ?>
										<a class="button" title="Make me the primary account holder" id="set-self-as-master"><?php esc_html_e( 'Make me primary', 'jetpack' ); ?></a>
									<# } #>
								</div><!-- // wp-user -->
							</div>

						</div><!-- // j-row -->

					<# } #><?php /* else user must be the primary account holder */ ?>

						<div class="j-row">

							<div class="j-col j-lrg-6 j-md-6 j-sm-12 jp-user">
								<h3 title="<?php _e('User', 'jetpack' ); ?>"><?php _e( 'Primary User', 'jetpack' ); ?></h3>
								<div class="user-01">{{{ data.connectionLogic.masterUserLink }}} </div>
							</div><!-- // jp-user -->

							<div class="j-col j-lrg-6 j-md-6 j-sm-12 jp-user">
								<h3 title="<?php _e( 'WordPress.com Username', 'jetpack' ); ?>"><?php _e( 'WordPress.com Username', 'jetpack' ); ?></h3>
								<div class="wpuser-02">{{{ data.masterComData.login }}}</div>
							</div><!-- // wp-user -->

						</div><!-- // j-row -->

				</div><?php /* </ connection-details > */ ?>
				<div class="j-col j-lrg-12 j-md-12 j-sm-12">
					<a class="button alignright" id="jetpack-disconnect" title="Disconnect Jetpack"><?php esc_html_e( 'Disconnect Jetpack', 'jetpack' ); ?></a>
				</div>

			<# } else { #><?php /* User doesn't have admin privledges */ ?>

				<div class="connection-details">

					<div class="j-row">

						<div class="j-col j-lrg-6 j-md-6 j-sm-12 jp-user">
							<h3 title="<?php _e( 'Site', 'jetpack' ); ?>"><?php _e( 'Site Username', 'jetpack' ); ?></h3>
							<div class="user-01"><span>{{{ data.connectionLogic.adminUsername }}}</span></div>
						</div><!-- // jp-user -->
						<div class="j-col j-lrg-6 j-md-6 j-sm-12 wp-user">
							<h3 title="<?php _e( 'WordPress.com', 'jetpack' ); ?>"><?php _e( 'WordPress.com Username', 'jetpack' ); ?></h3>
							<div class="wpuser-02">
								<# if ( data.connectionLogic.isUserConnected ) { #><?php /* user is connected to Jetpack */ ?>
									{{{ data.userComData.login }}} <a class="alignright" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=unlink' ), 'jetpack-unlink' ); ?>"  title="Disconnect your WordPress.com account from Jetpack" onclick="return confirm('<?php echo htmlspecialchars( __( 'Are you sure you want to disconnect your WordPress.com account?', 'jetpack' ), ENT_QUOTES ); ?>');" ><?php esc_html_e( '(Unlink my account)', 'jetpack' ); ?></a>
								<# } else { #>
									<a class="button button-primary" href="<?php echo Jetpack::init()->build_connect_url() ?>" ><?php esc_html_e( 'Link your account', 'jetpack' ); ?></a>
								<# } #>
							</div><!-- // wp-user -->
						</div>

					</div><!-- // j-row -->

				</div><!-- // connection-details -->

			<# } #><?php /* end data.isAdmin */ ?>
		</div>
		<div id="jetpack-disconnect-content">
			<h2>Disconnecting Jetpack</h2>
			<p>Are you sure you want to disconnect Jetpack from WordPress.com?</p>
			<a class="button" title="Disconnect Jetpack" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=disconnect' ), 'jetpack-disconnect' ); ?>">Confirm Disconnect</a>
			<a target="_blank" title="Jetpack Support" href="http://jetpack.me/contact-support/">Get Support</a>
		</div>
	</div>

</script>

<script id="tmpl-connection-modal-loading" type="text/html">
<p>Loading...</p>
</script>
