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

	// Set url to your Varnish server
	$url = 'localhost';
	$ch = curl_init($url);

	// set curl to BAN
	curl_setopt($ch, curlOPT_CUSTOMREQUEST, 'BAN');
	
	// // set the curl to Purge the cache
	// curl_setopt($ch, curlOPT_RETURNTRANSFER, true);
	// curl_setopt($ch, curlOPT_HTTPHEADER, array('X-Purge-Method: varnish'));
	// curl_setopt($ch, curlOPT_CUSTOMREQUEST, 'PURGE');
	// curl_setopt($ch, curlOPT_POSTFIELDS, '');
	
	// Execute the request. IF DEBUGGING, comment out line below and uncomment "$exec_result = curl_exec($ch);"
	curl_exec($ch);


	// // START OF DEBUGGING SECTION
	// // Get result of curl request. ONLY FOR DEBUGGING PURPOSES
	// $exec_result = curl_exec($ch);

	// // Check if the curl request was successful
	// if (curl_errno($ch)) {
	// 	// print to error file if curl request was not successful
	// 	curl_status_log('Error: ' . curl_error($ch));
	// } else {
	// 	// print to error file if curl request was successful
	// 	curl_status_log('Success: ' . $exec_result);
	// }

	// curl_status_log($exec_result);

	// // END OF DEBUGGING SECTION


	curl_close($ch);
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
// 	fwrite($log, $message . PHP_EOL);
// 	fclose($log);
// }

// // END OF DEBUGGING SECTION