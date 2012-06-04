<?php
			wp_enqueue_style( 'farbtastic' );
			wp_enqueue_script( 'farbtastic' );
			wp_register_script( 'fpw-fpt', plugins_url( '/fpw-post-thumbnails/js/fpw-fpt.js' ), array( 'jquery', 'farbtastic' ) );
			wp_enqueue_script( 'fpw-fpt' );
			$protocol = isset( $_SERVER[ 'HTTPS' ] ) ? 'https://' : 'http://';
			wp_localize_script( 'fpw-fpt', 'fpw_fpt', array(
				'ajaxurl'			=> admin_url( 'admin-ajax.php', $protocol ),
				'help_link_text'	=> esc_html( __( 'Help for FPW Post Thumbnails', 'fpw-fpt' ) )
			));
?>