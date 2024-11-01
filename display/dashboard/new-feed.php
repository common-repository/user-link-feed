<?php
/**
 * This file is part of User Link Feed. Please see the user-link-feed.php file for copyright
 *
 * @author Sulaeman
 * @version 1.1.0
 * @package user-link-feed
 */
?>
<?php if (is_array($feeds) && count($feeds) > 0) : ?>
<?php $i_feed = 0; ?>
<div class="ulf_list_widget_box">
	<ul>
		<?php foreach($feeds as $feed) : ?>
		<?php $feed_id = str_replace('ulf_', '', $feed['meta_key']); ?>
		<?php $feed_value = unserialize($feed['meta_value']); ?>
		<li id="comment-<?php echo $feed_id; ?>">
			<strong>
				<a target="_blank" href="<?php echo stripslashes(str_replace('\"', '"', $feed_value['url'])); ?>"><?php echo stripslashes(str_replace('\"', '"', $feed_value['title'])); ?></a>
			</strong>
			<?php if ($options['show_description'] == 1) : ?>
			<p><?php echo stripslashes(preg_replace(array('@(\")+@', '@([\r\n])+@'), array('"', '<br/><br/>'), $feed_value['description'])); ?></p>
			<?php endif; ?>
			<div class="clear"></div>
			<small class="alignright"><?php echo date('F d, Y', $feed_value['date']); ?></small>
			<div class="clear"></div>
		</li>
		<?php ++$i_feed; ?>
		<?php endforeach; ?>
	</ul>
	<div class="ulf_view_all_pagination">
		<a class="next" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=user-link-feed/user-link-feed-class.php">view all feeds</a>
	</div>
</div>
<?php endif; ?>