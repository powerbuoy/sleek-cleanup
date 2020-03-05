<?php
namespace Sleek\Cleanup;

##############
# Cleanup head
if (!is_admin()) {
	# TODO: Investigate and document all of these
	add_action('init', function () {
		# Remove RSS feed <link>s
		remove_action('wp_head', 'feed_links', 2);

		# Remove more feed <link>s
		remove_action('wp_head', 'feed_links_extra', 3);

		# Remove RSD <link>
		remove_action('wp_head', 'rsd_link');

		# Remove WMLManifest <link>
		remove_action('wp_head', 'wlwmanifest_link');

		# NOTE: Does nothing?
	#	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

		# Remove <meta> generator
		remove_action('wp_head', 'wp_generator');

		# Remove link[rel=shortlink]
		remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

		# Remove Shortlink HTTP header
		remove_action('template_redirect', 'wp_shortlink_header', 11);

		# Remove canonical
		remove_action('wp_head', 'rel_canonical');

		# Remove Emoji script
		remove_action('wp_head', 'print_emoji_detection_script', 7);

		# NOTE: Does nothing?
	#	remove_action('admin_print_scripts', 'print_emoji_detection_script');

		# Remove Emoji style
		remove_action('wp_print_styles', 'print_emoji_styles');

		# NOTE: Does nothing?
	#	remove_action('admin_print_styles', 'print_emoji_styles');

		# Remove the REST API endpoint.
		# remove_action('rest_api_init', 'wp_oembed_register_route');

		# Turn off oEmbed auto discovery. Don't filter oEmbed results.
		# remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

		# Remove oEmbed discovery links.
		remove_action('wp_head', 'wp_oembed_add_discovery_links');

		# Remove oEmbed-specific JavaScript from the front-end and back-end.
		remove_action('wp_head', 'wp_oembed_add_host_js');

		# Remove REST API <link>
		remove_action('wp_head', 'rest_output_link_wp_head', 10);

	#	remove_filter('the_content_feed', 'wp_staticize_emoji');
	#	remove_filter('comment_text_rss', 'wp_staticize_emoji');
	#	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
	#	add_filter('use_default_gallery_style', '__return_false');
	#	add_filter('emoji_svg_url', '__return_false');

		# Remove Recent Comments CSS 🙄
		add_filter('show_recent_comments_widget_style', '__return_false');
	});
}
