<script id="tmpl-connection-modal" type="text/html">
	<span id="modal-label" class="screen-reader-text"><?php _e( 'Modal window. Press escape to close.', 'jetpack' ); ?></span>
	<a href="#" class="close">&times; <span class="screen-reader-text"><?php _e( 'Close modal window', 'jetpack' ); ?></span></a>
	<div class="content-container <# if ( data.available) { #>modal-footer<# } #>">
		<h2><?php _e( 'Jetpack Connection Status' ); ?></h2>
		<div id="my-connection-content" class="content">
			<# if ( data.isAdmin ) { #><?php /* if user has admin privledges */ ?>
				<div class="connection-details">
					<div class="j-row">
						<div class="j-col j-lrg-6 j-md-6 j-sm-6 jp-user">
							<h3 title="<?php _e( 'Username', 'jetpack' ); ?>"><?php _e( 'Site Username', 'jetpack' ); ?></h3>
							<# if ( !data.connectionLogic.isMasterUser ) { #>
								<div class="user-01">{{{ data.connectionLogic.adminUsername }}} (you)</div>
							<# } #>
							<div class="user-01">{{{ data.connectionLogic.masterUserLink }}} (primary)</div>
						</div><!-- // jp-user -->
						<div class="j-col j-lrg-6 j-md-6 j-sm-6 wp-user">
							<h3 title="<?php _e( 'WordPress.com Username', 'jetpack' ); ?>"><?php _e( 'WordPress.com', 'jetpack' ); ?></h3>
							<# if ( !data.connectionLogic.isMasterUser && !data.connectionLogic.isUserConnected ) { #>
								<div class="wpuser-linkacct"><a href="<?php echo Jetpack::init()->build_connect_url() ?>" ><?php esc_html_e( 'Link your account', 'jetpack' ); ?></a></div>
							<# } else if ( !data.connectionLogic.isMasterUser ) { #>
								<div class="wpuser-02">{{{ data.userComData.login }}}</div> 
							<# } #>
								<div class="wpuser-02">{{{ data.masterComData.login }}}</div>
						</div><!-- // wp-user -->
					</div><!-- // j-row -->
				<div class="j-col j-lrg-12 j-md-12 j-sm-12 btm-actions">
					<# if ( !data.connectionLogic.isMasterUser && data.connectionLogic.isUserConnected ) { #>
						<a class="button button-primary" title="Make me the primary account holder" id="set-self-as-master"><?php esc_html_e( 'Make me primary', 'jetpack' ); ?></a>
					<# } #>
						<a class="button alignright" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=disconnect' ), 'jetpack-disconnect' ); ?>" onclick="return confirm('<?php echo htmlspecialchars( __( 'Are you sure you want to disconnect from WordPress.com?', 'jetpack' ), ENT_QUOTES ); ?>');"><?php esc_html_e( 'Disconnect Site', 'jetpack' ); ?></a>
					<# if ( !data.connectionLogic.isMasterUser && data.connectionLogic.isUserConnected ) { #>
						<a class="button alignright" title="Disconnect your WordPress.com account from Jetpack" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=unlink' ), 'jetpack-unlink' ); ?>"><?php esc_html_e( 'Unlink my account ', 'jetpack' ); ?></a>
					<# } #>
				</div>
				</div>
			<# } else { #><?php /* User doesn't have admin privledges */ ?>
				<div class="connection-details">
					<div class="j-row">
						<div class="j-col j-lrg-3 j-md-3 j-sm-12 jp-user">
							<h3 title="<?php _e( 'Site', 'jetpack' ); ?>"><?php _e( 'Site Username', 'jetpack' ); ?></h3>
							<div class="user-01"><span>{{{ data.connectionLogic.adminUsername }}}</span></div>
						</div><!-- // jp-user -->
						<div class="j-col j-lrg-4 j-md-4 j-sm-12 wp-user">
							<h3 title="<?php _e( 'WordPress.com', 'jetpack' ); ?>"><?php _e( 'WordPress.com Username', 'jetpack' ); ?></h3>
							<div class="wpuser-02">{{{ data.userComData.login }}}</div>
						</div><!-- // wp-user -->
						<div class="j-col j-lrg-5 j-md-5 j-sm-12 jp-action">
							<h3 title="<?php _e( 'Action', 'jetpack' ); ?>">&nbsp;</h3>
							<# if ( data.connectionLogic.isUserConnected ) { #><?php /* user is connected to Jetpack */ ?>
								<a class="button" title="Disconnect your WordPress.com account from Jetpack" href="<?php echo wp_nonce_url( Jetpack::admin_url( 'action=unlink' ), 'jetpack-unlink' ); ?>"><?php esc_html_e( 'Unlink my account ', 'jetpack' ); ?></a>
							<# } else { #>
								<a class="button" href="<?php echo Jetpack::init()->build_connect_url() ?>" class="button"><?php esc_html_e( 'Link your account to WordPress.com', 'jetpack' ); ?></a>
							<# } #>
						</div>
					</div><!-- // j-row -->
				</div><!-- // connection-details -->
			<# } #><?php /* end data.isAdmin */ ?>
		</div>
	</div>
</script>

<script id="tmpl-connection-modal-loading" type="text/html">
<p>Loading...</p>
</script>
