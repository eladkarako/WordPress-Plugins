<?php
  /**
   * here you put "assistance" functions so your "modifier will not be huge.
   * you can put here any type of helping function, as long its "context-free" (for example, you give it text, and get
   * variation of it, etc..)
   */

  /**
   * CSS minifier.
   * same logic, minimum whitespace.
   * - it runs using a heuristic-set of rules according to minimum whitespace required by latest W3C standard of CSS,
   * - it takes into account CSS3 pseudo-selectors and tags such as '::before'.
   * - it is flexible enough  to handle any future additions
   * - hassle free.
   *
   * @param string $css - raw input to be minified.
   *
   * @return string
   * @author Elad Karako (eladkarako@gmail.com)
   * @link   http://icompile.eladkarako.com
   */
  function minify_css($css = '') {
    /**
     * step #0.
     * remove multi-line comments.
     */
    $css = preg_replace("/\/\*[^\/]+\*\//ims", "", $css); //remove comments


    /**
     * step #1.
     * collapse whitespace chars in an intelligent-way.
     */
    $css = call_user_func_array(function ($css) {
      $css = str_split($css);
      $buffer = [];

      foreach ($css as $index => $char) {
        $val = ord($char);
        $char_prev = array_key_exists($index - 1, $css) ? $css[ $index - 1 ] : '#';       // '#' is outside of our 'ABC' language definition (Discreet-Mathematics)
        $char_next = array_key_exists($index + 1, $css) ? $css[ $index + 1 ] : '#';       // '#' is outside of our 'ABC' language definition (Discreet-Mathematics)

        "\n" === $char
        ||
        9 === $val //tab
        ||
        ' ' === $char && false !== mb_strpos(" ;:{},", $char_prev)
        ||
        ' ' === $char && false !== mb_strpos(" ;:{},", $char_next)
        ||
        array_push($buffer, $char);
      }

      $css = implode('', $buffer);

      return $css;
    }, [$css]);


    /**
     * provide an additional enhancements that might save some space.
     */
    $css = call_user_func_array(function ($css) {
      $enhancements = ["/;{2,}/sm" => ';',  //   enhancement #1: semicolon duplication to single semicolon.
                       "/;}/sm"    => '}']; //   enhancement #2: last-semicolon before closing-curly-bracket is not needed.

      return preg_replace(array_keys($enhancements), array_values($enhancements), $css);
    }, [$css]);

    return $css;
  }


  /**
   * simple javascript minifier.
   *
   * @param string $javascript - raw input to be minified.
   *
   * @return string
   */
  function minify_javascript($javascript = '') {

//    /**
//     * comments-remover
//     */
//    $javascript = call_user_func_array(function ($javascript) {
//      $enhancements = ["#\/\*[\w\'\s\r\n\*]*\*\/#ims"        => '',  //   enhancement #1: /* */ remover
//                       "#\/\/[\w\s\']*$#ims"                 => '',  //   enhancement #2: // remover
//                       "#(<![CDATA[([^]]|(]+[^>]))*]+>)#ims" => ''];  //  enhancement #3: /* <![CDATA[ */   /* ]]> */
//
//      return preg_replace(array_keys($enhancements), array_values($enhancements), $javascript);
//    }, [$javascript]);


    /**
     * provide simple enhancements that might save some space.
     */
    $javascript = call_user_func_array(function ($javascript) {
      $enhancements = ["/;{2,}/sm"         => ';',  //   enhancement #1: semicolon duplication to single semicolon.
                       "/;}/sm"            => '}',  //   enhancement #2: last-semicolon before closing-curly-bracket is not needed.
                       "/;(\s*\n*\s*)+/sm" => ';'];  //   enhancement #3: whitespace after last-semicolon can be omitted.

      return preg_replace(array_keys($enhancements), array_values($enhancements), $javascript);
    }, [$javascript]);


    return $javascript;
  }


  /**
   * step #1.
   *
   *
   * /**
   * number formatting, just like http://php.net/number_format ,  but using string manipulation,
   * providing, virtually, an unlimited precision.
   *
   * @param float|int|string $number        - the input to be formatted.
   * @param int              $decimals      - amount of digits after.
   * @param string           $dec_point     - normally a dot.
   * @param string           $thousands_sep - specify empty string to avoid formatting.
   *
   * @return string
   * @author Elad Karako (icompile.eladkarako.com)
   * @link   http://icompile.eladkarako.com
   */
  function format_number($number, $decimals = 0, $dec_point = '.', $thousands_sep = ',') {

    $number = (string)$number; // convert to string to provide an unlimited precision.
    $number = explode($dec_point, $number);

    //break float to integer and reminder
    $remainder = isset($number[1]) ? $number[1] : (string)0; //  reminder
    $number = $number[0]; //                                     integer

    //make reminder match decimal length, pad with zeros on the right
    $remainder .= str_repeat(
      "0",
      max(0, strlen($decimals - $remainder)) //if reminder length is shorted then needed, pad with zeros
    );

    $remainder = substr($remainder, 0, $decimals); //shorten if needed


    //format thousands
    $number = preg_replace_callback('/(\d)(?=(\d{3})+$)/', function ($arr) use ($thousands_sep) {
      return isset($arr[0]) ? ($arr[0] . $thousands_sep) : "";
    }, $number);

    $remainder = preg_replace_callback('/(\d)(?=(\d{3})+$)/', function ($arr) use ($thousands_sep) {
      return isset($arr[0]) ? ($arr[0] . $thousands_sep) : "";
    }, $remainder);

    return (0 === $decimals) ? $number : ($number . $dec_point . $remainder); //return int if 0 decimals
  }


  /**
   * @param float $size                - the memory size to format
   * @param bool  $is_full_description (optional) - use *Bytes instead of *b (GigaBytes instead of gb, etc...).
   * @param int   $digits              (optional) - number of digits decimal point, to limit.
   *
   * @return string
   */
  function human_readable_memory_sizes($size, $is_full_description = false, $digits = 20) {
    $unit = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];
    $unit_full = ['Bytes', 'KiloByte', 'MegaBytes', 'GigaBytes', 'TeraBytes', 'PetaBytes'];

    $out = $size / pow(1024, ($i = floor(log($size, 1024))));

    $out = sprintf("%." . $digits . "f", $out);

    $out = format_number($out, 2);

    $out .= ' ' . (!$is_full_description ? $unit[ (int)$i ] : $unit_full[ (int)$i ]);

    return $out;
  }


  /**
   * measure the difference,
   * return a formatter HTML comment.
   *
   * @param string $html_before - the raw HTML
   * @param string $html_after  - any state HTML (preferably, at final state..)
   * @param string $mode        - delta-information shown as "bytes" or "chars" or "all" (for both).
   *
   * @return string             - HTML comment you can place at the end of the HTML (for example).
   */
  function get_delta_information($html_before, $html_after, $mode = "bytes") {
    $mode = ("bytes" === $mode || "chars" === $mode || "all" === $mode) ? $mode : "all";

    $length_chars_before = mb_strlen($html_before);
    $length_bytes_before = mb_strlen($html_before, '8bit');

    $length_chars_after = mb_strlen($html_after);
    $length_bytes_after = mb_strlen($html_after, '8bit');

    unset($html_before); //just locally to the function.
    unset($html_after);  //just locally to the function.

    $results = [
      "chars" => [
        "before"  => format_number($length_chars_before),
        "after"   => format_number($length_chars_after),
        "delta"   => format_number($length_chars_before - $length_chars_after),
        "percent" => format_number(100 * (($length_chars_after - $length_chars_before) / $length_chars_before)) . '%'
      ],
      "bytes" => [
        "before"  => human_readable_memory_sizes($length_bytes_before),
        "after"   => human_readable_memory_sizes($length_bytes_after),
        "delta"   => human_readable_memory_sizes($length_bytes_before - $length_bytes_after),
        "percent" => format_number(100 * (($length_bytes_after - $length_bytes_before) / $length_bytes_before)) . '%'
      ]
    ];

    $results = array_merge([], ["all" => $results]); //adds "all"

    //--

    $output = base64_decode("CjwhLS0KV29yZFByZXNzIFJhdy1IVE1MLVByb2Nlc3NpbmcgRnJhbWV3b3JrIEZvciBQSFAtRGV2ZWxvcGVycyAvRWxhZCBLYXJha28gKDIwMTUpIAoK");
    $output .= json_encode($results, JSON_PRETTY_PRINT);
    $output .= "\n-->\n";

    return $output;

  }
