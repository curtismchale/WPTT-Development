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

/**
 * @todo add logging of all emails locally
 */

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