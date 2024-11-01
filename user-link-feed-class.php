<?php
/**
 * This file is part of User Link Feed. Please see the user-link-feed.php file for copyright
 *
 * @author Sulaeman
 * @version 1.2.0
 * @package user-link-feed
 */

class User_Link_Feed
{
	/**
     * Called automatically to register hooks and
     * add the actions and filters.
     *
     * @static
     * @access public
     */
	function bootstrap()
	{
		global $wpdb;
		
		// Add the actions and filters
		add_action('admin_menu', array(ULF_PLUGIN_NAME, 'admin_menu'));
		add_shortcode('userlinkfeed', array(ULF_PLUGIN_NAME, 'get_link_feeds'));
		add_shortcode('userlinkfeedform', array(ULF_PLUGIN_NAME, 'get_link_feed_form'));
		add_action('template_redirect', array(ULF_PLUGIN_NAME, 'get_head_tags'));
		add_action('plugins_loaded', array(ULF_PLUGIN_NAME, 'setup_widget'));
		
		// Hook to register new widgets
		if (function_exists('wp_add_dashboard_widget'))
		{
			do_action('wp_dashboard_setup');
			wp_add_dashboard_widget('ulf_dashboard_widget', 'New User Link Feed', array(ULF_PLUGIN_NAME, 'dashboard_feed_list'));
		}
		
		// check for previously installed version 1.0.0
		$meta_id = $wpdb->get_var('SELECT meta_id FROM ' . $wpdb->postmeta . ' WHERE meta_key LIKE "%ulf_%" LIMIT 1');
		if ($meta_id > 0)
		{
			User_Link_Feed::update_data();
		}
	}
	
	function admin_menu()
	{
		// Add the options page
		add_options_page(ULF_DISPLAY_NAME, ULF_DISPLAY_NAME, 6, __FILE__, array(ULF_PLUGIN_NAME, 'get_options_menu'));
	}
	
