<?php
		//	AJAX request to update options

		//	prevent direct access
		if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) )  
			die( "Direct access to this script is forbidden!" );

		$p = $_POST;
		
		if ( isset( $_POST[ 'boxes' ] ) ) { 
			$boxes = $_POST[ 'boxes' ];
			foreach ( $boxes as $b ) 
				$p[ $b ] = $b;
		}
		
		$resp = $this->fptValidateInput( $p );
		
		echo '<p><strong>';
		
		if ( '' == $resp ) { 
			$ok = update_option( 'fpw_post_thumbnails_options', $this->fptOptions );
			if ( $ok ) {
				$this->uninstallMaintenance();
				echo __( 'Options updated successfully.', 'fpw-fpt' );
			} else {
				echo __( 'No changes detected. Nothing to update.', 'fpw-fpt' );
			}
		} else {
			echo __( 'Validation failed!', 'fpw-fpt' ) . ' ' . $resp;
		}
		echo '</strong></p>';
		die();
?>