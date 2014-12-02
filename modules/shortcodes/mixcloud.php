<?php
/*
 * Mixcloud embeds
 *
 * examples:
 * [mixcloud MalibuRum/play-6-kissy-sellouts-winter-sun-house-party-mix/ /]
 * [mixcloud MalibuRum/play-6-kissy-sellouts-winter-sun-house-party-mix/ width=640 height=480 /]
 * [mixcloud http://www.mixcloud.com/MalibuRum/play-6-kissy-sellouts-winter-sun-house-party-mix/ /]
 * [mixcloud http://www.mixcloud.com/MalibuRum/play-6-kissy-sellouts-winter-sun-house-party-mix/ width=640 height=480 /]
 * [mixcloud]http://www.mixcloud.com/MalibuRum/play-6-kissy-sellouts-winter-sun-house-party-mix/[/mixcloud]
 * [mixcloud]MalibuRum/play-6-kissy-sellouts-winter-sun-house-party-mix/[/mixcloud]
*/

// Register oEmbed provider
// Example URL: http://www.mixcloud.com/oembed/?url=http://www.mixcloud.com/MalibuRum/play-6-kissy-sellouts-winter-sun-house-party-mix/
wp_oembed_add_provider('#https?://(?:www\.)?mixcloud\.com/\S*#i', 'http://www.mixcloud.com/oembed', true);

// Register mixcloud shortcode
add_shortcode( 'mixcloud', 'mixcloud_shortcode' );
function mixcloud_shortcode( $atts, $content = null ) {

	if ( empty( $atts[0] ) && empty( $content ) )
		return "<!-- mixcloud error: invalid mixcloud resource -->";

	$regular_expression = "#((?<=mixcloud.com/)([A-Za-z0-9_-]+/[A-Za-z0-9_-]+))|^([A-Za-z0-9_-]+/[A-Za-z0-9_-]+)#i";
	preg_match( $regular_expression, $content, $match );
	if ( ! empty( $match ) ) {
		$resource_id = trim( $match[0] );
	} else {
		preg_match( $regular_expression, $atts[0], $match );
		if ( ! empty( $match ) )
			$resource_id = trim( $match[0] );
	}

	if ( empty( $resource_id ) )
		return "<!-- mixcloud error: invalid mixcloud resource -->";

	$atts = shortcode_atts( array(
		'width'    => 300,
		'height'   => 300,
	), $atts, 'mixcloud' );


	// Build URL
	$url = add_query_arg( $atts, "http://api.mixcloud.com/$resource_id/embed-html/" );
	$head = wp_remote_head( $url );
	if ( is_wp_error( $head ) || 200 != $head['response']['code'] )
		return "<!-- mixcloud error: invalid mixcloud resource -->";

	return sprintf( '<iframe width="%d" height="%d" scrolling="no" frameborder="no" src="%s"></iframe>', $atts['width'], $atts['height'], esc_url( $url ) );

}

function jetpack_mixcloud_shortcode_js_template() {
	if ( ! isset( get_current_screen()->id ) || get_current_screen()->base != 'post' ) {
		return;
	}
	?>
	<script type="text/html" id="tmpl-jetpack_mixcloud_shortcode">
		<iframe width="{{ data.width }}" height="{{ data.height }}" scrolling="no" frameborder="no" src="//api.mixcloud.com/{{ data.id }}/embed-html/"></iframe>
	</script>
<?php
}
add_action( 'admin_print_footer_scripts', 'jetpack_mixcloud_shortcode_js_template' );

function jetpack_mixcloud_shortcode_footer_scripts() {
	if ( ! isset( get_current_screen()->id ) || get_current_screen()->base != 'post' ) {
		return;
	}
	?>
	<script>
		/* global tinyMCE, console */
		(function( $, wp ){
			wp.mce = wp.mce || {};
			wp.mce.jetpack_mixcloud_renderer = {
				shortcode_data : {},
				View : {
					template   : wp.template( 'jetpack_mixcloud_shortcode' ),
					initialize : function( options ) {
						this.shortcode = options.shortcode;
						// Do any needed tweaking here
						wp.mce.jetpack_mixcloud_renderer.shortcode_data = this.shortcode;
					},
					getHtml : function() {
						var options   = $.extend( {
								height : '300',
								width  : '300'
							}, this.shortcode.attrs.named ),
							url_or_id = null;
						// Do any needed tweaking here

						options.id = wp.mce.jetpack_mixcloud_renderer.parseId( this.shortcode );
						console.log( options );
						return this.template( options );
					}
				},
				parseId : function( shortcode ) {
					var match, url_or_id;

					console.log( this.shortcode );
					if ( shortcode.attrs.numeric.length ) {
						url_or_id = shortcode.attrs.numeric[0];
					}
					if ( ! url_or_id && shortcode.content ) {
						url_or_id = shortcode.content.trim();
					}

					// If we've got just the ID, return just the ID.
					if ( match = url_or_id.match( /^([a-z\d_-]+\/[a-z\d_-]+)/i ) ) {
						return match[1];
					}
					if ( match = url_or_id.match( /mixcloud.com\/([a-z\d_-]+\/[a-z\d_-]+)/i ) ) {
						return match[1];
					}
					return null;
				},
				edit: function( node ) {
					var values = this.shortcode_data.attrs.named;
					// Do any needed tweaking here
					values = $.extend( {
						height : '300',
						width  : '300'
					}, values );
					values.id = wp.mce.jetpack_mixcloud_renderer.parseId( this.shortcode_data );
					wp.mce.jetpack_mixcloud_renderer.popupwindow( tinyMCE.activeEditor, values );
				},
				popupwindow: function( editor, values, onsubmit_callback ){
					if ( typeof onsubmit_callback != 'function' ) {
						onsubmit_callback = function( e ) {
							var s = '[mixcloud ' + e.data.id,
								i;
							for ( i in e.data ) {
								if ( e.data.hasOwnProperty( i ) && 'id' !== i ) {
									s += ' ' + i + '="' + e.data[ i ] + '"';
								}
							}
							s += ']';
							editor.insertContent( s );
						};
					}
					editor.windowManager.open( {
						title : '<?php echo esc_js( __( 'Mixcloud Embed', 'Jetpack' ) ); ?>',
						body  : [
							{
								type  : 'textbox',
								name  : 'id',
								label : '<?php echo esc_js( __( 'Mixcloud ID', 'jetpack' ) ); ?>',
								value : values.id
							},
							{
								type  : 'textbox',
								name  : 'height',
								label : '<?php echo esc_js( __( 'Height', 'jetpack' ) ); ?>',
								value : values.height
							},
							{
								type  : 'textbox',
								name  : 'width',
								label : '<?php echo esc_js( __( 'Width', 'jetpack' ) ); ?>',
								value : values.width
							}
						],
						onsubmit : onsubmit_callback
					} );
				}
			};
			wp.mce.views.register( 'mixcloud', wp.mce.jetpack_mixcloud_renderer );
		}( jQuery, wp ));
	</script>
<?php
}
add_action( 'admin_print_footer_scripts', 'jetpack_mixcloud_shortcode_footer_scripts' );

