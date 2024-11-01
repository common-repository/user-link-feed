<?php
/**
 * This file is part of User Link Feed. Please see the user-link-feed.php file for copyright
 *
 * @author Sulaeman
 * @version 1.2.0
 * @package user-link-feed
 */
?>
<?php if ( ! $ulf_options['registered_user'] ) : ?>
<script type="text/javascript">
/* <![CDATA[ */
var ULF_URL = "<?php echo ULF_DISPLAY_URL; ?>/";
/* ]]> */
</script>
<div class="ulf_form_box">
	<div class="comment_title">
		<h3>Submit News</h3>
	</div>
	<form class="commentform" method="post" action="<?php echo get_permalink(); ?>">
		<?php if (is_array($status)) : ?>
		<div class="ulf_errors">
			<p>Sorry, there were errors in your submission. Please try again.</p>
			<ul>
			<?php foreach($status as $error) : ?>
				<li><?php echo $error; ?></li>
			<?php endforeach; ?>
			</ul>
		</div>
		<br/>
		<?php endif; ?>
		<?php if ($status === TRUE) : ?>
		<p class="ulf_success">
			Your news successfully submited and waiting for moderation.
		</p>
		<?php endif; ?>
		<?php unset($_SESSION['ulf_form_status']); ?>
		<label for="ulf_news">News</label>
		<br/>
		<textarea id="ulf_news" tabindex="1" name="ulf_news" ></textarea>
		<table id="ulf_url_container_form" class="ulf_url_container_form" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td>
					<p id="ulf_loading" style="display:none;">Please wait while fetching your link information......&nbsp;&nbsp;</p>
					<input id="ulf_url" type="text" value="http://" name="ulf_url" placeholder="http://" title="http://" />
				</td>
				<td>
					<input id="ulf_url_attach" type="button" value="Attach"/>
				</td>
			</tr>
		</table>
		<table id="ulf_url_attach_container" class="ulf_url_attach_container" width="100%" cellspacing="0" cellpadding="0" style="display:none;">
			<tr>
				<td>
					<div id="ulf_attached_images" class="ulf_attached_images"></div>
				</td>
				<td>
					<p>
						<span id="ulf_attached_url_title" class="ulf_attached_url_title"></span>
						<a class="ulf_change_link" href="javascript:;" title="change" onclick="javascript:ulf_change_link();">
							<img src="<?php echo ULF_DISPLAY_URL; ?>/delete.png" alt="change" />
						</a>
					</p>
					<hr style="clear:both;" />
					<p id="ulf_attached_url" class="ulf_attached_url"></p>
					<p id="ulf_attached_description" class="ulf_attached_description"></p>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<a id="ulf_prev_image" href="javascript:;" title="previous image" onclick="javascript:ulf_prev_image();">
						<img src="<?php echo ULF_DISPLAY_URL; ?>/arrow_left.png" alt="previous" />
					</a>
					<a id="ulf_next_image" href="javascript:;" title="next image" onclick="javascript:ulf_next_image();">
						<img src="<?php echo ULF_DISPLAY_URL; ?>/arrow_right.png" alt="next" />
					</a>
					<input type="checkbox" id="ulf_without_image" name="ulf_without_image" value="1" style="width:20px;" onclick="javascript:ulf_without_image_func(this);" />
					<label for="ulf_without_image">without image</label>
				</td>
			</tr>
		</table>
		<?php if ( ! is_user_logged_in() ) : ?>
		<p>
			<label for="name">Your Name : </label>
			<br/>
			<input id="name" type="text" tabindex="3" value="" name="ulf_name"/>
		</p>
		<p>
			<label for="email">Your Email : </label>
			<br/>
			<input id="email" type="text" tabindex="4" value="" name="ulf_email"/>
		</p>
		<?php endif; ?>
		<p>
			<?php echo $recaptcha_html ?>
		</p>
		<br/>
		<p>
			<input id="ulf_title" type="hidden" value="" name="ulf_title" />
			<input id="ulf_image" type="hidden" value="" name="ulf_image" />
			<textarea id="ulf_description" name="ulf_description" style="display:none;"></textarea>
			<input id="submit-comment" type="submit" tabindex="6" value="Submit" name="ulf_submit"/>
			<?php wp_nonce_field('ulf_nonce', 'ulf_nonce'); ?>
		</p>
		<div class="clear"></div>
	</form>
</div>
<?php endif; ?>