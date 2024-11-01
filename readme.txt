=== User Link Feed ===
Contributors: Sulaeman
Donate link: http://www.feelinc.me/
Tags: user, link, feed, plugin
Requires at least: 2.8.x
Tested up to: 2.9.1
Stable tag: 1.2.2

== Description ==

User Link Feed enables user blog to contribute link feeds include an image fetched for the link.
Title, Description, and Images are fetched directly from the link source in realtime.
The user interaction when contribute the link is like facebook link share, user can choose which images to be use that fetched.

Features :

* Fetch Title, Description, and Images directly from the link source in realtime.
* User choose image to be use.
* reCaptcha for blocking spam.
* Pagination Feed List.
* Specify number of feed to display per page.
* Enable / Disable unregistered user to submit a link feed.
* Feed List on Admin panel using Wordpres Default Grid View.
* Sort Feed List by All/New/Approved on Admin panel.
* Pagination Feed List on Admin Panel.
* Admin Approval.
* Delete Feed on Admin Panel.
* Feed List Sidebar Widget
* New 5 Link Feed Dashboard Widget
* If you want to customize the styles/user-link-feed.css stylesheet, you can place it in your active theme folder, and User Link Feed will find it there (that way you won't lose your stylesheet customizations when upgrading User Link Feed).

== Screenshots ==

1. This screen shot is the User Link Feed Page
2. This screen shot is the User Link Feed Form
3. This screen shot is the User Link Feed List Sidebar Widget
4. This screen shot is the User Link Feed List Panel
5. This screen shot is the User Link Feed Options Panel

== Installation == 

Download the zip file, unzip it, and copy the "user-link-feed" folder to your plugins directory. Then activate it from your plugin panel. After successful activation, User Link Feed will appear under your "Settings" tab. Note that User Link Feed requires WordPress 2.8.x or higher.

Putting User Link Feed on a page ?
Just put the shortcode tag:

[userlinkfeed]

Putting User Link Feed Form on a page ?
Just put the shortcode tag:

[userlinkfeedform]

if you put those two shortcode tag in one page, then use enter to separate them.

== Changelog ==

= 1.2.1 =
* Fetch from meta description if not found in body

= 1.2.0 =
* Fetch Title, Description, and Images directly from the link source in realtime.
* User choose image to be use.

= 1.1.2 =
* add new 5 feed list dashboard widget
* fix url existance checking

= 1.1.1 =
* fix getting 404 when user is not logged in
* add feed icon on feed list
* add xml feed format

= 1.1.0 =
* separate shortcode tag for userlinkfeed (feed list) and userlinkfeedform (feed form)
* add sidebar widget for feed list
* add url existance checking

== Frequently Asked Questions ==

Before you can use User Link Feed, you need to fill out its Settings form. First, you will need a reCAPTCHA API key. If you are already using the WP-reCAPTCHA comments plugin, then you don't need another key (you'll see the form is pre-filled with your existing key). If you're not using WP-reCAPTCHA, then follow the link on the Settings form to get a key. Then the only other requirement is that you provide the email address where you'd like the form submissions to go. All the other settings include comments explaining what they do.

Putting User Link Feed on a page ?
Just put the shortcode tag:

[userlinkfeed]

Putting User Link Feed Form on a page ?
Just put the shortcode tag:

[userlinkfeedform]

on a page, and that's where the User Link Feed will appear.
That's all