	function get_options_menu()
	{
		global $wpdb;
		
		$ulf_options = unserialize(get_option('ulf_options'));
		$recaptcha_options = get_option('recaptcha');
		
		$max_feeds_per_page = 10;
		$current_page = (isset($_GET['ulf_paged']) && !empty($_GET['ulf_paged'])) ? $_GET['ulf_paged'] : 1;
		$view = (isset($_GET['ulf_view']) && !empty($_GET['ulf_view'])) ? $_GET['ulf_view'] : 'all';
		
		// action feed
		if (isset($_REQUEST['ulf_action']) && $_REQUEST['ulf_action'] != '')
		{
			$feed = $wpdb->get_row('SELECT * FROM ' . $wpdb->postmeta . ' WHERE meta_id = '.$_REQUEST['ulf_id'].' LIMIT 1', ARRAY_A);
			if (is_array($feed) && count($feed))
			{
				switch($_REQUEST['ulf_action'])
				{
					case 'approve':
						$feed_value = unserialize($feed['meta_value']);
						$feed_value = array_merge($feed_value, array('approved' => "1"));
						$wpdb->update($wpdb->postmeta, array('meta_value' => serialize($feed_value)), array('meta_id' => $_REQUEST['ulf_id']));
					break;
					case 'delete':
						$wpdb->query('DELETE FROM ' . $wpdb->postmeta . ' WHERE meta_id = ' . $_REQUEST['ulf_id']);
					break;
				}
			}
			
			?>
			<script type="text/javascript">/* <![CDATA[ */
			window.location.href = "<?php echo get_option('siteurl') . '/wp-admin/options-general.php?page=user-link-feed/user-link-feed-class.php&ulf_paged=' . $current_page . (($view != 'all') ? '&ulf_view=' . $view : ''); ?>";
			/* ]]> */</script>
			<?php
			exit();
		}
		
		$n_all_feed = $wpdb->get_var('SELECT count(meta_key) as n_new_feed FROM ' . $wpdb->postmeta . ' WHERE meta_key = "ulf" LIMIT 1');
		
		// get feeds
		switch(strtolower($view))
		{
			case 'new':
				$n_feed = $wpdb->get_var('SELECT count(meta_key) as n_new_feed FROM ' . $wpdb->postmeta . ' WHERE meta_key = "ulf" AND meta_value LIKE "%\"approved\";s:1:\"0\";%" LIMIT 1');
				$feeds = $wpdb->get_results('SELECT meta_id, meta_key, meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "ulf" AND meta_value LIKE "%\"approved\";s:1:\"0\";%" ORDER BY meta_id DESC LIMIT ' . (($current_page - 1) * $max_feeds_per_page) . ', ' . $max_feeds_per_page, ARRAY_A);
			break;
			case 'approved':
				$n_feed = $wpdb->get_var('SELECT count(meta_key) as n_new_feed FROM ' . $wpdb->postmeta . ' WHERE meta_key = "ulf" AND meta_value LIKE "%\"approved\";s:1:\"1\";%" LIMIT 1');
				$feeds = $wpdb->get_results('SELECT meta_id, meta_key, meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "ulf" AND meta_value LIKE "%\"approved\";s:1:\"1\";%" ORDER BY meta_id DESC LIMIT ' . (($current_page - 1) * $max_feeds_per_page) . ', ' . $max_feeds_per_page, ARRAY_A);
			break;
			default:
				$n_feed = $n_all_feed;
				$feeds = $wpdb->get_results('SELECT meta_id, meta_key, meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "ulf" ORDER BY meta_id DESC LIMIT ' . (($current_page - 1) * $max_feeds_per_page) . ', ' . $max_feeds_per_page, ARRAY_A);
			break;
		}
		
		// count new feeds
		$n_new_feed = $wpdb->get_var('SELECT count(meta_key) as n_new_feed FROM ' . $wpdb->postmeta . ' WHERE meta_key = "ulf" AND meta_value LIKE "%\"approved\";s:1:\"0\";%" LIMIT 1');
		
		// count approved feeds
		$n_approved_feed = $wpdb->get_var('SELECT count(meta_key) as n_approved_feed FROM ' . $wpdb->postmeta . ' WHERE meta_key = "ulf" AND meta_value LIKE "%\"approved\";i:1;%" LIMIT 1');
		
		$page_links = paginate_links( array(
			'base' => add_query_arg( (($view != 'all') ? 'ulf_view=' . $view . '&' : '').'ulf_paged', '%#%' ),
			'format' => '',
			'prev_text' => __('&laquo;'),
			'next_text' => __('&raquo;'),
			'total' => ceil($n_feed/$max_feeds_per_page),
			'current' => $current_page
		));
		
        // Start the cache
        ob_start();
		
		if (isset($_REQUEST['ulf_save_options']) && $_REQUEST['ulf_save_options'] != '')
		{
			array_walk($_REQUEST['ulf_options'], array(ULF_PLUGIN_NAME, '_trim'));
			array_walk($_REQUEST['recaptcha'], array(ULF_PLUGIN_NAME, '_trim'));
			
			if (@$_REQUEST['ulf_options']['registered_user'])
			{
				$_REQUEST['ulf_options']['registered_user'] = 0;
			}
			else
			{
				$_REQUEST['ulf_options']['registered_user'] = 1;
			}
			
			$ulf_options = array_merge($ulf_options, $_REQUEST['ulf_options']);
			update_option('ulf_options', serialize($ulf_options));

			if (is_array($recaptcha_options))
			{
				$recaptcha_options = array_merge($recaptcha_options, $_REQUEST['recaptcha']);
			}
			else
			{
				$recaptcha_options = $_REQUEST['recaptcha'];
			}

            update_option('recaptcha', $recaptcha_options);
			
			$message = 'User Link Feed settings saved.';
		}
		
		// Get the markup and display
        require(ULF_DIR . '/display/options-main.php');
        $options_form = ob_get_contents();
        ob_end_clean();
        echo $options_form;
	}
	
	/**
     * Gets the User Link Feed stylesheet. Loads it only on pages where needed, and
     * allows for a custom stylesheet in the active theme folder.
     *
     * @static
     * @access public
     */
    function get_head_tags() {
		if (file_exists(TEMPLATEPATH . '/styles/user-link-feed.css'))
		{
			$ulf_css = get_bloginfo('template_directory') . '/styles/user-link-feed.css';
		}
		else
		{
			$ulf_css = ULF_DISPLAY_URL . '/user-link-feed.css';
		}

		wp_enqueue_style('user-link-feed_css', $ulf_css, false, ULF_VERSION);
		wp_enqueue_script('jquery');
		wp_enqueue_script('user-link-feed_js', ULF_DISPLAY_URL . '/user-link-feed.js', false, ULF_VERSION);
    }
	
