<?php

/**
* Required for class.media-extractor.php to match expected function naming convention.
*
* @param $url Can be just the $url or the whole $atts array
* @return bool|mixed The Youtube video ID via jetpack_get_youtube_id
*/

function jetpack_shortcode_get_youtube_id( $url ) {
    return jetpack_get_youtube_id( $url );
}

/**
* @param $url Can be just the $url or the whole $atts array
* @return bool|mixed The Youtube video ID
*/
function jetpack_get_youtube_id( $url ) {
	// Do we have an $atts array?  Get first att
	if ( is_array( $url ) )
		$url = $url[0];

	$url = youtube_sanitize_url( $url );
	$url = parse_url( $url );
	$id  = false;

	if ( ! isset( $url['query'] ) )
		return false;

	parse_str( $url['query'], $qargs );

	if ( ! isset( $qargs['v'] ) && ! isset( $qargs['list'] ) )
		return false;

	if ( isset( $qargs['list'] ) )
		$id = preg_replace( '|[^_a-z0-9-]|i', '', $qargs['list'] );

	if ( empty( $id ) )
		$id = preg_replace( '|[^_a-z0-9-]|i', '', $qargs['v'] );

	return $id;
}

if ( !function_exists( 'youtube_sanitize_url' ) ) :
/**
* Normalizes a YouTube URL to include a v= parameter and a query string free of encoded ampersands.
*
* @param string $url
* @return string The normalized URL
*/
function youtube_sanitize_url( $url ) {
	$url = trim( $url, ' "' );
	$url = trim( $url );
	$url = str_replace( array( 'youtu.be/', '/v/', '#!v=', '&amp;', '&#038;', 'playlist' ), array( 'youtu.be/?v=', '/?v=', '?v=', '&', '&', 'videoseries' ), $url );

	// Replace any extra question marks with ampersands - the result of a URL like "http://www.youtube.com/v/9FhMMmqzbD8?fs=1&hl=en_US" being passed in.
	$query_string_start = strpos( $url, "?" );

	if ( false !== $query_string_start ) {
		$url = substr( $url, 0, $query_string_start + 1 ) . str_replace( "?", "&", substr( $url, $query_string_start + 1 ) );
	}

	return $url;
}
endif;

if ( ! function_exists( 'get_autoloaded_option' ) ) :
/**
 * Very similar to `get_option()` except if the option isn't loaded in with `$alloptions`, it returns
 * the default value and doesn't trigger an extra db query in case the option isn't set or findable
 * in the object cache.
 *
 * @param string $name    Option name
 * @param mixed  $default (optional) The default value passed back if not found
 * @return mixed
 */
function get_autoloaded_option( $option, $default = false ) {
	$option = trim( $option );

	// Go the long way if any plugins are hooking on to `pre_option_{$option}`.
	/** This filter is documented in wp-includes/option.php */
	if ( has_filter( 'pre_option_' . $option ) ) {
		return get_option( $option, $default );
	}

	// If it's loaded into $alloptions, it's safe to call `get_option()` directly,
	// as it won't trigger an additional db query.
	$alloptions = wp_load_alloptions();
	if ( isset( $alloptions[ $option ] ) ) {
		return get_option( $option, $default );
	} else {
		/** This filter is documented in wp-includes/option.php */
		return apply_filters( 'default_option_' . $option, $default );
	}
}
endif;
