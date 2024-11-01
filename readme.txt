=== Super Simple Event Calendar ===
Contributors: mpol
Tags: simple event calendar, event calendar, calendar, simple calendar
Requires at least: 4.1
Tested up to: 6.6
Stable tag: 2.1.2
License: GPLv2 or later
Requires PHP: 7.0

Super Simple Event Calendar is an event calendar for people who just want something simple for events.

== Description ==

Super Simple Event Calendar is an event calendar for people who just want something simple for events.
The goal is to provide a simple way to show events to your visitors.


Current features include:

* Shortcode with a list of future events.
* Widget to display future events.
* Simple and clean admin interface that integrates seamlessly into WordPress admin.
* Admin page to quickly add an event.
* Localization. Own languages can be added very easily through [GlotPress](https://translate.wordpress.org/projects/wp-plugins/super-simple-event-calendar).

= Support =

If you have a problem or a feature request, please post it on the plugin's support forum on [wordpress.org](https://wordpress.org/support/plugin/super-simple-event-calendar). I will do my best to respond as soon as possible.

If you send me an email, I will not reply. Please use the support forum.

= Translations =

Translations can be added very easily through [GlotPress](https://translate.wordpress.org/projects/wp-plugins/super-simple-event-calendar).
You can start translating strings there for your locale. They need to be validated though, so if there's no validator yet, and you want to apply for being validator (PTE), please post it on the support forum.
I will make a request on make/polyglots to have you added as validator for this plugin/locale.

= Demo =

Check out the demo at [my local chess club Pegasus](https://svpegasus.nl/kalender/).

= Compatibility =

This plugin is compatible with [ClassicPress](https://www.classicpress.net).

= Contributions =

This plugin is also available in [Codeberg](https://codeberg.org/cyclotouriste/super-simple-event-calendar).


== Installation ==

= Installation =

* Install the plugin through the admin page "Plugins".
* Alternatively, unpack and upload the contents of the zipfile to your '/wp-content/plugins/' directory.
* Activate the plugin through the 'Plugins' menu in WordPress.
* Place the shortcode '[super_simple_event_calendar]' in a page.
* Add Events through the admin menu.

= How to add events and format them =

What I do is use the title field for the date and possibly day. Use the content for content, description and everything.
In the publishing postbox I set the date to the end date and end time of the event, so it will be listed under future events for as long as it lasts.

= License =

The plugin itself is released under the GNU General Public License. A copy of this license can be found at the license homepage or in the super-simple-event-calendar.php file at the top.


== Frequently Asked Questions ==

= I want to translate this plugin =

Translations can be added very easily through [GlotPress](https://translate.wordpress.org/projects/wp-plugins/super-simple-event-calendar).
You can start translating strings there for your locale.
They need to be validated though, so if there's no validator yet, and you want to apply for being validator (PTE), please post it on the support forum.
I will make a request on make/polyglots to have you added as validator for this plugin/locale.

= I only want to show events in the simple list from a category. =

You can use a shortcode parameter for showing events only from certain categories (seasons really):

	[super_simple_event_calendar season="213,212"]

= I want to limit the number of events in the shortcode. =

You can use a shortcode parameter for showing events a limited number of events:

	[super_simple_event_calendar posts_per_page="3"]

= I want to show past events too in the shortcode. =

You can use a shortcode parameter for showing events with a different status, or from multiple statuses (statii?) in a comma-separated list:

	[super_simple_event_calendar status="future,publish"]

= I want to change the order of events in the shortcode. =

You can use a shortcode parameter for order of events (either DESC or ASC):

	[super_simple_event_calendar order="DESC"]

= Is there an easy way to add a lot of events in one go? =

There is a Quick Edit menu option that might suit your needs. Myself I now use a [Duplicate Post](https://wordpress.org/plugins/duplicate-post/) plugin to add events.

= Can I use a block instead of a widget? =

You can add a shortcode to the shortcode block under Appearance > Widgets. It has similar parameters as the widget, like:

	[super_simple_event_calendar_widget title="My Special Calendar" num_entries="5" season="3" postid="28921"]

Defaults are: title = Calendar, title of the widget or block. num_entries = 3, number of events shown. season = 0, show only events from this term. postid = 0, postid of the calendar page, will become a link.


== Screenshots ==

1. Example of the use of this plugin. At the left the content field with the shortcode and its output, the event list. At the right the widget with 3 future events.


== Changelog ==

= 2.1.2 =
* 2024-06-16
* Add order parameter to shortcode (thanks ajhill).
* Better check for direct access of files.

= 2.1.1 =
* 2024-05-17
* Add filters for title and content (thanks nellie73).

= 2.1.0 =
* 2024-01-01
* Add shortcode for widget to be used in a shortcode block.

= 2.0.2 =
* 2023-10-27
* Support custom fields for event post type.
* Add filter 'ssec_get_the_date'.

= 2.0.1 =
* 2023-10-26
* Add option to show day of the week, default no (thanks malgra).

= 2.0.0 =
* 2023-10-26
* Use date from the post date field (thanks jensuwe12).
* Support status parameter in shortcode for simple list (thanks jensuwe12).
* Add filter 'ssec_add_td_s_to_table' to add custom fields to shortcode (thanks jensuwe12).

= 1.5.1 =
* 2023-02-16
* Escape more output.
* Only run check for missed cronjob once every 10 requests.

= 1.5.0
* 2022-12-06
* Set event to status 'publish' in case of a missed cronjob.
* Improve default datetime on quick edit.
* Improve styling of update message on quick edit.
* Fix output message in shortcode if there are no events.

= 1.4.2 =
* 2022-06-05
* Fix error when saving the page with shortcode.

= 1.4.1 =
* 2022-04-15
* Support posts_per_page parameter in shortcode for simple list.
* Support season in widget as well.

= 1.4.0 =
* 2022-01-10
* Support season parameter in shortcode for simple list.

= 1.3.3 =
* 2021-11-13
* Revert previous update, it acts funky in practice.

= 1.3.2 =
* 2021-11-12
* Use date/hour in WP_Query too, in case future events fail to get their status changed on roll-over.

= 1.3.1 =
* 2021-08-20
* Only show edit link when appropriate.
* Some updates from phpcs and wpcs.

= 1.3.0 =
* 2021-03-25
* Use admin page with quick edit instead of dashboard widget, more focused this way.

= 1.2.0 =
* 2021-03-23
* Add dashboard widget to quickly add an event.

= 1.1.3 =
* 2020-04-10
* Fix wrong usage of get_the_ID().

= 1.1.2 =
* 2020-04-10
* Fix undefined error.

= 1.1.1 =
* 2020-04-10
* Add term classes for season to each event post.

= 1.1.0 =
* 2020-03-23
* Update and add classes for html elements.

= 1.0.3 =
* 2019-12-18
* Remove ':' character from displays.

= 1.0.2 =
* 2019-01-31
* Better dashicon.

= 1.0.1 =
* 2018-09-23
* Use 'nl2br()' on the content.

= 1.0.0 =
* 2018-08-23
* Initial release.
