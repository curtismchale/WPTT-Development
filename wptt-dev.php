<?php
/*
Plugin Name: Development Plugin by WP Theme Tutorial
Plugin URI: http://wpthemetutorial.com
Description: Bundles a bunch of development stuff in to a plugin so I know it's not repeated.
Version: 0.01
Author: WP Theme Tutorial, Curtis McHale
Author URI: http://wpthemetutorial.com
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

class WPTT_Dev{

    function __construct(){
        add_filter( 'wp_logging_post_type_args', array( $this, 'change_logging_params' ), 10, 1 );

	    $this->includes();

	    add_filter( 'wptt_dev_wp_mail', array( $this, 'log_wp_mail' ), 10, 6 );
    }

	/**
	 * Does the logging of wp_mail for us and allows us to define the environments. You can
	 * filter if emails send on different environments using 'wptt_dev_env_email_log'. Defaults to
	 * FALSE for local and dev environments and TRUE for live.
	 *
	 * If no environments have been defined then it will just send the email anyway.
	 *
	 * @since 0.01
	 * @access public
	 * @author WP Theme Tutorial, Curtis McHale
	 *
	 * @param string    $to             req     Who the email is to
	 * @param string    $subject        req     The subject of the email
	 * @param string    $message        req     The message content
	 * @param string    $headers        req     The headers on the message
	 * @param string    $attachments    opt     Any message attachments
	 * @param           $phpmailer              The built phpmailer object from wp_mail
	 *
	 * @return bool
	 *
	 * @uses wp_kses_post()     Sanitizes post data
	 * @uses $this->is_local()  Returns true if on the local environment
	 * @uses $this->is_dev()    Returns true if on the dev environment
	 * @uses $this->is_live()   Returns true if on the live environment
	 */
	public function log_wp_mail( $to, $subject, $message, $headers, $attachments, $phpmailer ){

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
			'php_mailer'    => $phpmailer,
		);

		$log_entry = WP_Logging::insert_log( $log_data, $log_meta );

		if( $this->is_local() ){
			return apply_filters( 'wptt_dev_env_email_log', false );
		} elseif ( $this->is_dev() ){
			return apply_filters( 'wptt_dev_env_email_log', false );
		} elseif ( $this->is_live() ){
			return apply_filters( 'wptt_dev_env_email_log', true );
		} else {
			return true;
		}

	} // log_wp_mail

	/**
	 * Our conditional to decide if we are on the defined live environment
	 *
	 * @since 0.01
	 * @access private
	 * @author WP Theme Tutorial, Curtis McHale
	 *
	 * @return bool         True if we are on the defined live environment
	 *
	 * @uses site_url()     Returns the URL for the site
	 */
	public function is_live(){

		if( defined( 'WPTT_LIVE' ) && WPTT_LIVE ){
			if( WPTT_LIVE === site_url() ) return true;
		}

		return false;
	} // is_live

	/**
	 * Our conditional to decide if we are on the defined development environment
	 *
	 * @since 0.01
	 * @access private
	 * @author WP Theme Tutorial, Curtis McHale
	 *
	 * @return bool         True if we are on the defined dev environment
	 *
	 * @uses site_url()     Returns the URL for the site
	 */
	public function is_dev(){

		if( defined( 'WPTT_DEV' ) && WPTT_DEV ){
			if( WPTT_DEV === site_url() ) return true;
		}

		return false;
	} // is_dev

	/**
	 * Our conditional to decide if we are local
	 *
	 * @since 0.01
	 * @access private
	 * @author WP Theme Tutorial, Curtis McHale
	 *
	 * @return bool         True if we are on the defined local environment
	 *
	 * @uses site_url()     Returns the URL for the site
	 */
	public function is_local(){

		if( defined( 'WPTT_LOCAL' ) && WPTT_LOCAL ){
			if( WPTT_LOCAL === site_url() ) return true;
		}

		return false;
	} // is_local

	/**
	 * Sets includes all the stuff we need from WP_Logging
	 *
	 * @since   0.01
	 * @access private
	 * @author  WP Theme Tutorial, Curtis McHale
	 *
	 * @uses plugin_dir_path()      Gets path to the plugin directory
	 */
	private function includes(){

		if( ! class_exists( 'WP_Loging' ) ){

			// our logging base class
			require_once( plugin_dir_path( __FILE__ ) . '/lib/wp-logging/WP_Logging.php' );
			require_once( plugin_dir_path( __FILE__ ) . '/lib/wp-logging/Logging_UI.php' );

			require_once( plugin_dir_path( __FILE__ ) . '/pluggable.php' );
		}

	} // wp_logging

    /**
     * Changing the logging arguments for the post type so that we have a basic admin
     *
     * @param   array   $params         The original parameters
     * @return  array                   Our modified parameters
     *
     * @since   0.01
     * @author  WP Theme Tutorial, Curtis McHale
     */
    public function change_logging_params( $params ){

        $params['public'] = true;
        $params['show_in_nav_menus'] = false;
        $params['show_in_menu'] = 'tools.php';

        return $params;

    } // change_logging_params

} // wptt_dev

$wptt_dev = new WPTT_Dev();

if ( ! function_exists( 'pr' ) ){
	/*
	 * Produces print_r inside <pre> limited to development users
	 *
	 * @param string $data The variable we want to print
	 * @uses get_the_author_meta
	 * @uses current_user_can
	 * @ueses in_array
	 */
	function pr( $data ) {

		global $current_user;

		$validemails = array( 'curtis@curtismchale.ca', 'curtis.mchale@gmail.com' );

		$useremail = get_the_author_meta( 'user_email', $current_user->ID );

		if( WP_DEBUG || current_user_can( 'activate_plugins' ) || in_array( $useremail, $validemails )  ){
			echo '<pre>';
			print_r( $data );
			echo '</pre>';
		}

	} // pr
} // function_exists( 'pr' )

/**
 * A helper function to log things inside your projects.
 *
 * @uses  wp_logging      WP_Logging class from @pippinsplugins
 * @uses  wp_kses_post    sanitize my data of course
 *
 * @since   0.01
 * @author  WP Theme Tutorial, Curtis McHale
 */
function wptt_log_error( $title, $message, $args = array() ){

    global $wp_query;

    $log_data = array(
        'post_title'    => $title,
        'post_content'  => wp_kses_post( $message ),
        'log_type'      => 'error',
    );

    // meta
    $log_meta = array(
        'date_time'     => time(),
        'wp_query'      => $wp_query,
    );

    $log_meta = array_merge( $log_meta, $args );

    $log_entry = WP_Logging::insert_log( $log_data, $log_meta );

} // wptt_log_error
