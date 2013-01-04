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

/* WP Logging to handle the logging */
require_once( plugin_dir_path( __FILE__ ) . '/lib/wp-logging/WP_Logging.php' );

class WPTT_Dev{

    function __construct(){
        add_filter( 'wp_logging_post_type_args', array( $this, 'change_logging_params' ), 10, 1 );
    }

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

if( site_url() === WPTT_LOCAL ){
    /* log the emails - requires wp logging */
    require_once( plugin_dir_path( __FILE__ ) . '/class.log-email.php' );
}

if( ! function_exists( 'pr' ) ){
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

    }
} // function_exists( pr )