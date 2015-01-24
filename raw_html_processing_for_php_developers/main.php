<?php
  /**
   * Plugin Name:        Raw HTML Processing For PHP Developers
   * Plugin URI:         http://icompile.eladkarako.com/wordpress-plugin-raw-html-processing/
   * Donate link:        https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=7994YX29444PA
   * Description:        Raw HTML Manipulation At A Level Beyond WordPress-API
   * Version:            1.3.6.5
   * Author:             eladkarako
   * Author URI:         http://icompile.eladkarako.com
   * Author Email:       eladkarako@gmail.com
   * License:            GPL2
   */

  if (!is_admin()) {


    add_action('template_redirect', function () {
      @ob_start(function ($buffer) {
        return raw_html_process($buffer);
      });
    }, -9999999);

    add_action('shutdown', function () {
      while (ob_get_level() > 0)
        @ob_flush_end();
    }, 9999999);


    require_once('functions.php');

    function raw_html_process($html) {

      $html = put_all_scripts_at_end($html); //example for a function that modifies the raw HTML. ---- please add more...

      /*******************************
       * add more modifiers here...  *
       *******************************/

      return $html;
    }
  }

?>