<?php

  /**
   * a read-only zone, don't mess with it,
   * it does the hard-work of handling buffers in PHP, and wrapping everything so all you have to do,
   * is call the hook_html with the function to modify the raw html, hassle-free!
   */

  /* "read only" zone... */

  /**
   * set the hooks required by WordPress to get the very start till the very end of the PHP processing of the HTML.
   *
   * @param callable $html_modifier            - function callable to process the final outgoing HTML output
   * @param bool     $is_dump_previous_buffers - optionally dump all running buffers, store the final value,
   *                                           starting single, fresh buffer and making sure everything,
   *                                           include the final value, will pipe though it. (oh.. just keep it false)
   */
  function hook_html($html_modifier, $is_dump_previous_buffers = false) {
    if (!is_admin()) {
      /**
       * initiate a output-buffer at the very start of the template initializing stage,
       * the callback will be executed with the buffer's content, at the very end flush.
       */
      add_action('template_redirect', function () use (&$html_modifier, $is_dump_previous_buffers) {
        $html = "";
        /* dump all other buffers. */
        if (true === $is_dump_previous_buffers) {
          while (ob_get_level() > 0)
            $html = @ob_get_flush();
        }

        /* start last one */
        @ob_start($html_modifier);


        /* dump anything if was any */
        echo $html;
      }, -9999999);


      /**
       * trigger end flush, looping until the buffer(s) will be flushed out of the PHP-engine.
       */
      add_action('shutdown', function () {
        while (ob_get_level() > 0)
          @ob_flush_end();
      }, 9999999);
    }
  }

?>
