# Posts in Sidebar #
**Contributors:** aldolat  
**Donate link:** http://dev.aldolat.it/projects/posts-in-sidebar/  
**Tags:** post, sidebar, widget  
**Requires at least:** 3.5  
**Tested up to:** 4.0  
**Stable tag:** 1.21  
**License:** GPLv3 or later  
**License URI:** http://www.gnu.org/licenses/gpl-3.0.html  

This plugin adds a widget to display a list of posts in the WordPress sidebar.

## Description ##

This plugin creates a new widget for your sidebar. In this widget you can display a list of post using author, category, tag, post format, custom post type, and so on. You can also display the featured image, the tags and also a link to the archive page. A bunch of useful options are also available.

### Documentation, Help & Bugs ###

The plugin's documentation is hosted on [GitHub](https://github.com/aldolat/posts-in-sidebar/wiki). Please refer to it before asking for support.

If you need help, please use [WordPress forum](http://wordpress.org/support/plugin/posts-in-sidebar). Do not send private email unless it is really necessary.

If you have found a bug, please report it on [GitHub](https://github.com/aldolat/posts-in-sidebar/issues).

This plugin is developed using [GitHub](https://github.com/aldolat/posts-in-sidebar). If you wrote an enhancement and would share it with the world, please send me a [Pull request](https://github.com/aldolat/posts-in-sidebar/pulls).

### Credits ###

I would like to say *Thank You* to all the people who helped me in making this plugin better.

### Translations of the plugin ###

This plugin has been translated into these languages:

* Persian, thanks to AlirezaJamali
* French, thanks to Thérèse Lachance and [cilya](https://profiles.wordpress.org/cilya)
* Hebrew, thanks to [Ahrale](http://www.atar4u.com)
* Serbo-Croatian, thanks to [Borisa Djuraskovic](http://www.webhostinghub.com/)

## Installation ##

This section describes how to install the plugin and get it working.

1. Upload  the `posts-in-sidebar` directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the Plugins menu in WordPress
1. Go to the widgets manager and add the newly available widget into the sidebar
1. Adjust the options to fit your needs
1. Save and test your results.

## Frequently Asked Questions ##

Please, see [FAQ page](https://github.com/aldolat/posts-in-sidebar/wiki/FAQ) on GitHub.

## Screenshots ##

### 1. The widget panel ###
![The widget panel](https://github.com/aldolat/posts-in-sidebar/wiki/images/screenshot-1.png)

### 2. A simple output of the widget: title, excerpt and link to the entire archive. ###
![A simple output of the widget: title, excerpt and link to the entire archive.](https://github.com/aldolat/posts-in-sidebar/wiki/images/screenshot-2.png)

### 3. Displaying the featured image, floating left. ###
![Displaying the featured image, floating left.](https://github.com/aldolat/posts-in-sidebar/wiki/images/screenshot-3.png)

### 4. The same image as before, but in larger size. ###
![The same image as before, but in larger size.](https://github.com/aldolat/posts-in-sidebar/wiki/images/screenshot-4.png)

### 5. The introductory text for the widget. ###
![The introductory text for the widget.](https://github.com/aldolat/posts-in-sidebar/wiki/images/screenshot-5.png)

### 6. Displaying the full set of items (categories, date, author, tags, and so on). ###
![Displaying the full set of items (categories, date, author, tags, and so on).](https://github.com/aldolat/posts-in-sidebar/wiki/images/screenshot-6.png)


## Changelog ##

### 1.22 ###

* Updated the Hebrew translation (thanks to Ahrale).
* Added Serbo-Croatian language (thanks to Borisa Djuraskovic).

### 1.21 ###

* NEW: Added an option to exclude the current post in single post page or the current page in single page.
* Added an alert in the widget admin if the current theme doesn't support the Post Thumbnail feature.

### 1.20 ###

* FIX: Now the dropdown menu for post type selection correctly displays all the public post types (thanks to pathuri).

### 1.19 ###

* NEW: Selection of categories and tags is in form of comma separated values. This will prevent server load in case there are too many terms. Also, now the user can get posts from multiple categories.

### 1.18 ###

* NEW: The section with author, date, and comments can now be displayed before the post's excerpt.
* Various small improvements.

### 1.17 ###

* NEW: Added option to exclude posts with certain IDs.
* NEW: Added option to display image before post title.
* NEW: Completed options for Order by parameter.
* Now the plugin requires at least WordPress 3.5.
* Code optimization.
* The class for the custom container class is now sanitized.
* The custom container receives now only a single CSS class.
* Completed the PhpDocumentor tags.

### 1.16.1 ###

* NEW: The cache can be flushed now.
* Updated Hebrew translation.

### 1.16 ###

* NEW: Added a field to define a class for a container.
* NEW: Now the user can define a cache when retrieving posts from database.

### 1.15.1 ###

* FIX: The HTML for ul is now fixed.

### 1.15 ###

* NEW: The posts can be retrieved using the ID (props by Matt).
* NEW: The list of posts can now be displayed in a numbered list (props by Sean).
* NEW: The excerpt can be displayed up to the "more" tag (props by EvertVd).
* FIX: There are no more empty spaces after "Category" or "Tags" text.
* The widget panel has been slightly enlarged.
* Deleted unused options in widgets dropdown menus.
* Minor refinements.

### 1.14 ###

* FIX: fetching posts from tags now works correctly.
* Updated Hebrew translation, thanks to Ahrale.

### 1.13 ###

* NEW: Added option for adding user defined styles (props by Ahrale).
* NEW: Added option for setting the space around the image (props by Ahrale).
* NEW: Added check for rtl languages (the arrow can now be from right to left, props by Ahrale).
* NEW: Added option for ordering by "Menu order" and "Comment count" (props by hypn0ticnet).
* Updated Hebrew translation (thanks to Ahrale).
* Minor bug fixings.
* Minor enhancements.

### 1.12 ###

* NEW: added option for rich content.
* NEW: added option for displaying the custom fields value/key of the post.
* NEW: added option for removing bullets and extra left space for the list elements.
* Code improvements.

### 1.11 ###

* FIX: image align has been fixed (thanks to Clarry).

### 1.10 ###

* FIX: If the post is password protected, now the post password form is displayed before showing the post.
* NEW: Now the user-defined excerpt can display a paragraph break, if any.
* NEW: Added Hebrew translation, thanks to Ahrale.
* Other minor changes.

### 1.9 ###

* NEW: The space after each line can be defined via widget interface.
* NEW: The featured image can be aligned with text.
* NEW: Added `apply_filters` where needed.
* FIX: HTML structure for the archive link is now W3C valid, thanks to [cilya](http://wordpress.org/support/profile/cilya) for reporting it.
* Updated French translation, thanks to [cilya](http://wordpress.org/support/profile/cilya).
* Minor bug fixings.

### 1.8 ###

* New: added post format as option to get posts.
* New: added option for link to custom post type archive.
* New: added option for link to post format archive.
* Other minor changes.

### 1.7 ###

* New: The widget can display the author of the post.
* New: Now the user can choose which type of posts to display: posts, pages, custom post types, etc.
* New: The widget can display the full content (as in single posts).
* New: Now the user can add a custom "Read more" text.
* Added French translation by Thérèse Lachance.
* Code improvements and sanitization.

### 1.6 ###

* New: if in single post, the user can now stylize the current post in the sidebar (feature request from lleroy).

### 1.5 ###

* New: Now the title of the widget can be linked to a user-defined URL (feature request from Mike).

### 1.4 ###

* New: Now the user can add an introductory text to the widget (feature request from Mike).

### 1.3 ###

* New: The date can be linkified or not.
* New: The widget panel now shows empty categories and tags.
* New: The 'No posts yet.' text can be customized.
* Bug fix: The markup no longer shows empty containers.
* Some minor enhancements.

### 1.2.1 ###

* Changed the minimum required WordPress version to 3.3.
* Added Persian language, thanks to AlirezaJamali.

### 1.2 ###

* Enhancement: Now the user can display the entire content for each post. Feature request from [sjmsing](http://wordpress.org/support/topic/plugin-posts-in-sidebar-great-plugin-feature-request)
* Moved screenshots to `/assets/` directory.

### 1.1 ###

* Enhancement: Now it is possible to show the categories of the post
* Enhancement: Now it is possible to exclude posts coming from some categories and/or tags
* Moved the widget section into a separate file.

### 1.0.2 ###

* Updated *Credits* section.

### 1.0.1 ###

* Small typo in `readme.txt`.

### 1.0 ###

* First release of the plugin.

## Upgrade Notice ##

### 1.17 ###

This version requires WordPress 3.5 (for "post__in" option in "Order by" field).

### 1.10 ###

Bugfix for password-protected posts.

### 1.2 ###

Version 1.2 has changed the option to display the text of the post. When upgrading to version 1.2, check every Posts in Sidebar widget at section The text of the post to make sure that the option fits your needs.

### 1.0.2 ###

No notice to display.

### 1.0.1 ###

No notice to display.

### 1.0 ###

No notice to display.
