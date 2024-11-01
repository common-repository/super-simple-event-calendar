<?php


// No direct calls to this script
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



/*
 * Add Custom Post Type with a taxonomy for seasons.
 *
 * @since 1.0.0
 */
function ssec_post_type() {
	$labels = array(
		'name'                          => esc_attr__('Event', 'super-simple-event-calendar'),
		'singular_name'                 => esc_attr__('Event', 'super-simple-event-calendar'),
		'add_new'                       => esc_attr__('New Event', 'super-simple-event-calendar'),
		'add_new_item'                  => esc_attr__('Add new Event', 'super-simple-event-calendar'),
		'edit_item'                     => esc_attr__('Edit Event', 'super-simple-event-calendar'),
		'new_item'                      => esc_attr__('New Event', 'super-simple-event-calendar'),
		'view_item'                     => esc_attr__('View Event', 'super-simple-event-calendar'),
		'search_items'                  => esc_attr__('Search Event', 'super-simple-event-calendar'),
		'not_found'                     => esc_attr__('No Event found', 'super-simple-event-calendar'),
		'not_found_in_trash'            => esc_attr__('No Event found in trash', 'super-simple-event-calendar'),
		'parent_item_colon'             => '',
		'menu_name'                     => esc_attr__('Events', 'super-simple-event-calendar'),
	);
	register_post_type('ssec_event', array(
		'public'                        => true,
		'show_in_menu'                  => true,
		'show_ui'                       => true,
		'labels'                        => $labels,
		'hierarchical'                  => false,
		'supports'                      => array( 'title', 'editor', 'excerpt', 'custom-fields' ),
		'capability_type'               => 'post',
		'taxonomies'                    => array( 'ssec_season' ),
		'exclude_from_search'           => false,
		'rewrite'                       => true,
		'rewrite'                       => array(
		                                        'slug' => 'ssec_event',
		                                        'with_front' => true,
		                                   ),
		'menu_icon'                     => 'dashicons-calendar',
		)
	);

	$labels = array(
		'name'                          => esc_attr__('Season', 'super-simple-event-calendar'),
		'singular_name'                 => esc_attr__('Season', 'super-simple-event-calendar'),
		'search_items'                  => esc_attr__('Search Season', 'super-simple-event-calendar'),
		'popular_items'                 => esc_attr__('Popular Season', 'super-simple-event-calendar'),
		'all_items'                     => esc_attr__('All Seasons', 'super-simple-event-calendar'),
		'parent_item'                   => esc_attr__('Parent Season', 'super-simple-event-calendar'),
		'edit_item'                     => esc_attr__('Edit Season', 'super-simple-event-calendar'),
		'update_item'                   => esc_attr__('Update Season', 'super-simple-event-calendar'),
		'add_new_item'                  => esc_attr__('Add new Season', 'super-simple-event-calendar'),
		'new_item_name'                 => esc_attr__('New Season name', 'super-simple-event-calendar'),
		'not_found'                     => esc_attr__('No Season found', 'super-simple-event-calendar'),
		'separate_items_with_commas'    => esc_attr__('Separate Seasons with commas', 'super-simple-event-calendar'),
		'add_or_remove_items'           => esc_attr__('Add or remove Seasons', 'super-simple-event-calendar'),
		'choose_from_most_used'         => esc_attr__('Choose Season from most used', 'super-simple-event-calendar'),
		);

	$args = array(
		'label'                         => esc_attr__('Season', 'super-simple-event-calendar'),
		'labels'                        => $labels,
		'public'                        => true,
		'hierarchical'                  => true,
		'show_ui'                       => true,
		'show_in_nav_menus'             => true,
		'args'                          => array( 'orderby' => 'date' ),
		'rewrite'                       => true,
		'rewrite'                       => array(
		                                        'slug' => 'ssec_season',
		                                        'with_front' => true,
		                                   ),
		'query_var'                     => true,
	);
	register_taxonomy( 'ssec_season', 'ssec_event', $args );

}
add_action('init', 'ssec_post_type');


/*
 * Show all events in a taxonomy/term with correct order.
 *
 * @since 1.0.0
 */
function ssec_pre_get_posts_taxonomy( $query ) {

	if ( $query->is_tax('ssec_season') && $query->is_main_query() ) {
		$query->set( 'post_status', array( 'publish', 'future' ) );
		$query->set( 'posts_per_page', -1 );
		$query->set( 'nopaging', true );
		$query->set( 'orderby', 'date' );
		$query->set( 'order', 'ASC' );
	}
	// do not return.

}
add_action( 'pre_get_posts', 'ssec_pre_get_posts_taxonomy' );
