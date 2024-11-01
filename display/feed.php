<?php
/**
 * This file is part of User Link Feed. Please see the user-link-feed.php file for copyright
 *
 * @author Sulaeman
 * @version 1.2.0
 * @package user-link-feed
 */
?>
<?php if (is_array($feeds) && count($feeds) > 0) : ?>
<?php $i_feed = 0; ?>
<div class="ulf_list_box">
	<a class="ulf_feed_icon" href="<?= ULF_DISPLAY_URL; ?>/feed/" target="_blank"><img src="<?= ULF_DISPLAY_URL; ?>/feed.png" alt="feed" /></a>
	<div class="clear"></div>
	<ul>
		<?php foreach($feeds as $feed) : ?>
		<?php $feed_id = str_replace('ulf_', '', $feed['meta_key']); ?>
		<?php $feed_value = unserialize($feed['meta_value']); ?>
		<li id="comment-<?php echo $feed_id; ?>"<?= ((($i_feed % 2) > 0) ? '' : ' class="alt"'); ?>>
			<small class="alignright"><?php echo date('F d, Y', $feed_value['date']); ?></small>
			<strong>
				<a target="_blank" href="<?php echo stripslashes(str_replace('\"', '"', $feed_value['url'])); ?>"><?php echo stripslashes(str_replace('\"', '"', $feed_value['title'])); ?></a>
			</strong>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<?php $image = (isset($feed_value['image']) && $feed_value['image'] != '') ? stripslashes(str_replace('\"', '"', $feed_value['image'])) : ''; ?>
						<?php if ($image != '') : ?>
						<img src="<?php echo $image; ?>" alt="<?php echo stripslashes(str_replace('\"', '"', $feed_value['title'])); ?>" width="100" height="100" />
						<?php endif; ?>
					</td>
					<td>
						<p>
							<?php echo stripslashes(preg_replace(array('@(\")+@', '@([\r\n])+@'), array('"', '<br/><br/>'), $feed_value['description'])); ?>
						</p>
					</td>
				</tr>
			</table>
			<?php $news = (isset($feed_value['news']) && $feed_value['news'] != '') ? stripslashes(str_replace('\"', '"', $feed_value['news'])) : ''; ?>
			<?php if ($news != '') : ?>
			<p class="ulf_author_comment">
				<strong>author comment: </strong>
				<?php echo $news; ?>
			</p>
			<?php endif; ?>
		</li>
		<?php ++$i_feed; ?>
		<?php endforeach; ?>
	</ul>
	<?php echo $pagination->getOutput(); ?>
</div>
<?php endif; ?>