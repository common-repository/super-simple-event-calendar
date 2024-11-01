<?php
/*
Plugin Name: Super Simple Event Calendar
Plugin URI: https://wordpress.org/plugins/super-simple-event-calendar/
Description: Super Simple Event Calendar is an event calendar for people who just want something simple for events.
Version: 2.1.2
Author: Marcel Pol
Author URI: https://timelord.nl
License: GPLv2 or later
Text Domain: super-simple-event-calendar
Domain Path: /lang/


Copyright 2018 - 2024  Marcel Pol  (marcel@timelord.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// Plugin Version
define('SSEC_VER', '2.1.2');


/*
 * Todo List:
 *
 */


/*
 * Definitions
 */
define('SSEC_FOLDER', plugin_basename(dirname(__FILE__)));
define('SSEC_DIR', WP_PLUGIN_DIR . '/' . SSEC_FOLDER);
define('SSEC_URL', plugins_url( '/', __FILE__ ));


require_once SSEC_DIR . '/ssec-hooks.php';
require_once SSEC_DIR . '/ssec-meta-box.php';
require_once SSEC_DIR . '/ssec-posttypes.php';
require_once SSEC_DIR . '/ssec-shortcode.php';
require_once SSEC_DIR . '/ssec-taxonomy-content-filter.php';
require_once SSEC_DIR . '/ssec-widget-calendar.php';

// Functions and pages for the backend
if ( is_admin() ) {
	require_once SSEC_DIR . '/ssec-admin-quick-edit.php';
}


/*
 * Get the terms of each event post in the form of classes.
 *
 * @param int $postid of an instance of WP_Post
 * @return string text with term classes of this event post.
 * @since 1.1.1
 */
function ssec_get_term_classes( $postid ) {

	$postid = (int) $postid;
	$seasons = get_the_terms( $postid, 'ssec_season' );
	$classes = array();

	if ( $seasons && ! is_wp_error( $seasons ) ) {
		$classes[] = 'ssec-season';
		foreach ( $seasons as $season ) {
			if ( isset( $season->term_id ) ) {
				$class = sanitize_html_class( $season->slug, $season->term_id );
				$classes[] = 'ssec-season-' . esc_attr( $class );
				$classes[] = 'ssec-season-' . (int) $season->term_id;
			}
		}
	}
	$classes = join( ' ', $classes );
	return $classes;

}


/*
 * Set event to status 'publish' in case of a missed cronjob.
 *
 * @since 1.5.0
 */
function ssec_publish_future_post_from_missed_cronjob() {

	$random = rand( 1, 10 );
	if ( $random !== 2 ) { // not everytime, that is too much load.
		return;
	}

	$args = array(
		'post_type'      => 'ssec_event',
		'post_status'    => 'future',
		'posts_per_page' => 1,
		'orderby'        => 'date',
		'order'          => 'ASC',
	);

	$posts = get_posts( $args );

	if ( is_array( $posts ) && ! empty( $posts ) ) {
		foreach ( $posts as $post ) {

			$time_of_post = strtotime( get_gmt_from_date( $post->post_date ) . ' GMT' );
			$time = time();

			// set status of event to published.
			if ( $time_of_post < $time ) {
				check_and_publish_future_post( $post->ID );
			}

		}
	}

}
add_action( 'shutdown', 'ssec_publish_future_post_from_missed_cronjob' );


/*
 * Return the full written date of the event.
 *
 * Modify output with the get_the_date filter.
 * https://developer.wordpress.org/reference/hooks/get_the_date/
 *
 * @param object $post instance of WP_Post.
 * @return string text with full written date of this event post.
 *
 * @since 2.0.0
 */
function ssec_get_full_date( $post ) {

	$the_date = get_the_date( '', $post );
	$format = get_option( 'date_format' );

	$ssec_event_show_day = get_post_meta( $post->ID, 'ssec_event_show_day', true );
	if ( $ssec_event_show_day ) {
		$day = get_the_date( 'l', $post );
		if ( $day ) {
			$the_date .= ' (' . $day . ') ';
		}
	}

	$the_date = apply_filters( 'ssec_get_the_date', $the_date, $format, $post );

	return $the_date;

}
