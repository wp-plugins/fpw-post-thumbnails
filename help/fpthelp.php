<?php
		global	$current_screen;
		
		$sidebar =	'<p style="font-size: larger">' . __( 'More information', 'fpw-fpt' ) . '</p>' . 
					'<blockquote><a href="http://fw2s.com/themes-and-fpw-category-thumbnails/" target="_blank">' . __( "Plugin's site", "fpw-fpt" ) . '</a></blockquote>' . 
					'<p style="font-size: larger">' . __( 'Support', 'fpw-fpt' ) . '</p>' . 
					'<blockquote><a href="http://wordpress.org/support/plugin/fpw-post-thumbnails" target="_blank">WordPress</a><br />' . 
					'<a href="http://fw2s.com/support/fpw-post-thumbnails-support/" target="_blank">FWSS</a></blockquote>'; 
			
		$current_screen->set_help_sidebar( $sidebar );
			
		$intro =	'<p style="font-size: larger">' . __( 'Introduction', 'fpw-fpt' ) . '</p><blockquote>' . 
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
					'</blockquote>';
					
		$current_screen->add_help_tab( array(
   			'title'   => __( 'Introduction', 'fpw-fpt' ),
    		'id'      => 'fpw-fpt-help-introduction',
   			'content' => $intro,
		) );
			
		$opts =		'<p style="font-size: larger">' . __( 'Available Options', 'fpw-fpt' ) . '</p><blockquote>' . 
					'will be added in new version...</blockquote>'; 

		$current_screen->add_help_tab( array(
   			'title'   => __( 'Options', 'fpw-fpt' ),
    		'id'      => 'fpw-fpt-help-options',
	   		'content' => $opts,
		) );

		$faq =		'<p style="font-size: larger">' . __( 'Frequently Asked Questions', 'fpw-fpt' ) . '</p><blockquote><strong>' . 
					__( 'Question:', 'fpw-fct' ) . '</strong> ' .
					__( 'Can I use this plugin if my theme supports and displays thumbnails?', 'fpw-fpt' ) . '<br /><strong>' . 
					__( 'Answer:', 'fpw-fct' ) . '</strong> ' . __( "If the theme displays thumbnails for both the content and excerpts I would not recommended it as you would get two thumbnails displayed. However I can imagine one exception. The theme displays thumbnails for full content but not for excerpts or the other way around. The plugin will display thumbnails for the part not being displayed by the theme not adding thumbnails to the other part.", "fpw-fpt" ) . 
					'</blockquote>'; 
			
		$current_screen->add_help_tab( array(
   			'title'   => __( 'FAQ', 'fpw-fpt' ),
    		'id'      => 'fpw-fpt-help-faq',
	   		'content' => $faq,
		) );
?>