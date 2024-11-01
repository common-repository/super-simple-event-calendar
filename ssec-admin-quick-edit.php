<?php


// No direct calls to this script
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



/*
 * Admin page to add a new event.
 *
 * @since 1.3.0
 */
function ssec_quick_edit_page() {

	if ( ! current_user_can( 'publish_posts' ) ) {
		return;
	}
	?>

	<div class="wrap ssec-quick-edit">
		<h1><?php esc_html_e( 'Quick Edit', 'super-simple-event-calendar' ); ?></h1>

		<?php
		$message = ssec_quick_edit_get_message();
		if ( $message ) { ?>
		<div class="notice"><?php echo wp_kses_post( $message ); ?></div>
		<?php } ?>

		<div id="poststuff" class="ssec-quick-edit metabox-holder">
			<div class="postbox-container">
				<?php
				add_meta_box( 'ssec_quick_edit_metabox', esc_html__( 'Quickly add an Event', 'super-simple-event-calendar' ), 'ssec_quick_edit_metabox', 'ssec_quick_edit_page', 'normal' );
				do_meta_boxes( 'ssec_quick_edit_page', 'normal', '' );
				?>
			</div>
		</div>

	</div>

	<?php
}


/*
 * Metabox for admin page to add a new event.
 *
 * @since 1.3.0
 */
function ssec_quick_edit_metabox() {

	if ( ! current_user_can( 'publish_posts' ) ) {
		return;
	}

	global $wp_locale;
	$nonce = wp_create_nonce( 'ssec_dashboard_quick_edit' );
	?>
	<form name="ssec_quick_edit" id="ssec_quick_edit" action="#" method="POST" accept-charset="UTF-8">
		<input type="hidden" name="ssec_dashboard_quick_edit" id="ssec_dashboard_quick_edit" value="<?php echo esc_attr( $nonce ); ?>" />
		<input type="hidden" name="ssec_quick_edit_action" id="ssec_quick_edit_action" value="ssec_quick_edit_action" />
		<input type="hidden" name="post_type" value="ssec_event" />

		<table>
			<tbody>

			<tr>
			<td colspan="2">
				<label for="ssec_dashboard_title">
					<span class="title"><?php esc_html_e( 'Title', 'super-simple-event-calendar' ); ?></span><br />
					<span class="input-text-wrap"><input type="text" name="ssec_dashboard_title" style="min-width:400px;" class="ssec_dashboard_title" value=""></span>
				</label>
			</td>
			</tr>

			<tr>
			<td colspan="2">
				<label for="ssec_dashboard_content">
					<span class="title"><?php esc_html_e( 'Content', 'super-simple-event-calendar' ); ?></span><br />
					<textarea rows="3" cols="15" autocomplete="off" name="ssec_dashboard_content" class="ssec_dashboard_content editor-area" style="min-width:400px;min-height:140px;" placeholder="<?php esc_attr_e( 'Your next event...', 'super-simple-event-calendar' ); ?>"></textarea>
				</label>
			</td>
			</tr>

			<tr>
			<td>
				<span class="title"><?php esc_html_e( 'Date', 'super-simple-event-calendar' ); ?></span>
			</td>
			<td>
				<?php
				$date = current_time( 'timestamp' );
				$dd = date_i18n( 'd', $date );
				$mm = date_i18n( 'm', $date );
				$yy = date_i18n( 'Y', $date );
				?>
				<label for="dd">
					<span class="screen-reader-text"><?php esc_html_e( 'Day', 'super-simple-event-calendar' ); ?></span>
					<input type="text" class="dd" name="dd" value="<?php echo esc_attr( $dd ); ?>" size="2" maxlength="2" autocomplete="off" />
				</label>
				<label for="mm">
					<span class="screen-reader-text"><?php esc_html_e( 'Month', 'super-simple-event-calendar' ); ?></span>
					<select class="mm" name="mm">
					<?php
					for ( $i = 1; $i < 13; ++$i ) {
						$monthnum = zeroise($i, 2);
						echo '
						<option value="' . (int) $monthnum . '" ' . selected( $monthnum, $mm, false ) . '>';
						/* translators: 1: month number (01, 02, etc.), 2: month abbreviation */
						echo sprintf( esc_html__( '%1$s-%2$s', 'super-simple-event-calendar' ), (int) $monthnum, esc_html( $wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ) ) ) . '</option>';
					}
					?>
					</select>
				</label>
				<label for="yy">
					<span class="screen-reader-text"><?php esc_html_e( 'Year', 'super-simple-event-calendar' ); ?></span>
					<input type="text" class="yy" name="yy" value="<?php echo esc_attr( $yy ); ?>" size="4" maxlength="4" autocomplete="off" />
				</label>
			</td>
			</tr>

			<tr>
			<td>
				<span class="title"><?php esc_html_e( 'Time', 'super-simple-event-calendar' ); ?></span>
			</td>
			<td>
				<label for="hh">
					<span class="screen-reader-text"><?php esc_html_e( 'Hour', 'super-simple-event-calendar' ); ?></span>
					<input type="text" class="hh" name="hh" value="23" size="2" maxlength="2" autocomplete="off" />
				</label> :
				<label for="mn">
					<span class="screen-reader-text"><?php esc_html_e( 'Minute', 'super-simple-event-calendar' ); ?></span>
					<input type="text" class="mn" name="mn" value="59" size="2" maxlength="2" autocomplete="off" />
				</label>
			</td>
			</tr>

			<tr>
			<td>
				<label>
					<span class="title"><?php esc_html_e( 'Status', 'super-simple-event-calendar' ); ?></span>
				</label>
			</td>
			<td>
				<select name="ssec_dashboard_status">
					<option value="future"><?php esc_html_e( 'Scheduled', 'super-simple-event-calendar' ); ?></option>
					<option value="draft"><?php esc_html_e( 'Draft', 'super-simple-event-calendar' ); ?></option>
					<option value="publish"><?php esc_html_e( 'Published', 'super-simple-event-calendar' ); ?></option>
				</select>
			</td>
			</tr>

			<tr>
			<td colspan="2">
			<?php
			$taxonomy = get_taxonomy( 'ssec_season' );
			if ( is_object( $taxonomy ) && is_a( $taxonomy, 'WP_Taxonomy' ) ) {
				?>
				<label class="inline-edit-cats" for="ssec_season-checklist">
					<span class="title inline-edit-categories-label"><?php echo esc_html( $taxonomy->labels->name ); ?></span>
				</label>
				<ul class="cat-checklist ssec_season-checklist">
					<?php wp_terms_checklist( null, array( 'taxonomy' => $taxonomy->name ) ); ?>
				</ul>
				<?php
			} ?>
			</td>
			</tr>

			<tr>
			<td colspan="2">
				<span class="ssec-save">
					<input type="submit" name="ssec_dashboard_submit" class="button button-primary ssec-save" value="<?php esc_attr_e( 'Publish Event', 'super-simple-event-calendar' ); ?>" />
				</span>
			</td>
			</tr>

			</tbody>
		</table>
	</form>

	<?php
}


