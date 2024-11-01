<?php


// No direct calls to this script
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



/*
 * Metabox for Event admin.
 *
 * @since 2.0.1
 */
function ssec_add_meta_box() {
	add_meta_box('ssec_event-posts-box', esc_html__( 'Event options', 'super-simple-event-calendar' ), 'ssec_display_meta_box', 'ssec_event', 'normal', 'high');
}
add_action( 'admin_menu', 'ssec_add_meta_box' );


/*
 * Metabox for Event admin.
 *
 * @since 2.0.1
 */
function ssec_display_meta_box() {

	$post_id = get_the_ID();

	wp_nonce_field( basename( __FILE__ ), 'ssec_event_metabox_nonce' );

	$ssec_event_show_day = get_post_meta( $post_id, 'ssec_event_show_day', true );
	$ssec_checked = '';
	if ( $ssec_event_show_day ) {
		$ssec_checked = ' checked="checked"';
	}
	echo '
		<div class="ssec_event_custom_field">
			<label class="ssec_event_show_day" for="ssec_event_show_day">
				<input type="checkbox" name="ssec_event_show_day" id="ssec_event_show_day" ' . $ssec_checked . ' ">
				<span class="ssec_event_show_day">' . esc_html__('Show Day', 'super-simple-event-calendar' ) . '</span>
			</label>
		</div>';

}


/*
 * Save metabox for event.
 *
 * @since 2.0.1
 */
function ssec_save_meta_box( $id ) {

	if ( ! is_admin() ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
		return;
	if ( defined( 'DOING_CRON' ) && DOING_CRON )
		return;

	$post = get_post();

	if ( 'ssec_event' !== get_post_type( $post ) ) {
		return;
	}

	$page = get_current_screen();
	$page = $page->base;

	/* Check that the user is allowed to edit the post. */
	if ( ! current_user_can( 'edit_post', $post->ID ) ) {
		return;
	}

	/* Check Nonce */
	$verified = false;
	if ( isset($_POST['ssec_event_metabox_nonce']) ) {
		$verified = wp_verify_nonce( $_POST['ssec_event_metabox_nonce'], basename( __FILE__ ) );
	}
	if ( $verified == false ) {
		return; // Nonce is invalid, do not process further.
	}

	/* Show Day checkbox */
	if (isset($_POST['ssec_event_show_day']) && $_POST['ssec_event_show_day'] === 'on') {
		update_post_meta( $id, 'ssec_event_show_day', 1 );
	}
	/* Only delete on post.php page, not on Quick Edit. */
	if ( empty($_POST['ssec_event_show_day']) ) {
		if ( $page === 'post' ) {
			delete_post_meta( $id, 'ssec_event_show_day' );
		}
	}

}
add_action( 'save_post', 'ssec_save_meta_box' );


/*
 * Make our meta fields protected, so they are not in the custom fields metabox.
 * Since 2.0.1
 */
function ssec_is_protected_meta( $protected, $meta_key, $meta_type ) {

	switch ($meta_key) {
		case 'ssec_event_show_day':
			return true;
	}

	return $protected;

}
add_filter( 'is_protected_meta', 'ssec_is_protected_meta', 10, 3 );
