<?php
/**
 * This file is part of User Link Feed. Please see the user-link-feed.php file for copyright
 *
 * @author Sulaeman
 * @version 1.1.0
 * @package user-link-feed
 */

require_once( dirname(__FILE__) . '../../../../../../wp-load.php');

global $wpdb;

ob_start();

header ("Content-type: text/xml");
echo '<?xml version="1.0" encoding="utf-8" ?>';
echo '<rss version="2.0">';

$feeds = $wpdb->get_results('SELECT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "ulf" AND meta_value LIKE "%\"approved\";s:1:\"1\";%" ORDER BY meta_id DESC', ARRAY_A);

echo '<channel>';
echo '
<title>'.get_bloginfo('title').' User Link Feed</title>
<link>'.get_bloginfo('home').'</link>
<description>'.get_bloginfo('description').'</description>
';

if (is_array($feeds) && count($feeds) > 0)
{
	foreach ($feeds as $feed)
	{
	$feed_data = unserialize($feed['meta_value']);
	echo '
	  <item>
		<title>'.$feed_data['title'].'</title>
		<link>'.$feed_data['url'].'</link>
		<description>'.strip_tags(stripslashes(preg_replace(array('@(\")+@', '@([\r\n])+@'), array('"', '<br/><br/>'), $feed_data['description']))).'</description>
	  </item>
	';
	}
}

echo '</channel>';
echo '</rss>';
?>