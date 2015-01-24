<?php
  /**
   * Plugin Name:        Raw HTML Processing For PHP Developers
   * Plugin URI:         http://icompile.eladkarako.com/wordpress-plugin-raw-html-processing/
   * Donate link:        https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=7994YX29444PA
   * Description:        Raw HTML Manipulation At A Level Beyond WordPress-API
   * Version:            1.3.6.1
   * Author:             eladkarako
   * Author URI:         http://icompile.eladkarako.com
   * Author Email:       eladkarako@gmail.com
   * License:            GPL2
   */

  if (is_admin()) {
    die(0);
  }


  require_once(mb_pathinfo(__FILE__, 'path', 'functions.php'));

  add_action('template_redirect', function () {
    ob_start(function ($buffer) {
      return raw_html_process($buffer);
    });
  }, -9999999);

  add_action('shutdown', function () {
    ob_end_flush();
  }, 9999999);


  function raw_html_process($html) {

    $html = put_all_scripts_at_end($html); //example for a function that modifies the raw HTML. ---- please add more...

    /*
       add more here, the format is:....
            $html =  function_that_does_something($html);
    */

    return $html;
  }


  //
  // MAIN
  // --------------------


  // --------------------
  // LIB
  //
  //

  /**
   * mb_pathinfo (yet another multi-byte-safe pathinfo alternative)
   *
   * @param string $path    - filename-like complete string (does not need to be actually existing in the OS).
   * @param string $segment - optionally specify just one path-segments ('all' - all of them in an associative array).
   * @param string $concat  - optionally specify any string (filename) to append at the end.
   *
   * @return string|array   - a break-down to path-like component (entire segments, or just one of them).
   *
   * @link   https://github.com/eladkarako/PHP-Snippets/blob/master/multi_byte_safe_pathinfo.php
   * @author Elad Karako (icompile.eladkarako.com)
   */
  function mb_pathinfo($path = __FILE__, $segment = 'all', $concat = '') {
    $placements = ['path', 'dirname', 'basename', 'filename', 'dot_extension', 'extension'];
    $segment = 'all' === $segment ? $segment : in_array($segment, $placements) ? $segment : 'all'; //normalize input (only be whats available initially in placements, or 'all').

    $info = [];
    preg_replace_callback('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im', function ($arr) use (&$info, $placements) {
      foreach ($placements as $index => $value)
        $info[ $value ] = isset($arr[ $index ]) ? $arr[ $index ] : '';
    }, $path);

    $info['all'] = array_merge([], $info); //copy to place in self (if returning 'all')

//    var_dump($info);

    //we want to use the resolved path, as base to another file.
    $path = rtrim($info['dirname'], '/\\') . DIRECTORY_SEPARATOR . $concat;

    return ('' === $concat) ? $info[ $segment ] : mb_pathinfo($path, $segment); //at most 1 more recursive request.
  }

