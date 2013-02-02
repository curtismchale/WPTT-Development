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
    }

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
			require_once( plugin_dir_path( __FILE__ ) . '/lib/wp-logging/WP_Logging.php' );
			require_once( plugin_dir_path( __FILE__ ) . '/lib/wp-logging/Logging_UI.php' );
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
