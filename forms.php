<?php
namespace Sleek\Cleanup;

#######################
# Cleanup comment form
add_filter('comment_form_opening_tag', function () {
	return '<section id="comment-form">';
});

add_filter('comment_form_closing_tag', function () {
	return '</section>';
});

add_filter('comment_form_defaults', function ($args) {
	# A little nicer output
	$args['id_form'] = null;
	$args['class_form'] = null;
	$args['title_reply_before'] = '<h2>';
	$args['title_reply_after'] = '</h2>';
	$args['format'] = 'html5';

	# Add placeholders to fields
	if (get_theme_support('sleek/cleanup/comment_form_placeholders')) {
		# All fields we want to add placeholders to
		# NOTE: We're using WP core's translations here
		$fieldsToReplace = [
			'author' => __('Name'),
			'email' => __('Email'),
			'url' => __('Website')
		];

		foreach ($fieldsToReplace as $field => $value) {
			# Add asterisk if required
			$required = strstr($args['fields'][$field], 'required') ? ' *' : '';

			# Insert placeholder
			$args['fields'][$field] = str_replace(
				'name="' . $field . '"',
				'name="' . $field . '" placeholder="' . $value . $required . '"',
				$args['fields'][$field]
			);
		}

		# Comment field is special and always required
		$args['comment_field'] = str_replace(
			'name="comment"',
			'name="comment" placeholder="' . _x('Comment', 'noun') . ' *"',
			$args['comment_field']
		);
	}

	return $args;
});
