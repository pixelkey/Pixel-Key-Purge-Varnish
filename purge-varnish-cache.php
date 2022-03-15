<?php

/**
 * Plugin Name: Pixel Key Purge Varnish
 * Description: Purge Vanish Cache on post save/update with curl method.
 * Version: 1.0.0
 * Author: Pixel Key
 * Author URI: https://pixelkey.com
 * Requires PHP: 7.2.0
 */


namespace pixelkey_purge_varnish;

class PostHooks
{
	public static function init()
	{
		// Add action to save post to purge varnish cache
		add_action('save_post', [static::class, 'onPostSaveAndUpdate']);

		// // Add action to post status transition to purge varnish cache. This may be overkill in some cases. Hence it has been disabled unless you need it.
		// add_action('transition_post_status', [static::class, 'onPostStatusTransition'], 10, 3);
	}

	public static function onPostSaveAndUpdate($postId)
	{
		do_action('pixelkey_purge_varnish');
	}

	public static function onPostStatusTransition($newStatus, $oldStatus, $post)
	{
		if ($newStatus == 'publish' || $newStatus == $oldStatus) return;
		do_action('pixelkey_purge_varnish');
	}
}


PostHooks::init();


add_action('pixelkey_purge_varnish', function () {

	// Set url to trigger cache purge
	$url = 'http://localhost';

	// Set purge method.
	$purge_method = 'BAN';

	$args = array(
		'method'    => $purge_method
	);

	$debug_mode = false;
	$debug_log_file = 'curl_status_log.txt';

	// wp_remote_request is used to PURGE or BAN varnish cache
	$response = wp_remote_request($url, $args);

	

	// START OF DEBUGGING SECTION
	if ($debug_mode === true) {
		$file_path = plugin_dir_path(__FILE__) . $debug_log_file;
		if (file_exists($file_path)) {
			unlink($file_path);
		}
		$log = fopen($file_path, 'a');
		// json_encode() is used to convert the array to a JSON string.
		fwrite($log, json_encode($response) . PHP_EOL);
		fclose($log);
	}
	// END OF DEBUGGING SECTION

});