	/**
     * Generates and returns the HTML for the link feed list page.
     *
     * @static
     * @access public
     * @returns string HTML for link feed list
     */
	function get_link_feeds($attributes)
	{
		extract(shortcode_atts(array('template' => ULF_LIST_TEMPLATE), $attributes));
		$ulf_options = unserialize(get_option('ulf_options'));
		array_walk($ulf_options, array(ULF_PLUGIN_NAME, '_stripslashes'));
		
		return User_Link_Feed::get_link_feed_list($template, $ulf_options);
	}
	
	function get_link_feed_list($template, $options, $approved = TRUE, $use_pagination = TRUE)
	{
		global $wpdb, $query_string;
		
		$paged = User_Link_Feed::extractArgument($query_string, 'ulf_paged');
		$max_feed_per_page = $options['max_feed_per_page'];
		$curr_feed_page = (isset($paged) && $paged > 0) ? $paged : 1;
		
		$status_query = '\"approved\";s:1:\"1\";';
		if (!$approved)
		{
			$status_query = '\"approved\";s:1:\"0\";';
		}
		
		$n_feed = $wpdb->get_var('SELECT count(meta_key) as n_new_feed FROM ' . $wpdb->postmeta . ' WHERE meta_key = "ulf" AND meta_value LIKE "%'.$status_query.'%" LIMIT 1');
		$feeds = $wpdb->get_results('SELECT meta_key, meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key = "ulf" AND meta_value LIKE "%'.$status_query.'%" ORDER BY meta_id DESC LIMIT ' . (($curr_feed_page - 1) * $max_feed_per_page) . ', ' . $max_feed_per_page, ARRAY_A);
		
		if ($use_pagination)
		{
			$pagination = new User_Link_Feed_Pagination();
			$pagination->Items($n_feed);
			$pagination->limit($max_feed_per_page);
			$pagination->adjacents(1);
			$pagination->currentPage($curr_feed_page);
			$pagination->parameterName('ulf_paged');
			$pagination->target(get_permalink());
			$pagination->nextLabel('Next');
			$pagination->prevLabel('Prev');
			$pagination->nextIcon('');
			$pagination->prevIcon('');
			$pagination->changeClass('ulf_pagination');
		}
		
		ob_start();
		require ULF_DIR . '/display/' . $template;
		$output = ob_get_contents();
        ob_end_clean();

        return $output;
	}
	
