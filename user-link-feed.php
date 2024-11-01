<?php
/*
Plugin Name: User Link Feed
Plugin URI: http://www.feelinc.me/plugin/user-link-feed-plugin-for-wordpress/
Description: User Link Feed enables user blog to contribute link feeds.
Version: 1.2.2
Author: Sulaeman
Author URI: http://www.feelinc.me/
*/

define('ULF_VERSION', '1.0.0');
define('ULF_PLUGIN_NAME', 'User_Link_Feed');
define('ULF_FILE', basename(__FILE__));
define('ULF_DIR', dirname(__FILE__));
define('ULF_ADMIN_URL', $_SERVER['PHP_SELF'] . "?page=" . basename(ULF_DIR) . '/' . ULF_FILE);
define('ULF_PATH', ULF_DIR . '/' . ULF_FILE);
define('ULF_DISPLAY_NAME', 'User Link Feed');
define('ULF_DISPLAY_URL', get_bloginfo('wpurl') . '/wp-content/plugins/' . basename(ULF_DIR) . '/display');
define('ULF_LIST_TEMPLATE', 'feed.php');
define('ULF_FORM_TEMPLATE', 'feed-form.php');
define('ULF_WIDGET_FEED_CONTROL_TEMPLATE', 'feed-control.php');
define('ULF_NEW_LIST_TEMPLATE', 'new-feed.php');

require_once(ABSPATH . '/wp-includes/pluggable.php');

get_currentuserinfo();

if ($current_user->wp_capabilities['administrator'] == 1)
{
	require_once(ABSPATH . '/wp-admin/includes/template.php');
	require_once(ABSPATH . '/wp-admin/includes/dashboard.php');
}

include_once('user-link-feed-class.php');
include_once('user-link-feed-pagination-class.php');

// Add the installation and uninstallation hooks
register_activation_hook(__FILE__, array(ULF_PLUGIN_NAME, 'install'));
register_deactivation_hook(__FILE__, array(ULF_PLUGIN_NAME, 'uninstall'));

if (class_exists('User_Link_Feed')) {
	User_Link_Feed::bootstrap();
}

?>
