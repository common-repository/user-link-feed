<?php
/**
 * This file is part of User Link Feed. Please see the user-link-feed.php file for copyright
 *
 * @author Sulaeman
 * @version 1.0.1
 * @package user-link-feed
 */

if (!function_exists('add_action'))
{
	require_once('../../../../wp-config.php');
}

$ulf_url = (isset($_POST['ulf_url']) && !empty($_POST['ulf_url'])) ? $_POST['ulf_url'] : '';

if (!preg_match('/'.$_SERVER['HTTP_HOST'].'/i', $_SERVER['HTTP_REFERER']))
{
	echo 'WHO ARE YOU !!!';
	exit();
}

require_once('../include/sasl/sasl.php');
require_once('../include/http.php');
require_once('../include/Services_JSON.php');

$json = new Services_JSON;

set_time_limit(0);
$http = new http_class;

/* Connection timeout */
$http->timeout = 0;

/* Data transfer timeout */
$http->data_timeout = 600;

/* Output debugging information about the progress of the connection */
$http->debug = 0;

/* Format dubug output to display with HTML pages */
$http->html_debug = 0;

/*
*  Need to emulate a certain browser user agent?
*  Set the user agent this way:
*/
$http->user_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)';

/*
*  If you want to the class to follow the URL of redirect responses
*  set this variable to 1.
*/
$http->follow_redirect = 1;

/*
*  How many consecutive redirected requests the class should follow.
*/
$http->redirection_limit = 5;

/*
*  If your DNS always resolves non-existing domains to a default IP
*  address to force the redirection to a given page, specify the
*  default IP address in this variable to make the class handle it
*  as when domain resolution fails.
*/
$http->exclude_address = '';

/*
*  Generate a list of arguments for opening a connection and make an
*  HTTP request from a given URL.
*/
$error = $http->GetRequestArguments($ulf_url, $arguments);

/* Set additional request headers */
$arguments['Headers']['Pragma'] = 'nocache';

flush();
$error = $http->Open($arguments);

$body_doc = '';

if($error == '')
{
	flush();
	$error = $http->SendRequest($arguments);
	if($error == '')
	{
		flush();
		$error = $http->ReadReplyHeaders($headers);
		if($error == '')
		{
			flush();
			for(;;)
			{
				$error = $http->ReadReplyBody($body,1000);
				if($error != '' || strlen($body) == 0)
				{
					break;
				}
				$body_doc .= $body;
			}
			
			if ($body_doc != '')
			{
				
				$images = array();
				
				$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $body_doc, $matches);
				
				if (is_array($matches) && count($matches) > 0)
				{
					$images = $matches[1];
				}
				unset($matches);
				
				$title = 'no title';
				if(preg_match('/<title ?.*>(.*)<\/title>/', $body_doc, $matches))
				{
					$title = $matches[1];
				}
				unset($matches);
				
				if (preg_match('/<p>(.*)<\/p>/', $body_doc, $matches))
				{
					$description = $matches[1];
				}
				unset($matches);
				
				if (!$description)
				{
					if (preg_match('/<meta.+name="description".+content="(.*)"/', $body_doc, $matches))
					{
						$description = $matches[1];
					}
					unset($matches);
				}
				
				$payload = array(
					'url' => ((strlen($ulf_url) > 60) ? substr($ulf_url, 0, 60) . '...' : $ulf_url),
					'title' => strip_tags($title),
					'description' => substr(strip_tags($description), 0, 145),
					'images' => $images
				);
				
				echo '{"error":{"code":0,"description":""},"payload":'.$json->encode($payload).'}';
				
				flush();
				$http->Close();
				
				exit();
				
			}
		}
	}
	
	$http->Close();
}

echo '{"error":{"code":1,"description":"GEHEL"}}';

?>