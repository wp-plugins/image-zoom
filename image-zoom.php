<?php
/*
Plugin Name: Image Zoom
Description: <p>Allow to zoom dynamically on images in posts/pages/... </p><p>This plugin implements the highslide javascript library. </p><p>Plugin developped from the orginal plugin <a href="http://wordpress.org/extend/plugins/zoom-highslide/">Zoom-Hishslide</a>. </p><p>This plugin is under GPL licence (please note that the <a href="http://highslide.com/">highslide library</a> is not under GPL licence but under Creative Commons Attribution-NonCommercial 2.5 License. This means you need the author's permission to use Highslide JS on commercial websites.) </p>
Version: 1.0.2
Author: SedLex
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
		$this->path = __FILE__ ; 
		$this->pluginID = get_class() ; 
		
		//Init et des-init
		register_activation_hook(__FILE__, array($this,'install'));
		register_deactivation_hook(__FILE__, array($this,'uninstall'));
		
		//Paramètres supplementaires
		add_action('init', array($this,'zoom_highslide_javascript'));
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
		}
		return null ;
	}
	
	/** ====================================================================================================================================================
	* Load the javascript
	* 
	* @return variant of the option
	*/
	function zoom_highslide_javascript() {
		if ( !function_exists('wp_enqueue_script') || is_admin() ) return;
		wp_enqueue_script('prototype');
		wp_enqueue_script('scriptaculous-effects');
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

			
			
			hs.transitions = ['expand', 'crossfade'];
			hs.outlineType = 'rounded-white';
			hs.wrapperClassName = 'controls-in-heading';
			hs.showCredits=false;
			hs.fadeInOut = true;
			hs.dimmingOpacity = <?php echo $this->get_param('background_opacity');?>;
		
			// Add the controlbar
			hs.addSlideshow({
				//slideshowGroup: 'group1',
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
		//echo '<script type="text/javascript">' ; 
		//echo $content ;
		//echo '</script>' ; 

	}
	
	/** ====================================================================================================================================================
	* Load the configuration of the javascript in the header
	* 
	* @return variant of the option
	*/
	function zoom($string) {
		$pattern = '/(<a href="([^"]*.)'.$this->image_type.'"><img(.*?)src="([^"]*.)'.$this->image_type.'"(.*?)\><\/a>)/ie';
		$replacement = 'stripslashes("<a href=\"\2\3\" class=\"highslide\" onclick=\"return hs.expand(this , { maxWidth: '.$this->get_param('widthRestriction').', maxHeight: '.$this->get_param('heightRestriction').' });\"><img\4src=\"\5\6\" \7></a>")';
		return preg_replace($pattern, $replacement, $string);
	}



	/** ====================================================================================================================================================
	* The configuration page
	* 
	* @return void
	*/
	function configuration_page() {
		global $wpdb;
		$table_name = $wpdb->prefix . $this->pluginID;
	
		?>
		<div class="wrap">
			<div id="icon-themes" class="icon32"><br></div>
			<h2><?php echo $this->pluginName ?></h2>
			<?php echo $this->signature ; ?>
			<p>This plugin allows a dynamic zoom on the images (based on the highslide javascript library)</p>
			<!--debut de personnalisation-->
		<?php
			
			//==========================================================================================
			//
			// Mise en place du systeme d'onglet
			//		(bien mettre a jour les liens contenu dans les <li> qui suivent)
			//
			//==========================================================================================
	?>		
			<script>jQuery(function($){ $('#tabs').tabs(); }) ; </script>		
			<div id="tabs">
				<ul class="hide-if-no-js">
					<li><a href="#tab-parameters"><? echo __('Parameters',$this->pluginName) ?></a></li>					
				</ul>
				<?php
				//==========================================================================================
				//
				// Premier Onglet 
				//		(bien verifier que id du 1er div correspond a celui indique dans la mise en 
				//			place des onglets)
				//
				//==========================================================================================
				?>
				<div id="tab-parameters" class="blc-section">
				
					<h3 class="hide-if-js"><? echo __('Parameters',$this->pluginName) ?></h3>
					<p><?php echo __('Here is the parameters of the plugin. Please modify them at your convenience.',$this->pluginName) ; ?> </p>
				
					<?php					
					$params = new parametersSedLex($this, 'tab-parameters') ; 
					$params->add_title(__('What is the clipped dimensions of the zoomed image?',$this->pluginName)) ; 
					$params->add_param('widthRestriction', __('Max width:',$this->pluginName)) ; 
					$params->add_param('heightRestriction', __('Max height:',$this->pluginName)) ; 
					
					$params->add_title(__('What is the other parameters?',$this->pluginName)) ; 
					$params->add_param('show_interval', __('Transition time if the slideshow is "on":',$this->pluginName)) ; 
					$params->add_param('controler_position', __('The position of the button (play, next, ...) (e.g "top center"):',$this->pluginName)) ; 
					$params->add_param('background_opacity', __('The opacity of the background:',$this->pluginName)) ; 
						
					$params->flush() ; 
					
					?>
				</div>
			</div>
			<!--fin de personnalisation-->
			<?php echo $this->signature ; ?>
		</div>
		<?php
	}
}

$updatemessage = imagezoom::getInstance();

?>