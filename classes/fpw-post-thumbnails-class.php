<?php
//	prevent direct access
if ( preg_match( '#' . basename(__FILE__) . '#', $_SERVER[ 'PHP_SELF' ] ) )  
	die( "Direct access to this script is forbidden!" );

//	plugin's main class
class fpwPostThumbnails {
	var	$fptOptions;
	var	$fptPath;
	var	$fptUrl;
	var $fptVersion;
	var	$fptPage;
	var	$fptLocale;
	var	$translationURL;
	var	$translationPath;
	var $translationStatus;
	var $translationResponse;
	var	$wpVersion;
	
	//	constructor
	function __construct( $path, $version ) {
		global $wp_version;

		//	set WP version
		$this->wpVersion = $wp_version;
		
		//	set plugin's path
		$this->fptPath = $path;
		
		//	set plugin's url
		$this->fptUrl = WP_PLUGIN_URL . '/fpw-post-thumbnails';
		
		//	set version
		$this->fptVersion = $version;
		define( 'FPW_POST_THUMBNAILS_VERSION', $version );
		
		//	set locale
		$this->fptLocale = get_locale();

		//	set translation URL
		$this->translationURL = 'http://svn.wp-plugins.org/fpw-post-thumbnails/translations/' . 
								$this->fptVersion . '/fpw-fpt-' . $this->fptLocale . '.mo';
								
		//	set translation path
		$this->translationPath = $this->fptPath . '/languages/fpw-fpt-' . $this->fptLocale . '.mo';
		
		//	get post thumbnails options
		$this->fptOptions = get_option( 'fpw_post_thumbnails_options' );
		
		if ( ! is_array( $this->fptOptions ) ) {
			$this->fptOptions = $this->fptBuildOptions();
		} else {
			if ( !isset( $this->fptOptions[ 'content' ][ 'base' ] ) )
				$this->fptOptions[ 'content' ][ 'base' ] = 'width';
			if ( !isset( $this->fptOptions[ 'excerpt' ][ 'base' ] ) )
				$this->fptOptions[ 'excerpt' ][ 'base' ] = 'width';
		}
		
		//	actions and filters
		add_action( 'after_setup_theme', array( &$this, 'enableThemeSupportForThumbnails' ), 999 ); 
		add_action( 'init', array( &$this, 'init' ) );

		register_activation_hook( $this->fptPath . '/fpw-post-thumbnails.php', array( &$this, 'uninstallMaintenance' ) );
		
		//	actions below are not used in front end
		add_action( 'admin_menu', array( &$this, 'adminMenu' ) );
		
		//	AJAX group of actions
		add_action( 'wp_ajax_fpw_pt_update', array( &$this, 'fpw_pt_update_ajax' ) );
		add_action( 'wp_ajax_fpw_pt_language', array( &$this, 'fpw_pt_language_ajax' ) );
		add_action( 'wp_ajax_fpw_pt_copy_right', array( &$this, 'fpw_pt_copy_right_ajax' ) );
		add_action( 'wp_ajax_fpw_pt_copy_left', array( &$this, 'fpw_pt_copy_left_ajax' ) );

		add_action( 'after_plugin_row_fpw-post-thumbnails/fpw-post-thumbnails.php', array( &$this, 'afterPluginMeta' ), 10, 2 );

		add_filter( 'plugin_action_links_fpw-post-thumbnails/fpw-post-thumbnails.php', array( &$this, 'pluginLinks' ), 10, 2);
		add_filter( 'plugin_row_meta', array( &$this, 'pluginMetaLinks'), 10, 2 );
		
		$anyButtonPressed =
			(	isset( $_POST['submit-update'] ) || 
				isset( $_POST['submit-language'] ) ) ? true : false; 

		if ( $anyButtonPressed ) 
			$this->fptOptions[ 'abar' ] = ( isset( $_POST[ 'abar' ] ) ) ? true : false;
		if ( $this->fptOptions[ 'abar' ] ) 
			add_action( 'admin_bar_menu', array( &$this, 'pluginToAdminBar' ), 1010 );
	}
	
	//	add theme support for thumbnails
	function enableThemeSupportForThumbnails() {
		if ( !current_theme_supports( 'post-thumbnails' ) ) 
			add_theme_support( 'post-thumbnails' );
	}

