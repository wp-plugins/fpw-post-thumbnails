<?php
		//	AJAX request handler for Get Language File button
		if ( 'not_exist' == $this->translationStatus ) {
			$m = __( 'Language file for this version is not yet available.', 'fpw-fpt' );
		} elseif ( 'installed' == $this->translationStatus ) {
			$m = __( 'Language file is already installed. Please reload this page.', 'fpw-fpt' );
		} else {
			$handle = @fopen( $this->translationPath, 'wb' );
			fwrite( $handle, $this->translationResponse[ 'body' ] );
			fclose($handle);
			$this->translationStatus = 'installed';
			$m = __( 'Language file downloaded successfully. It will be applied as soon as this page is reloaded.', 'fpw-fpt' );
		}			
		echo '<p><strong>' . $m . '</strong></p>';
		die();
?>