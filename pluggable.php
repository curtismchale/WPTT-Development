<?php
/**
 * @todo check for and toss an error if WP_Logging isn't around
 * @todo test to make sure that no emails are getting sent
 * @todo can I bundle this into a class
 * @todo code when I have more coffee
 * @todo build a better admin interface
 */

/**
 * wp_mail is a pluggable function so we are just declaring it and thus taking over for it.
 * Since we take over I'm logging all emails with WP_Logging.
 */
if( function_exists( 'wp_mail' ) ){
  function wp_mail( $to, $subject, $message, $headers = '', $attachments = array() ) {

      $log_data = array(
          'post_title'    => 'Email: '. $subject,
          'post_content'  => wp_kses_post( $message ),
          'log_type'      => 'event',
      );

      // meta
      $log_meta = array(
          'to_email'      => $to,
          'headers'       => $headers,
          'attachments'   => $attachments,
          'date_time'     => time(),
          'raw_message'   => $message,
      );

      $log_entry = WP_Logging::insert_log( $log_data, $log_meta );

  } // wp_mail
} // function_exists
