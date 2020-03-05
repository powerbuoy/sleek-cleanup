<?php
namespace Sleek\Cleanup\Metaboxes;

#############################
# Hide some unused meta boxes
# https://www.vanpattenmedia.com/2014/code-snippet-hide-post-meta-boxes-wordpress
add_filter('default_hidden_meta_boxes', function ($hidden, $screen) {
	$boxes = [
		'authordiv',
		'revisionsdiv',
		'trackbacksdiv',
		'postcustom',
		'commentstatusdiv',
		'commentsdiv',
		'slugdiv'
	];

	if ($screen->post_type === 'page') {
		$hidden = array_merge($hidden, $boxes);
	}
	else {
		$hidden = array_merge($hidden, $boxes, ['pageparentdiv']);
	}

	return $hidden;
}, 10, 2);

################
# Collapse Yoast
add_action('admin_footer', function () {
	?>
	<script>
		window.addEventListener('load', function () {
			var button = document.querySelector('#wpseo_meta:not(.closed) button.handlediv');

			if (button) {
				button.click();
			}
		});
	</script>
	<?php
}, 99);
