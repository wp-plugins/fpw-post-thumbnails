=== FPW Post Thumbnails ===
Plugin URI: http://fw2s.com/my-plugins/fpw-post-thumbnails/
Contributors: frankpw
Donate link: http://fw2s.com/payments-and-donations/
Tags: post thumbnails,enable,support,themes
Requires at least: 3.3
Tested up to: 3.4-RC1
Stable tag: 1.0.0

Enables theme support for post thumbnails if the current theme does not provide it. And more...

== Description ==
There are many nice themes not providing any support for *post thumbnails* ( now called *featured images* ). Some themes provide such support but do not display them. Then we have three choices. First is to find another theme supporting and displaying thumbnails, second - forget about thumbnails, and the third is to get our hands dirty. The last one requires modifications to the current theme's files ( not very elegant and practical as the next theme's upgrade will wipe out those modifications ) or at least creating a child theme. **FPW Post Thumbnails** plugin makes these choices obsolete. It will add support for thumnails, display them, and give you more control over their appearance. And what's most important it will not modify the current theme in any way. 

== Installation ==
1. Download the latest zip file and extract the directory it contains.
2. Upload the directory to your '/wp-content/plugins/' directory.
3. Activate the plugin on the 'Plugins' menu in the WordPress Dashboard.

== Frequently Asked Questions ==

= Can I use this plugin if my theme supports and displays thumbnails? =
If the theme displays thumbnails for both the content and excerpts I would not recommended it as you would get two thumbnails displayed. However I can imagine one exception. The theme displays thumbnails for full content but not for excerpts or the other way around. The plugin will display thumbnails for the part not being displayed by the theme not adding thumbnails to the other part.

== Screenshots ==
1. Options Page

== Changelog ==

= 1.0.0 =
* First version.
* Nothing has changed!

= 1.0.1 = 
* Fixed typo causing distortion of plugin list by upgrade notice
* Fixed **Get Language File** button not being displayed
* Localized and minified JavaScript
* Added check to prevent front end being loaded when both thumbnails for content and thumbnails for excerpt are not enabled 
* Added missing elements of the help file
* Added .pot file for translation
* Added Polish language files ( .po and .mo )

== Upgrade Notice ==
Please upgrade!