<?php


// No direct calls to this script
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



/*
 * Replace the content of our taxonomy and single post.
 *
 * @since 1.0.0
 */
function ssec_content_filter( $content ) {

	if ( is_admin() ) {
		return $content;
	}

	$post = get_post();
	if ( is_tax('ssec_season') && is_main_query() ) {
		$content .= '<table class="ssec-contentfilter-calendar"><tbody>';
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

		$content .= '
			<tr class="' . esc_attr( $classes ) . '">
				<td class="ssec-title">' . $td_title . ' </td>
				<td class="ssec-content">' . $td_content . '</td>
				' . $td_s . '
			</tr>
			';
		$content .= '</tbody></table>';
		return $content;
	}

	$post_type = get_post_type();
	if ( $post_type === 'ssec_event' && is_singular() ) {
		$content .= '<table class="ssec-contentfilter-calendar"><tbody>';
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

		$content .= '
			<tr class="' . esc_attr( $classes ) . '">
				<td class="ssec-title">' . $td_title . ' </td>
				<td class="ssec-content">' . $td_content . '</td>
				' . $td_s . '
			</tr>
			';
		$content .= '</tbody></table>';
		return $content;
	}

	return $content;

}
add_filter( 'the_content', 'ssec_content_filter', 12 );
