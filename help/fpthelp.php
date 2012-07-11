<?php
		//	prevent direct access
		if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) )  
			die( "Direct access to this script is forbidden!" );

		global	$current_screen;
		
		$sidebar =	'<p style="font-size: larger">' . __( 'More information', 'fpw-fpt' ) . '</p>' . 
					'<blockquote><a href="http://fw2s.com/themes-and-fpw-category-thumbnails/" target="_blank">' . __( "Plugin's site", "fpw-fpt" ) . '</a></blockquote>' . 
					'<p style="font-size: larger">' . __( 'Support', 'fpw-fpt' ) . '</p>' . 
					'<blockquote><a href="http://wordpress.org/support/plugin/fpw-post-thumbnails" target="_blank">WordPress</a><br />' . 
					'<a href="http://fw2s.com/support/fpw-post-thumbnails-support/" target="_blank">FWSS</a></blockquote>'; 
			
		$current_screen->set_help_sidebar( $sidebar );
			
		$intro =	'<p style="text-align: justify;">' . 
					__( 'There are many beautiful themes not providing any support for', 'fpw-fpt' ) . ' <em>' .  
					__( 'post thumbnails', 'fpw-fpt' ) . '</em> ( ' . 
					__( 'now called', 'fpw-fpt' ) . ' <em>' . 
					__( 'featured images', 'fpw-fpt' ) . '</em> ). ' . 
					__( 'Some themes provide such support but do not display them.', 'fpw-fpt' ) . ' ' . 
					__( 'Then we have three choices.', 'fpw-fpt' ) . ' ' . 
					__( "First is to find another theme supporting and displaying " . 
						"thumbnails, second - forget about thumbnails, and the third " . 
						"is to get our hands dirty. The last one requires modifications " . 
						"to the current theme's files ( not very elegant and practical " . 
						"as the next theme's upgrade will wipe out those modifications " . 
						") or at least creating a child theme.", "fpw-fpt" ) . ' <strong>' . 
					__( 'FPW Post Thumbnails', 'fpw-fpt' ) . '</strong> ' . 
					__( 'plugin makes these choices obsolete. It will add support for ' . 
						'thumnails, display them, and give you more control over their appearance.', 'fpw-fpt' ) . ' ' . 
					__( "And what's most important it will not modify the current theme in any way.", "fpw-fpt" ) . 
					'</p>';
					
		$current_screen->add_help_tab( array(
   			'title'   => __( 'Introduction', 'fpw-fpt' ),
    		'id'      => 'fpw-fpt-help-introduction',
   			'content' => $intro,
		) );
			
		$opts =		'<p style="font-size: larger">' . __( 'General', 'fpw-fpt' ) . '</p><blockquote>' . 
					'<p style="text-align: justify;"><strong>' . 
					__( "Remove plugin's data from database on uninstall", "fpw-fpt") . '</strong> - ' . 
					__( "if checked then plugin's data will be removed from the database during uninstall procedure", "fpw-fpt" ) . 
					'<br /><strong>' . __( 'Add this plugin to the Admin Bar', 'fpw-fpt' ) . '</strong> - ' . 
					__( "if checked then plugin's link to settings page will be added to the Admin Bar", "fpw-fpt" ) . 
					'</p></blockquote>' . 
					'<p style="font-size: larger">' . __( 'Panels', 'fpw-fpt' ) . '</p><blockquote>' . 
					'<p style="text-align: justify;"><strong>' . 
					__( 'Thumbnails for Content enabled:', 'fpw-fpt' ) . '</strong> - ' . 
					__( 'if checked then enables thumbnails for contents', 'fpw-fpt' ) . '<br /><strong>' . 
					__( 'Thumbnails for Excerpt enabled:', 'fpw-fpt' ) . '</strong> - ' . 				
					__( 'if checked then enables thumbnails for excerpts', 'fpw-fpt' ) . '</p>' . 
					'<p style="text-align: justify;"><strong>width</strong> ' . __( 'and', 'fpw-fpt' ) . ' <strong>height</strong> - ' . 
					__( 'width and height of the thumbnail', 'fpw-fpt' ) . '<br /><strong>scaling base</strong> - ' . 
					__( 'base dimension for scaling', 'fpw-fpt' ) . '<br /><strong>float</strong> - ' . 
					__( 'position of the thumbnail relative to the content ( excerpt )', 'fpw-fpt' ) . 
					'</p><p style="text-align: justify;"><strong>border</strong> - ' . 
					__( 'if checked then the thumbnail will have a border and next four parameters will be applied', 'fpw-fpt') . 
					'<br /><strong>border-radius</strong> - ' . 
					__( 'if > 0 then the border will have rounded corners with the radius ' . 
						'of corners in pixels equal to the specified value', 'fpw-fpt' ) . 
					'<br /><strong>border-width</strong> - ' . __( 'thickness of the border in pixels', 'fpw-fpt' ). 
					'<br /><strong>border-color</strong> - ' . __( 'color of the border', 'fpw-fpt' ) . 
					'<br /><strong>background-color</strong> - ' . __( "thumbnail's background color", "fpw-fpt" ) . 
					'</p><p style="text-align: justify;"><strong>padding-xxx and margin-xxx</strong> - ' . 
					__( 'these are standard padding and margin parameters', 'fpw-fpt' ) . '</p></blockquote>';

		$current_screen->add_help_tab( array(
   			'title'   => __( 'Options', 'fpw-fpt' ),
    		'id'      => 'fpw-fpt-help-options',
	   		'content' => $opts,
		) );
		
		$faq =		'<p style="text-align: justify"><strong>' . 
					__( 'Question:', 'fpw-fpt' ) . '</strong> ' .
					__( 'Can I use this plugin if my theme supports and displays thumbnails?', 'fpw-fpt' ) . '<br /><strong>' . 
					__( 'Answer:', 'fpw-fpt' ) . '</strong> ' . __( "If the theme displays thumbnails for both the content and excerpts I would not recommended it as you would get two thumbnails displayed. However I can imagine one exception. The theme displays thumbnails for full content but not for excerpts or the other way around. The plugin will display thumbnails for the part not being displayed by the theme not adding thumbnails to the other part.", "fpw-fpt" ) . 
					'</p>'; 
			
		$current_screen->add_help_tab( array(
   			'title'   => __( 'FAQ', 'fpw-fpt' ),
    		'id'      => 'fpw-fpt-help-faq',
	   		'content' => $faq,
		) );
?>