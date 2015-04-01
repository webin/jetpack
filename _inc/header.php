<?php $current = $_GET['page']; ?>
<div class="jp-content">
	<div class="jp-frame">
		<div class="header">
			<nav role="navigation" class="header-nav drawer-nav nav-horizontal">
				<style>/*
					.jp-button--settings {
						cursor: pointer;
					}

					.jp-modal-wrapper {
						display: none;
						position: absolute;
						left: 0px;
						top: 0px;
						width:100%;
						height:100%;
						text-align:center;
						z-index: 1000;
					}

					.jp-modal-wrapper div {
						width:300px;
						margin: 100px auto;
						background-color: #fff;
						border:1px solid #000;
						padding:15px;
						text-align:center;
					}
				*/
				</style>
				<div class="main-nav">
					<div class="jetpack-logo">
						<a href="<?php echo Jetpack::admin_url(); ?>" title="<?php esc_attr_e( 'Jetpack', 'jetpack' ); ?>" <?php if ( 'jetpack' == $current ) { echo 'class="current"'; } ?>><span><?php esc_html_e( 'Jetpack', 'jetpack' ); ?></span></a>
					</div>
					<?php if ( ( Jetpack::is_active() || Jetpack::is_development_mode() )
						&& current_user_can( 'jetpack_manage_modules' )
					 ) : ?>
					 <div class="options">
						<a title="View your Jetpack settings" href="<?php echo Jetpack::admin_url( 'page=jetpack_modules' ); ?>" class="jp-button--settings <?php if ( 'jetpack_modules' == $current ) { echo 'current'; } ?>"><?php esc_html_e( 'Settings', 'jetpack' ); ?></a>
						<a title="Give Jetpack feedback" href="http://jetpack.me/survey/?rel=<?php echo JETPACK__VERSION; ?>" class="jp-button--settings"><?php esc_html_e( 'Feedback', 'jetpack' ); ?></a>
						<a title="View your Jetpack connection details" id="jp-connection" class="jp-button--settings"><?php esc_html_e( 'My Connection', 'jetpack' ); ?></a>
					</div>
					<?php endif; ?>
				</div>

			</nav>
		</div><!-- .header -->
		<div class="wrapper">