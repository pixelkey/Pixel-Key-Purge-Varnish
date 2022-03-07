# Pixel Key Purge Varnish
This is a WordPress plugin to purge or ban the varnish Cache on post save/update with cURL method.

 You can also add the action for post status transition to purge varnish cache. However, this may be overkill in some most cases. Hence it has been disabled unless you need it.

 Set cURL options and header according to your server requirements.
 There are two methods supplied: BAN and PURGE
 By default it is set to BAN.

 It is recommended to place this plugin file in the mu-plugins folder, where it will be automatically included by WordPress as a "Must Use" Plugin. Aternatively, create a folder with the plugin name, place the file in the folder and include it the plugins folder.

 For testing, uncomment debugging sections. A curl_status_log file will be created in current plugins folder - mu-plugins folder.
