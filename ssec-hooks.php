<?php

/*
 * WordPress Actions and Filters.
 * See the Plugin API in the Codex:
 * http://codex.wordpress.org/Plugin_API
 */


// No direct calls to this script
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



/*
 * Add link to the main plugin page.
 *
 * @since 1.0.0
 */
function ssec_links( $links, $file ) {
	if ( $file === plugin_basename( dirname(__FILE__) . '/super-simple-event-calendar.php' ) ) {
		$links[] = '<a href="' . esc_attr( admin_url( 'edit.php?post_type=ssec_event' ) ) . '">' . esc_html__( 'Events', 'super-simple-event-calendar' ) . '</a>';
	}
	return $links;
}
add_filter( 'plugin_action_links', 'ssec_links', 10, 2 );


/*
 * Load Language files for frontend and backend.
 *
 * @since 1.0.0
 */
function ssec_load_lang() {
	load_plugin_textdomain( 'super-simple-event-calendar', false, SSEC_FOLDER . '/lang' );
}
add_action('plugins_loaded', 'ssec_load_lang');
