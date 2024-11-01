<?php


// No direct calls to this script
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



if (function_exists('register_sidebar') && class_exists('WP_Widget')) {

	/*
	 * Widget to display the next events.
	 *
	 * @since 1.0.0
	 */
	class SSEC_Widget_Calendar extends WP_Widget {

		/* Constructor */
		public function __construct() {
			$widget_ops = array(
				'classname' => 'ssec_widget_calendar',
				'description' => esc_html__( 'Super Simple Event Calendar.', 'super-simple-event-calendar'  ),
				'customize_selective_refresh' => true,
			);
			parent::__construct( 'ssec_widget_calendar', esc_html__( 'Event Calendar', 'super-simple-event-calendar'  ), $widget_ops );
			$this->alt_option_name = 'ssec_widget_calendar';
		}

		/** @see WP_Widget::widget */
		public function widget( $args, $instance ) {

			$default_values = array(
				'title'       => esc_html__('Calendar', 'super-simple-event-calendar'),
				'num_entries' => 3,
				'season'      => 0,
				'postid'      => 0,
			);

			$instance      = wp_parse_args( (array) $instance, $default_values );
			$widget_title  = esc_attr($instance['title']);

			$widget_content = ssec_get_widget_content( $instance, 'widget' );

			// Init
			$widget_html = '';

			$widget_html .= $args['before_widget'];
			$widget_html .= '
			<div class="ssec-widget-calendar">
				';

			if ( $widget_title !== false ) {
				$widget_html .= $args['before_title'] . apply_filters('widget_title', $widget_title) . $args['after_title'];
			}

			$widget_html .= $widget_content;

			$widget_html .= '
			</div>
			' . $args['after_widget'];

			if ( strlen( $widget_content ) > 0 ) {
				// Only display widget if there is any real content with events.
				echo $widget_html;
			}
		}

		/** @see WP_Widget::update */
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title']       = wp_strip_all_tags($new_instance['title']);
			$instance['num_entries'] = (int) $new_instance['num_entries'];
			$instance['season']      = (int) $new_instance['season'];
			$instance['postid']      = (int) $new_instance['postid'];

			return $instance;
		}

		/** @see WP_Widget::form */
		public function form( $instance ) {

			$default_value = array(
					'title'       => esc_html__('Calendar', 'super-simple-event-calendar'),
					'num_entries' => 3,
					'season'      => 0,
					'postid'      => 0,
				);
			$instance      = wp_parse_args( (array) $instance, $default_value );

			$title         = esc_attr($instance['title']);
			$num_entries   = (int) $instance['num_entries'];
			$season        = (int) $instance['season'];
			$postid        = (int) $instance['postid'];
			?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>" /><?php esc_html_e('Title:', 'super-simple-event-calendar'); ?></label>
				<br />
				<input type="text" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" value="<?php echo esc_attr( $title ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('num_entries') ); ?>" /><?php esc_html_e('Number of items:', 'super-simple-event-calendar'); ?></label>
				<br />
				<select id="<?php echo esc_attr( $this->get_field_id('num_entries') ); ?>" name="<?php echo esc_attr( $this->get_field_name('num_entries') ); ?>">
					<?php
					for ($i = 1; $i <= 15; $i++) {
						echo '<option value="' . (int) $i . '"';
						if ( $i === $num_entries ) {
							echo ' selected="selected"';
						}
						echo '>' . (int) $i . '</option>';
					}
					?>
				</select>
			</p>

			<?php
			$args = array(
					'orderby'    => 'name',
					'order'      => 'ASC',
					'hide_empty' => false,
				);
			$seasons = get_terms( 'ssec_season', $args );
			if ( is_array( $seasons ) && ! empty( $seasons ) ) {
				?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('season') ); ?>" /><?php esc_html_e('Only events from this season or category:', 'super-simple-event-calendar'); ?></label>
				<br />
				<select id="<?php echo esc_attr( $this->get_field_id('season') ); ?>" name="<?php echo esc_attr( $this->get_field_name('season') ); ?>">
					<option value="0" <?php if ( 0 === $season ) { echo ' selected="selected"'; } ?> > <?php esc_html_e( 'Select...', 'super-simple-event-calendar' ); ?></option>
					<?php foreach ( $seasons as $item ) {
						echo '<option value="' . (int) $item->term_id . '"';
						if ( $item->term_id === $season ) {
							echo ' selected="selected"';
						}
						echo '>' . esc_html( $item->name ) . '</option>';
					}
					?>
				</select>
			</p>
			<?php } ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('postid') ); ?>"><?php esc_html_e('Select the page of the calendar:', 'super-simple-event-calendar'); ?></label>
				<br />
				<select id="<?php echo esc_attr( $this->get_field_id('postid') ); ?>" name="<?php echo esc_attr( $this->get_field_name('postid') ); ?>">
					<option value="0"><?php esc_html_e('Select page', 'super-simple-event-calendar'); ?></option>
					<?php
					$args = array(
						'post_type'              => 'page',
						'orderby'                => 'title',
						'order'                  => 'ASC',
						'posts_per_page'         => 500,
						'update_post_term_cache' => false,
						'update_post_meta_cache' => false,
					);

					$sel_query = new WP_Query( $args );
					if ( $sel_query->have_posts() ) {
						while ( $sel_query->have_posts() ) {
							$sel_query->the_post();
							$selected = false;
							if ( get_the_ID() === $postid ) {
								$selected = true;
							}
							echo '<option value="' . get_the_ID() . '"'
							. selected( $selected )
							. '>' . get_the_title() . '</option>';
						}
					}
					wp_reset_postdata(); ?>
				</select>
			</p>

			<?php
		}
	}

	function ssec_widget_calendar_init() {
		register_widget('SSEC_Widget_Calendar');
	}
	add_action( 'widgets_init', 'ssec_widget_calendar_init' );
}