	//	build FPW Post Thumbnails options
	function fptBuildOptions() {
		$opt = array(
					'clean'		=> false,
					'abar'		=> false,
					'content' 	=> array(
						'enabled'			=> false,
						'width'				=> 64,
						'height'			=> 64,
						'base'				=> 'width',
						'position'			=> 'left',
						'border'			=> false,
						'border_width'		=> 1,
						'border_radius'		=> 0,
						'border_color'		=> '#000000',
						'background_color'	=> '#FFFFFF',
						'padding_top'		=> 0,
						'padding_left'		=> 0,
						'padding_bottom'	=> 0,
						'padding_right'		=> 0,
						'margin_top'		=> 0,
						'margin_left'		=> 0,
						'margin_bottom'		=> 0,
						'margin_right'		=> 0 
					),
					'excerpt' => array(
						'enabled'			=> false,
						'width'				=> 64,
						'height'			=> 64,
						'base'				=> 'width',
						'position'			=> 'left',
						'border'			=> false,
						'border_width'		=> 1,
						'border_radius'		=> 0,
						'border_color'		=> '#000000',
						'background_color'	=> '#FFFFFF',
						'padding_top'		=> 0,
						'padding_left'		=> 0,
						'padding_bottom'	=> 0,
						'padding_right'		=> 0,
						'margin_top'		=> 0,
						'margin_left'		=> 0,
						'margin_bottom'		=> 0,
						'margin_right'		=> 0 
					) );
					
		update_option( 'fpw_post_thumbnails_options', $opt );
		return $opt;
	}

	//	check translation file availability
	function translationAvailable() {
		
    	//	if language file exist, do not load it again
		if ( is_readable( $this->translationPath ) ) 
			return 'installed';

		$this->translationResponse = wp_remote_get( $this->translationURL, array( 'timeout' => 300 ) );

		//	if no translation file exists exit the check
		if ( is_wp_error( $this->translationResponse ) || $this->translationResponse[ 'response' ][ 'code' ] != '200' )
			return 'not_exist';
		
		return 'available';		
	}
    
	//	register plugin's textdomain
	function init() {
		load_plugin_textdomain( 'fpw-fpt', false, 'fpw-post-thumbnails/languages/' );

		if ( !( 'en_US' == $this->fptLocale ) ) 
			$this->translationStatus = $this->translationAvailable();
	} 

	//	register admin menu
	function adminMenu() {
		$page_title = __( 'FPW Post Thumbnails', 'fpw-fpt' ) . ' (' . $this->fptVersion . ')';
		$menu_title = __( 'FPW Post Thumbnails', 'fpw-fpt' );
		$this->fptPage = add_theme_page( $page_title, $menu_title, 'manage_options', 
							'fpw-post-thumbnails', array( &$this, 'fptSettings' ) );
		
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueueScripts' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'enqueuePointerScripts' ) );
		add_action( 'load-' . $this->fptPage, array( &$this, 'fptHelp' ) );
	}

	//	register styles, scripts, and localize javascript
	function enqueueScripts( $hook ) {
		if ( $this->fptPage == $hook ) 
			require_once $this->fptPath . '/scripts/enqueuescripts.php';
	}
	
	//	enqueue pointer scripts
	function enqueuePointerScripts( $hook ) {
		if ( $this->fptPage == $hook )
			require_once $this->fptPath . '/scripts/enqueuepointerscripts.php';
	}

	// 	handle pointer
	public function custom_print_footer_scripts() {
		$pointer = 'fpwfpt' . str_replace( '.', '', $this->fptVersion );
    	$pointerContent  = '<h3>' . esc_js( __( "What's new in this version?", 'fpw-fpt' ) ) . '</h3>';
		$pointerContent .= '<li style="margin-left:25px;margin-top:20px;margin-right:10px;list-style:square">' . 
						   __( 'Removed built-in front end stylesheet and added dynamic CSS instead', 'fpw-fpt' ) . '</li>';
    	?>
    	<script type="text/javascript">
    	// <![CDATA[
    		jQuery(document).ready( function($) {
        		$('#fpt-settings-title').pointer({
        			content: '<?php echo $pointerContent; ?>',
        			position: 'top',
            		close: function() {
						jQuery.post( ajaxurl, {
							pointer: '<?php echo $pointer; ?>',
							action: 'dismiss-wp-pointer'
						});
            		}
				}).pointer('open');
			});
    	// ]]>
    	</script>
    	<?php
	}

	//	contextual help
	function fptHelp() {
		require_once $this->fptPath . '/help/fpthelp.php';
	}
	
