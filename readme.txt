=== Image Zoom ===

Contributors: SedLex
Author URI: http://www.sedlex.fr/
Plugin URI: http://wordpress.org/extend/plugins/image-zoom/
Tags: plugin, image, highslide, zoom, scale, picture
Requires at least: 3.0
Tested up to: 3.2
Stable tag: trunk

Allow to zoom dynamically on images in posts / pages / etc.

== Description ==

Allow to zoom dynamically on images in posts / pages / etc.

When clicked, the image will dynamically scale-up. Please note that you have to insert image normally with the wordpress embedded editor.

You may configure :

* The max width/height of the image
* The transition delay 
* The position of the buttons
* The auto-start of the slideshow
* the opacity of the background

If the image does not scale-up, please verify that the HTML looks like the following : &lt;a href=' '&gt;&lt;img src=' '&gt;&lt;/a&gt;.

This plugin implements the highslide javascript library.

Plugin developped from the orginal plugin Zoom-Hishslide.

= Localizations =

* Hungarian translation (by Metoyou and DvnyiFerenc)
* Russian translation (by Sprigin)
* German translation (by Frutte)
* Spanish translation (by genteblackberry)
* Czech translation (by jurajh) 
* French translation (by me)

= Features of the framework = 

This plugin use SL framework.

You may translate this plugin with an embedded feature which is very easy to use by any end-users (without any external tools / knowledge).

You may also create a new plugin. You will download, from the plugin, an "hello World" plugin: You just have to fill the blank and follow the comments.

Moreover, all the plugins developped with this framework is able to use astonishing tools, such as :

* embedded SVN client (subversion) to easily commit/update the plugin in wordpress.org repository ; 
* detailled documentation of all available classes and methodes ; 
* updating the core with a single click ; 
* etc.

= Licence =

This plugin is under GPL licence 

Please note that the highslide library is not under GPL licence but under Creative Commons Attribution-NonCommercial 2.5 License. This means you need the author's permission to use Highslide JS on commercial websites.

== Installation ==

1. Upload this folder to your plugin directory (for instance '/wp-content/plugins/')
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to the 'SL plugins' box
4. All plugins developed with the SL core will be listed in this box
5. Enjoy !

== Screenshots ==

1. The configuration page of the plugin
2. An image zommed

== Changelog ==

= 1.2.2 =
* Hungarian translation (by Metoyou and DvnyiFerenc)

= 1.2.1 =
* It is possible to activate the slideshow upon start (auto-start)

= 1.2.0 =
* SVN support for committing changes

= 1.1.3 =
* Bug correction (conflict between prototype library and jQuery library)
* Update of the German translation by Frutte
* Czech translation (by jurajh) 

= 1.1.2 =
* Update of the core plugin (bug correction on the hash of the plugin/core)

= 1.1.1 =
* Update of the core plugin
* Update of the Russian translation by Sprigin

= 1.1.0 =
* New translation for Russian made by Sprigin 

= 1.0.9 =
* New translation for German made by Frutte

= 1.0.8 =
* New translation for Spanish made by genteblackberry 

= 1.0.7 =
* ZipArchive class has been suppressed and pclzip is used instead

= 1.0.6 =
* Ensure that folders and files permissions are correct for an adequate behavior

= 1.0.5 =
* Enhance the framework (feedback, other plugins, translations)

= 1.0.4 =
* Correction of a bug in the load-style.php which change dynamically the url of the image contained in the CSS file
* Enable the translation of the plugin (modification in the framework, thus all your plugin developped with this framework can enable this feature easily)
* Add the email of the author in the header of the file to be able to send email to him
* Enhance the localization of the plugin
* The javascript function to be called for table cell can have now complex parameters (instead of just the id of the line)
* Add the French localization
* Add a form to send feedback to the author

= 1.0.3 =
* Major release of the framework (3.0)

= 1.0.2 =
* Bug correction (thanks to Chipset): block property of images was cleared and this action could have change their paginations

= 1.0.1 =
* First release in the wild web (enjoy)

== Frequently Asked Questions ==

* Where can I read more?

Visit http://www.sedlex.fr/cote_geek/
 
 
InfoVersion:8801ab56bd1e736457e9a50cdc6da972