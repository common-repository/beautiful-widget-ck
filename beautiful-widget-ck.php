<?php
/**
 * Plugin Name: Beautiful Widget CK
 * Plugin URI: http://www.wp-pluginsck.com/en/wordpress-plugins/beautiful-widget-ck
 * Description: Beautifulck Widget CK enhance your widgets with a nice design with ease.
 * Version: 1.0.4
 * Author: Cédric KEIFLIN
 * Author URI: http://www.wp-pluginsck.com/
 * License: GPL2
 */

defined('ABSPATH') or die;

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

class Beautifulck {
	
	public $pluginname, $pluginurl, $plugindir, $options, $pro, $settings_field, $ispro, $prourl, $params;

	public $default_settings = array();
	
	private $ckfield;

	/**
	 * Class constructor
	 */
	function __construct() {
		$this->pluginname = 'beautiful-widget-ck';
		$this->pluginurl = plugins_url( '' , __FILE__ );
		$this->plugindir = WP_PLUGIN_DIR . '/' . $this->pluginname;
		$this->settings_field = 'beautifulck_options';
		$this->options = get_option( $this->settings_field );
		$this->prourl = 'http://www.wp-pluginsck.com/en/wordpress-plugins/beautiful-widget-ck';
	}
	
	/**
	 * Launch the actions
	 */
	function init() {
		if (is_admin()) {
			// load the pro version
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if (file_exists(WP_PLUGIN_DIR . '/beautiful-widget-ck-pro-addon/beautiful-widget-ck-pro-addon.php')
				&& is_plugin_active('beautiful-widget-ck-pro-addon/beautiful-widget-ck-pro-addon.php')
				) {
				$this->ispro = true;
				if (!class_exists(ucfirst($this->pluginname) . '_Pro')) {
					require(WP_PLUGIN_DIR . '/beautiful-widget-ck-pro-addon/beautiful-widget-ck-pro-addon.php');
					$proclassname = get_class($this) . '_Pro';
					$this->pro = new $proclassname();
				}
			}
			
			// add the get pro link in the plugins list
			add_filter( 'plugin_action_links', array( $this, 'show_pro_message_action_links'), 10, 2);

			// add options in the widget admin page
			add_action( 'sidebar_admin_setup', array( $this, 'add_widgets_admin_options') );

			// set up the ajax callbacks
			add_action( 'wp_ajax_beautifulck_showoptions', array( $this, 'show_options') );
			add_action( 'wp_ajax_beautifulck_render_admin_preview', array( $this, 'render_admin_preview') );
		}

		// load the needed scripts and styles if we are on the widget pages
		add_action('admin_head', array($this, 'load_admin_assets'));
		add_action('customize_controls_print_scripts', array($this, 'load_admin_assets'));
		
		// to change the widget style on frontend
		add_filter('dynamic_sidebar_params', array(&$this, 'set_widget_html'));
	}

	/**
	 * Dispay additional link in the extensions page
	 * 
	 * @param array $links
	 * @param string $file
	 * @return array
	 */
	function show_pro_message_action_links($links, $file) {
		if ($file == plugin_basename(__FILE__)) {
			if (!$this->ispro) {
				array_push($links, '<br /><img class="iconck" src="' .$this->pluginurl . '/images/star.png" /><a target="_blank" href="' . $this->prourl .'">' . __('Get the PRO Version') . '</a>');
			} else {
				array_push($links, '<br /><img class="iconck" src="' .$this->pluginurl . '/images/tick.png" /><span style="color: green;">' . __('You are using the PRO Version. Thank you !') . '</span>' );
			}
		}
		return $links;
	}

