<?php
  /**
   * Plugin Name:        WordPress Raw-HTML-Processing Framework For PHP-Developers
   * Plugin URI:         http://icompile.eladkarako.com/wordpress-plugin-raw-html-processing/
   * Donate link:        https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=7994YX29444PA
   * Description:        Raw HTML Manipulation At A Level Beyond WordPress-API
   * Version:            3.1.5.2
   * Author:             eladkarako
   * Author URI:         http://icompile.eladkarako.com
   * Author Email:       eladkarako@gmail.com
   * License:            GPL2
   */

  require_once('lib/hook.php');
  require_once('lib/assist.php');
  require_once('lib/modifiers.php');

  hook_html(function ($html) {
    $html_before = $html . '';

    $html = protect_specific_tags_from_modifications($html);  //    protect pre-tags and code-tags original content.

    //-------------------------------------------------------------------------------------------------------------
    $html = minify_all_inner_css_in_style_tags($html);  //               saves about 2%
    $html = minify_all_inner_javascript_in_script_tags($html);  //       saves about 4%
    $html = remove_type_text_javascript_in_script_tags($html);  //       saves about 4%
    $html = put_all_link_css_at_end_of_head($html);  //                  Google PageSpeed "best practices"
    $html = put_all_scripts_at_end_of_body($html);  //                   Google PageSpeed "best practices"
    $html = collapse_multiple_line_feed($html);  //                      saves about 2%
    $html = collapse_white_space_between_tags($html);  //                saves about 5-8%

    /*******************************
     * add more modifiers here...  *
     *******************************/
    //-------------------------------------------------------------------------------------------------------------

    $html = unprotect_pre_and_code_tags_content_from_change($html);  //  unprotect (bring back) pre-tags and code-tags original content.


    $html = $html . get_delta_information($html_before, $html); //  (optional) see delta, compared to raw HTML.
    unset($html_before); //                                         cleanup.

    return $html;
  });


?>
