=== WordPress Raw HTML Processing For PHP Developers ===
Contributors: eladkarako 
Donate link:https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=7994YX29444PA
Tags: raw,html,modify,string,manipulation,buffer,php,async,js,headjs,asynchronous,javascript,css,perfor,api,framework,easy,wordpress,developer
Requires at least: 3.0
Tested up to: 4.5
Stable tag: trunk
License: GPLv2 or later

A WordPress Plugin (or a framework) to help you manipulate your blog's raw HTML, as string. No WordPress-API knowledge required, just good-old PHP skills.

== Description ==

Makes editing your blog's HTML On-The-Fly, really **(really!)** easy.

this plugin, <small>is actually a small framework</small>, that allows you to edit the HTML of your blog, after all the processing was done on it already, just before it is returning and going to be presented by the PHP engine. 

the name of the plugin is 'Raw HTML Processing For PHP Developers' because you have a very easy, very clean access to the end-point of the HTML buffer, and as a developer you may add remove or modify - the HTML string, and returning the result.

No WordPress-API knowledge is required, you can do basic or advanced string-manipulation, and look around the HTML modifying it in a much lower level than provided by WordPress.

*   removing tags.
*   modify information above the head, html or after the body tag end (essentially everywhere).
*   run custom/hand-made optimizations, or HTML/CSS/JavaScript minifing.
*   encrypt/decrypt the page or inject <script> tags without the need to stop or start other plugins.
*   compatible with caching-plugins.
*   this plugins, is a framework that works FOR YOU.
*   if you find a bug please report it.
*   consider a <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=7994YX29444PA" target="_blank" title="keep the developer happy :)">small donation to keep the developer happy</a> :)

== Installation ==
Like any other plugin, well.. **no- even simple!!**

1.  Upload <code>raw_html_processing_for_php_developers/</code> folder to the <code>/wp-content/plugins/</code> directory
2.  Activate the plugin through the 'Plugins' menu in WordPress
3.  <strong>no configuration: <u>you are a developer:</u></strong>  
    - You implement your own methods or PHP-commands to manipulate the HTML,  
    - either directly-edit the <code>raw_html_process</code> function (at the **<code>main.php</code>** file)  
    - or add another function in the <code>functions.php</code> file and send the processed <code>$html</code> to it too, you can chain as much functions as you like.    
    - thats your whole blog's HTML.
4.  enjoy.

== Upgrade Notice ==
there is no configuration, but, you should make a copy of your modified plugin,
if you'll upgrade the plugin, your modified files will be overwrite with new ones.
 

== Frequently Asked Questions ==

**I am not a PHP developer, I'm a WordPress Plugin-Developer, Is there a difference ?**
Yes. essentially its the same difference as a farmer, and a pickle-making-person, both have created-something, but one has a deeper level of understanding.

**Give me a reason why should I use this plugin/framework of yours..**
You may want a good reason, but there is none, you might as well be written a complete Wordpress-Plugin to do a specific task,

this way I'm giving you a wrapping around a very efficient way of HTML modifying, and a sure way of knowing after you've run your stuff- no other plugin will run and break things..


**Really? I'm the last one.. ?**
Well...

there are rewrite rules of Apache (or nginx, or even NodeJS), stuff you'll be adding your <code>.htaccess</code> might have effect,
and.. there is <code>JavaScript</code> which renderes your page at the very end into a <code>Document-Object-Model</code>,

so.. no you are not the definitive "last one", 
but you are the "server-side" "last one", so, enjoy!

**notes about simple Google PageSpeed**
in addition you can activate through .htaccess some minifications, that (naturally) works after the PHP output has modified, try it:

# BEGIN Google-Page-SpeedWordPress
<IfModule pagespeed_module>
   #default
   ModPagespeed on
   ModPagespeedEnableFilters extend_cache

   #https://developers.google.com/speed/pagespeed/module/filter-comment-remove
   ModPagespeedEnableFilters remove_comments
   ModPagespeedRetainComment " _*"
   ModPagespeedRetainComment " WordPress Raw-HTML-Processing*"

   #https://developers.google.com/speed/pagespeed/module/filter-whitespace-collapse
   ModPagespeedEnableFilters collapse_whitespace
</IfModule>
# END Google-Page-SpeedWordPress

== Screenshots ==
1. An unmodified HTML (Sample)
2. A modified HTML, the included example runs raw-html string modification to move all the <code>script</code> tags to the end of the body tag [both inline and external resources].

== Known incompatibilities ==
* nothing!
  * Compatible with all versions of WordPress.
  * Compatible with every version of PHP.
  * Compatible with every browser version.

== Changelog ==
**3.1.6.1**
handling single-line comments in target textual-content. code-mountance: replacing single-line in-code-comments to multi-line best-practice.

**3.1.5.2**
protect pre, code, textarea content before start modifying the HTML, so their content will remain, the original unmodified one (even if contains HTML, JavaSctipt, \n, etc...)

**3.1.5**
code fix, logic enhancement, simplifying the implementation of the hooks. adding more html-modifiers. optionally adding information at the bottom of the page.

**1.3.6.5.2**
minor code design-pattern and distribution.

**1.3.6.5**
fixing flow issues.

**1.3.6.1**
fixing path related issues.

**1.3.5**
First release.
