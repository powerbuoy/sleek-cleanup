<?php
namespace Sleek\Cleanup;

require_once __DIR__ . '/comments.php';
require_once __DIR__ . '/forms.php';
require_once __DIR__ . '/head.php';
require_once __DIR__ . '/metaboxes.php';

################################
# Don't do this inside the admin
# Some stuff in here originally from: http://wpengineer.com/1438/wordpress-header/ & https://github.com/roots/soil/
if (!is_admin()) {
	################################################
	# Remove "Protected:" from protected post titles
	add_filter('private_title_format', function () {
		return '%s';
	});

	add_filter('protected_title_format', function () {
		return '%s';
	});

	#############################
	# Change password form button
	add_filter('gettext_with_context', function ($translation, $text, $context, $domain) {
		if ($text === 'Enter' and $context === 'post password form') {
			return __('Log in', 'sleek');
		}

		return $translation;
	}, 10, 4);

	##############################################################
	# Fix pagination output (Remove h2, wrapping div, classes etc)
	add_filter('navigation_markup_template', function ($template, $class) {
		return '<nav id="pagination">%3$s</nav>';
	}, 10, 2);

	#####################################
	# Prevent WP wrapping iframe's in <p>
	# NOTE: Is this needed? Yes it is
	# https://gist.github.com/KTPH/7901c0d2c66dc2d754ce
	add_filter('the_content', function ($content) {
		return preg_replace('/<p>\s*(<iframe .*>*.<\/iframe>)\s*<\/p>/iU', '\1', $content);
	});

	########################
	# Clean up widget output
	# https://core.trac.wordpress.org/ticket/48033
	add_filter('register_sidebar_defaults', function ($defaults) {
		$defaults['before_widget'] = '<section id="widget-%1$s" class="%2$s">';
		$defaults['after_widget'] = '</section>';
		$defaults['before_title'] = '<h2>';
		$defaults['after_title'] = '</h2>';

		return $defaults;
	});

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
}
