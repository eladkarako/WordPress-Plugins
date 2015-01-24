<?php

  //"add stuff here" zone

  /**
   * quickly cut all script tags and paste them before the end-body tag.
   *
   * @param $html
   *
   * @return string
   * @link http://icompile.eladkarako.com/php-raw-html-processing-move-all-script-tags-to-the-end-of-body-tag/
   */
  function put_all_scripts_at_end($html) {
    $scripts = [];

    $html = preg_replace_callback("#<script(.*?)>(.*?)</script>#is", function ($arr) use (&$scripts) {
      $full = $arr[0];
      $attributes = trim($arr[1]);
      $inline = $arr[2];

      array_push($scripts, $full); //    store content.

      return ""; //                      clean from HTML.
    }, $html);

    $html = explode("</body", $html);
    $html = $html[0] . "\n" . implode("\n", $scripts) . "\n" . "</body" . $html[1];

    return $html;
  }


  //O0o0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo
  //0o0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0
  //o0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0O
  //0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo0Oo

  //"read only" zone...

  /**
   * set the hooks required by WordPress to get the very start till the very end of the PHP processing of the HTML.
   *
   * @param $html_modifier - function callable to process the final outgoing HTML output
   */
  function hook_html($html_modifier) {

    /**
     * initiate a output-buffer at the very start of the template initializing stage,
     * the callback will be executed with the buffer's content, at the very end flush.
     */
    add_action('template_redirect', function () use (&$html_modifier) {
      @ob_start($html_modifier);
    }, -9999999);


    /**
     * trigger end flush, looping until the buffer(s) will be flushed out of the PHP-engine.
     */
    add_action('shutdown', function () {
      while (ob_get_level() > 0)
        @ob_flush_end();
    }, 9999999);
  }

?>
