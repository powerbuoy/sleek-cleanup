<?php
namespace Sleek\Cleanup;

################################################
# Remove "Protected:" from protected post titles
add_filter('private_title_format', function () {
	return '%s';
});

add_filter('protected_title_format', function () {
	return '%s';
});

##############################################################
# Fix pagination output (Remove h2, wrapping div, classes etc)
add_filter('navigation_markup_template', function ($template, $class) {
	return '<nav id="pagination">%3$s</nav>';
}, 10, 2);

##########################
# Wrap videos in div.video
# https://wordpress.stackexchange.com/questions/50779/how-to-wrap-oembed-embedded-video-in-div-tags-inside-the-content
add_filter('embed_oembed_html', function($html, $url, $attr, $post_id) {
	return '<div class="video">' . $html . '</div>';
}, 99, 4);

#####################################
# Prevent WP wrapping iframe's in <p>
# NOTE: Double check what this actually does
# https://gist.github.com/KTPH/7901c0d2c66dc2d754ce
# add_filter('the_content', function ($content) {
#	return preg_replace('/<p>\s*(<iframe .*>*.<\/iframe>)\s*<\/p>/iU', '\1', $content);
# });

########################
# Clean up widget output
# NOTE: Not yet in WP-Core
# https://core.trac.wordpress.org/ticket/48033
add_filter('register_sidebar_defaults', function ($defaults) {
	$defaults['before_widget'] = '<div id="widget-%1$s" class="%2$s">';
	$defaults['after_widget'] = '</div>';
	$defaults['before_title'] = '<h2>';
	$defaults['after_title'] = '</h2>';

	return $defaults;
});

#############################
# Clean up wp_list_categories
add_action('wp_list_categories', function ($output) {
	# Remove title attributes (which can be insanely long)
	# https://www.isitwp.com/remove-title-attribute-from-wp_list_categories/
	$output = preg_replace('/ title="(.*?)"/s', '', $output);

	# If there's no current cat - add the class to the "all" link
	if (strpos($output, 'current-cat') === false) {
		$output = str_replace('cat-item-all', 'cat-item-all current-cat', $output);
	}

	# If there are no categories, don't display anything
	if (strpos($output, 'cat-item-none') !== false) {
		$output = false;
	}

	return $output;
});

#########
# Thanks:
# http://wpengineer.com/1438/wordpress-header/ & https://github.com/roots/soil/

##########################
# Remove self closing tags
function remove_self_closing_tags ($input) {
	return str_replace(' />', '>', $input);
}

add_filter('get_avatar', __NAMESPACE__ . '\\remove_self_closing_tags');
add_filter('comment_id_fields', __NAMESPACE__ . '\\remove_self_closing_tags');
add_filter('post_thumbnail_html', __NAMESPACE__ . '\\remove_self_closing_tags');

################
# Cleanup <link>
add_filter('style_loader_tag', function ($html) {
	preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $html, $matches);

	# Only display media if it is meaningful
	$media = ($matches[3][0] !== '' and $matches[3][0] !== 'all') ? ' media="' . $matches[3][0] . '"' : '';

	return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
});

##################
# Cleanup <script>
add_filter('script_loader_tag', function ($html) {
	return str_replace("'", '"', str_replace("type='text/javascript' ", '', $html));
});

##############
# Cleanup head
# TODO: Investigate and document all of these
add_action('init', function () {
	# Remove RSS feed <link>s
	remove_action('wp_head', 'feed_links', 2);

	# Remove more feed <link>s NOTE: Does nothing?
#	remove_action('wp_head', 'feed_links_extra', 3);

	# Remove RSD <link>
	remove_action('wp_head', 'rsd_link');

	# Remove WMLManifest <link>
	remove_action('wp_head', 'wlwmanifest_link');

	# NOTE: Does nothing?
#	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

	# Remove <meta> generator
	remove_action('wp_head', 'wp_generator');

	# NOTE: Does nothing?
#	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

	# Remove Emoji script
	remove_action('wp_head', 'print_emoji_detection_script', 7);

	# NOTE: Does nothing?
#	remove_action('admin_print_scripts', 'print_emoji_detection_script');

	# Remove Emoji style
	remove_action('wp_print_styles', 'print_emoji_styles');

	# NOTE: Does nothing?
#	remove_action('admin_print_styles', 'print_emoji_styles');

#	remove_action('wp_head', 'wp_oembed_add_discovery_links');
#	remove_action('wp_head', 'wp_oembed_add_host_js');

	# Remove the REST API endpoint.
	# remove_action('rest_api_init', 'wp_oembed_register_route');

	# Turn off oEmbed auto discovery. Don't filter oEmbed results.
	# remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

	# Remove oEmbed discovery links.
	# remove_action('wp_head', 'wp_oembed_add_discovery_links');

	# Remove oEmbed-specific JavaScript from the front-end and back-end.
	# remove_action('wp_head', 'wp_oembed_add_host_js');

	# Remove REST API <link>
	remove_action('wp_head', 'rest_output_link_wp_head', 10);

#	remove_filter('the_content_feed', 'wp_staticize_emoji');
#	remove_filter('comment_text_rss', 'wp_staticize_emoji');
#	remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
#	add_filter('use_default_gallery_style', '__return_false');
#	add_filter('emoji_svg_url', '__return_false');
#	add_filter('show_recent_comments_widget_style', '__return_false');
});

##########################
# Remove a bunch of CSS/JS
add_action('wp_enqueue_scripts', function () {
	# Don't touch the admin
	if (!is_admin()) {
		# Remove Gutenberg blocks CSS
		wp_dequeue_style('wp-block-library');

		# Remove duplicate post CSS when not logged in
	#	if (!is_user_logged_in()) {
	#		wp_dequeue_style('duplicate-post');
	#	}
	}
});
