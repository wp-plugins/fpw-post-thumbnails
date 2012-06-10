<?php
/*
Plugin Name: FPW Post Thumbnails
Description: Manages post thumbnails for themes not supporting them.
Plugin URI: http://fw2s.com/my-plugins/fpw-post-thumbnails/
Version: 1.0.2
Author: Frank P. Walentynowicz
Author URI: http://fw2s.com/

Copyright 2011 Frank P. Walentynowicz (email : frankpw@fw2s.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//	prevent direct access
if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) )  
	die( "Direct access to this script is forbidden!" );

// checks is WP is at least a certain version
function is_wp_version( $is_ver ) {
    $wp_ver = explode( '.', get_bloginfo( 'version' ) );
    $is_ver = explode( '.', $is_ver );
    for( $i=0; $i<=count( $is_ver ); $i++ )
        if( !isset( $wp_ver[$i] ) ) array_push( $wp_ver, 0 );
 
    foreach( $is_ver as $i => $is_val )
        if( $wp_ver[$i] < $is_val ) return false;
    return true;
}

if ( ! is_wp_version( '3.3' ) ) 
	wp_die( '<center>This version of <strong>FPW Post Thumbnails</strong> plugin ' . 
			'requires WordPress version <strong>3.3 or higher</strong>!<br />' . 
			'Cannot activate! Press <strong><em>Go back one page</em></strong> ' . 
			'button of your browser.</center>');


$needClass = false;
if ( is_admin() ) {
	//	back end
	$needClass = true;
	require_once dirname(__FILE__) . '/classes/fpw-post-thumbnails-class.php';
} else {
	//	front end
	$o = get_option( 'fpw_post_thumbnails_options' );
	if ( is_array( $o ) && ( $o[ 'content' ][ 'enabled' ] || $o[ 'excerpt' ][ 'enabled' ] ) ) {
		$needClass = true;
		require_once dirname(__FILE__) . '/classes/fpw-post-thumbnails-front-class.php';
	}
}
if ( $needClass ) {
	global $fpw_PT;

	$fpw_PT = new fpwPostThumbnails( dirname(__FILE__), '1.0.2' );
}
?>