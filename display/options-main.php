<?php
/**
 * This file is part of User Link Feed. Please see the user-link-feed.php file for copyright
 *
 * @author Sulaeman
 * @version 1.0.1
 * @package user-link-feed
 */
?>
<style>
.unapproved th,
.unapproved td {
	background-color:#FFFFE0;
}
</style>
<div class="wrap">
	<div id="icon-edit" class="icon32"><br/></div>
	<h2>User Link Feed Options</h2>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="9713025">
		Support User Link Feed &raquo; <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="Support User Link Feed using PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
	</form>
	<?php if (strlen($message)) : ?>
		<div id="message" class="updated fade"><p><?php echo $message; ?></p></div>
	<?php unset($message); ?>
	<?php endif; ?>
	<form name="options" action="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=user-link-feed/user-link-feed-class.php" method="post">
		<div id="poststuff" class="metabox-holder">
			<div id="post-body">
				<div id="post-body-content">
					<div id="normal-sortables" class="meta-box-sortables ui-sortable">
						<div class="postbox ">
							<div class="handlediv" title="Click to toggle"><br/></div>
							<h3 class="hndle">
								<span>Options</span>
							</h3>
							<div class="inside">
								<p>
									<input id="registered_user" class="code" type="checkbox" name="ulf_options[registered_user]" value="1" <?php echo ($ulf_options['registered_user'] == 0) ? 'checked="checked"' : ''; ?>/>
									<label for="registered_user"><strong>allow unregistered user to post a feed</strong></label>
								</p>
								<br/>
								<p>
									<label for="max_feed_per_page"><strong>Max Feed Entries Per Page : </strong></label>
									<input id="max_feed_per_page" class="code" type="text" name="ulf_options[max_feed_per_page]" value="<?php echo $ulf_options['max_feed_per_page']; ?>"/>
								</p>
								<br/>
								<p>
									<label for="recaptcha_public_key"><strong>reCAPTCHA public key : </strong></label>
									<input id="recaptcha_public_key" class="code" type="text" name="recaptcha[pubkey]" size="50" value="<?php echo $recaptcha_options['pubkey']; ?>"/>
								</p>
								<br/>
								<p>
									<label for="recaptcha_private_key"><strong>reCAPTCHA private key : </strong></label>
									<input id="recaptcha_private_key" class="code" type="text" name="recaptcha[privkey]" size="50" value="<?php echo $recaptcha_options['privkey']; ?>"/>
								</p>
								<br/>
								<p>
									If you are already using the WP-reCAPTCHA plugin for comments, Deko Boko will use the API key you've already set. If you are not using the WP-reCAPTCHA plugin, then you need to get a <a target="_blank" href="http://recaptcha.net/api/getkey?domain=&app=wordpress">free reCAPTCHA API key for your site</a> and enter the public and private keys here.
								</p>
								<br/>
								<p>
									<label for="recaptcha_theme_key"><strong>reCAPTCHA theme : </strong></label>
									<select name="ulf_options[recaptcha_theme]">
										<option value="red" <?php if ($ulf_options['recaptcha_theme'] == 'red') echo ' selected="selected"'; ?>>red</option>
										<option value="white" <?php if ($ulf_options['recaptcha_theme'] == 'white') echo ' selected="selected"'; ?>>white</option>
										<option value="blackglass" <?php if ($ulf_options['recaptcha_theme'] == 'blackglass') echo ' selected="selected"'; ?>>blackglass</option>
										<option value="clean" <?php if ($ulf_options['recaptcha_theme'] == 'clean') echo ' selected="selected"'; ?>>clean</option>
										<option value="custom" <?php if ($ulf_options['recaptcha_theme'] == 'custom') echo ' selected="selected"'; ?>>custom</option>
									</select>
								</p>
								<br/>
								<p>
									<label for="recaptcha_theme_key"><strong>reCAPTCHA language : </strong></label>
									<select name="ulf_options[recaptcha_lang]">
										<option value="en" <?php if ($ulf_options['recaptcha_lang'] == 'en') echo 'selected="selected"'; ?>>English</option>
										<option value="nl" <?php if ($ulf_options['recaptcha_lang'] == 'nl') echo 'selected="selected"'; ?>>Dutch</option>
										<option value="fr" <?php if ($ulf_options['recaptcha_lang'] == 'fr') echo 'selected="selected"'; ?>>French</option>
										<option value="de" <?php if ($ulf_options['recaptcha_lang'] == 'de') echo 'selected="selected"'; ?>>German</option>
										<option value="pt" <?php if ($ulf_options['recaptcha_lang'] == 'pt') echo 'selected="selected"'; ?>>Portuguese</option>
										<option value="ru" <?php if ($ulf_options['recaptcha_lang'] == 'ru') echo 'selected="selected"'; ?>>Russian</option>
										<option value="es" <?php if ($ulf_options['recaptcha_lang'] == 'es') echo 'selected="selected"'; ?>>Spanish</option>
										<option value="tr" <?php if ($ulf_options['recaptcha_lang'] == 'tr') echo 'selected="selected"'; ?>>Turkish</option>
									</select>
								</p>
								<br/>
								<p>
									'Red' is the default theme. Activating the 'clean' theme allows you to adjust the reCAPTCHA widget's colors - see user-link-feed.css for the classes. <a target="_blank" href="http://wiki.recaptcha.net/index.php/Theme">You can preview the themes here</a>. 'Custom' is for advanced users only, who want to change the layout of the reCAPTCHA widget - see the <a target="_blank" href="http://recaptcha.net/apidocs/captcha/client.html">reCAPTCHA Client API Documentation</a>.
								</p>
							</div>
							<div id="major-publishing-actions">
								<div id="delete-action"> </div>
								<div id="publishing-action">
									<input class="button-primary" type="submit" value="Save" name="ulf_save_options" />
								</div>
								<div class="clear"></div>
							</div>
						</div><!-- postbox -->
					</div>
				</div>
			</div>
		</div>
	<form>
	<form name="feeds" action="" method="post">
		<ul class="subsubsub">
			<li>
				<a <?php echo (!isset($_GET['ulf_view'])) ? 'class="current"' : ''; ?> href="options-general.php?page=user-link-feed/user-link-feed-class.php">
					All
					<span class="count">(<?php echo $n_all_feed; ?>)</span>
				</a>
				|
			</li>
			<li>
				<a <?php echo (isset($_GET['ulf_view']) && $_GET['ulf_view'] == 'new') ? 'class="current"' : ''; ?> href="options-general.php?page=user-link-feed/user-link-feed-class.php&ulf_view=new">
					New
					<span class="count">(<?php echo $n_new_feed; ?>)</span>
				</a>
				|
			</li>
			<li>
				<a <?php echo (isset($_GET['ulf_view']) && $_GET['ulf_view'] == 'approved') ? 'class="current"' : ''; ?> href="options-general.php?page=user-link-feed/user-link-feed-class.php&ulf_view=approved">
					Approved
					<span class="count">(<?php echo $n_approved_feed; ?>)</span>
				</a>
			</li>
		</ul>
		<?php if ( $page_links ) : ?>
		<div class="tablenav">
			<div class="tablenav-pages">
			<?php
			$page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
			number_format_i18n( ( $_GET['paged'] - 1 ) * $wp_query->query_vars['posts_per_page'] + 1 ),
			number_format_i18n( min( $_GET['paged'] * $wp_query->query_vars['posts_per_page'], $wp_query->found_posts ) ),
			number_format_i18n( $wp_query->found_posts ),
			$page_links
			); echo $page_links_text;
			?>
			</div>
		</div>
		<?php endif; ?>
		<div class="clear"></div>
		<table class="widefat post fixed" cellspacing="0">
			<thead>
				<tr>
					<th id="cb" class="manage-column column-cb check-column" style="" scope="col">
						<input type="checkbox" />
					</th>
					<th id="title" class="manage-column column-title" style="" scope="col">News</th>
					<th id="author" class="manage-column column-author" style="" scope="col">Author</th>
					<th id="url" class="manage-column column-comment" style="" scope="col">Comment</th>
					<th id="date" class="manage-column column-date" style="" scope="col">Date</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th id="cb" class="manage-column column-cb check-column" style="" scope="col">
						<input type="checkbox" />
					</th>
					<th id="title" class="manage-column column-title" style="" scope="col">News</th>
					<th id="author" class="manage-column column-author" style="" scope="col">Author</th>
					<th id="url" class="manage-column column-comment" style="" scope="col">Comment</th>
					<th id="date" class="manage-column column-date" style="" scope="col">Date</th>
				</tr>
			</tfoot>
			<?php if (is_array($feeds) && count($feeds) > 0) : ?>
			<tbody>
				<?php foreach($feeds as $feed) : ?>
				<?php $feed_id = $feed['meta_id']; ?>
				<?php $feed_value = unserialize($feed['meta_value']); ?>
				<tr id="feed-<?php echo $feed_id; ?>" class="<?php echo ($feed_value['approved'] == 0) ? 'unapproved' : '' ?>" valign="top">
					<th class="check-column" scope="row">
						<input type="checkbox" value="1" name="feed[]"/>
					</th>
					<td class="post-title column-title">
						<strong>
							<a class="row-title" href="<?php echo stripslashes(str_replace('\"', '"', $feed_value['url'])); ?>" target="_blank"><?php echo stripslashes(str_replace('\"', '"', $feed_value['title'])); ?></a>
						</strong>
						<p>
							<?php if (isset($feed_value['image']) && $feed_value['image'] != '') : ?>
							<img src="<?php echo $feed_value['image']; ?>" width="100" height="100" />
							<?php endif; ?>
							<?php echo stripslashes(preg_replace(array('@(\")+@', '@([\r\n])+@'), array('"', '<br/><br/>'), $feed_value['description'])); ?>
						</p>
						<div class="row-actions">
							<?php if ($feed_value['approved'] == 0) : ?>
							<span>
								<a href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=user-link-feed/user-link-feed-class.php&ulf_action=approve&ulf_id=<?php echo $feed_id; ?><?php echo (isset($_GET['ulf_paged']) && !empty($_GET['ulf_paged'])) ? '&ulf_paged=' . $_GET['paged'] : ''; ?>" title="Approve this feed">Approve</a>
								|
							</span>
							<?php endif; ?>
							<span class="delete">
								<a class="submitdelete" onclick="if ( confirm('You are about to delete this feed\n \'Cancel\' to stop, \'OK\' to delete.') ) { return true;}return false;" href="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=user-link-feed/user-link-feed-class.php&ulf_action=delete&ulf_id=<?php echo $feed_id; ?><?php echo (isset($_GET['ulf_paged']) && !empty($_GET['ulf_paged'])) ? '&ulf_paged=' . $_GET['paged'] : ''; ?>" title="Delete this feed">Delete</a>
							</span>
						</div>
					</td>
					<td class="author column-author">
						<?php echo stripslashes(str_replace('\"', '"', $feed_value['name'])); ?>
						<br/>
						<?php echo stripslashes(str_replace('\"', '"', $feed_value['email'])); ?>
					</td>
					<td class="column-comment">
						<?php if (isset($feed_value['news']) && $feed_value['news'] != '') : ?>
						<?php echo stripslashes(preg_replace(array('@(\")+@', '@([\r\n])+@'), array('"', '<br/><br/>'), $feed_value['news'])); ?>
						<?php else : ?>
						<?php echo stripslashes(str_replace('\"', '"', $feed_value['url'])); ?>
						<?php endif; ?>
					</td>
					<td class="date column-date">
						<?php echo date('Y-m-d h:i:s A', $feed_value['date']); ?>
						<br/>
						Submited
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<?php endif; ?>
		</table>
	</form>
</div>