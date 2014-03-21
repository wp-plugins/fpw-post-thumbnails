=== FPW Post Thumbnails ===
Plugin URI: http://fw2s.com/my-plugins/fpw-post-thumbnails/
Contributors: frankpw
Donate link: http://fw2s.com/payments-and-donations/
Tags: post thumbnails,enable,support,themes
Requires at least: 3.3
Tested up to: 3.7.1
Stable tag: 1.0.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Enables theme support for post thumbnails if the current theme does not provide it. And more...

== Description ==
**This is the last supported version of standalone FPW Post Thumbnails plugin. Now this plugin is embedded into FPW Category Thumbnails plugin.**

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
1. Content - Preview
1. Excerpt - Preview

== Changelog ==

= 1.0.8 =
* Removed built-in front end stylesheet and added dynamic CSS instead

= 1.0.7 =
* Added choice of base dimension for image scaling
* Removed built-in stylesheet for preview and added dynamic CSS instead

= 1.0.6 =
* Added check to prevent activation if FPW Category Thumbnails plugin with bundled FPW Post Thumbnails is installed and active

= 1.0.5 =
* Improved data entry validation
* Loading JavaScript in the footer

= 1.0.4 =
* Fixed incorrect values of border-radius in previews 

= 1.0.3 =
* Added preview for both content and excerpt

= 1.0.2 =
* Added copy between panels
* Changed data entry validation

= 1.0.1 = 
* Fixed typo causing distortion of plugin list by upgrade notice
* Fixed **Get Language File** button not being displayed
* Localized and minified JavaScript
* Added check to prevent front end being loaded when both thumbnails for content and thumbnails for excerpt are not enabled 
* Added missing elements of the help file
* Added .pot file for translation
* Added Polish language files ( .po and .mo )

= 1.0.0 =
* First version.
* Nothing has changed!

== Upgrade Notice ==
Please upgrade!