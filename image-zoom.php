<?php
/*
Plugin Name: Image Zoom
Plugin Name: zoom, highslide, image, panorama
Description: <p>Allow to dynamically zoom on images in posts/pages/... </p><p>When clicked, the image will dynamically scale-up. Please note that you have to insert image normally with the wordpress embedded editor.</p><p>You may configure :</p><ul><li>The max width/height of the image;</li><li>The transition delay </li><li>The position of the buttons</li><li>The auto-start of the slideshow</li><li>the opacity of the background</li></ul><p>If the image does not scale-up, please verify that the HTML looks like the following : &lt;a href=' '&gt;&lt;img src=' '&gt;&lt;/a&gt;.</p><p>This plugin implements the highslide javascript library. </p><p>Plugin developped from the orginal plugin <a href="http://wordpress.org/extend/plugins/zoom-highslide/">Zoom-Hishslide</a>. </p><p>This plugin is under GPL licence (please note that the <a href="http://highslide.com/">highslide library</a> is not under GPL licence but under Creative Commons Attribution-NonCommercial 2.5 License. This means you need the author's permission to use Highslide JS on commercial websites.) </p>
Version: 1.4.1
Author: SedLex
Author Email: sedlex@sedlex.fr
Framework Email: sedlex@sedlex.fr
Author URI: http://www.sedlex.fr/
Plugin URI: http://wordpress.org/extend/plugins/image-zoom/
License: GPL3
*/

require_once('core.php') ; 

class imagezoom extends pluginSedLex {
	/** ====================================================================================================================================================
	* Initialisation du plugin
	* 
	* @return void
	*/
	static $instance = false;
	static $path = false;
	
	var $image_type ;

	protected function _init() {
		// Configuration
		$this->pluginName = 'Image Zoom' ; 
		$this->tableSQL = "" ; 
		$this->table_name = $wpdb->prefix . "pluginSL_" . get_class() ; 
		
		$this->path = __FILE__ ; 
		$this->pluginID = get_class() ; 
		
		//Init et des-init
		register_activation_hook(__FILE__, array($this,'install'));
		register_deactivation_hook(__FILE__, array($this,'uninstall'));
		
		//Paramètres supplementaires
		add_action('wp_print_scripts', array($this,'header_init'));
		add_filter('the_excerpt', array($this,'zoom'),100);
		add_filter('the_content', array($this,'zoom'),100);
		
		$this->image_type = "(bmp|gif|jpeg|jpg|png)" ;
	}
	