	// AJAX wrapper to perform options update
	function fpw_pt_update_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require_once $this->fptPath . '/ajax/fptupdate.php';
	}

	// AJAX wrapper to perform translation file loading
	function fpw_pt_language_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require_once $this->fptPath . '/ajax/language.php';
	}

	// AJAX wrapper to perform options update
	function fpw_pt_copy_right_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require_once $this->fptPath . '/ajax/fptcopyright.php';
	}

	// AJAX wrapper to perform options update
	function fpw_pt_copy_left_ajax() {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
			require_once $this->fptPath . '/ajax/fptcopyleft.php';
	}

	//	add update information after plugin meta
	function afterPluginMeta( $file, $plugin_data ) {
		$current = get_site_transient( 'update_plugins' );
		if ( !isset( $current -> response[ $file ] ) ) 
			return false;
		$url = "http://fw2s.com/fpwpostthumbsupdate.txt";
		$update = wp_remote_fopen( $url );
		echo '<tr class="plugin-update-tr"><td></td><td></td><td class="plugin-update"><div class="update-message">' . 
			'<img class="alignleft" src="' . $this->fptUrl . '/images/Thumbs_Up.png" width="64">' . $update . '</div></td></tr>';
	}

	//	add link to Donation to plugins meta
	function pluginMetaLinks( $links, $file ) {
		if ( 'fpw-post-thumbnails/fpw-post-thumbnails.php' == $file ) 
			$links[] = '<a href="http://fw2s.com/payments-and-donations/" target="_blank">' . __( "Donate", "fpw-fpt" ) . '</a>';
		return $links;
	}
	
	//	add link to settings page in plugins list
	function pluginLinks( $links, $file ) {
   		$settings_link = '<a href="' . site_url( '/wp-admin/' ) . 'themes.php?page=fpw-post-thumbnails">' . __( 'Settings', 'fpw-fpt' ) . '</a>';
		array_unshift( $links, $settings_link );
    	return $links;
	}
	
	//	uninstall file maintenance
	function uninstallMaintenance() {
		if ( $this->fptOptions[ 'clean' ] ) {
			if ( file_exists( $this->fptPath . '/uninstall.txt' ) ) 
				rename( $this->fptPath . '/uninstall.txt', $this->fptPath . '/uninstall.php' );
		} else {
			if ( file_exists( $this->fptPath . '/uninstall.php' ) ) 
				rename( $this->fptPath . '/uninstall.php', $this->fptPath . '/uninstall.txt' );
		}
	}	
	
	//	add plugin to admin bar	
	function pluginToAdminBar() {
		if ( current_user_can( 'manage_options' ) ) {
			global 	$wp_admin_bar;
			
			$main = array(
				'id' => 'fpw_plugins',
				'title' => __( 'FPW Plugins', 'fpw-fpt' ),
				'href' => '#' );

			$subm = array(
				'id' => 'fpw_bar_post_thumbnails',
				'parent' => 'fpw_plugins',
				'title' => __( 'FPW Post Thumbnails', 'fpw-fpt' ),
				'href' => get_admin_url() . 'themes.php?page=fpw-post-thumbnails' );

			$addmain = ( is_array( $wp_admin_bar->get_node( 'fpw_plugins' ) ) ) ? false : true;

			if ( $addmain )
				$wp_admin_bar->add_menu( $main );
			$wp_admin_bar->add_menu( $subm );

		}
	}

	private function fptValidateInput( $p ) {

		$valuesToCheck = array( 
			'width',
			'height',
			'border_radius',
			'border_width',
			'padding_top',
			'padding_left',
			'padding_bottom',
			'padding_right',
			'margin_top',
			'margin_left',
			'margin_bottom',
			'margin_right'
		);
			
		$checkboxes = array(
			'enabled',
			'border'
		);
		
		$colorsToCheck = array(
			'border_color',
			'background_color'
		);

		$this->fptOptions[ 'clean' ] = ( isset( $p[ 'clean' ] ) ) ? true : false;
		$this->fptOptions[ 'abar' ] = ( isset( $p[ 'abar' ] ) ) ? true : false;

		foreach ( $checkboxes as $ck ) {
			$this->fptOptions[ 'content' ][ $ck ] = 
				( isset( $p[ 'content_' . $ck ] ) ) ? true : false;
			$this->fptOptions[ 'excerpt' ][ $ck ] = 
				( isset( $p[ 'excerpt_' . $ck ] ) ) ? true : false;
		}
		
		$this->fptOptions[ 'content' ][ 'base' ] = $p[ 'content_base' ];
		$this->fptOptions[ 'excerpt' ][ 'base' ] = $p[ 'excerpt_base' ];
		$this->fptOptions[ 'content' ][ 'position' ] = $p[ 'content_position' ];
		$this->fptOptions[ 'excerpt' ][ 'position' ] = $p[ 'excerpt_position' ];
			
		foreach ( $valuesToCheck as $val ) {
			$this->fptOptions[ 'content' ][ $val ] = $p[ 'content_' . $val ];
			$this->fptOptions[ 'excerpt' ][ $val ] = $p[ 'excerpt_' . $val ]; 
		}
		
		foreach( $colorsToCheck as $col ) {
			$this->fptOptions[ 'content' ][ $col ] = $p[ 'content_' . $col ];
			$this->fptOptions[ 'excerpt' ][ $col ] = $p[ 'excerpt_' . $col ];
		}
		
		$response = '';
		$valid = true;
		
		foreach ( $valuesToCheck as $val ) {
			if ( !ctype_digit( (string) $p[ 'content_' . $val ] ) ) { 
				$response = __( 'In Content panel field', 'fpw-fpt' ) . ' "' . 
							str_replace( '_', '-', $val ) . '" ' . 
							__( 'contains non-numeric characters.', 'fpw-fpt' );
				$valid = false;
				break;
			}
			if ( !ctype_digit( (string) $p[ 'excerpt_' . $val ] ) ) {
				$response = __( 'In Excerpt panel field', 'fpw-fpt' ) . ' "' . 
							str_replace( '_', '-', $val ) . '" ' . 
							__( 'contains non-numeric characters.', 'fpw-fpt' );
				$valid = false;
				break;
			}
		}
		
		if ( !$valid )  
			return $response;
		
		foreach( $colorsToCheck as $col ) {

			if ( !( 7 == strlen( $p[ 'content_' . $col ] ) ) || 
				 !( '#' == substr( $p[ 'content_' . $col ], 0, 1 ) ) ) {
				$response = __( 'In Content panel field', 'fpw-fpt' ) . ' "' . 
							str_replace( '_', '-', $col ) . '" ' . 
							__( "must start with '#' charcter followed by 6 hexadecimal digits.", "fpw-fpt" );
				break;
			}

			if ( !( 7 == strlen( $p[ 'excerpt_' . $col ] ) ) || 
				 !( '#' == substr( $p[ 'excerpt_' . $col ], 0, 1 ) ) ) {
				$response = __( 'In Excerpt panel field', 'fpw-fpt' ) . ' "' . 
							str_replace( '_', '-', $col ) . '" ' . 
							__( "must start with '#' charcter followed by 6 hexadecimal digits.", "fpw-fpt" );
				break;
			}

			// $ac = str_split( $p[ 'content_' . $col ] );
			$ac = substr( $p[ 'content_' . $col ], 1, strlen( $p[ 'content_' . $col ] ) - 1 );
			// $ae = str_split( $p[ 'excerpt_' . $col ] );
			$ae = substr( $p[ 'excerpt_' . $col ], 1, strlen( $p[ 'excerpt_' . $col ] ) - 1 );
			
			if ( !ctype_xdigit( $ac ) ) {
				$response = __( 'In Content panel field', 'fpw-fpt' ) . ' "' . 
							str_replace( '_', '-', $col ) . '" ' . 
							__( "must start with '#' charcter followed by 6 hexadecimal digits.", "fpw-fpt" );
				break;
			}

			if ( !ctype_xdigit( $ae ) ) {
				$response = __( 'In Excerpt panel field', 'fpw-fpt' ) . ' "' . 
							str_replace( '_', '-', $col ) . '" ' . 
							__( "must start with '#' charcter followed by 6 hexadecimal digits.", "fpw-fpt" );
				break;
			}
		}
		
		return $response;
	} 
	
	//	copy values between panels
	private function copyPanels( $where ) {
		$valuesToCopy = array(
			'enabled',
			'width',
			'height',
			'base',
			'position',
			'border',
			'border_radius',
			'border_width',
			'border_color',
			'background_color',
			'padding_top',
			'padding_left',
			'padding_bottom',
			'padding_right',
			'margin_top',
			'margin_left',
			'margin_bottom',
			'margin_right'
		);
		$from 	= ( 'right' == $where ) ? 'content' : 'excerpt';
		$to		= ( 'right' == $where ) ? 'excerpt' : 'content';
		
		foreach ( $valuesToCopy as $value )
			$this->fptOptions[ $to ][ $value ] = $this->fptOptions[ $from ][ $value ];
	}
	
	//	FPW Post Thumbnails - settings page
	function fptSettings() {
		
		//	check if form was submited
		if ( isset( $_POST[ 'submit-update' ] ) || 
			 isset( $_POST[ 'submit-language' ] ) || 
			 isset( $_POST[ 'submit-copy-right' ] ) ||
			 isset( $_POST[ 'submit-copy-left' ] ) ) {
			if ( !isset( $_POST[ 'fpw-fpt-nonce' ] ) ) 
				die( '<br />&nbsp;<br /><p style="padding-left: 20px; color: red"><strong>' . 
					 __( 'You did not send any credentials!', 'fpw-fpt' ) . '</strong></p>' );
			if ( !wp_verify_nonce( $_POST[ 'fpw-fpt-nonce' ], 'fpw-fpt-nonce' ) ) 
				die( '<br />&nbsp;<br /><p style="padding-left: 20px; color: red;"><strong>' . 
					 __( 'You did not send the right credentials!', 'fpw-fpt' ) . '</strong></p>' );

			$resp = $this->fptValidateInput( $_POST );

		}


		//	HTML starts here
		echo 	'<div class="wrap">';
		
		echo	'<div id="icon-themes" class="icon32"></div><h2 id="fpt-settings-title">' . 
				__( 'FPW Post Thumbnails', 'fpw-fpt' ) . ' (' . 
				$this->fptVersion . ')</h2>';

		echo	'<div style="display: none; position: relative">';

		//	notification division for AJAX
		echo 	'<div id="fpt-message" class="updated" style="position: absolute; ' . 
				'display: none; z-index: 10;"><p>&nbsp;</p></div>';
				
		echo	'</div>';
				
		//	the form starts here
		echo '<form name="fpw_post_thmb_form" action="?page=fpw-post-thumbnails" method="post">';
		
		//	protect this form with nonce
		echo '<input name="fpw-fpt-nonce" type="hidden" value="' . wp_create_nonce( 'fpw-fpt-nonce' ) . '" />';

		echo	'<div style="margin-top: 10px; margin-bottom: 10px;">';
		
		echo	'<input title="' . 
				__( 'write modified options to the database', 'fpw-fpt' ) . 
				'" id="fpt-update" class="button-primary fpt-submit" ' . 
				'type="submit" name="submit-update" value=" ' . 
				__( 'Update Options', 'fpw-fpt' ) . ' " /> ';
		
		if ( !( 'en_US' == $this->fptLocale ) && 
				( ( 'available' == $this->translationStatus ) || 
				( 'not_exist' == $this->translationStatus ) ) )  
		
			echo	'<input title="' . 
					__( 'load language file for your version', 'fpw-fpt' ) . 
					'" id="fpt-language" class="button-primary fpt-submit" ' . 
					'type="submit" name="submit-language" value=" ' . 
					__( 'Get Language File', 'fpw-fpt' ) . ' " />';
		
			
        echo	'</div>';
		
		//	options section
		echo '<div id="fpw-fpt-options">';
		
		//	cleanup checkbox
		echo '<input type="checkbox" class="fpt-option-group" id="box-clean" name="clean" value="clean"';
		if ( $this->fptOptions[ 'clean' ] ) 
			echo ' checked';
		echo '> ' . __( "Remove plugin's data from database on uninstall", 'fpw-fpt' ) . '<br />';

		//	add plugin to admin bar checkbox
		echo '<input type="checkbox" class="fpt-option-group" id="box-abar" name="abar" value="abar"';
		if ( $this->fptOptions[ 'abar' ] ) 
			echo ' checked';
		echo '> ' . __( 'Add this plugin to the Admin Bar', 'fpw-fpt' ) . '<br />';

		//	end of options section
		echo '</div>';

		//	notification division
		if ( isset( $_POST[ 'submit-update' ] ) ) {
			echo '<div id="fpt-message" class="updated fade" style="margin-bottom: 10px;"><p><strong>';
			if ( '' == $resp ) {
				$updateOK = update_option( 'fpw_post_thumbnails_options', $this->fptOptions );
				if ( $updateOK ) {
					echo __( 'Options updated successfully.', 'fpw-fpt' );				
				} else {
					echo __( 'No changes detected. Nothing to update.', 'fpw-fpt' );				
				}
			} else {
				echo $resp;
			}
			echo '</strong></p></div>';
		} 
		
		if ( isset( $_POST[ 'submit-language' ] ) ) {
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
			echo '<div id="fpt-message" class="updated fade" style="margin-bottom: 10px;"><p><strong>' . $m;
			echo '</strong></p></div>';
		}			

		if ( isset( $_POST[ 'submit-copy-right' ] ) ) { 
			if ( '' == $resp ) {
				$this->copyPanels( 'right' );
				$m = __( 'Values copied from the left to the right panel.', 'fpw-fpt' );
			} else {
				$m = $resp;			
			}
			echo '<div id="fpt-message" class="updated fade" style="margin-bottom: 10px;"><p><strong>' . $m;
			echo '</strong></p></div>';
		
		} elseif ( isset( $_POST[ 'submit-copy-left' ] ) ) {
			if ( '' == $resp ) {
				$this->copyPanels( 'left' );
				$m = __( 'Values copied from the right to the left panel.', 'fpw-fpt' );
			} else {
				$m = $resp;
			}
			echo '<div id="fpt-message" class="updated fade" style="margin-bottom: 10px;"><p><strong>' . $m;
			echo '</strong></p></div>';
		}
		
		echo	'<div class="metabox-holder" style="width:49%; float:left; margin-right:10px;">';
 
        echo	'<div class="postbox">';
        
		echo	'<h3 style="cursor:default; background-color: #F1F1F1; background-image: -moz-linear-gradient(center top , #F9F9F9, #CCCCCC);">' . 
				__( 'Content thumbnails enabled:', 'fpw-fpt' ) . ' <input type="checkbox" class="fpt-option-group" ' . 
				'id="box-content-enabled" name="content_enabled" value="content_enabled"';
		if ( $this->fptOptions[ 'content' ][ 'enabled' ] ) 
		echo	' checked';
		echo	'> <input type="submit" title="' . __( 'copy all values to the right panel', 'fpw-fpt' ) . 
				'" id="fpt-copy-right" name="submit-copy-right" value="' . 
				__( 'Copy', 'fpw-fpt' ) . ' &raquo;' . 
				'" class="button-secondary fpt-submit"> <input alt="#TB_inline?height=300&width=400&inlineId=fptContentPreviev" ' . 
				'title="' . __( 'Content - Preview', 'fpw-fpt' ) . '" class="thickbox button-secondary" ' . 
				'type="button" value="' . __( 'Preview', 'fpw-fpt' ) . '" id="content-preview" />' . 
				'</h3>';
				
		echo	'<div id="fptContentPreviev" class="thickbox" style="display: none;">';
		echo	'<div class="entry"><img class="wp-post-image-content" src="' . 
				$this->fptUrl . '/images/Frank.jpg" /><p style="text-align: justify">Lorem ipsum dolor sit amet consectetuer ' . 
				'nunc enim laoreet pellentesque augue. Vestibulum Vivamus lacus dis ' . 
				'Nunc semper laoreet platea Pellentesque ultrices metus. Tincidunt ' . 
				'ridiculus nec Lorem orci metus hac Nam Lorem nascetur orci. Sed et ' . 
				'quis aliquet urna tortor ut neque nec elit nibh. At justo condimentum ' . 
				'sit Aenean ac vitae aliquam quis adipiscing dolor. Nibh leo nibh ' . 
				'aliquam laoreet elit convallis condimentum volutpat id consequat. Ut quis.</p>';
		echo	'</div></div>';			
        
		echo	'<div class="inside" style="padding:0px 6px 0px 6px;">';
		
		echo	'<table style="width:100%">';
		
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'width' ] . '" name="content_width" id="content-width" class="content-width-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">width</td>';
		echo	'</tr>';
		
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'height' ] . '" name="content_height" id="content-height" class="content-height-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">height</td>';
		echo	'</tr>';
		
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle"><select name="content_base" id="content-base" style="width: 70px">' . 
				'<option value="width"';
		if ( 'width' == $this->fptOptions[ 'content' ][ 'base' ] ) 
			echo ' selected="selected"'; 
		echo	'>width</option>' . 
				'<option value="height"';
		if ( 'height' == $this->fptOptions[ 'content' ][ 'base' ] ) 
			echo ' selected="selected"'; 
		echo 	'>height</option></select></td>'; 
		echo	'<td style="verical-align: middle">scaling base</td>';
		echo	'</tr>';
		
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle"><select name="content_position" id="content-position" style="width: 70px">' . 
				'<option value="left"';
		if ( 'left' == $this->fptOptions[ 'content' ][ 'position' ] ) 
			echo ' selected="selected"'; 
		echo	'>left</option>' . 
				'<option value="right"';
		if ( 'right' == $this->fptOptions[ 'content' ][ 'position' ] ) 
			echo ' selected="selected"'; 
		echo 	'>right</option></select></td>'; 
		echo	'<td style="verical-align: middle">float</td>';
		echo	'</tr>';
		
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="checkbox" class="fpt-option-group" id="box-content-border" name="content_border" value="content_border"';
		if ( $this->fptOptions[ 'content' ][ 'border' ] ) 
			echo	' checked';
		echo	'></td>';
		echo	'<td style="verical-align: middle">border</td>';
		echo	'</tr>';
		
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'border_width' ] . '" name="content_border_width" id="content-border-width" class="content-border-width-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">border-width</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'border_radius' ] . '" name="content_border_radius" id="content-border-radius" class="content-border-radius-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">border-radius</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<div class="color-picker" style="position: relative;">';
		echo	'<input style="text-transform: uppercase" type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'border_color' ] . '" name="content_border_color" id="content-border-color" class="content-border-color-value" />';
		echo	'<div style="position: absolute; z-index: 10" id="colorpicker-content-border-color"></div>';
		echo	'</div></td>';
		echo	'<td style="verical-align: middle">border-color</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<div class="color-picker" style="position: relative;">';
		echo	'<input style="text-transform: uppercase" type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'background_color' ] . '" name="content_background_color" id="content-background-color" class="content-background-color-value" />';
		echo	'<div style="position: absolute; z-index: 10" id="colorpicker-content-background-color"></div>';
		echo	'</div></td>';
		echo	'<td style="verical-align: middle">background-color</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'padding_top' ] . '" name="content_padding_top" id="content-padding-top" class="content-padding-top-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">padding-top</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'padding_right' ] . '" name="content_padding_right" id="content-padding-right" class="content-padding-right-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">padding-right</td>';
		echo	'</tr>';
		
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'padding_bottom' ] . '" name="content_padding_bottom" id="content-padding-bottom" class="content-padding-bottom-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">padding-bottom</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'padding_left' ] . '" name="content_padding_left" id="content-padding-left" class="content-padding-left-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">padding-left</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'margin_top' ] . '" name="content_margin_top" id="content-margin-top" class="content-margin-top-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">margin-top</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'margin_right' ] . '" name="content_margin_right" id="content-margin-right" class="content-margin-right-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">margin-right</td>';
		echo	'</tr>';
		
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'margin_bottom' ] . '" name="content_margin_bottom" id="content-margin-bottom" class="content-margin-bottom-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">margin-bottom</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'content' ][ 'margin_left' ] . '" name="content_margin_left" id="content-margin-left" class="content-margin-left-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">margin-left</td>';
		echo	'</tr>';

		echo	'</table>';
		        
		echo	'</div>';
        
		echo	'</div>';
 
    	echo	'</div>';
 
    	echo	'<div class="metabox-holder" style="width:49%; float:left;">';
 
        echo	'<div class="postbox">';
        
		echo	'<h3 style="cursor:default;background-color: #F1F1F1; background-image: -moz-linear-gradient(center top , #F9F9F9, #CCCCCC);">' . 
				__( 'Excerpt thumbnails enabled:', 'fpw-fpt' ) . ' <input type="checkbox" class="fpt-option-group" ' . 
				'id="box-excerpt-enabled" name="excerpt_enabled" value="excerpt_enabled"';
		if ( $this->fptOptions[ 'excerpt' ][ 'enabled' ] ) 
		echo	' checked';
		echo	'> <input type="submit" title="' . __( 'copy all values to the left panel', 'fpw-fpt' ) . 
				'" id="fpt-copy-left" name="submit-copy-left" value="&laquo; ' . 
				__( 'Copy', 'fpw-fpt' ) . 
				'" class="button-secondary fpt-submit"> <input alt="#TB_inline?height=300&width=400&inlineId=fptExcerptPreviev" ' . 
				'title="' . __( 'Excerpt - Preview', 'fpw-fpt' ) . '" class="thickbox button-secondary" ' . 
				'type="button" value="' . __( 'Preview', 'fpw-fpt' ) . '" id="excerpt-preview" /></h3>';

		echo	'<div id="fptExcerptPreviev" class="thickbox" style="display: none;">';
		echo	'<div class="excerpt"><img class="wp-post-image-excerpt" src="' . 
				$this->fptUrl . '/images/Frank.jpg" /><p style="text-align: justify">Lorem ipsum dolor sit amet consectetuer ' . 
				'nunc enim laoreet pellentesque augue. Vestibulum Vivamus lacus dis ' . 
				'Nunc semper laoreet platea Pellentesque ultrices metus. Tincidunt ' . 
				'ridiculus nec Lorem orci [...]</p>';
		echo	'</div></div>';			
        
		echo	'<div class="inside" style="padding:0px 6px 0px 6px;">';
        
		echo	'<table style="width:100%">';
		
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'width' ] . '" name="excerpt_width" id="excerpt-width" class="excerpt-width-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">width</td>';
		echo	'</tr>';
		
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'height' ] . '" name="excerpt_height" id="excerpt-height" class="excerpt-height-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">height</td>';
		echo	'</tr>';
		
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle"><select name="excerpt_base" id="excerpt-base" style="width: 70px">' . 
				'<option value="width"';
		if ( 'width' == $this->fptOptions[ 'excerpt' ][ 'base' ] ) 
			echo ' selected="selected"'; 
		echo	'>width</option>' . 
				'<option value="height"';
		if ( 'height' == $this->fptOptions[ 'excerpt' ][ 'base' ] ) 
			echo ' selected="selected"'; 
		echo 	'>height</option></select></td>'; 
		echo	'<td style="verical-align: middle">scaling base</td>';
		echo	'</tr>';
		
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle"><select name="excerpt_position" id="excerpt-position" style="width: 70px">' . 
				'<option value="left"';
		if ( 'left' == $this->fptOptions[ 'excerpt' ][ 'position' ] ) 
			echo ' selected="selected"'; 
		echo	'>left</option>' . 
				'<option value="right"';
		if ( 'right' == $this->fptOptions[ 'excerpt' ][ 'position' ] ) 
			echo ' selected="selected"'; 
		echo 	'>right</option></select></td>'; 
		echo	'<td style="verical-align: middle">float</td>';
		echo	'</tr>';
		
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="checkbox" class="fpt-option-group" id="box-excerpt-border" name="excerpt_border" value="excerpt_border"';
		if ( $this->fptOptions[ 'excerpt' ][ 'border' ] ) 
			echo	' checked';
		echo	'></td>';
		echo	'<td style="verical-align: middle">border</td>';
		echo	'</tr>';
		
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'border_width' ] . '" name="excerpt_border_width" id="excerpt-border-width" class="excerpt-border-width-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">border-width</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'border_radius' ] . '" name="excerpt_border_radius" id="excerpt-border-radius" class="excerpt-border-radius-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">border-radius</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<div class="color-picker" style="position: relative;">';
		echo	'<input style="text-transform: uppercase" type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'border_color' ] . '" name="excerpt_border_color" id="excerpt-border-color" class="excerpt-border-color-value" />';
		echo	'<div style="position: absolute; z-index: 10" id="colorpicker-excerpt-border-color"></div>';
		echo	'</div></td>';
		echo	'<td style="verical-align: middle">border-color</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<div class="color-picker" style="position: relative;">';
		echo	'<input style="text-transform: uppercase" type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'background_color' ] . '" name="excerpt_background_color" id="excerpt-background-color" class="excerpt-background-color-value" />';
		echo	'<div style="position: absolute; z-index: 10" id="colorpicker-excerpt-background-color"></div>';
		echo	'</div></td>';
		echo	'<td style="verical-align: middle">background-color</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'padding_top' ] . '" name="excerpt_padding_top" id="excerpt-padding-top" class="excerpt-padding-top-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">padding-top</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'padding_right' ] . '" name="excerpt_padding_right" id="excerpt-padding-right" class="excerpt-padding-right-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">padding-right</td>';
		echo	'</tr>';
		
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'padding_bottom' ] . '" name="excerpt_padding_bottom" id="excerpt-padding-bottom" class="excerpt-padding-bottom-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">padding-bottom</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'padding_left' ] . '" name="excerpt_padding_left" id="excerpt-padding-left" class="excerpt-padding-left-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">padding-left</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'margin_top' ] . '" name="excerpt_margin_top" id="excerpt-margin-top" class="excerpt-margin-top-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">margin-top</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'margin_right' ] . '" name="excerpt_margin_right" id="excerpt-margin-right" class="excerpt-margin-right-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">margin-right</td>';
		echo	'</tr>';
		
		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'margin_bottom' ] . '" name="excerpt_margin_bottom" id="excerpt-margin-bottom" class="excerpt-margin-bottom-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">margin-bottom</td>';
		echo	'</tr>';

		echo	'<tr>';
		echo	'<td style="width: 30%; verical-align: middle">';
		echo	'<input type="text" size="7" maxlength="7" value="' . 
					$this->fptOptions[ 'excerpt' ][ 'margin_left' ] . '" name="excerpt_margin_left" id="excerpt-margin-left" class="excerpt-margin-left-value" />';
		echo	' px</td>';
		echo	'<td style="verical-align: middle">margin-left</td>';
		echo	'</tr>';

		echo	'</table>';
        
		echo	'</div>';
 
        echo	'</div>';
 
    	echo	'</div>';
 
		//	end of form
		echo	'</form>';
 
		echo	'<div style="clear:both;"></div>';
	}
}
?>