<?php

  /**
   * here you put small functions that will handle the modification of the raw html,
   * if you need to- break a long function into few assistance-functions and place them in the assist.php file.
   */


  /**
   * quickly cut all script tags and paste them before the end-body tag.
   *
   * @param string $html
   *
   * @return string
   * @link http://icompile.eladkarako.com/php-raw-html-processing-move-all-script-tags-to-the-end-of-body-tag/
   */
  function put_all_scripts_at_end_of_body($html) {
    $elements = [];

    $html = preg_replace_callback("#<script(.*?)>(.*?)</script>#is", function ($arr) use (&$elements) {
      if (!isset($arr[0])) //no found: no add, no delete
        return;

      $full = $arr[0];
//      $attributes = trim($arr[1]);
//      $inline = $arr[2];

      array_push($elements, $full); //    store content.

      return ""; //                      clean from HTML.
    }, $html);

    $html = explode("</body>", $html);
    $html = $html[0] . "\n" . implode("\n", $elements) . "\n" . "</body>" . $html[1];

    return $html;
  }


  /**
   * extract both <link...rel="stylesheet".../> and <style..type="text/css"...></style> (IN THE SAME ORDER!!!)
   * removes them from their original location and place them at the head tag.
   * the stack of elements are in the same order in the page (thats why its important to use single
   * regular-expression),
   * this is important since CSS rules are meant to be overriding one-another, so THE ORDER OF LINK AND STYLE IS
   * IMPORTANT!!!
   *                for example:
   *                  <style type=text/css>wwww</style> <link rel=stylesheet a=b
   *                  href=\"http://www.google.com/style.css\"><head></head><body><style
   *                  type=text/css>rrrrrrrr</style></body> will became (plus \n)
   *                  <head>
   *                  <style type=text/css>wwww</style>
   *                  <link rel=stylesheet a=b href="http://www.google.com/style.css">
   *                  <style type=text/css>rrrrrrrr</style>
   *                  </head><body></body>
   *
   * @param string $html
   *
   * @return string
   */
  function put_all_link_css_at_end_of_head($html) {
    $elements = [];

    $html = preg_replace_callback("#(<link[^\>]*?rel=[\',\"]?stylesheet[\',\"]?[^\>]*?>|<style[^\>]*?type=[\',\"]?text\/css[\',\"]?[^\>]*?>[^\<]*?<\/style>)#is", function ($arr) use (&$elements) {
      $full = $arr[0];

      array_push($elements, $full); //    store content.

      return ""; //                      clean from HTML.
    }, $html);

    $html = explode("</head>", $html);
    $html = $html[0] . "\n" . implode("\n", $elements) . "\n" . "</head>" . $html[1];

    return $html;
  }


  /**
   * make multiple \n collapse to one
   *
   * @param string $html
   *
   * @return string
   */
  function collapse_multiple_line_feed($html) {

    $html = preg_replace("#\n{2,}#is", "\n", $html);

    return $html;
  }


  /**
   * make multiple \n collapse to one
   * remmember that this might effect also stuff like code in pre-tags or code-tags but thats minor change..
   *
   * @param string $html
   *
   * @return string
   */
  function collapse_double_line_feed($html) {

    $html = preg_replace("#\n{2,}#is", "\n", $html);

    return $html;
  }


  /**
   * make multiple \n collapse to one
   * remmember that this might effect also stuff like code in pre-tags or code-tags but thats minor change..
   *
   * @param string $html
   *
   * @return string
   */
  function collapse_white_space_between_tags($html) {

    $html = preg_replace("/>\s+</s", "> <", $html);

    return $html;
  }


  function minify_all_inner_css_in_style_tags($html) {

    $html = preg_replace_callback("/(<style[^\>]*?type=[\',\"]?text\/css[\',\"]?[^\>]*?\>)([^\<]*?)\<\/style\>/msi", function ($arr) {
      if (!array_key_exists(0, $arr) || !array_key_exists(1, $arr) || !array_key_exists(2, $arr)) //not found
        return "";

      $full = $arr[0];
      $tag_start = $arr[1];
      $inline = $arr[2];

      $inline = minify_css($inline); //minify

      return $tag_start . $inline . '</style>'; //reassemble
    }, $html);

    return $html;
  }

  function minify_all_inner_javascript_in_script_tags($html) {
    $html = preg_replace_callback("/(<script[^\>]*?type=[\',\"]?text\/javascript[\',\"]?[^\>]*?\>)([^\<]*?)\<\/script\>/msi", function ($arr) {
      if (!array_key_exists(0, $arr) || !array_key_exists(1, $arr) || !array_key_exists(2, $arr)) //not found
        return "";

      $full = $arr[0];
      $tag_start = $arr[1];
      $inline = $arr[2];

      $inline = minify_javascript($inline); //minify

      return $tag_start . $inline . '</script>'; //reassemble
    }, $html);

    return $html;
  }


?>
