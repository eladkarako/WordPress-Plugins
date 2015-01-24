<?php


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

?>