	// credits and thanks to http://wordpress.org/extend/plugins/widget-logic/ for a part of the code
	/**
	 * Register and save the additional options for each widget
	 * 
	 * @global type $wp_registered_widgets
	 * @global type $wp_registered_widget_controls
	 */
	function add_widgets_admin_options() {
		global $wp_registered_widgets, $wp_registered_widget_controls;

		// add options to each widdget
		foreach ( $wp_registered_widgets as $id => $widget )
		{
			if (!isset($wp_registered_widget_controls[$id]))
				wp_register_widget_control($id,$widget['name'], array( $this, 'add_widget_empty_option'));
			$wp_registered_widget_controls[$id]['callback_beautifulck_redirect']=$wp_registered_widget_controls[$id]['callback'];
			$wp_registered_widget_controls[$id]['callback']=array( $this, 'add_widget_extra_option');
			array_push($wp_registered_widget_controls[$id]['params'],$id);
		}
		
		// update options
		if ( 'post' == strtolower($_SERVER['REQUEST_METHOD']) )
		{	foreach ( (array) $_POST['widget-id'] as $widget_number => $widget_id )
				if (isset($_POST[$widget_id.'-beautifulck']))
					$this->options[$widget_id]=trim($_POST[$widget_id.'-beautifulck']);
		}
		update_option($this->settings_field, $this->options);
	}
	
	/**
	 * Needed
	 */
	function add_widget_empty_option() {}
	
	// credits and thanks to http://wordpress.org/extend/plugins/widget-logic/ for a part of the code
	/**
	 * Render the additional option for the widget
	 * 
	 * @global type $wp_registered_widget_controls
	 */
	function add_widget_extra_option() {
		global $wp_registered_widget_controls;

		$params=func_get_args();
		$id = end($params);

		// go to the original control function
		$callback=$wp_registered_widget_controls[$id]['callback_beautifulck_redirect'];
		if (is_callable($callback))
			call_user_func_array($callback, $params);

		$value = !empty( $this->options[$id] ) ? htmlspecialchars( stripslashes( $this->options[$id] ),ENT_QUOTES ) : '';

		// dealing with multiple widgets - get the number. if -1 this is the 'template' for the admin interface
		$id_disp=$id;
		if (!empty($params) && isset($params[0]['number']))
		{	$number=$params[0]['number'];
			if ($number==-1) {$number="__i__"; $value="";}
			$id_disp=$wp_registered_widget_controls[$id]['id_base'].'-'.$number;
		}
		// output our extra widget field
		echo '<a class="button btn-primary btn" href="javascript:void(0)" onclick="ckajax_show_dialog(this, \'' . $id_disp . '\');"><img src="' . $this->pluginurl . '/images/rainbow.png" style="display: inline-block;margin: 0 3px 0 0;vertical-align: middle;" />' . __('Beautiful Me !') . '<input type="hidden" name="' . $id_disp . '-beautifulck" id="' . $id_disp . '-beautifulck" value="' .$value . '" /></a>';
	}

	/**
	 * Loads all the needed scripts and styles in the admin side
	 */
	function load_admin_assets() {
		// does nothing if we are not in the widget edition page
		if ( 'widgets' == get_current_screen()->id || 'customize'  == get_current_screen()->id) :
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core ');
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core ');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'beautifulck_admin', $this->pluginurl . '/assets/beautiful-widget-ck_admin.css' );
		wp_enqueue_style( 'ckradio2', $this->pluginurl . '/cklibrary/ckfields/ckradio2/ckradio2.css' );
		wp_enqueue_script( 'ckradio2', $this->pluginurl . '/cklibrary/ckfields/ckradio2/ckradio2.js', array('jquery') );
		wp_enqueue_script( 'ckcolor', $this->pluginurl . '/cklibrary/ckfields/ckcolor/ckcolor.js', array('jquery', 'jquery-ui-button', 'wp-color-picker') );
	?>
	<script type="text/javascript">
		var render_admin_css_launched = false;
		var RENDERADMINBLOCKED;

		// method to limit the update of the preview if we are playing with a colorpicker
		function ckajax_render_admin_css_colorpicker() {
			if (render_admin_css_launched == true)
				return;
			render_admin_css_launched = true;
			ckajax_render_admin_css();
		}
		// method to render the preview
		function ckajax_render_admin_css() {
			if (RENDERADMINBLOCKED == true)
				return;
			SUGGESTIONLOCKED = 1;
			jQuery('#ckajaxoptionsstyles').addClass('ckwait_mini');
			var fields = new Object();
			jQuery('.beautifulck_admincss').each(function(i, field) {
				fields[jQuery(field).attr('id')] = jQuery(field).val();
			});
			var jsonfields = JSON.stringify(fields, null, 2);
			var data = {
				action: 'beautifulck_render_admin_preview',
				jsonfields: jsonfields
			};

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(response) {
				render_admin_css_launched = false;
				SUGGESTIONLOCKED = 0;
				jQuery('#ckajaxoptionsstyles').html(response).removeClass('ckwait_mini');
			});
			update_titleicon_admin_css();
			update_theme_admin_css();
		}
		
