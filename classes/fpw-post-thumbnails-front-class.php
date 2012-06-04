<?php
//	front end class
class fpwPostThumbnails {
	public	$fptPath;
	public	$fptUrl;
	public	$fptVersion;
	public	$pluginPage;
	public	$wpVersion;
	public	$fptOptions;
	
	//	constructor
	public	function __construct( $path, $version ) {
		global $wp_version;

		//	set plugin's path
		$this->fptPath = $path;
		
		//	set plugin's url
		$this->fptUrl = WP_PLUGIN_URL . '/fpw-category-thumbnails';
		
		//	set version
		$this->fptVersion = $version;

		//	set WP version
		$this->wpVersion = $wp_version;

		//	read tte options
		$this->fptOptions = get_option( 'fpw_post_thumbnails_options' );
		
		add_action( 'after_setup_theme', array( &$this, 'enableThemeSupportForThumbnails' ), 999 ); 
		
		if ( is_array( $this->fptOptions ) ) {
			if ( $this->fptOptions[ 'content' ][ 'enabled' ] )
				add_filter( 'the_content', array( &$this, 'fptContent' ) );
			if ( $this->fptOptions[ 'excerpt' ][ 'enabled' ] ) 
				add_filter( 'the_excerpt', array( &$this, 'fptExcerpt' ) );
		}
	}
	
	//	enable post thumbnails support and add image sizes
	function enableThemeSupportForThumbnails() {
		if ( !current_theme_supports( 'post-thumbnails' ) ) 
			add_theme_support( 'post-thumbnails' );
		add_image_size( 'content-thumbnail', $this->fptOptions[ 'content' ][ 'width' ], $this->fptOptions[ 'content' ][ 'height' ], false );
		add_image_size( 'excerpt-thumbnail', $this->fptOptions[ 'excerpt' ][ 'width' ], $this->fptOptions[ 'excerpt' ][ 'height' ], false );
	}

	//	thumbnail for content filter
	function fptContent( $content ) {
		global $post;
		
	    ?>
    	<style type="text/css">
    	<!--
		.attachment-thumbnail-content,
		.wp-post-image-content {
    		float: <?php echo $this->fptOptions[ 'content' ][ 'position' ] ?>;
    		padding-top: <?php echo $this->fptOptions[ 'content' ][ 'padding_top' ] ?>px;
    		padding-left: <?php echo $this->fptOptions[ 'content' ][ 'padding_left' ] ?>px;
    		padding-bottom: <?php echo $this->fptOptions[ 'content' ][ 'padding_bottom' ] ?>px;
			padding-right: <?php echo $this->fptOptions[ 'content' ][ 'padding_right' ] ?>px;
    		margin-top: <?php echo $this->fptOptions[ 'content' ][ 'margin_top' ] ?>px;
    		margin-left: <?php echo $this->fptOptions[ 'content' ][ 'margin_left' ] ?>px;
    		margin-bottom: <?php echo $this->fptOptions[ 'content' ][ 'margin_bottom' ] ?>px;
    		margin-right: <?php echo $this->fptOptions[ 'content' ][ 'margin_right' ] ?>px;
    		width: <?php echo $this->fptOptions[ 'content' ][ 'width' ] ?>px;
		<?php
    	if ( $this->fptOptions[ 'content' ][ 'border' ] ) {
		?>
        	background-color: <?php echo $this->fptOptions[ 'content' ][ 'background_color' ] ?>;
        	border: <?php echo $this->fptOptions[ 'content' ][ 'border_width' ] ?>px solid <?php echo $this->fptOptions[ 'content' ][ 'border_color' ] ?>;
        	border-radius: <?php echo $this->fptOptions[ 'content' ][ 'border_radius' ] ?>px;
        	-moz-border-radius: <?php echo $this->fptOptions[ 'content' ][ 'border_radius' ] ?>px;
        	-webkit-border-radius: <?php echo $this->fptOptions[ 'content' ][ 'border_radius' ] ?>px;
		<?php	
		}
		?>
		}
    	-->
    	</style>
    	<?php

		$thumbID = get_post_meta( $post->ID, '_thumbnail_id', true );
	
		if ( !( '' === $thumbID ) ) {
		
			if ( 'ngg-' == substr( $thumbID, 0, 4 ) ) {
		
				if ( class_exists( 'nggdb' ) ) {
					$id = substr( $thumbID, 4 );
					$picture = nggdb::find_image( $id );
					if ( !$picture ) {
						$pic = '' ;
					} else {
						$pic 	= $picture->imageURL;
						$pic	= '<img class="wp-post-image-content ngg-image-' . $id .  
									'" src="' . $pic . '" />';
					}
				} else {
					$pic =	'';
				}
			} elseif ( wp_attachment_is_image( $thumbID ) ) {
				$pic = wp_get_attachment_image( $thumbID, 'content-thumbnail', NULL, array( 'class' => 'attachment-thumbnail-content' ) );
			} else {
				$pic = '';		
			}
		} else {
			$pic = '';
		}
		return $pic . $content;
	}
	