	/**
     * Generates and returns the HTML for the link feed form page.
     *
     * @static
     * @access public
     * @returns string HTML for link feed form
     */
	function get_link_feed_form($attributes)
	{
		global $wpdb, $current_user;
		
		$status = FALSE;
		
		extract(shortcode_atts(array('template' => ULF_FORM_TEMPLATE), $attributes));
		
		$ulf_options = unserialize(get_option('ulf_options'));
		array_walk($ulf_options, array(ULF_PLUGIN_NAME, '_stripslashes'));
		$recaptcha_options = get_option('recaptcha');
		
		// don't try to include the recaptcha lib if it's already been included
		// by another plugin - if you have recaptcha_get_html then you'll have
		// the other functions in recaptchalib too.
		if (!function_exists('recaptcha_get_html'))
		{
			require_once(ULF_DIR . '/recaptcha/recaptchalib.php');
		}
		
		// if the form has been submitted, check to make sure it's safe to send
		if (isset($_POST['ulf_submit']))
		{
			$status = User_Link_Feed::checkFeed($ulf_options, $recaptcha_options);
		}
		
		if ($status === TRUE)
		{
			get_currentuserinfo();
			
			// check is url already exist
			$meta_id = $wpdb->get_var('SELECT meta_id FROM ' . $wpdb->postmeta . ' WHERE meta_key = "ulf" AND meta_value like "%\"'.mysql_real_escape_string($_POST['ulf_url']).'\";%" LIMIT 1');
			if ($meta_id == null)
			{
				// collecting input data
				$form_input = array(
					'news' => User_Link_Feed::escape_data($_POST['ulf_news']),
					'title' => mysql_real_escape_string($_POST['ulf_title']),
					'url' => mysql_real_escape_string($_POST['ulf_url']),
					'description' => User_Link_Feed::escape_data($_POST['ulf_description']),
					'image' => ((@$_POST['ulf_without_image']) ? '' : mysql_real_escape_string($_POST['ulf_image'])),
					'date' => strtotime(date('Y-m-d h:i:s A')),
					'approved' => 0
				);
				
				// if not logged in user
				if ( ! is_user_logged_in() )
				{
					$form_input = array_merge($form_input, array(
						'name' => mysql_real_escape_string($_POST['ulf_name']),
						'email' => mysql_real_escape_string($_POST['ulf_email'])
					));
				}
				else
				{
					$form_input = array_merge($form_input, array(
						'name' => mysql_real_escape_string($current_user->display_name),
						'email' => mysql_real_escape_string($current_user->user_email)
					));
				}
				
				add_post_meta( 0, 'ulf', $form_input );
			}
			else
			{
				$status = array('<strong>Your URL is already exist in our database</strong>');
			}
		}
		
		ob_start();
		
		$js_opts = "
			<script type='text/javascript'>
			var RecaptchaOptions = { theme : '"
			. $ulf_options['recaptcha_theme'] . "', lang : '"
			. $ulf_options['recaptcha_lang'] . "' };
			</script>
		";
		
		$use_ssl = FALSE;
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on")
		{
			$use_ssl = TRUE;
		}

		$recaptcha_html = $js_opts . recaptcha_get_html($recaptcha_options['pubkey'], null, $use_ssl);
		require ULF_DIR . '/display/' . $template;
		
		$output = ob_get_contents();
        ob_end_clean();

        return $output;
	}
	
	/**
     * Checks the re-captcha response and checks for bad or malicious data
     * submissions.
     *
     * @static
     * @access public
     * @returns boolean|array true if message is safe; array of error messages if not
     */
	function checkFeed($ulf_options, $recaptcha_options)
	{
		$errors = array();

		$resp = recaptcha_check_answer($recaptcha_options['privkey'], $_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

		if (!$resp->is_valid)
		{
			$errors[] = '<strong>ReCAPTCHA error</strong> your captcha response was incorrect - please try again';
		}

		if (!wp_verify_nonce($_POST['ulf_nonce'], 'ulf_nonce'))
		{
			$errors[] = '<strong>Invalid Nonce</strong>';
		}
		
		if (!strlen($_POST['ulf_title']))
		{
			$errors[] = 'Required field <strong>TItle</strong> is blank';
		}
		
		if (!strlen($_POST['ulf_url']))
		{
			if (!User_Link_Feed::check_url($_POST['ulf_url']))
			{
				$errors[] = 'Required field <strong>URL</strong> is not a valid url address';
			}
		}
		
		if ( ! is_user_logged_in() )
		{
			if (!strlen($_POST['ulf_name']))
			{
				$errors[] = 'Required field <strong>Name</strong> is blank';
			}
			
			if (!strlen($_POST['ulf_email']))
			{
				if (!User_Link_Feed::check_email($_POST['email']))
				{
					$errors[] = 'Required field <strong>Email</strong> is not a valid email address';
				}
			}
		}
		
		if (!strlen($_POST['ulf_description']))
		{
			$errors[] = 'Required field <strong>Description</strong> is blank';
		}

		if (!empty($errors)) {
			return $errors;
		}

		return true;
	}
	
	function dashboard_feed_list()
	{
		$options = array();
		$options['max_feed_per_page'] = 5;
		$options['show_description'] = 0;
		$options['view_all_url'] = 1;
		echo User_Link_Feed::get_link_feed_list('dashboard/' . ULF_NEW_LIST_TEMPLATE, $options, FALSE, FALSE);
	}
	
	function setup_widget()
	{
		if (!function_exists('register_sidebar_widget'))
		{
			return;
		}
		
		register_sidebar_widget('User Link Feed List', array(ULF_PLUGIN_NAME, 'widget_feed_list'));
		register_widget_control('User Link Feed List', array(ULF_PLUGIN_NAME, 'widget_feed_list_control'));
	}
	
	function widget_feed_list($args)
	{
		extract($args);
		$options = get_option('ulf_widget_feeds_options');
		$title = $options['title'];
		echo $before_widget . $before_title . $title . $after_title;
		echo User_Link_Feed::get_link_feed_list('widget/' . ULF_LIST_TEMPLATE, $options, TRUE, FALSE);
		echo $after_widget;
	}
	
	function widget_feed_list_control()
	{
		$options = get_option('ulf_widget_feeds_options');
		if ( $_POST['ulf_feed_submit'] )
		{
			$options['title'] = strip_tags(stripslashes($_POST['ulf_feed_title']));
			$options['max_feed_per_page'] = intval($_POST['ulf_max_feed']);
			$options['show_description'] = (@$_POST['ulf_show_description']) ? 1 : 0;
			$options['view_all_url'] = strip_tags(stripslashes($_POST['ulf_view_all_url']));
			update_option('ulf_widget_feeds_options', $options);
		}
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$max_feed = htmlspecialchars($options['max_feed_per_page'], ENT_QUOTES);
		$show_description = $options['show_description'];
		$view_all_url = ($options['view_all_url'] != '') ? trailingslashit($options['view_all_url']) : '';
		$settingspage = trailingslashit(get_option('siteurl')).'wp-admin/options-general.php?page=user-link-feed/'.basename(__FILE__);
		
		ob_start();
		require ULF_DIR . '/display/widget/' . ULF_WIDGET_FEED_CONTROL_TEMPLATE;
		$output = ob_get_contents();
        ob_end_clean();

        echo $output;
	}
	
	/**
     * Validates email addresses. Borrowed from
     * http://www.phpbuilder.com/columns/ian_gilfillan20060412.php3
     *
     * @static
     * @access public
     * @returns int|boolean 0 if not a valid email, 1 if it is valid, false on error
     */
    function check_email($email)
	{
        return preg_match('#^[a-z0-9.!\#$%&\'*+-/=?^_`{|}~]+@([0-9.]+|([^\s]+\.+[a-z]{2,6}))$#si', $email);
    }
	
	/**
     * Validates url addresses. Borrowed from
     * http://www.phpcentral.com/208-url-validation-php.html
     *
     * @static
     * @access public
     * @returns int|boolean 0 if not a valid email, 1 if it is valid, false on error
     */
	function check_url($url)
	{
		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}  
	
	function update_data()
	{
		global $wpdb;
		
		$feeds = $wpdb->get_results('SELECT meta_value FROM ' . $wpdb->postmeta . ' WHERE meta_key LIKE "%ulf_%"', ARRAY_A);
		if (is_array($feeds) && count($feeds))
		{
			foreach($feeds as $feed)
			{
				add_post_meta( 0, 'ulf', unserialize($feed['meta_value']) );
			}
			
			$wpdb->query('DELETE FROM ' . $wpdb->postmeta . ' WHERE meta_key LIKE "%ulf_%"');
		}
	}
	
	function install()
	{
		$ulf_options = unserialize(get_option('ulf_options'));
        $ulf_options_defaults = array(
			'max_feed_per_page' => 10,
			'registered_user' => 0,
			'recaptcha_lang' => 'en',
			'recaptcha_theme' => 'red'
        );
		
		// flag whether to add or update options
		$add_options = empty($ulf_options);
		
		// import and delete old-style Deko Boko options if necessary
		foreach ($ulf_options_defaults as $k => $v)
		{
			if (!$ulf_options[$k])
			{
				$ulf_options[$k] = $v;
			}
		}
		
		$ulf_options['version'] = ULF_VERSION;

		if ($add_options === FALSE)
		{
			update_option('ulf_options', serialize($ulf_options));
		}
		else
		{
			add_option('ulf_options', serialize($ulf_options));
		}
	}
	
	function uninstall()
	{
		delete_option('ulf_options');
	}
	
	/**
     * array_walk callback method for htmlentities()
     *
     * @static
     * @access private
     * @param string $string (required): the string to update
     * @param mixed $key (ignored): the array key of the string (not needed but passed automatically by array_walk)
     */
    function _htmlentities(&$string, $key) {
        $string = htmlentities($string, ENT_COMPAT, 'UTF-8');
    }

    /**
     * array_walk callback method for trim()
     *
     * @static
     * @access private
     * @param string $string (required): the string to update
     * @param mixed $key (ignored): the array key of the string (not needed but passed automatically by array_walk)
     */
    function _trim(&$string, $key) {
        $string = trim($string);
    }

    /**
     * array_walk callback method for stripslashes()
     *
     * @static
     * @access private
     * @param string $string (required): the string to update
     * @param mixed $key (ignored): the array key of the string (not needed but passed automatically by array_walk)
     */
    function _stripslashes(&$string, $key) {
        $string = stripslashes($string);
    }
	
	function escape_data($data)
	{
		return strip_tags(addslashes($data));
	}  
	
	function extractArgument($params, $name)
	{
		$ix = -1;
		$iy = -1;
		if (strlen($params) != 0)
		{
			$args = strtolower($params);
			$arg = strtolower($name).'=';
			$ix = strpos($args, $arg);
			if ($ix > 0)
			{
				$ix = $ix + strlen($arg);
				$iy = strpos(substr($args, $ix, strlen($args)), '&');
				if (!$iy)
				{
					$iy = strlen($args);
				}
			}
		}
		return $argument = ($ix > 0) ? substr($params, $ix, $iy) : '';
	}
}

?>
