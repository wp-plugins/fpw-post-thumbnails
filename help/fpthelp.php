<?php
		global	$current_screen;
		
		$sidebar =	'<p style="font-size: larger">' . __( 'More information', 'fpw-fpt' ) . '</p>'; 
			
		$current_screen->set_help_sidebar( $sidebar );
			
		$intro =	'<p style="font-size: larger">' . __( 'Introduction', 'fpw-fpt' ) . '</p>';

		$current_screen->add_help_tab( array(
   			'title'   => __( 'Introduction', 'fpw-fpt' ),
    		'id'      => 'fpw-fpt-help-introduction',
   			'content' => $intro,
		) );
			
		$opts =		'<p style="font-size: larger">' . __( 'Available Options', 'fpw-fpt' ) . '</p>'; 

		$current_screen->add_help_tab( array(
   			'title'   => __( 'Options', 'fpw-fpt' ),
    		'id'      => 'fpw-fpt-help-options',
	   		'content' => $opts,
		) );

		$faq =		'<p style="font-size: larger">' . __( 'Frequently Asked Questions', 'fpw-fpt' ) . '</p>';
			
		$current_screen->add_help_tab( array(
   			'title'   => __( 'FAQ', 'fpw-fpt' ),
    		'id'      => 'fpw-fpt-help-faq',
	   		'content' => $faq,
		) );
?>