	//	thumbnail for excerpt filter
	function fptExcerpt( $excerpt ) {
		global $post;
		
	    ?>
    	<style type="text/css">
    	<!--
		.attachment-thumbnail-excerpt,
		.wp-post-image-excerpt {
    		float: <?php echo $this->fptOptions[ 'excerpt' ][ 'position' ] ?>;
    		padding-top: <?php echo $this->fptOptions[ 'excerpt' ][ 'padding_top' ] ?>px;
    		padding-left: <?php echo $this->fptOptions[ 'excerpt' ][ 'padding_left' ] ?>px;
    		padding-bottom: <?php echo $this->fptOptions[ 'excerpt' ][ 'padding_bottom' ] ?>px;
			padding-right: <?php echo $this->fptOptions[ 'excerpt' ][ 'padding_right' ] ?>px;
    		margin-top: <?php echo $this->fptOptions[ 'excerpt' ][ 'margin_top' ] ?>px;
    		margin-left: <?php echo $this->fptOptions[ 'excerpt' ][ 'margin_left' ] ?>px;
    		margin-bottom: <?php echo $this->fptOptions[ 'excerpt' ][ 'margin_bottom' ] ?>px;
    		margin-right: <?php echo $this->fptOptions[ 'excerpt' ][ 'margin_right' ] ?>px;
    		width: <?php echo $this->fptOptions[ 'excerpt' ][ 'width' ] ?>px;
		<?php
    	if ( $this->fptOptions[ 'excerpt' ][ 'border' ] ) {
		?>
        	background-color: <?php echo $this->fptOptions[ 'excerpt' ][ 'background_color' ] ?>;
        	border: <?php echo $this->fptOptions[ 'excerpt' ][ 'border_width' ] ?>px solid <?php echo $this->fptOptions[ 'excerpt' ][ 'border_color' ] ?>;
        	border-radius: <?php echo $this->fptOptions[ 'excerpt' ][ 'border_radius' ] ?>px;
        	-moz-border-radius: <?php echo $this->fptOptions[ 'excerpt' ][ 'border_radius' ] ?>px;
        	-webkit-border-radius: <?php echo $this->fptOptions[ 'excerp' ][ 'border_radius' ] ?>px;
		<?php	
		}
		?>
		}
    	-->
    	</style>
    	<?php

		$thumbID = get_post_meta( $post->ID, '_thumbnail_id', true );
	
		if ( !( '' === $thumbID ) ) {
		
			if ( 'ngg-' == substr( $thumbID, 0, 4 ) ) {
		
				if ( class_exists( 'nggdb' ) ) {
					$id = substr( $thumbID, 4 );
					$picture = nggdb::find_image( $id );
					if ( !$picture ) {
						$pic = '' ;
					} else {
						$pic 	= $picture->imageURL;
						$pic	= '<img class="wp-post-image-excerpt ngg-image-' . $id .  
									'" src="' . $pic . '" />';
					}
				} else {
					$pic =	' ';
				}
			} elseif ( wp_attachment_is_image( $thumbID ) ) {
				$pic = wp_get_attachment_image( $thumbID, 'excerpt-thumbnail', NULL, array( 'class' => 'attachment-thumbnail-excerpt' ) );
			} else {
				$pic = '';		
			}
		} else {
			$pic = '';
		}
		return $pic . $excerpt;
	}
	 
}
?>