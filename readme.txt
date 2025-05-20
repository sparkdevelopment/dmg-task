=== DMG Task ===
Contributors:      Ryan Jarrett      
Tags:              block
Tested up to:      6.7
Stable tag:        0.1.0
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Contains a stylised Read More block, and a WP CLI command to return the post IDs that contains the block.

== Description ==

This plugin contains a custom block that displays a stylised Read More block. It also contains a WP CLI command that returns the post IDs that contain the block.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/dmg-task` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Use the 'DMG Task' block in the block editor to add a stylised Read More block to your posts.
4. Use the `wp dmg-task get-post-ids` command to return the post IDs that contain the block.

== Frequently Asked Questions ==

= How do I use the DMG Task block? =
To use the DMG Task block, simply add it to your post or page using the block editor. You can then customize the block settings to your liking.
= How do I use the WP CLI command? =
To use the WP CLI command, simply run `wp dmg-read-more-search` in your terminal. This will return a list of post IDs that contain the DMG Task block in the last 30 days.
You can also specify a different date range by using the `--date-before` and `--date-after` options. For example, to search for posts that contain the DMG Task block in a certain week, you can run:
```
wp dmg-read-more-search --date-before=2023-10-01 --date-after=2023-10-08
```

== Changelog ==

= 0.1.0 =
* Release