/*
 * Shortcode to be used as a widget or a block. Displays a few events isn short layout.
 *
 * @param $atts array shortcode parameters, similar to the widget options. Defaults are:
 *   - title = Calendar, title of the widget or block.
     - num_entries = 3, number of events shown.
     - season = 0, show only events from this term.
     - postid = 0, postid of the calendar page, will become a link.
 * @param $context string context of the caller.
 *
 * @return string html with the layout for the widget or block.
 *
 * @since 2.1.0
 */
function ssec_get_widget_content( $atts, $context = '' ) {

	$shortcode_atts = shortcode_atts( array(
		'title'       => esc_html__('Calendar', 'super-simple-event-calendar'),
		'num_entries' => 3,
		'season'      => 0,
		'postid'      => 0,
	), $atts );

	//$instance      = wp_parse_args( (array) $instance, $default_value );

	$widget_title  = esc_attr($shortcode_atts['title']);
	$num_entries   = (int) $shortcode_atts['num_entries'];
	$season        = (int) $shortcode_atts['season'];
	$postid        = (int) $shortcode_atts['postid'];

	$tax_query = array();
	if ( $season > 0 ) {
		$tax_query[] = array(
			'taxonomy'         => 'ssec_season',
			'terms'            => (int) $season,
			'field'            => 'term_id',
			'include_children' => true,
		);
	}

	// Init
	$widget_content = '';

	if ( $context !== 'widget' ) {
		// Widget will add this before the widget_title.
		$widget_content .= '
			<div class="ssec-widget-calendar">';

		if ( strlen( $widget_title ) > 0 ) {
			$widget_content .= '
				<h2 class="widget-title wp-block-heading">' . apply_filters('widget_title', $widget_title) . '</h2>';
		}
	}

	$widget_content .= '
				<ul class="ssec-widget-calendar-list">';

	$query_args = array(
		'post_type'      => 'ssec_event',
		'post_status'    => 'future',
		'posts_per_page' => (int) $num_entries,
		'orderby'        => 'date',
		'order'          => 'ASC',
		'tax_query'      => $tax_query,
	);

	// The Query
	$the_query = new WP_Query( $query_args );

	// The Loop
	if ( $the_query->have_posts() ) {
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$post = get_post();
			$classes = ssec_get_term_classes( get_the_ID() );

			$td_title = apply_filters( 'ssec_td_title', ssec_get_full_date( $post ), $post );
			$td_content = apply_filters( 'ssec_td_content', nl2br(get_the_content()) , $post );

			$widget_content .= '
					<li class="ssec-widget-listitem ' . esc_attr( $classes ) . '">
						<span class="ssec-title">' . $td_title . '</span><br />
						<span class="ssec-content">' . $td_content . '</span>
					</li>';
		}
	}
	/* Restore original Post Data */
	wp_reset_postdata();

	$widget_content .= '
				</ul>';

	// Post the link to the Calendar.
	if ( (int) $postid > 0 ) {
		$permalink = get_permalink( (int) $postid );
		$widget_content .= '
				<p class="ssec-widget-calendar-link">
					<a href="' . esc_attr( $permalink ) . '" title="' . esc_attr__('View Full Calendar.', 'super-simple-event-calendar') . '">' . esc_html__('Full Calendar', 'super-simple-event-calendar') . ' &raquo;</a>
				</p>';
	}

	if ( $context !== 'widget' ) {
		// Widget will add this at the end of the widget.
		$widget_content .= '
			</div>';
	}

	if ( $the_query->have_posts() ) {
		// Only display widget if there are any events.
		return $widget_content;
	}

	return '';

}
add_shortcode( 'super_simple_event_calendar_widget', 'ssec_get_widget_content' );
