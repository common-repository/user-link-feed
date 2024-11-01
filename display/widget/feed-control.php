<?php
/**
 * This file is part of User Link Feed. Please see the user-link-feed.php file for copyright
 *
 * @author Sulaeman
 * @version 1.1.0
 * @package user-link-feed
 */
?>
<p>
	<label for="ulf_feed_title">
		Title:
		<input id="ulf_feed_title" class="widefat" name="ulf_feed_title" type="text" value="<?php echo $title; ?>" />
	</label>
</p>
<p>
	<label for="ulf_max_feed">
		Max Feed Entries:
		<input id="ulf_max_feed" class="widefat" type="text" name="ulf_max_feed" value="<?php echo $max_feed; ?>" />
	</label>
</p>
<p>
	<label for="ulf_show_description">
		<input id="ulf_show_description" type="checkbox" name="ulf_show_description" value="1"<?php echo ($show_description == 1) ? ' checked="checked"' : ''; ?>/>
		Show Description
	</label>
</p>
<p>
	<label for="ulf_view_all_url">
		View All URL:
		<input id="ulf_view_all_url" class="widefat" type="text" name="ulf_view_all_url" value="<?php echo $view_all_url; ?>" />
	</label>
</p>
<p>
	To control the other settings, please visit the <a href="<?php echo $settingspage; ?>">User Link Feed Settings page</a>.
</p>
<input type="hidden" id="ulf_feed_submit" name="ulf_feed_submit" value="1" />