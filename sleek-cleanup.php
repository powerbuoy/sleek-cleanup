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

#####################################
# Prevent WP wrapping iframe's in <p>
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

##########################
# Remove self closing tags
# NOTE: From Roots: https://github.com/roots/soil/tree/master/modules
function remove_self_closing_tags ($input) {
	return str_replace(' />', '>', $input);
}

add_filter('get_avatar', __NAMESPACE__ . '\\remove_self_closing_tags');
add_filter('comment_id_fields', __NAMESPACE__ . '\\remove_self_closing_tags');
add_filter('post_thumbnail_html', __NAMESPACE__ . '\\remove_self_closing_tags');

##############
# Cleanup head
# http://wpengineer.com/1438/wordpress-header/
add_action('init', function () {
#	remove_action('wp_head', 'feed_links', 2);
#	remove_action('wp_head', 'feed_links_extra', 3);
#	remove_action('wp_head', 'rsd_link');
#	remove_action('wp_head', 'wlwmanifest_link');
#	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
#	remove_action('wp_head', 'wp_generator');
#	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
});