	/**
	 * Function to instantiate our class and make it a singleton
	 */
	public static function getInstance() {
		if ( !self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	


	/** ====================================================================================================================================================
	* Define the default option value of the plugin
	* 
	* @return variant of the option
	*/
	function get_default_option($option) {
		switch ($option) {
			case 'widthRestriction'		 	: return 640 	; break ; 
			case 'heightRestriction'		: return 900 	; break ; 
			case 'show_interval'		 	: return 5000 	; break ; 
			case 'controler_position'		: return 'top center' 	; break ; 
			case 'background_opacity'		: return "0.8" ; break ; 
			case 'slideshow_autostart'		: return false ; break ; 
			case 'tra_load'		: return "Load" ; break ; 
			case 'tra_expand'		: return "Expand" ; break ; 
			case 'tra_previous'		: return "Previous" ; break ; 
			case 'tra_next'		: return "Next" ; break ; 
			case 'tra_move'		: return "Move" ; break ; 
			case 'tra_close'		: return "Close" ; break ; 
			case 'tra_play'		: return "Play" ; break ; 
			case 'tra_pause'		: return "Pause" ; break ; 
			case 'tra_restore'		: return "Restore" ; break ; 
		}
		return null ;
	}
	
	/** ====================================================================================================================================================
	* Load the configuration of the javascript in the header
	* 
	* @return variant of the option
	*/
	function header_init() {
		ob_start() ; 
	?>
			hs.graphicsDir = '<?php echo WP_PLUGIN_URL."/".str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); ?>img/';
			hs.align = 'center';
			hs.registerOverlay({
				html: '<div class="closebutton" onclick="return hs.close(this)" title="Close"></div>',
				position: 'top right',
				useOnHtml: true,
				fade: 2 // fading the semi-transparent overlay looks bad in IE
			});

			hs.lang = {
			   loadingText :     '<?php echo $this->get_param('tra_load') ; ?>',
			   loadingTitle :    '<?php echo $this->get_param('tra_load') ; ?>',
			   fullExpandTitle : '<?php echo $this->get_param('tra_expand') ; ?>',
			   fullExpandText :  '<?php echo $this->get_param('tra_expand') ; ?>',
			   previousText :    '<?php echo $this->get_param('tra_previous') ; ?>',
			   previousTitle :   '<?php echo $this->get_param('tra_previous') ; ?>',
			   nextText :        '<?php echo $this->get_param('tra_next') ; ?>',
			   nextTitle :       '<?php echo $this->get_param('tra_next') ; ?>',
			   moveTitle :       '<?php echo $this->get_param('tra_move') ; ?>',
			   moveText :        '<?php echo $this->get_param('tra_move') ; ?>',
			   closeText :       '<?php echo $this->get_param('tra_close') ; ?>',
			   closeTitle :      '<?php echo $this->get_param('tra_close') ; ?>',
			   playText :        '<?php echo $this->get_param('tra_play') ; ?>',
			   playTitle :       '<?php echo $this->get_param('tra_play') ; ?>',
			   pauseText :       '<?php echo $this->get_param('tra_pause') ; ?>',
			   pauseTitle :      '<?php echo $this->get_param('tra_pause') ; ?>',
			   restoreTitle :    '<?php echo $this->get_param('tra_restore') ; ?>'
			};			
			
			hs.transitions = ['expand', 'crossfade'];
			hs.outlineType = 'rounded-white';
			hs.wrapperClassName = 'controls-in-heading';
			hs.showCredits=false;
			hs.fadeInOut = true;
			hs.dimmingOpacity = <?php echo $this->get_param('background_opacity');?>;
		
			// Add the controlbar
			hs.addSlideshow({
				interval: <?php echo $this->get_param('show_interval');?>,
				repeat: true,
				useControls: true,
				fixedControls: 'fit',
				overlayOptions: {
					opacity: 0.9,
					offsetX: 0,
					offsetY: -10,
					position: <?php echo '\''.$this->get_param('controler_position').'\'' ?>,
					hideOnMouseOut: true
				}
			});			
						
	<?php 
		$content = ob_get_clean() ; 
		$this->add_inline_js($content) ; 
	}
	
	/** ====================================================================================================================================================
	* Load the configuration of the javascript in the header
	* 
	* @return variant of the option
	*/
	function zoom($string) {
		$pattern = '/(<a([^>]*?)href="([^"]*[.])'.$this->image_type.'"([^>]*?)>([^<]|<br)*<img([^>]*?)src="([^"]*[.])'.$this->image_type.'"([^>]*?)\>([^<]|<br)*<\/a>)/iesU';
		$autostart = "false" ; 
		if ( $this->get_param('slideshow_autostart') ) {
			$autostart = "true" ; 
		}
		$replacement = 'stripslashes("<a\2href=\"\3\4\" class=\"highslide\" onclick=\"return hs.expand(this , { maxWidth: '.$this->get_param('widthRestriction').', maxHeight: '.$this->get_param('heightRestriction').', autoplay: '.$autostart.' });\"\5>\6<img\7src=\"\8\9\" \10>\11</a>")';
		return preg_replace($pattern, $replacement, $string);
	}

	/** ====================================================================================================================================================
	* The configuration page
	* 
	* @return void
	*/
	function configuration_page() {
		global $wpdb;
	
		?>
		<div class="wrap">
			<div id="icon-themes" class="icon32"><br></div>
			<h2><?php echo $this->pluginName ?></h2>
		</div>
		<div style="padding:20px;">
			<?php echo $this->signature ; ?>
			
			<!--debut de personnalisation-->
		<?php
		
			// On verifie que les droits sont corrects
			$this->check_folder_rights( array() ) ; 	
			
			//==========================================================================================
			//
			// Mise en place du systeme d'onglet
			//		(bien mettre a jour les liens contenu dans les <li> qui suivent)
			//
			//==========================================================================================
			
			
			$tabs = new adminTabs() ; 
			echo "<p>".__('This plugin allows a dynamic zoom on the images (based on the highslide javascript library)', $this->pluginID)."</p>" ; 
			ob_start() ; 
				$params = new parametersSedLex($this, 'tab-parameters') ; 
				$params->add_title(__('What are the clipped dimensions of the zoomed image?',$this->pluginID)) ; 
				$params->add_param('widthRestriction', __('Max width:',$this->pluginID)) ; 
				$params->add_param('heightRestriction', __('Max height:',$this->pluginID)) ; 
				
				$params->add_title(__('What is the text for the frontend?',$this->pluginID)) ; 
				$params->add_param('tra_load', __('Load:',$this->pluginID)) ; 
				$params->add_param('tra_expand', __('Expand:',$this->pluginID)) ; 
				$params->add_param('tra_previous', __('Previous:',$this->pluginID)) ; 
				$params->add_param('tra_next', __('Next:',$this->pluginID)) ; 
				$params->add_param('tra_move', __('Move:',$this->pluginID)) ; 
				$params->add_param('tra_close', __('Close:',$this->pluginID)) ; 
				$params->add_param('tra_play', __('Play:',$this->pluginID)) ; 
				$params->add_param('tra_pause', __('Pause:',$this->pluginID)) ; 
				$params->add_param('tra_restore', __('Restore:',$this->pluginID)) ; 
				
				$params->add_title(__('What are the other parameters?',$this->pluginID)) ; 
				$params->add_param('show_interval', __('Transition time if the slideshow is on:',$this->pluginID)) ; 
				$params->add_param('slideshow_autostart', __('Auto-start the slideshow when launched:',$this->pluginID)) ; 
				$params->add_param('controler_position', __('The position of the button (play, next, ...) (e.g top center):',$this->pluginID)) ; 
				$params->add_param('background_opacity', __('The opacity of the background:',$this->pluginID)) ; 
				
				$params->flush() ; 
			$tabs->add_tab(__('Parameters',  $this->pluginID), ob_get_clean() , WP_PLUGIN_URL.'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__))."core/img/tab_param.png") ; 	

						
			ob_start() ; 
				$plugin = str_replace("/","",str_replace(basename(__FILE__),"",plugin_basename( __FILE__))) ; 
				$trans = new translationSL($this->pluginID, $plugin) ; 
				$trans->enable_translation() ; 
			$tabs->add_tab(__('Manage translations',  $this->pluginID), ob_get_clean() , WP_PLUGIN_URL.'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__))."core/img/tab_trad.png") ; 	

			ob_start() ; 
				$plugin = str_replace("/","",str_replace(basename(__FILE__),"",plugin_basename( __FILE__))) ; 
				$trans = new feedbackSL($plugin,  $this->pluginID) ; 
				$trans->enable_feedback() ; 
			$tabs->add_tab(__('Give feedback',  $this->pluginID), ob_get_clean() , WP_PLUGIN_URL.'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__))."core/img/tab_mail.png") ; 	
			
			ob_start() ; 
				$trans = new otherPlugins("sedLex", array('wp-pirates-search')) ; 
				$trans->list_plugins() ; 
			$tabs->add_tab(__('Other plugins',  $this->pluginID), ob_get_clean() , WP_PLUGIN_URL.'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__))."core/img/tab_plug.png") ; 	
			

			echo $tabs->flush() ; 
			
			echo $this->signature ; ?>
		</div>
		<?php
	}
}

$updatemessage = imagezoom::getInstance();

?>