		function update_titleicon_admin_css() {
			jQuery('.beautifulckicon', jQuery('#ckajaxoptions')).attr('src', '<?php echo $this->pluginurl ?>/icons/' + jQuery('#beautifulck_options_icon').val());
		}
		
		function update_theme_admin_css() {
			jQuery('#beautifulck_preview').attr('class', 'beautifulck ' + jQuery('#beautifulck_options_theme').val() + ' ' + jQuery('#beautifulck_options_colorvariation').val());
		}

		function ckajax_show_dialog(button, widgetid) {
			var widget_title = jQuery('#widget-'+widgetid+'-title').val();
			var data = {
				action: 'beautifulck_showoptions',
				widget_title: widget_title,
				returninput: jQuery('input', jQuery(button)).attr('id')
			};
			jQuery(document.body).append('<div id="ckwait_open_dialog" class="ui-widget-overlay ui-front ckwait_black" style="z-index:1001090;"></div>');
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(response) {
				//alert('Got this from the server: ' + response);
				myform = jQuery('<div title="Beautiful Widget CK" ></div>');
				myform.html(response);
				myform.dialog({
							autoOpen: true,
							draggable: false,
							resizable: false,
							modal: true,
							width: 960,
							dialogClass: 'wp-dialog',
							create: function( event, ui ) {
								jQuery('#ckwait_open_dialog').remove();
								var jsonfields = jQuery('#'+jQuery('#ckajaxoptions-return').val()).val();
								var fields = jQuery.parseJSON(jsonfields);
								// loop each field and set the value from the widget options
								for (var name in fields) {
									if (jQuery('#'+name).length) {
										jQuery('#'+name).val(fields[name]);
										if (jQuery('#'+name).attr('isradio') == '1') 
											initckradiojs(name);
										if (name == 'beautifulck_options_title_googlefont' && jQuery('#'+name).val() != '')
											jQuery('#ckajaxoptionsgooglefont').html(jQuery('#'+name).val());
									} 
								}
								// init radio buttons
								makeckradio2js();
								// init colorpickers
								var myOptions = {defaultColor: true, change:  function(event, ui){window.setTimeout(ckajax_render_admin_css_colorpicker, 500);}, clear: function(event, ui){ckajax_render_admin_css();},hide: true,palettes: true};
								jQuery('.ck-color-field').wpColorPicker(myOptions);
								// render the preview
								ckajax_render_admin_css();
								jQuery(this).parent().css('z-index', '1001091');
								jQuery('.ui-widget-overlay').css('z-index', '1001090');
							},
							close: function( event, ui ) {
								jQuery(this).dialog( "destroy" );
								jQuery(this).remove();
							},
							buttons: {
								"OK": function() {
									// collect the fields values and set it in the widget option
									var fields = new Object();
									jQuery('#ckajaxoptions input:not([type="radio"],[type="button"]), #ckajaxoptions select').each(function(i, field) {
										fields[jQuery(field).attr('id')] = jQuery(field).val();
									});
									var jsonfields = JSON.stringify(fields, null, 2);
									jQuery('#'+jQuery('#ckajaxoptions-return').val()).val(jsonfields).trigger('change');
									setTimeout( function(){
										wp.customize.previewer.refresh();
									}, 800 );
									// close the popup
									jQuery(this).dialog( "close" );
									jQuery(this).remove();
								},
								Cancel: function() {
									jQuery(this).dialog( "destroy" );
									jQuery(this).remove();
								}
							}
						});
			});
		}
	</script>
	<?php endif;
	}

	/**
	 * Ajax : Render the options in the dialog
	 */
	function show_options() {
		// add the needed classes
		if (!class_exists("CKfolder"))
			require_once($this->plugindir . '/cklibrary/class-ckfolder.php');

		if (!class_exists("Beautifulwidgetck_CKfields"))
			require($this->plugindir . '/cklibrary/class-ckfields.php');
		$this->ckfields = new Beautifulwidgetck_CKfields();

		require($this->plugindir . '/' . $this->pluginname . '-ajaxoptions.php');
		
	}
	
	/**
	 * Ajax : Render the css styles for the preview
	 */
	function render_admin_preview() {
		$this->render_css('ckajaxoptions', json_decode(str_replace('\\', '', $_POST['jsonfields'])));
		die;
	}
	
	/**
	 * Return the HTML code of the field
	 * 
	 * @param string $type
	 * @param string $name
	 * @param mixed $value
	 * @param string $classname
	 * @param mixed $optionsgroup - can be array or string
	 * @param boolean $isfiles
	 * @param string $attribs
	 * @return string - the field html code
	 */
	function get_field($type, $name, $value, $classname = '', $optionsgroup = '', $isfiles = false, $attribs = '') {
		return  $this->ckfields->get($type, $name, $value, $classname, $optionsgroup, $isfiles, $attribs);
	}

	/**
	 * Return the field name
	 * 
	 * @param string $name
	 * @return string
	 */
	function get_field_name( $name ) {
		return sprintf( '%s[%s]', $this->settings_field, $name );
	}

	/**
	 * Return the field value
	 * 
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	function get_field_value( $key, $default = null ) {
		if (isset($this->options[$key])) {
			return $this->options[$key];
		} else {
			if ($default == null && isset($this->default_settings[$key])) 
				return $this->default_settings[$key];
		}
		return $default;
	}

	/**
	 * Render a message to get the pro version if the actual version is not the pro one
	 */
	function show_pro_message() { ?>
		<?php if (!file_exists($this->plugindir . '/' . $this->pluginname . '-pro-addon.php')) : ?>
		<div class="ckcheckproversion">
				<img class="iconck" src="<?php echo $this->pluginurl ?>/images/star.png" />
				<a target="_blank" href="<?php echo $this->prourl ?>"><?php _e('Get the PRO Version'); ?></a>
		</div>
		<?php endif;
	}

	/**
	 * Add the html code to the widget before rendering it in the page
	 * 
	 * @param array $params - the widget params
	 * @return array - the modified params
	 */
	function set_widget_html($params) {
		$widget_id = $params[0]['widget_id'];

		// if there is no beautifulck options for this widget, skip
		if (! isset($this->options[$widget_id]))
			return $params;

		// retrieve the object of beautifulck options for this widget
		$beautifulck_options = json_decode(str_replace('\\', '', $this->options[$widget_id]));
		// load the css file for the widget theme
		if (isset($beautifulck_options->beautifulck_options_theme) && '0' != $beautifulck_options->beautifulck_options_theme) {
			wp_register_style( 'beautifulck' . $beautifulck_options->beautifulck_options_theme, plugins_url( '' , __FILE__ ) . '/themes/' . $beautifulck_options->beautifulck_options_theme . '/beautiful-widget-ck.css' );
			wp_enqueue_style( 'beautifulck' . $beautifulck_options->beautifulck_options_theme);
		}
		// set the html code of the widget
		$title_icon = ( isset($beautifulck_options->beautifulck_options_icon) && $beautifulck_options->beautifulck_options_icon ) ? '<img src="' . $this->pluginurl . '/icons/' . $beautifulck_options->beautifulck_options_icon . '" class="beautifulckicon" />' : '';
		$params[0]['before_title'] = '<div class="beautifulck_title">' . $title_icon . '<div class="beautifulck_titletext">' . $params[0]['before_title'];
		$params[0]['after_title'] = $params[0]['after_title'] . '</div></div>';
		$params[0]['before_widget'] = $params[0]['before_widget'] . '<div class="beautifulck ' . ( isset($beautifulck_options->beautifulck_options_theme) ? $beautifulck_options->beautifulck_options_theme : '') . (isset($beautifulck_options->beautifulck_options_colorvariation) ? ' ' . $beautifulck_options->beautifulck_options_colorvariation : '') . '">';
		$params[0]['after_widget'] = '</div>' . $params[0]['after_widget'];
		// render the css styles in the page for this widget only
		$this->render_css($widget_id, $beautifulck_options);
		// load the google font stylesheet if needed
		if ( isset($beautifulck_options->beautifulck_options_title_fontfamily) && $beautifulck_options->beautifulck_options_title_fontfamily
				&& ( stristr($beautifulck_options->beautifulck_options_title_googlefont, trim($beautifulck_options->beautifulck_options_title_fontfamily, "'")) )) {
			echo $beautifulck_options->beautifulck_options_title_googlefont;
		}

		return $params;
	}
	
	/**
	 * Render the css styles
	 * 
	 * @param string $id
	 * @param object $params - beautifulck options for this widget
	 */
	function render_css($id, $params) {
		?>
		<style type="text/css">
			#<?php echo $id ?> .beautifulck_title {<?php echo implode('', $this->create_css($params, 'title_')) ?>}
			#<?php echo $id ?> .beautifulck {<?php echo implode('', $this->create_css($params, 'block_')) ?>}
		</style>
		<?php
	}

	/**
	 * Get the value of a params from a params object list
	 * 
	 * @param string $key
	 * @param mixed $default
	 * @return mixed the param value
	 */
	function get_param($key, $default = null) {
		$key = $this->settings_field .'_' . $key;
		if (isset($this->params->$key)) {
			return $this->params->$key;
		} else {
			if ($default == null && isset($this->default_settings[$key])) 
				return $this->default_settings[$key];
		}
		return $default;
	}
	
	/**
	 * Create the css array from the params
	 * 
	 * @param object $params
	 * @param string $prefix
	 * @return array of styles
	 */
	function create_css($params, $prefix = '') {
		$this->params = $params;
		$css = Array();
		$css['paddingtop'] = ($this->get_param($prefix.'paddingtop')) ? 'padding-top: ' . $this->get_param($prefix.'paddingtop', '0').'px;' : '';
                $css['paddingright'] = ($this->get_param($prefix.'paddingright')) ? 'padding-right: ' . $this->get_param($prefix.'paddingright', '0').'px;' : '';
                $css['paddingbottom'] = ($this->get_param($prefix.'paddingbottom') ) ? 'padding-bottom: ' . $this->get_param($prefix.'paddingbottom', '0').'px;' : '';
                $css['paddingleft'] = ($this->get_param($prefix.'paddingleft')) ? 'padding-left: ' . $this->get_param($prefix.'paddingleft', '0').'px;' : '';
                
		$css['margintop'] = ($this->get_param($prefix.'margintop')) ? 'margin-top: ' . $this->get_param($prefix.'margintop', '0').'px;' : '';
                $css['marginright'] = ($this->get_param($prefix.'marginright')) ? 'margin-right: ' . $this->get_param($prefix.'marginright', '0').'px;' : '';
                $css['marginbottom'] = ($this->get_param($prefix.'marginbottom')) ? 'margin-bottom: ' . $this->get_param($prefix.'marginbottom', '0').'px;' : '';
                $css['marginleft'] = ($this->get_param($prefix.'marginleft')) ? 'margin-left: ' . $this->get_param($prefix.'marginleft', '0').'px;' : '';
		$css['background'] = ($this->get_param($prefix.'bgcolor1')) ? 'background-color: ' . $this->get_param($prefix.'bgcolor1').';' : '';
                $css['background'] .= ($this->get_param($prefix.'bgimage')) ? 'background-image: url("' . JURI::ROOT() . $this->get_param($prefix.'bgimage').'");' : '';
		$css['background'] .= ($this->get_param($prefix.'bgimage')) ? 'background-repeat: ' . $this->get_param($prefix.'bgimagerepeat').';' : '';
                $css['background'] .= ($this->get_param($prefix.'bgimage')) ? 'background-position: ' . $this->get_param($prefix.'bgpositionx').' ' . $this->get_param($prefix.'bgpositiony').';' : '';
		$css['gradient'] = ($css['background'] AND $this->get_param($prefix.'bgcolor2')) ? 
			"background: -moz-linear-gradient(top,  " . $this->get_param($prefix.'bgcolor1', '#f0f0f0') . " 0%, " . $this->get_param($prefix.'bgcolor2', '#e3e3e3') . " 100%);"
			. "background: -webkit-gradient(linear, left top, left bottom, color-stop(0%," . $this->get_param($prefix.'bgcolor1', '#f0f0f0') . "), color-stop(100%," . $this->get_param($prefix.'bgcolor2', '#e3e3e3') . ")); "
			. "background: -webkit-linear-gradient(top,  " . $this->get_param($prefix.'bgcolor1', '#f0f0f0') . " 0%," . $this->get_param($prefix.'bgcolor2', '#e3e3e3') . " 100%);"
			. "background: -o-linear-gradient(top,  " . $this->get_param($prefix.'bgcolor1', '#f0f0f0') . " 0%," . $this->get_param($prefix.'bgcolor2', '#e3e3e3') . " 100%);"
			. "background: -ms-linear-gradient(top,  " . $this->get_param($prefix.'bgcolor1', '#f0f0f0') . " 0%," . $this->get_param($prefix.'bgcolor2', '#e3e3e3') . " 100%);"
			. "background: linear-gradient(top,  " . $this->get_param($prefix.'bgcolor1', '#f0f0f0') . " 0%," . $this->get_param($prefix.'bgcolor2', '#e3e3e3') . " 100%); "
			. "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $this->get_param($prefix.'bgcolor1', '#f0f0f0') . "', endColorstr='" . $this->get_param($prefix.'bgcolor2', '#e3e3e3') . "',GradientType=0 );"
			: '';	
		$css['borderradius'] = ($this->get_param($prefix.'roundedcornerstl', '0') && $this->get_param($prefix.'roundedcornerstr', '0') && $this->get_param($prefix.'roundedcornersbr', '0') && $this->get_param($prefix.'roundedcornersbl', '0')) ? 
			'-moz-border-radius: '.$this->get_param($prefix.'roundedcornerstl', '0').'px '.$this->get_param($prefix.'roundedcornerstr', '0').'px '.$this->get_param($prefix.'roundedcornersbr', '0').'px '.$this->get_param($prefix.'roundedcornersbl', '0').'px;'
			.'-webkit-border-radius: '.$this->get_param($prefix.'roundedcornerstl', '0').'px '.$this->get_param($prefix.'roundedcornerstr', '0').'px '.$this->get_param($prefix.'roundedcornersbr', '0').'px '.$this->get_param($prefix.'roundedcornersbl', '0').'px;'
			.'border-radius: '.$this->get_param($prefix.'roundedcornerstl', '0').'px '.$this->get_param($prefix.'roundedcornerstr', '0').'px '.$this->get_param($prefix.'roundedcornersbr', '0').'px '.$this->get_param($prefix.'roundedcornersbl', '0').'px;'
			: '' ;
		$shadowinset = $this->get_param($prefix.'shadowinset', 0) ? 'inset ' : '';
		$css['shadow'] = ($this->get_param($prefix.'shadowcolor') AND $this->get_param($prefix.'shadowblur')) ?
			'-moz-box-shadow: '.$shadowinset.($this->get_param($prefix.'shadowoffsetx', '0') ? $this->test_unit($this->get_param($prefix.'shadowoffsetx', '0')) : '0').' '.($this->get_param($prefix.'shadowoffsety', '0') ? $this->test_unit($this->get_param($prefix.'shadowoffsety', '0')) : '0').' '.$this->test_unit($this->get_param($prefix.'shadowblur', '')).' '.($this->get_param($prefix.'shadowspread', '0') ? $this->test_unit($this->get_param($prefix.'shadowspread', '0')) : '0').' '.$this->get_param($prefix.'shadowcolor', '').';'
			.'-webkit-box-shadow: '.$shadowinset.($this->get_param($prefix.'shadowoffsetx', '0') ? $this->test_unit($this->get_param($prefix.'shadowoffsetx', '0')) : '0').' '.($this->get_param($prefix.'shadowoffsety', '0') ? $this->test_unit($this->get_param($prefix.'shadowoffsety', '0')) : '0').' '.$this->test_unit($this->get_param($prefix.'shadowblur', '')).' '.($this->get_param($prefix.'shadowspread', '0') ? $this->test_unit($this->get_param($prefix.'shadowspread', '0')) : '0').' '.$this->get_param($prefix.'shadowcolor', '').';'
			.'box-shadow: '.$shadowinset.($this->get_param($prefix.'shadowoffsetx', '0') ? $this->test_unit($this->get_param($prefix.'shadowoffsetx', '0')) : '0').' '.($this->get_param($prefix.'shadowoffsety', '0') ? $this->test_unit($this->get_param($prefix.'shadowoffsety', '0')) : '0').' '.$this->test_unit($this->get_param($prefix.'shadowblur', '')).' '.($this->get_param($prefix.'shadowspread', '0') ? $this->test_unit($this->get_param($prefix.'shadowspread', '0')) : '0').' '.$this->get_param($prefix.'shadowcolor', '').';'
			: '';
		$css['border'] = ($this->get_param($prefix.'bordercolor') AND $this->get_param($prefix.'borderwidth')) ?
			'border: '.$this->get_param($prefix.'bordercolor', '#efefef').' '.$this->test_unit($this->get_param($prefix.'borderwidth', '1')).' solid;'
			: '';
		$css['fontsize'] = ($this->get_param($prefix.'fontsize')) ?
			'font-size: '.$this->test_unit($this->get_param($prefix.'fontsize')).';'
			: '';
		$css['fontcolor'] = ($this->get_param($prefix.'fontcolor')) ?
			'color: '.$this->get_param($prefix.'fontcolor').';'
			: '';
		$css['fontweight'] = ($this->get_param($prefix.'fontweight')) ?
			'font-weight: '.$this->get_param($prefix.'fontweight').';'
			: '';
		$css['fontfamily'] = ($this->get_param($prefix.'fontfamily')) ?
			'font-family: '.$this->get_param($prefix.'fontfamily').';'
			: '';
		return $css;
	}

	/**
	 * Test if there is already a unit, else add the px
	 *
	 * @param string $value
	 * @return string
	 */
	static function test_unit($value) {

		if ((stristr($value, 'px')) OR (stristr($value, 'em')) OR (stristr($value, '%')))
			return $value;

		return $value . 'px';
	}
}

$BeautifulckClass = new Beautifulck();
$BeautifulckClass->init();