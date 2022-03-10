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
	$url = 'https://localhost';
	$args = [];

	// wp_remote_get() is used to BAN varnish cache
	$response = wp_remote_get($url, $args);

	// // START OF DEBUGGING SECTION
	// // If the response is an error, print it out.
	// if (is_wp_error($response)) {
	// 	curl_status_log($response->get_error_message());
	// } else {
	// 	// If the response is successful, print out the response code.
	// 	curl_status_log($response);
	// }
	// // END OF DEBUGGING SECTION

});


// // START OF DEBUGGING SECTION

// // Print to curl_status_log file in current plugins folder - mu-plugins folder.
// function curl_status_log($message)
// {
// 	// Delete old log file
// 	$file_name = 'curl_status_log.txt';
// 	$file_path = plugin_dir_path(__FILE__) . $file_name;
// 	if (file_exists($file_path)) {
// 		unlink($file_path);
// 	}
// 	$log = fopen($file_path, 'a');
// 	// json_encode() is used to convert the array to a JSON string.
// 	fwrite($log, json_encode($message) . PHP_EOL);
// 	fclose($log);
// }

// // END OF DEBUGGING SECTION