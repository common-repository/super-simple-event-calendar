<?php


// No direct calls to this script
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



/*
 * Shortcode to be used in the content. Displays a simple calendar in a table.
 *
 * Parameters:
 *   - tax_query season parameters for term_ids (since 1.4.0).
 *   - posts_per_page integer, default -1 (all posts) (since 1.4.1).
 *   - status, default to list only posts with future post status, use comma separated list (since 2.0.0).
 *   - order, default is ASC. Only other option is DESC (since 2.1.2).
 *
 * @since 1.0.0
 */
function ssec_shortcode( $atts ) {

	$output = '';

	$shortcode_atts = shortcode_atts( array( 'posts_per_page' => -1, 'status' => 'future', 'order' => 'ASC' ), $atts );

	$posts_per_page = (int) $shortcode_atts['posts_per_page'];
	if ( $posts_per_page === -1 ) {
		$nopaging = true;
	} else {
		$nopaging = false;
	}

	$tax_query = array();
	if ( ! empty( $atts['season'] ) ) {
		$cat_in = explode( ',', $atts['season'] );
		$cat_in = array_map( 'absint', array_unique( (array) $cat_in ) );
		if ( ! empty( $cat_in ) ) {
			$tax_query['relation'] = 'OR';
			$tax_query[] = array(
				'taxonomy'         => 'ssec_season',
				'terms'            => $cat_in,
				'field'            => 'term_id',
				'include_children' => true,
			);
		}
	}

	$status = (string) $shortcode_atts['status'];
	if ( $status !== 'future' ) {
		$status = explode( ',', $status );
	}

	$order = (string) $shortcode_atts['order'];
	if ( $order !== 'DESC' ) {
		$order = 'ASC';
	}

	$args = array(
		'post_type'      => 'ssec_event',
		'post_status'    => $status,
		'posts_per_page' => (int) $posts_per_page,
		'nopaging'       => (bool) $nopaging,
		'orderby'        => 'date',
		'order'          => $order,
		'tax_query'      => $tax_query,
	);

	// The Query
	$the_query = new WP_Query( $args );

	// The Loop
	if ( $the_query->have_posts() ) {
		$output .= '
		<table class="ssec-shortcode-calendar">
		<tbody>';

		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$post = get_post();
			$postlink = '';
			if ( is_user_logged_in() ) {
				$postlink = get_edit_post_link( get_the_ID() );
				if ( strlen( $postlink ) > 0 ) {
					$postlink = ' <a class="post-edit-link" href="' . esc_attr( $postlink ) . '">' . esc_html__('(edit)', 'super-simple-event-calendar') . '</a>';
				}
			}
			$classes = ssec_get_term_classes( get_the_ID() );

			$td_title = apply_filters( 'ssec_td_title', ssec_get_full_date( $post ), $post );
			$td_content = apply_filters( 'ssec_td_content', nl2br(get_the_content()) . $postlink , $post );
			$td_s = apply_filters( 'ssec_add_td_s_to_table', '', $post ); /* Use this filter to add custom fields for example. */

			$output .= '
			<tr class="' . esc_attr( $classes ) . '">
				<td class="ssec-title">' . $td_title . ' </td>
				<td class="ssec-content">' . $td_content . '</td>
				' . $td_s . '
			</tr>';
		}
		$output .= '
		</tbody>
		</table>';

	} else {
		// no posts found
		$output .= esc_html__( 'No events found', 'super-simple-event-calendar' );
	}
	/* Restore original Post Data */
	wp_reset_postdata();

	return $output;

}
add_shortcode( 'super_simple_event_calendar', 'ssec_shortcode' );
