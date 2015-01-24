<?php
 /**
  * Plugin Name:        Raw HTML Processing For PHP Developers
  * Plugin URI:         http://icompile.eladkarako.com/wordpress-plugin-raw-html-processing/
  * Donate link:        https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=7994YX29444PA
  * Description:        Raw HTML Manipulation At A Level Beyond WordPress-API
  * Version:            1.3.5
  * Author:             eladkarako
  * Author URI:         http://icompile.eladkarako.com
  * Author Email:       eladkarako@gmail.com
  * License:            GPL2
  */

  if (is_admin()) {
    die(0);
  }

  require_once('on_page.php');

  add_action('template_redirect', function () {
    ob_start(function ($buffer) {
      return raw_html_process($buffer);
    });
  }, -9999999);

  add_action('shutdown', function () {
    ob_end_flush();
  }, 9999999);


  function raw_html_process($html) {
    require_once('raw_html_processing.php');

    $html = put_all_scripts_at_end($html);
    /*
       add more
    */

    return $html;
  }