/*
 * The hook to add a admin page for quick edit.
 *
 * @since 1.3.0
 */
function ssec_adminmenu_quick_edit() {

	if ( ! current_user_can('publish_posts') ) {
		return;
	}

	add_submenu_page( 'edit.php?post_type=ssec_event', esc_html__( 'Quick Edit', 'super-simple-event-calendar'), /* translators: Menu entry */ esc_html__('Quick Edit', 'super-simple-event-calendar'), 'publish_posts', 'ssec-quick-edit.php', 'ssec_quick_edit_page' );

}
add_action('admin_menu', 'ssec_adminmenu_quick_edit');


/*
 * Save data entered into the admin page for quick edit.
 *
 * @since 1.3.0
 */
function ssec_quick_edit_save() {

	if ( isset($_POST['ssec_quick_edit_action']) && $_POST['ssec_quick_edit_action'] === 'ssec_quick_edit_action' ) {

		$verified = false;
		if ( isset( $_POST['ssec_dashboard_quick_edit'] ) ) {
			$nonce = $_POST['ssec_dashboard_quick_edit'];
			$verified = wp_verify_nonce( $nonce, 'ssec_dashboard_quick_edit' );
		}
		if ( $verified === false ) {
			ssec_quick_edit_get_message( '<p>' . esc_html__( 'Unable to submit this form, please refresh and try again.', 'super-simple-event-calendar' ) . '</p>' );
			return;
		}

		if ( ! current_user_can('publish_posts') ) {
			ssec_quick_edit_get_message( '<p>' . esc_html__( 'Unable to submit this form, you have no permissions to publish an event.', 'super-simple-event-calendar' ) . '</p>' );
			return;
		}

		$ssec_dashboard_title = '';
		if ( isset($_POST['ssec_dashboard_title'] )) {
			$ssec_dashboard_title = wp_kses_post($_POST['ssec_dashboard_title']);
		}

		$ssec_dashboard_content = '';
		if ( isset($_POST['ssec_dashboard_content']) ) {
			$ssec_dashboard_content = wp_kses_post($_POST['ssec_dashboard_content']);
		}
		// Wrap content in the Paragraph block.
		if ( false === strpos( $ssec_dashboard_content, '<!-- wp:paragraph -->' ) ) {
			$ssec_dashboard_content = sprintf(
				'<!-- wp:paragraph -->%s<!-- /wp:paragraph -->',
				str_replace( array( "\r\n", "\r", "\n" ), '<br />', $ssec_dashboard_content )
			);
		}

		$date_was_posted = true;
		foreach ( array( 'yy', 'mm', 'dd', 'hh', 'mn' ) as $timeunit ) {
			if ( empty( $_POST["$timeunit"] ) ) {
				$date_was_posted = false;
				break;
			}
		}
		if ( $date_was_posted === true ) {
			$yy = $_POST['yy'];
			$mm = $_POST['mm'];
			$dd = $_POST['dd'];
			$hh = $_POST['hh'];
			$mn = $_POST['mn'];
			$ss = '00';
			$dd = ( $dd > 31 ) ? 31 : $dd;
			$hh = ( $hh > 23 ) ? ( $hh - 24 ) : $hh;
			$mn = ( $mn > 59 ) ? ( $mn - 60 ) : $mn;

			$post_date = "$yy-$mm-$dd $hh:$mn:$ss";
		} else {
			$post_date = current_time( 'mysql' ); // Y-m-d H:i:s
		}
		/* Setting both dates will set the published date to this, instead of when moderating. */
		$post_date_gmt = get_gmt_from_date( $post_date );

		$allowed_stati = array( 'future', 'draft', 'publish' );
		$ssec_dashboard_status = 'future';
		if ( isset($_POST['ssec_dashboard_status']) ) {
			$posted_status = wp_kses_post($_POST['ssec_dashboard_status']);
			foreach ( $allowed_stati as $allowed_status ) {
				if ( $posted_status === $allowed_status ) {
					$ssec_dashboard_status = $posted_status;
				}
			}
		}

		$post_data = array(
			'post_parent'    => 0,
			'post_status'    => $ssec_dashboard_status,
			'post_type'      => 'ssec_event',
			'post_date'      => $post_date,
			'post_date_gmt'  => $post_date_gmt,
			'post_author'    => get_current_user_id(),
			'post_password'  => '',
			'post_content'   => $ssec_dashboard_content,
			'post_title'     => $ssec_dashboard_title,
			'menu_order'     => 0,
		);

		if ( isset($_POST['tax_input']['ssec_season']) ) {
			$tax_input = $_POST['tax_input'];
			$post_data['tax_input']['ssec_season'] = array_map( 'absint', $tax_input['ssec_season'] );
		}

		$post_id = wp_insert_post( $post_data );

		if ( empty( $post_id ) ) {
			ssec_quick_edit_get_message( '<p>' . esc_html__( 'Sorry, something went wrong with saving your event. Please contact a site admin.', 'super-simple-event-calendar' ) . '</p>' );
		} else {
			ssec_quick_edit_get_message( '<p>' . esc_html__( 'Your event was saved.', 'super-simple-event-calendar' ) . '</p>' );
		}

	}

}
add_action('admin_init', 'ssec_quick_edit_save');


/*
 * Set and/or get message for the admin page for quick edit.
 *
 * @param  string text string with message.
 * @return string text string with message.
 *
 * @since 1.3.0
 */
function ssec_quick_edit_get_message( $message = '' ) {

	static $message_static;

	if ( $message_static ) {
		return $message_static;
	} else {
		$message_static = '';
	}

	if ( strlen( $message ) > 0 ) {
		$message_static = wp_kses_post( $message );
	}

	return $message_static;

}
