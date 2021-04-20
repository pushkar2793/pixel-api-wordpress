<?php 

    /*############ Wpdevart Lightbox Admin Menu Class ################*/

class wpdevart_lightbox_admin_menu{
	
	private $menu_parametrs;
	private $databese_parametrs;
	private $text_parametrs;

	/*###################### Construct parameters function ##################*/		
	
	function __construct($param){
		
		// Initial same required variables
		$this->initial_menu_variables();
		add_action('admin_menu', array($this,'create_menu'));
		$this->databese_parametrs=$param['databese_parametrs'];
		
		add_action( 'wp_ajax_wpdevart_lightbox_page_save', array($this,'save_in_databese') );
			
	
	}

	/*###################### Initial menu variables function ##################*/		
	
	private function initial_menu_variables(){
		$this->menu_parametrs=array(
			'main_menu_name'=>'Lightbox',
			'main_menu_link' => 'wpdevart_lightbox',
			'menu_icon'=>wpdevart_lightbox_plugin_url.'images/menu_icon.png'
		);
		$this->text_parametrs=array(
			'parametrs_sucsses_saved'=>'Successfully saved.',
			'error_in_saving'=>'can\'t save "%s" plugin parameter<br>',
			'authorize_problem' => 'Authorization Problem'
			
		);		
	}

    /*############ Function for creating menu ################*/		
	
	public function create_menu(){
		global $submenu;
		$main_page 	 		  = add_menu_page( $this->menu_parametrs['main_menu_name'], $this->menu_parametrs['main_menu_name'], 'manage_options', $this->menu_parametrs['main_menu_link'], array($this, 'main_menu_function'),$this->menu_parametrs['menu_icon']);
		$page_coming_soon	  =	add_submenu_page($this->menu_parametrs['main_menu_name'],  $this->menu_parametrs['main_menu_name'],  $this->menu_parametrs['main_menu_name'], 'manage_options',$this->menu_parametrs['main_menu_link'], array($this, 'main_menu_function'));
		$page_featured	 	  = add_submenu_page( $this->menu_parametrs['main_menu_link'], 'Featured Plugins', 'Featured Plugins', 'manage_options', 'wpdevart-lightbox-featured-plugins', array($this, 'featured_plugins'));
		$hire_expert	 	  = add_submenu_page( $this->menu_parametrs['main_menu_link'],  'Hire an Expert', '<span style="color:#00ff66" >Hire an Expert</span>', 'manage_options', 'wpdevart-lightbox-hire-expert', array($this, 'hire_expert'));

		add_action('admin_print_styles-' .$main_page, array($this,'menu_requeried_scripts'));
		add_action('admin_print_styles-' .$hire_expert, array($this,'menu_hire_expert_requeried_scripts'));
		if(isset($submenu[$this->menu_parametrs['main_menu_link']]))
			add_submenu_page( $this->menu_parametrs['main_menu_link'], "Support or Any Ideas?", "<span style='color:#00ff66' >Support or Any Ideas?</span>", 'manage_options',"wpdevart_lightbox_any_ideas",array($this, 'any_ideas'),155);
		if(isset($submenu[$this->menu_parametrs['main_menu_link']]))
			$submenu[$this->menu_parametrs['main_menu_link']][3][2]=wpdevart_lightbox_support_url;
	}
	public function any_ideas(){
		
	}
    /*############  Required scripts function  ################*/
	
	public function menu_requeried_scripts(){	
		wp_enqueue_script('wp-color-picker');		
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script( 'jquery-ui-datepicker' ); 
		wp_enqueue_script( 'wpdevart_lightbox_admin_scripts' ); 
		wp_enqueue_style('jquery-ui-style');
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_style('admin_style_wp_lightbox');			
		if (function_exists('wp_enqueue_media')) wp_enqueue_media();
	}
	public function menu_hire_expert_requeried_scripts(){	
		wp_enqueue_style("wpdevart_lightbox_hire_expert",wpdevart_lightbox_plugin_url.'includes/style/hire_expert.css');
	}

	/*###################### Generate parameters function ##################*/	
	
	private function generete_parametrs($page_name){
		if(!(isset($page_name) && $page_name!=''))
			return NULL;
		$page_parametrs=array();
		if(isset($this->databese_parametrs[$page_name])){
			foreach($this->databese_parametrs[$page_name] as $key => $value){
				$page_parametrs[$key]=wpdevart_lightbox_setting::get_option($key,$value);
			}
			return $page_parametrs;
		}
		return NULL;
	}
	
	/*############ Database Function ################*/
	
	public function save_in_databese(){
		$kk=1;	
			
		if(isset($_POST['wpdevart_lightbox_options_nonce']) && wp_verify_nonce( $_POST['wpdevart_lightbox_options_nonce'],'wpdevart_lightbox_options_nonce')){
			foreach($this->databese_parametrs[$_POST['curent_page']] as $key => $value){
				if(isset($_POST[$key])){
					wpdevart_lightbox_setting::update_option($key,$_POST[$key]);					
				}
				else{
					$kk=0;
					printf($this->text_parametrs['error_in_saving'],$key);
				}
			}	
		}
		else{		
			die($this->text_parametrs['authorize_problem']);
		}
		if($kk==0){
			exit;
		}
		die($this->text_parametrs['parametrs_sucsses_saved']);
	}

    /*############  Main menu function  ################*/
	
	public function main_menu_function(){
			
	$enable_disable=$this->generete_parametrs('main_settings');	
	$enable_disable=$enable_disable['eneble_lightbox_content'];
		?>
        <script>
        var wpdevart_lightbox_ajaxurl="<?php echo admin_url( 'admin-ajax.php'); ?>";
		var wpdevart_lightbox_plugin_url="<?php echo wpdevart_lightbox_plugin_url; ?>";
		var wpdevart_lightbox_parametrs_sucsses_saved="<?php echo $this->text_parametrs['parametrs_sucsses_saved'] ?>";
		var wpdevart_lightbox_all_parametrs = <?php echo json_encode($this->databese_parametrs); ?>;

        </script>
		<div class="wpdevart_plugins_header div-for-clear">
			<div class="wpdevart_plugins_get_pro div-for-clear">
				<div class="wpdevart_plugins_get_pro_info">
					<h3>WpDevArt Lightbox Premium</h3>
					<p>Powerful and Customizable Lightbox</p>
				</div>
					<a target="blank" href="http://wpdevart.com/wordpress-lightbox-plugin/" class="wpdevart_upgrade">Upgrade</a>
			</div>
			<a target="blank" href="<?php echo wpdevart_lightbox_support_url; ?>" class="wpdevart_support">Have any Questions? Get quick support!</a>
		</div>    
      <div id="wpdevart_lightbox_enable" class="field switch">
		<label for="radio1" class="cb-enable <?php if($enable_disable=='enable') echo 'selected'; ?>"><span>Enable</span></label>
		<label for="radio2" class="cb-disable <?php if($enable_disable=='disable') echo 'selected'; ?>"><span>Disable</span></label>
        <span class="progress_enable_disable_buttons"><span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span><span class="error_massage"></span></span>
         <div style="clear:both">  </div>
	  </div>
	<br>
     
       <div class="wp-table right_margin">
        <table class="wp-list-table widefat fixed posts">
        	<thead>
                <tr>
                    <th>     
                                   
                   <span class="save_all_paramss"> <button type="button" id="save_all_parametrs" class="save_all_section_parametrs button button-primary"><span class="save_button_span">Save All Sections</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button></span>
                    </th>
                </tr>
         	</thead>
            <tbody>
            <tr>
            	<td>
                <div id="wpdevart_lightbox_page">
    				<div class="left_sections">
						<?php
                       		$this->generete_overlay_settings($this->generete_parametrs('overlay_parametrs'));
							$this->generete_popup_settings($this->generete_parametrs('popup_parametrs'));
                       	?>
                     </div>
    				 <div class="right_sections">
                     <?php
					 		$this->control_buttons_panel($this->generete_parametrs('control_buttons'));
							$this->information_line_panel($this->generete_parametrs('information_panel'));
                     ?>
                  </div><div style="clear:both"></div>
               </td>
       		</tr>
            </tbody>
            <tfoot>
                <tr>
                    <th>                   
                    	<span class="save_all_paramss"><button type="button" id="save_all_parametrs" class="save_all_section_parametrs button button-primary"><span class="save_button_span">Save All Sections</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button></span>
                    </th>
                </tr>
         	</tfoot>
        </table>
        </div>      
       <?php
	  wp_nonce_field('wpdevart_lightbox_options_nonce','wpdevart_lightbox_options_nonce');
	
		
	}
	
	
	/********************************** Lightbox OVERLAY FUNCTION **********************************************/
	
	
	private function generete_overlay_settings($page_parametrs){
		
		?>
		<div class="main_parametrs_group_div closed_params">
			<div class="head_panel_div" title="Click to toggle">
            	<span class="title_parametrs_image"><img src="<?php echo wpdevart_lightbox_plugin_url.'images/overlay.png' ?>"></span>
				<span class="title_parametrs_group">Overlay</span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
				<tbody> 
               		<tr>
						<td>
							Transparency <span title="Configure the overlay transparency" class="desription_class">?</span>
						</td>
						<td>
                            
                          <input type="text" size="3" class="lightbox_number_slider" data-max-val="100" data-min-val="0" name="overlay_transparency_prancent" value="<?php echo $page_parametrs['overlay_transparency_prancent'] ?>" id="overlay_transparency_prancent" style="border:0; color:#f6931f; font-weight:bold; width:35px" >%
                         	<div class="slider_div"></div>
						</td>                
					</tr>
                    <tr>
						<td >
							Overlay top background color <span class="pro_feature"> (pro)</span> <span title="Set overlay top background color" class="desription_class">?</span>
						</td>
						<td class="pro_input">                            
							<input type="text" class="color_option" id="popup_background_color" name="popup_background_color"  value="#000000"/>			
                        </td>                
					</tr>
                    <tr>
						<td >
							Overlay bottom background color <span class="pro_feature"> (pro)</span><span title="Set overlay bottom background color(select this second background color and you will see nice background color effect)" class="desription_class">?</span>
						</td>
						<td class="pro_input">
							<input type="text" class="color_option" id="popup_background_color" name="popup_background_color"  value="#000000"/>					
						</td>                
					</tr>
                     <tr>
						<td>
							Overlay fade effect <span class="pro_feature"> (pro)</span><span title="Enable/disable overlay fade effect" class="desription_class">?</span>
						</td>
						<td>
							<select class="hide_overlay_time_when_disabled pro_select" id="overlay_fade_efect">
                                <option  value="true">Enable</option>
                                <option  value="false" selected="selected">Disable</option>
                        	</select>				
						</td>                
					</tr>                  
				</tbody>
					<tfoot>
						<tr>
							<th colspan="2" width="100%"><button type="button" id="overlay_parametrs" class="save_section_parametrs button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
						</tr>
					</tfoot>       
				</table>
			</div>     
		</div>        
		<?php		
	}
	
	/********************************** Lightbox POPUP FUNCTION **********************************************/
	
	
	private function generete_popup_settings($page_parametrs){
		
		?>
		<div class="main_parametrs_group_div closed_params">
			<div class="head_panel_div" title="Click to toggle">
            	<span class="title_parametrs_image"><img src="<?php echo wpdevart_lightbox_plugin_url.'images/popup.png' ?>"></span>
				<span class="title_parametrs_group">Popup</span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
				<tbody> 
					<tr>
						<td>
							Video Link(URL) Lightbox <span title="if you want video(youtube and vimeo) links open in lightbox enable option" class="desription_class">?</span>
						</td>
						<td>  
							<select id="enable_video_popuping">
                                <option <?php selected($page_parametrs['enable_video_popuping'],'true') ?> value="enable">Enable</option>
                                <option <?php selected($page_parametrs['enable_video_popuping'],'false') ?> value="dsiable">Dsiable</option>
                            </select>					
                        </td>                
					</tr>
                 	<tr>
						<td>
							Background color <span title="Set the Popup background color" class="desription_class">?</span>
						</td>
						<td>                            
							<input type="text" class="color_option" id="popup_background_color" name="popup_background_color"  value="<?php echo $page_parametrs['popup_background_color'] ?>"/>						
                        </td>                
					</tr>
                    <tr>
                        <td>
                           Loading image <span title="Choose the loading image url or just upload it" class="desription_class">?</span>
                        </td>
                        <td>
                          <input type="text"  class="upload" id="popup_loading_image" name="popup_loading_image"  value="<?php echo $page_parametrs['popup_loading_image'] ?>"/>
                          <input class="upload-button button" type="button" value="Upload"/>
                          <img src="<?php echo $page_parametrs['popup_loading_image'] ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr> 
                	<tr>
						<td colspan="2">
							<div class="wpdevart_lightbox_admin_description">Dimensions</div>
						</td>						           
					</tr>
               		<tr>
						<td>
							Initial Width <span title="Type initial width for Popup(px)" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="popup_initial_width" name="popup_initial_width"  min="0"  step="1"  value="<?php echo $page_parametrs['popup_initial_width'] ?>"/><small>Px</small>					
						</td>                
					</tr>
                    <tr>
						<td>
							Initial Height <span title="Type initial height for Popup(px)" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="popup_initial_height" name="popup_initial_height"  min="0"  step="1"  value="<?php echo $page_parametrs['popup_initial_height'] ?>"/><small>Px</small>					
						</td>                
					</tr>
                    
                    
                    <tr>
						<td>
							Default YouTube video width <span title="Type Default YouTube video player width(px)" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="popup_youtube_width" name="popup_youtube_width"  min="0"  step="1"  value="<?php echo $page_parametrs['popup_youtube_width'] ?>"/><small>Px</small>					
						</td>                
					</tr>
                    <tr>
						<td>
							Default YouTube video height <span title="Type Default YouTube video player height(px)" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="popup_youtube_height" name="popup_youtube_height"  min="0"  step="1"  value="<?php echo $page_parametrs['popup_youtube_height'] ?>"/><small>Px</small>					
						</td>                
					</tr>
                    
                    <tr>
						<td>
							Default Vimeo video width <span title="Type Default Vimeo video player width(px)" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="popup_vimeo_width" name="popup_vimeo_width"  min="0"  step="1"  value="<?php echo $page_parametrs['popup_vimeo_width'] ?>"/><small>Px</small>					
						</td>                
					</tr>
                    <tr>
						<td>
							Default Vimeo video height <span title="Type Default Vimeo video player height(px)" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="popup_vimeo_height" name="popup_vimeo_height"  min="0"  step="1"  value="<?php echo $page_parametrs['popup_vimeo_height'] ?>"/><small>Px</small>					
						</td>                
					</tr>
                    
                    <tr>
						<td>
							Max Width <span title="Type maximum width for Popup(px)" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="popup_max_width" name="popup_max_width"  min="0"  step="1"  value="<?php echo $page_parametrs['popup_max_width'] ?>"/><small>Px</small>					
						</td>                
					</tr>
                    <tr>
						<td>
							Max Height <span title="Type Popup maximum height" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="popup_max_height" name="popup_max_height"  min="0"  step="1"  value="<?php echo $page_parametrs['popup_max_height'] ?>"/><small>Px</small>					
						</td>                
					</tr>
                    <tr>
						<td colspan="2">
							<div class="wpdevart_lightbox_admin_description">Position</div>
						</td>						           
					</tr>

                    <tr>
						<td>
							Popup position <span title="Select popup position" class="desription_class">?</span>
						</td>
						<td>
                            <select id="popup_position">
                                <option <?php selected($page_parametrs['popup_position'],'1') ?> value="1">Top Left</option>
                                <option <?php selected($page_parametrs['popup_position'],'2') ?> value="2">Top center</option>
                                <option <?php selected($page_parametrs['popup_position'],'3') ?> value="3">Top right</option>
                                <option <?php selected($page_parametrs['popup_position'],'4') ?> value="4">Middle Left</option>
                                <option <?php selected($page_parametrs['popup_position'],'5') ?> value="5">Middle center</option>
                                <option <?php selected($page_parametrs['popup_position'],'6') ?> value="6">Middle right </option>
                                <option <?php selected($page_parametrs['popup_position'],'7') ?> value="7">Bottom Left</option>
                                <option <?php selected($page_parametrs['popup_position'],'8') ?> value="8">Bottom center</option>
                                <option <?php selected($page_parametrs['popup_position'],'9') ?> value="9">Bottom right</option>    
                            </select>
						</td>                
					</tr>
                    <tr>
						<td>
							Popup fixed position <span title="Enable fixed position for Popup" class="desription_class">?</span>
						</td>
						<td>
                            <select id="popup_fixed_position">
                                <option <?php selected($page_parametrs['popup_fixed_position'],'true') ?> value="true">Enable</option>
                                <option <?php selected($page_parametrs['popup_fixed_position'],'false') ?> value="false">Dsiable</option>
                            </select>
						</td>                
					</tr>
                    <tr>
						<td>
							Popup distance from window <span title="Type Popup distance from window when position is 1,2,3,4,6,7,8,9(except Middle center position - 5)" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="popup_outside_margin" name="popup_outside_margin"  min="0"  step="1"  value="<?php echo $page_parametrs['popup_outside_margin'] ?>"/><small>Px</small>					
						</td>                
					</tr>
                    <tr>
						<td colspan="2">
							<div class="wpdevart_lightbox_admin_description">Border</div>
						</td>						           
					</tr>
                    <tr>
						<td>
							Popup border color <span title="Set the Popup border color" class="desription_class">?</span>
						</td>
						<td>                            
							<input type="text" class="color_option" id="popup_border_color" name="popup_border_color"  value="<?php echo $page_parametrs['popup_border_color'] ?>"/>						
                        </td>                
					</tr>
                    <tr>
						<td>
							Popup border width <span title="Type the Popup border width(px)" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="popup_border_width" name="popup_border_width"  min="0"  step="1"  value="<?php echo $page_parametrs['popup_border_width'] ?>"/><small>Px</small>					
						</td>                
					</tr>
                    <tr>
						<td>
							Popup border radius <span title="Set the Popup border radius(px)" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="popup_border_radius" name="popup_border_radius"  min="0"  step="1"  value="<?php echo $page_parametrs['popup_border_radius'] ?>"/><small>Px</small>					
						</td>                
					</tr>
                    <tr>
						<td colspan="2">
							<div class="wpdevart_lightbox_admin_description">Animation</div>
						</td>						           
					</tr>
                    
                      <tr>
						<td>
							Popup opening type <span class="pro_feature"> (pro)</span> <span title="Select the Popup opening type" class="desription_class">?</span>
						</td>
						<td>
                            <select class="popup_first_open_type pro_select" id="popup_first_open_type">
                                <option  value="load">Load picture first, then open the popup</option>
                                <option selected="selected" value="open">Open popup first, then the load picture</option>

                            </select>
						</td>                
					</tr>
                    <tr>
						<td>
							Popup opening animation type <span class="pro_feature"> (pro)</span><span title="Select the popup opening animation type" class="desription_class">?</span>
						</td>
						<td>
                            <select class="wpdevart_lb_popup_animation_type pro_select" id="popup_animation_type">
                                <option selected="selected" value="disable">Disable</option>
                                <option value="wpdevart_lb_fade">Fade</option>
                                <option value="wpdevart_lb_zoom_out">Zoom  out</option>
                                <option value="wpdevart_lb_zoom_in">Zoom  in</option>
                                <option value="wpdevart_lb_slide_in_right">Slide in from right</option>
                                <option value="wpdevart_lb_slide_in_left">Slide in from left</option>
                                <option value="wpdevart_lb_slide_from_top">Slide in from top</option>
                                <option value="wpdevart_lb_slide_from_bottom">Slide in from Bottom</option>
                                <option value="wpdevart_lb_newspaper">Newspaper</option>
                                
                                <option value="wpdevart_lb_flip_hor_left">Flip Horizontal Left</option>
                                <option value="wpdevart_lb_flip_hor_right">Flip Horizontal Right</option>
                                <option value="wpdevart_lb_flip_ver_top">Flip Vertical Top</option>
                                <option value="wpdevart_lb_flip_ver_bottom">Flip Vertical Bottom</option>
                            </select>
						</td>                
					</tr>
                      <tr>
						<td colspan="2">
							<div class="wpdevart_lightbox_admin_description">Elements ordering inside Popup</div>
						</td>						           
					</tr>
                    
                    
                    
                    <tr>
						<td>
							Elements ordering <span class="pro_feature"> (pro)</span><span title="Set the elements ordering" class="desription_class">?</span>
						</td>
						<td>
                        <?php $ordering_elements=array(
								'control_buttons' => '<li date-value="control_buttons" class="ui-state-default">Control Buttons<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></li>',
								'content' => '<li date-value="content" class="ui-state-default">Picture<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></li>',	
								'information_line' => '<li date-value="information_line" class="ui-state-default">Information Line<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></li>',			
							) ;
							$ordering_by= json_decode(stripslashes('{"0":"control_buttons","1":"content","2":"information_line"}'), true);?>
                            <ul id="wpdevart_lightbox_sortable">
                                <?php foreach($ordering_by as $key =>$value){ 
								echo $ordering_elements[$value];
								 } ?>
                             </ul>                        
                         </td>                
					</tr>
                  
				</tbody>
					<tfoot>
						<tr>
							<th colspan="2" width="100%"><button type="button" id="popup_parametrs" class="save_section_parametrs button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
						</tr>
					</tfoot>       
				</table>
                <script>
                    	jQuery(document).ready(function(e) {
                            if(jQuery('#popup_animation_type').val()=='disable')
								jQuery('.popup_animation_time').hide();
							jQuery('.wpdevart_lb_popup_animation_type').change(function(){
								if(jQuery(this).val()=='disable')	
									jQuery('.popup_animation_time').hide();
								else
									jQuery('.popup_animation_time').show();
							})
								
                        });
                    </script>
                
			</div>     
		</div>        
		<?php		
	}
	
	/********************************** Lightbox Control Buttons Function **********************************************/

	private function control_buttons_panel($page_parametrs){
		
		?>
		<div class="main_parametrs_group_div closed_params">
			<div class="head_panel_div" title="Click to toggle">
            	<span class="title_parametrs_image"><img src="<?php echo wpdevart_lightbox_plugin_url.'images/control_buttons.png' ?>"></span>
				<span class="title_parametrs_group">Control Buttons</span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
				<tbody>                                     
                   <tr>
						<td colspan="2">
							<div class="wpdevart_lightbox_admin_description">Main</div>
						</td>						           
					</tr>

               	 	<tr>
						<td>
							Show Control Buttons <span title="Show/Hide control buttons" class="desription_class">?</span>
						</td>
						<td>
							<select id="control_buttons_show">
                                <option <?php selected($page_parametrs['control_buttons_show'],'true') ?> value="true">Show</option>
                                <option <?php selected($page_parametrs['control_buttons_show'],'false') ?> value="false">Hide</option>
                        	</select>				
						</td>                
					</tr>
                    <tr>
						<td>
							Show Control Buttons in content <span title="Show/Hide control buttons in content" class="desription_class">?</span>
						</td>
						<td>
							<select id="control_buttons_show_in_content">
                                <option <?php selected($page_parametrs['control_buttons_show_in_content'],'true') ?> value="true">Show</option>
                                <option <?php selected($page_parametrs['control_buttons_show_in_content'],'false') ?> value="false">Hide</option>
                        	</select>				
						</td>                
					</tr>
                    
               		 <tr>
						<td>
							Control button line height<span title="Type Control Button Line Height(px)" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="control_buttons_height" name="control_buttons_height"  min="0"  step="1"  value="<?php echo $page_parametrs['control_buttons_height'] ?>"/>	<small>Px</small>					
						</td>                
					</tr>
                    <tr>
						<td>
							 Control buttons line background color  <span title=" Choose control buttons line background color" class="desription_class">?</span>
						</td>
						<td>                            
							<input type="text" class="color_option" id="control_buttons_line_bg_color" name="control_buttons_line_bg_color"  value="<?php echo $page_parametrs['control_buttons_line_bg_color'] ?>"/>						
                        </td>                
					</tr>
                     <tr>
						<td>
							Control buttons line transparency <span class="pro_feature"> (pro)</span><span title="Set control buttons line background transparency" class="desription_class">?</span>
						</td>
						<td>
                        	<input type="text" size="3" class="lightbox_number_slider pro_input" data-max-val="100" data-min-val="0" name="control_buttons_line_default_transparency" value="100" id="control_buttons_line_default_transparency" style="border:0; color:#f6931f; font-weight:bold; width:35px" >%
                         	<div class="slider_div"></div>
						</td>                
					</tr>
                     <tr>
						<td>
							Control buttons line hover background transparency <span class="pro_feature"> (pro)</span><span title="Set control buttons line hover background transparency" class="desription_class">?</span>
						</td>
						<td>
                        	<input type="text" size="3" class="lightbox_number_slider pro_input" data-max-val="100" data-min-val="0" name="control_buttons_line_hover_trancparency" value="100" id="control_buttons_line_hover_trancparency" style="border:0; color:#f6931f; font-weight:bold; width:35px" >%
                         	<div class="slider_div"></div>
						</td>                
					</tr>
                     <tr>
						<td>
							Slideshow Time <span class="pro_feature"> (pro)</span><span title="Type the Slideshow Time" class="desription_class">?</span>
						</td>
						<td>
							<input type="number" class="pro_input"  id="control_button_play_slaidshow_time" name="control_button_play_slaidshow_time"  min="500"  step="100"  value="5000"/><small>ms</small>					
						</td>                
					</tr>
                    
                    <tr>
						<td>
							Image changing animation type <span class="pro_feature"> (pro)</span><span title="Select the image changing animation type" class="desription_class">?</span>
						</td>
						<td>
							<select id="control_button_change_image_effect" class="pro_select">
                                <option selected="selected" value="none">None</option>
                                <option value="fade">Fade</option>
                                <option value="fade_elastic">Fade And Elastic</option>
                        	</select>				
						</td>                
					</tr>
                    <tr>
						<td>
							Duration of image changing animation <span class="pro_feature"> (pro)</span><span title="Type the image changing animation duration" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  class="pro_select"  id="change_img_animation_duration" name="change_img_animation_duration"  min="500"  step="100"  value="1000"/><small>ms</small>					
						</td>                
					</tr>
                     <tr>
						<td colspan="2">
							<div class="wpdevart_lightbox_admin_description">Navigation buttons inside popup <span style="font-size:16px" class="pro_feature"> (pro)</span></div>
						</td>						           
					</tr>
                    <tr>
						<td>
							Show/Hide left and right navigation buttons<span class="pro_feature"> (pro)</span><span title="Choose to show or hide left and right navigation buttons inside popup(on the images)" class="desription_class">?</span>
						</td>
						<td>
							<select class="pro_select" id="control_buttons_content_navigation">
                                <option  value="true">Show</option>
                                <option  selected="selected" value="false">Hide</option>
                        	</select>				
						</td>                
					</tr>
                    <tr>
						<td>
							Left and right navigation buttons mouse-over functionality<span class="pro_feature"> (pro)</span> <span title="This functionality will show left and right navigation buttons only when you navigate mouse on image, if you disable it then your navigation buttons always will be active" class="desription_class">?</span>
						</td>
						<td>
							<select class="pro_select" id="control_buttons_content_navigation_hover">
                                <option selected="selected" value="true">Enable</option>
                                <option value="false">Disable</option>
                        	</select>				
						</td>                
					</tr>
                    <tr>
						<td>
							Background color of navigation buttons<span class="pro_feature"> (pro)</span> <span title="Set the background color of left and right navigation buttons inside the popup" class="desription_class">?</span>
						</td>
						<td>                            
							<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(0, 0, 0);"></a></div>
                            </div>					
                        </td>                
					</tr>
                     <tr>
						<td>
							Background transparency of navigation buttons<span class="pro_feature"> (pro)</span> <span title="Set the background transparency for left and right navigation buttons inside the popup" class="desription_class">?</span>
						</td>
						<td>
                        	<input type="text" size="3" class="pro_input lightbox_number_slider" data-max-val="100" data-min-val="0" name="control_button_content_transparency" value="0" id="control_button_content_transparency" style="border:0; color:#f6931f; font-weight:bold; width:35px" >%
                         	<div class="slider_div"></div>
						</td>                
					</tr>
                    <tr>
						<td>
							Background color of navigation buttons when hovering<span class="pro_feature"> (pro)</span> <span title="Set the background color for left and right navigation buttons inside the popup when hover" class="desription_class">?</span>
						</td>
						<td>                            
							<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(0, 0, 0);"></a></div>
                            </div>					
                        </td>                
					</tr>
                     <tr>
						<td>
							Background transparency of navigation buttons when hovering<span class="pro_feature"> (pro)</span> <span title="Set the background transparency for left and right navigation buttons inside the popup when hover" class="desription_class">?</span>
						</td>
						<td>
                        	<input type="text" size="3" class="pro_input lightbox_number_slider" data-max-val="100" data-min-val="0" name="control_button_content_hover_transparency" value="100" id="control_button_content_hover_transparency" style="border:0; color:#f6931f; font-weight:bold; width:35px" >%
                         	<div class="slider_div"></div>
						</td>                
					</tr>
                     <tr>
						<td colspan="2">
							<div class="wpdevart_lightbox_admin_description">Navigation buttons in overlay(outside popup)<span style="font-size:16px" class="pro_feature"> (pro)</span></div>
						</td>						           
					</tr>
                     <tr>
						<td>
							Show navigation buttons in overlay(outside popup)<span class="pro_feature"> (pro)</span> <span title="Choose to show or hide left and right navigation buttons in overlay(outside the popup)" class="desription_class">?</span>
						</td>
						<td>
							<select class="pro_select" id="control_buttons_window_navigation">
                                <option  value="true">Show</option>
                                <option selected="selected" value="false">Hide</option>
                        	</select>				
						</td>                
					</tr>
                     <tr>
						<td>
							Navigation buttons background color<span class="pro_feature"> (pro)</span>  <span title="Set the background color for left and right navigation buttons inside overlay" class="desription_class">?</span>
						</td>
						<td>                            
							<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(0, 0, 0);"></a></div>
                            </div>					
                        </td>                
					</tr>
                     <tr>
						<td>
							 Background transparency of navigation buttons<span class="pro_feature"> (pro)</span> <span title="Set the background transparency for left and right navigation buttons inside overlay" class="desription_class">?</span>
						</td>
						<td>
                        	<input type="text" size="3" class="pro_input lightbox_number_slider" data-max-val="100" data-min-val="0" name="control_button_window_transparency" value="50" id="control_button_window_transparency" style="border:0; color:#f6931f; font-weight:bold; width:35px" >%
                         	<div class="slider_div"></div>
						</td>                
					</tr>
                    <tr>
						<td>
							Background color of navigation buttons when hovering<span class="pro_feature"> (pro)</span> <span title="Set the background color for left and right navigation buttons inside overlay during hovering" class="desription_class">?</span>
						</td>
						<td>                            
							<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(0, 0, 0);"></a></div>
                            </div>					
                        </td>                
					</tr>
                     <tr>
						<td>
							Background transparency of navigation buttons when hovering<span class="pro_feature"> (pro)</span> <span title="Set the background transparency of left and right navigation buttons in overlay when hover" class="desription_class">?</span>
						</td>
						<td>
                        	<input type="text" size="3" class="pro_input lightbox_number_slider" data-max-val="100" data-min-val="0" name="control_button_window_hover_transparency" value="100" id="control_button_window_hover_transparency" style="border:0; color:#f6931f; font-weight:bold; width:35px" >%
                         	<div class="slider_div"></div>
						</td>                
					</tr>
                   
                    
                    
                    <tr>
						<td colspan="2">
							<div class="wpdevart_lightbox_admin_description">Images</div>
						</td>						           
					</tr>
                    <tr>
                        <td>
                            Icon Type(images,icons) <span class="pro_feature"> (pro)</span>
                        </td>
                        <td>
							<select class="pro_input" id="control_buttons_icon_type">
                                <option selected="selected" value="image">Image</option>
                                <option value="icon">Icon</option>
                        	</select>				
						</td>               
                    </tr>
                     <tr>
                        <td>
                            Previous img(icon) inside popup	<span class="pro_feature"> (pro)</span>
                        </td>
                        <td>
                          <input type="text"  class="upload pro_input" id="control_button_content_prev_img_src" name="control_button_content_prev_img_src"  value=""/>
                          <input class="pro_input button" type="button" value="Upload"/>
                          <img src="<?php echo wpdevart_lightbox_plugin_url.'images/contorl_buttons/prev_content.png' ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr> 
                     <tr>
                        <td>
							Next img(icon) inside popup  <span class="pro_feature"> (pro)</span>                      
                        </td>
                        <td>
                          <input type="text"  class="upload pro_input" id="control_button_content_next_img_src" name="control_button_content_next_img_src"  value=""/>
                          <input class="pro_input button" type="button" value="Upload"/>
                          <img src="<?php echo wpdevart_lightbox_plugin_url.'images/contorl_buttons/next_content.png' ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr> 
                    
                    <tr>
                        <td>
							Previous img(icon) in overlay(outside popup)<span class="pro_feature"> (pro)</span>
                        </td>
                        <td>
                          <input type="text"  class="upload pro_input" id="control_button_window_prev_img_src" name="control_button_window_prev_img_src"  value=""/>
                          <input class="pro_input button" type="button" value="Upload"/>
                          <img src="<?php echo wpdevart_lightbox_plugin_url.'images/contorl_buttons/prev_content.png' ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr> 
                     <tr>
                        <td>
							Next img(icon) in overlay(outside popup)<span class="pro_feature"> (pro)</span>
                        </td>
                        <td>
                          <input type="text"  class="upload pro_input" id="control_button_window_next_img_src" name="control_button_window_next_img_src"  value=""/>
                          <input class="pro_input button" type="button" value="Upload"/>
                          <img src="<?php echo wpdevart_lightbox_plugin_url.'images/contorl_buttons/next_content.png' ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr> 
                    
                    <tr>
                        <td>
								Previous img(icon)
                        </td>
                        <td>
                          <input type="text"  class="upload" id="control_button_prev_img_src" name="control_button_prev_img_src"  value="<?php echo $page_parametrs['control_button_prev_img_src'] ?>"/>
                          <input class="upload-button button" type="button" value="Upload"/>
                          <img src="<?php echo $page_parametrs['control_button_prev_img_src'] ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr> 
                    <tr>
                        <td>
							Previous img(icon) when hovering(optional) 	
                        </td>
                        <td>
                          <input type="text"  class="upload" id="control_button_prev_hover_img_src" name="control_button_prev_hover_img_src"  value="<?php echo $page_parametrs['control_button_prev_hover_img_src'] ?>"/>
                          <input class="upload-button button" type="button" value="Upload"/>
                          <img src="<?php echo $page_parametrs['control_button_prev_hover_img_src'] ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr> 
                    <tr>
                        <td>
							Next img(icon) 
                        </td>
                        <td>
                          <input type="text"  class="upload" id="control_button_next_img_src" name="control_button_next_img_src"  value="<?php echo $page_parametrs['control_button_next_img_src'] ?>"/>
                          <input class="upload-button button" type="button" value="Upload"/>
                          <img src="<?php echo $page_parametrs['control_button_next_img_src'] ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr> 
                    <tr>
                        <td>
							Next img(icon) when hovering(optional) 
                        </td>
                        <td>
                          <input type="text"  class="upload" id="control_button_next_hover_img_src" name="control_button_next_hover_img_src"  value="<?php echo $page_parametrs['control_button_next_hover_img_src'] ?>"/>
                          <input class="upload-button button" type="button" value="Upload"/>
                          <img src="<?php echo $page_parametrs['control_button_next_hover_img_src'] ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr>
                    <tr>
                        <td>
							Play img(icon) 
                        </td>
                        <td>
                          <input type="text"  class="pro_input upload" id="control_button_play_img_src" name="control_button_play_img_src"  value=""/>
                          <input class="pro_input button" type="button" value="Upload"/>
                          <img src="<?php echo wpdevart_lightbox_plugin_url.'images/contorl_buttons/play.png' ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr>
                    <tr>
                        <td>
							Play img(icon) when hovering(optional) 
                        </td>
                        <td>
                          <input type="text"  class="pro_input upload" id="control_button_play_hover_img_src" name="control_button_play_hover_img_src"  value=""/>
                          <input class="pro_input button" type="button" value="Upload"/>
                          <img src="<?php echo wpdevart_lightbox_plugin_url.'images/contorl_buttons/play_hover.png' ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr>
                     <tr>
                        <td>
							Pause img(icon) 
                        </td>
                        <td>
                          <input type="text"  class="pro_input upload" id="control_button_pause_img_src" name="control_button_pause_img_src"  value=""/>
                          <input class="pro_input button" type="button" value="Upload"/>
                          <img src="<?php echo wpdevart_lightbox_plugin_url.'images/contorl_buttons/pause.png' ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr>
                    <tr>
                        <td>
							Pause img(icon) when hovering(optional)  
                        </td>
                        <td>
                          <input type="text"  class="pro_input upload" id="control_button_pause_hover_img_src" name="control_button_pause_hover_img_src"  value=""/>
                          <input class="pro_input button" type="button" value="Upload"/>
                          <img src="<?php echo wpdevart_lightbox_plugin_url.'images/contorl_buttons/pause_hover.png' ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr>
                    <tr>
                        <td>
							Download img(icon)
                        </td>
                        <td>
                          <input type="text"  class="upload" id="control_button_download_img_src" name="control_button_download_img_src"  value="<?php echo $page_parametrs['control_button_download_img_src'] ?>"/>
                          <input class="upload-button button" type="button" value="Upload"/>
                          <img src="<?php echo $page_parametrs['control_button_download_img_src'] ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr> 
                    <tr>
                        <td>
							Download img(icon) when hovering(optional)
                        </td>
                        <td>
                          <input type="text"  class="upload" id="control_button_download_hover_img_src" name="control_button_download_hover_img_src"  value="<?php echo $page_parametrs['control_button_download_hover_img_src'] ?>"/>
                          <input class="upload-button button" type="button" value="Upload"/>
                          <img src="<?php echo $page_parametrs['control_button_download_hover_img_src'] ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr>
                    <tr>
                        <td>
							Open in new window img(icon)
                        </td>
                        <td>
                          <input type="text"  class="upload" id="control_button_innewwindow_img_src" name="control_button_innewwindow_img_src"  value="<?php echo $page_parametrs['control_button_innewwindow_img_src'] ?>"/>
                          <input class="upload-button button" type="button" value="Upload"/>
                          <img src="<?php echo $page_parametrs['control_button_innewwindow_img_src'] ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr> 
                    <tr>
                        <td>
							Open in new window img(icon) when hovering(optional) 
                        </td>
                        <td>
                          <input type="text"  class="upload" id="control_button_innewwindow_hover_img_src" name="control_button_innewwindow_hover_img_src"  value="<?php echo $page_parametrs['control_button_innewwindow_hover_img_src'] ?>"/>
                          <input class="upload-button button" type="button" value="Upload"/>
                          <img src="<?php echo $page_parametrs['control_button_innewwindow_hover_img_src'] ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr>
                     <tr>
                        <td>
							Full width img(icon)
                        </td>
                        <td>
                          <input type="text"  class="upload" id="control_button_fullwidth_img_src" name="control_button_fullwidth_img_src"  value="<?php echo $page_parametrs['control_button_fullwidth_img_src'] ?>"/>
                          <input class="upload-button button" type="button" value="Upload"/>
                          <img src="<?php echo $page_parametrs['control_button_fullwidth_img_src'] ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr>
                    <tr>
                        <td>
							Full width img(icon) when hovering(optional)  
                        </td>
                        <td>
                          <input type="text"  class="upload" id="control_button_fullwidht_hover_img_src" name="control_button_fullwidht_hover_img_src"  value="<?php echo $page_parametrs['control_button_fullwidht_hover_img_src'] ?>"/>
                          <input class="upload-button button" type="button" value="Upload"/>
                          <img src="<?php echo $page_parametrs['control_button_fullwidht_hover_img_src'] ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr>
                     <tr>
                        <td>
							Full width reset img(icon)
                        </td>
                        <td>
                          <input type="text"  class="upload" id="control_button_fullwidthrest_img_src" name="control_button_fullwidthrest_img_src"  value="<?php echo $page_parametrs['control_button_fullwidthrest_img_src'] ?>"/>
                          <input class="upload-button button" type="button" value="Upload"/>
                          <img src="<?php echo $page_parametrs['control_button_fullwidthrest_img_src'] ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr>
                    <tr>
                        <td>
							Full width reset img(icon) when hovering(optional) 
                        </td>
                        <td>
                          <input type="text"  class="upload" id="control_button_fullwidhtrest_hover_img_src" name="control_button_fullwidhtrest_hover_img_src"  value="<?php echo $page_parametrs['control_button_fullwidhtrest_hover_img_src'] ?>"/>
                          <input class="upload-button button" type="button" value="Upload"/>
                          <img src="<?php echo $page_parametrs['control_button_fullwidhtrest_hover_img_src'] ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr>
                    
                    <tr>
                        <td>
							Full screen img(icon)<span class="pro_feature"> (pro)</span>
                        </td>
                        <td>
                          <input type="text"  class="upload pro_input" id="control_button_fullscreen_img_src" name="control_button_fullscreen_img_src"  value=""/>
                          <input class="pro_input button" type="button" value="Upload"/>
                          <img src="<?php echo wpdevart_lightbox_plugin_url.'images/contorl_buttons/fullscreen.png' ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr>
                    <tr>
                        <td>
							Full screen img(icon) when hovering<span class="pro_feature"> (pro)</span>
                        </td>
                        <td>
                          <input type="text"  class="upload pro_input" id="control_button_fullscreen_hover_img_src" name="control_button_fullscreen_hover_img_src"  value=""/>
                          <input class="pro_input button" type="button" value="Upload"/>	
                          <img src="<?php echo wpdevart_lightbox_plugin_url.'images/contorl_buttons/fullscreen_hover.png' ?>"  class="cont_button_uploaded_img">
                         </td>                
                    </tr>
                     <tr>
                        <td>
							Full screen reset img(icon)<span class="pro_feature"> (pro)</span>
                        </td>
                        <td>
                          <input type="text"  class="upload pro_input" id="control_button_fullscreenrest_img_src" name="control_button_fullscreenrest_img_src"  value=""/>
                          <input class="pro_input button" type="button" value="Upload"/>
                          <img src="<?php echo wpdevart_lightbox_plugin_url.'images/contorl_buttons/fullscreenrest.png' ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr>
                    <tr>
                        <td>
							Full screen reset img(icon) when hovering<span class="pro_feature"> (pro)</span>
                        </td>
                        <td>
                          <input type="text"  class="upload pro_input" id="control_button_fullscreenrest_hover_img_src" name="control_button_fullscreenrest_hover_img_src"  value=""/>
                          <input class="pro_input button" type="button" value="Upload"/>
                          <img src="<?php echo wpdevart_lightbox_plugin_url.'images/contorl_buttons/fullscreeenrest_hover.png' ?>"  class="cont_button_uploaded_img">
                         </td>                
                    </tr>
                    <tr>
                        <td>
							Close img(icon) 
                        </td>
                        <td>
                          <input type="text"  class="upload" id="control_button_close_img_src" name="control_button_close_img_src"  value="<?php echo $page_parametrs['control_button_close_img_src'] ?>"/>
                          <input class="upload-button button" type="button" value="Upload"/>
                          <img src="<?php echo $page_parametrs['control_button_close_img_src'] ?>"  class="cont_button_uploaded_img">	
                         </td>                
                    </tr>
                    <tr>
                        <td>
							Close img(icon) when hovering 
                        </td>
                        <td>
                          <input type="text"  class="upload" id="control_button_close_hover_img_src" name="control_button_close_hover_img_src"  value="<?php echo $page_parametrs['control_button_close_hover_img_src'] ?>"/>
                          <input class="upload-button button" type="button" value="Upload"/>	
                          <img src="<?php echo $page_parametrs['control_button_close_hover_img_src'] ?>"  class="cont_button_uploaded_img">
                         </td>                
                    </tr>
                    <tr>
                    <?php
					$ordering_list=json_decode(stripslashes('{"left":[[1,"prev"],[1,"next"],[0,"play"],[1,"download"],[1,"innewwindow"],[1,"fullwidth"],[0,"fullscreen"]],"right":[[1,"close"]]}'),true);				
					
					 ?>
						<td>
                           Navigation line ordering(click to disable icons) <span class="pro_feature"> (pro)</span>	<span title="Set the navigation line ordering" class="desription_class">?</span>
                        </td>
                        <td id="control_button_ordering_main">
                        <div id="sortable_control_left_elements" class="pro_select connectedSortable" >
                   			<?php
								for($i=0;$i<count($ordering_list['left']);$i++){
									echo 	'<img date-value="'.$ordering_list['left'][$i][1].'" class="control_button_ord_img'.($ordering_list['left'][$i][0]?' control_img_active ':' control_img_deactive ').'" id="control_button_prev" src="'.wpdevart_lightbox_plugin_url.'images/contorl_buttons/'.$ordering_list['left'][$i][1].'_hover.png"  />';
								}							
							 ?>
                            
                        </div>
                        <div id="sortable_control_right_elements" class="pro_select connectedSortable" >
                        	<?php
								for($i=0;$i<count($ordering_list['right']);$i++){
									echo 	'<img date-value="'.$ordering_list['right'][$i][1].'" class="control_button_ord_img'.($ordering_list['right'][$i][0]?' control_img_active ':' control_img_deactive ').'" id="control_button_prev" src="'.wpdevart_lightbox_plugin_url.'images/contorl_buttons/'.$ordering_list['right'][$i][1].'_hover.png"  />';
								}							
							 ?>                        
                        </div>
						</td>						           
					</tr>                  
                	<script>
                    	jQuery(document).ready(function(e) {
                            if(jQuery('.hide_overlay_time_when_disabled').val()=='false')
								jQuery('.overlay_fade_effect').hide();
							jQuery('.hide_overlay_time_when_disabled').change(function(){
								if(jQuery(this).val()=='false')	
									jQuery('.overlay_fade_effect').hide();
								else
									jQuery('.overlay_fade_effect').show();
							})
								
                        });
                    </script>
				</tbody>
					<tfoot>
						<tr>
							<th colspan="2" width="100%"><button type="button" id="control_buttons" class="save_section_parametrs button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
						</tr>
					</tfoot>       
				</table>
			</div>     
		</div>        
		<?php		
	}
	
		/*################################## Lightbox INFORMATION LINE FUNCTION #########################################*/
		
	private function information_line_panel($page_parametrs){
		
		?>
		<div class="main_parametrs_group_div closed_params">
			<div class="head_panel_div" title="Click to toggle">
            	<span class="title_parametrs_image"><img src="<?php echo wpdevart_lightbox_plugin_url.'images/info_icon.png' ?>"></span>
				<span class="title_parametrs_group">Information Line</span>
				<span class="enabled_or_disabled_parametr"></span>
				<span class="open_or_closed"></span>         
			</div>
			<div class="inside_information_div">
				<table class="wp-list-table widefat fixed posts section_parametrs_table">                            
				<tbody> 
                	<tr>
						<td>
							Show information panel <span title="Show/Hide information panel" class="desription_class">?</span>
						</td>
						<td>
							<select id="information_panel_show">
                                <option <?php selected($page_parametrs['information_panel_show'],'true') ?>  value="true">Show</option>
                                <option <?php selected($page_parametrs['information_panel_show'],'false') ?> value="false">Hide</option>
                        	</select>				
						</td>                
					</tr>
               		<tr>
						<td>
							Show information panel in content(images) <span title="Show information panel inside or outside content" class="desription_class">?</span>
						</td>
						<td>
							<select id="information_panel_show_in_content">
                                <option <?php selected($page_parametrs['information_panel_show_in_content'],'true') ?>  value="true">Show</option>
                                <option <?php selected($page_parametrs['information_panel_show_in_content'],'false') ?> value="false">Hide</option>
                        	</select>				
						</td>                
					</tr>                    
                    <tr>
						<td>
							Information line background color <span title="Choose information line background color" class="desription_class">?</span>
						</td>
						<td>                            
							<input type="text" class="color_option" id="information_panel_bg_color" name="information_panel_bg_color"  value="<?php echo $page_parametrs['information_panel_bg_color'] ?>"/>						
                        </td>                
					</tr>
                     <tr>
						<td>
							Information line transparency <span class="pro_feature"> (pro)</span><span title="Set the information line transparency" class="desription_class">?</span>
						</td>
						<td>
                        	<input type="text" size="3" class="lightbox_number_slider pro_input" data-max-val="100" data-min-val="0" name="information_panel_default_transparency" value="100" id="information_panel_default_transparency" style="border:0; color:#f6931f; font-weight:bold; width:35px" >%
                         	<div class="slider_div"></div>
						</td>                
					</tr>
                    <tr>
						<td>
							Information line hover transparency <span class="pro_feature"> (pro)</span><span title="Set the information line hover transparency" class="desription_class">?</span>
						</td>
						<td>
                        	<input type="text" size="3" class="lightbox_number_slider pro_input" data-max-val="100" data-min-val="0" name="information_panel_hover_trancparency" value="100" id="information_panel_hover_trancparency" style="border:0; color:#f6931f; font-weight:bold; width:35px" >%
                         	<div class="slider_div"></div>
						</td>                
					</tr>
                    <tr>
						<td>
							Image count text  <span title="Type here the images count text" class="desription_class">?</span>
						</td>
						<td>
                        	<input type="text" size="7" id="information_panel_count_image_after_text" value="<?php echo $page_parametrs['information_panel_count_image_after_text'] ?>"> 7 <input size="3" type="text" id="information_panel_count_image_middle_text" value="<?php echo $page_parametrs['information_panel_count_image_middle_text'] ?>"> 25
                         	<div class="slider_div"></div>
						</td>                
					</tr>
                    <tr>
						<td colspan="2">
							<div class="wpdevart_lightbox_admin_description">Count</div>
						</td>						           
					</tr>
                     <tr>
						<td>
							Count section padding left <span title="Type the count section padding left" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="information_panel_count_padding_left" name="information_panel_count_padding_left"  min="0"  step="1"  value="<?php echo $page_parametrs['information_panel_count_padding_left'] ?>"/>	<small>Px</small>					
						</td>                
					</tr>
                     <tr>
						<td>
							 Count section padding right <span title="Type the count section padding right" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="information_panel_count_padding_right" name="information_panel_count_padding_right"  min="0"  step="1"  value="<?php echo $page_parametrs['information_panel_count_padding_right'] ?>"/>	<small>Px</small>					
						</td>                
					</tr>
                     <tr>
						<td>
							Count section text font size <span title="Type the count section text font size" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="information_panel_count_font_size" name="information_panel_count_font_size"  min="0"  step="1"  value="<?php echo $page_parametrs['information_panel_count_font_size'] ?>"/>	<small>Px</small>					
						</td>                
					</tr>
                    <tr>
						<td>
							Count section text font family <span class="pro_feature"> (pro)</span><span title="Select the count section text font family" class="desription_class">?</span>
						</td>
						<td>
							<?php wpdevart_lightbox_setting::generete_fonts('information_panel_count_font_family', 'Times New Roman,Times,Georgia,serif' ) ?>
						</td>                
					</tr>
                    <tr>
						<td>
							Count section text font weight <span class="pro_feature"> (pro)</span><span title="Select the count section text font weight" class="desription_class">?</span>
						</td>
						<td>
							<select class="pro_select" id="information_panel_count_font_weigth">
                                <option  value="lighter">lighter</option>
                                <option selected="selected"  value="normal">normal</option>
                                <option  value="bold">bold</option>                                
                        	</select>				
						</td>                
					</tr>
                    <tr>
						<td>
							Count section text font style <span class="pro_feature"> (pro)</span><span title="Select the count section text font style" class="desription_class">?</span>
						</td>
						<td>
							<select class="pro_select" id="information_panel_count_font_style">
                                <option selected="selected"  value="normal">normal</option>
                                <option value="italic">italic</option>                                
                        	</select>				
						</td>                
					</tr>
                    <tr>
						<td>
							Count section text color <span class="pro_feature"> (pro)</span><span title="Set the count section text color " class="desription_class">?</span>
						</td>
						<td>                            
							<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(0, 0, 0);"></a></div>
                            </div>                        
                        </td>                
					</tr>
                    
                    <tr>
						<td colspan="2">
							<div class="wpdevart_lightbox_admin_description">Description</div>
						</td>						           
					</tr>
                    
                     <tr>
						<td>
							Description section padding left <span title="Type the description section padding left" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="information_panel_desc_padding_left" name="information_panel_desc_padding_left"  min="0"  step="1"  value="<?php echo $page_parametrs['information_panel_desc_padding_left'] ?>"/>	<small>Px</small>					
						</td>                
					</tr>
                     <tr>
						<td>
							Description section padding right <span title="Type the description section padding right" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="information_panel_desc_padding_right" name="information_panel_desc_padding_right"  min="0"  step="1"  value="<?php echo $page_parametrs['information_panel_desc_padding_right'] ?>"/>	<small>Px</small>					
						</td>                
					</tr>
                     <tr>
						<td>
							 Font size of description section text <span title="Type the description section text font size" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="information_panel_desc_font_size" name="information_panel_desc_font_size"  min="0"  step="1"  value="<?php echo $page_parametrs['information_panel_desc_font_size'] ?>"/>	<small>Px</small>					
						</td>                
					</tr>
                    <tr>
						<td>
							Font family of description section text <span class="pro_feature"> (pro)</span><span title="Select the description section text font family" class="desription_class">?</span>
						</td>
						<td>
							<?php wpdevart_lightbox_setting::generete_fonts('information_panel_desc_font_family',  'Times New Roman,Times,Georgia,serif' ) ?>
						</td>                
					</tr>
                    <tr>
						<td>
							Description section text font weight <span class="pro_feature"> (pro)</span><span title="Select the description section text font weight" class="desription_class">?</span>
						</td>
						<td>
							<select class="pro_select"  id="information_panel_desc_font_weigth">
                                <option value="lighter">lighter</option>
                                <option selected="selected" value="normal">normal</option>
                                <option value="bold">bold</option>                                
                        	</select>				
						</td>                
					</tr>
                    <tr>
						<td>
							Description section text font style<span class="pro_feature"> (pro)</span> <span title="Select the description section text font style " class="desription_class">?</span>
						</td>
						<td>
							<select class="pro_select" id="information_panel_desc_font_style">
                                <option selected="selected"  value="normal">normal</option>
                                <option value="italic">italic</option>                                
                        	</select>				
						</td>                
					</tr>
                    <tr>
						<td>
							Description section text color <span class="pro_feature"> (pro)</span><span title="Set the description section text color " class="desription_class">?</span>
						</td>
						<td>                            
							<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(0, 0, 0);"></a></div>
                            </div>					
                        </td>                
					</tr>
                    <tr>
						<td>							
                            Show default description text if image doesn't have caption <span title="Show/Hide default description text if image doesn't have caption" class="desription_class">?</span>
						</td>
						<td>
							<select id="information_panel_desc_show_if_not">
                                <option <?php selected($page_parametrs['information_panel_desc_show_if_not'],'true') ?>  value="true">Show</option>
                                <option <?php selected($page_parametrs['information_panel_desc_show_if_not'],'false') ?> value="false">Hide</option>
                        	</select>				
						</td>                
					</tr>
                    <tr>
						<td>
							Default description text if the image doesn't have caption <span title="Type the default description text if the image doesn't have caption" class="desription_class">?</span>
						</td>
						<td>                            
							<input type="text" id="information_panel_text_for_no_caption" name="information_panel_text_for_no_caption"  value="<?php echo  $page_parametrs['information_panel_text_for_no_caption'] ?>"/>						
                        </td>                
					</tr>
                     <tr>
						<td colspan="2">
							<div class="wpdevart_lightbox_admin_description">Title</div>
						</td>						           
					</tr>
                    
                     <tr>
						<td>
							Title padding left <span title="Type the title padding left" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="information_panel_title_padding_left" name="information_panel_title_padding_left"  min="0"  step="1"  value="<?php echo $page_parametrs['information_panel_title_padding_left'] ?>"/>	<small>Px</small>					
						</td>                
					</tr>
                     <tr>
						<td>
							Title padding right <span title="Type the title padding right" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="information_panel_title_padding_right" name="information_panel_title_padding_right"  min="0"  step="1"  value="<?php echo $page_parametrs['information_panel_title_padding_right'] ?>"/>	<small>Px</small>					
						</td>                
					</tr>
                     <tr>
						<td>
							Title font size <span title="Type the title font size(px)" class="desription_class">?</span>
						</td>
						<td>
							<input type="number"  id="information_panel_title_font_size" name="information_panel_title_font_size"  min="0"  step="1"  value="<?php echo $page_parametrs['information_panel_title_font_size'] ?>"/>	<small>Px</small>					
						</td>                
					</tr>
                    <tr>
						<td>
							Title font family <span class="pro_feature"> (pro)</span><span title="Select the title font family" class="desription_class">?</span>
						</td>
						<td>
							<?php wpdevart_lightbox_setting::generete_fonts('information_panel_title_font_family','Times New Roman,Times,Georgia,serif' ) ?>
						</td>                
					</tr>
                    <tr>
						<td>
							Title font weight <span class="pro_feature"> (pro)</span><span title="Select the title font weight(px)" class="desription_class">?</span>
						</td>
						<td>
							<select class="pro_select" id="information_panel_title_font_weigth">
                                <option value="lighter">lighter</option>
                                <option selected="selected"  value="normal">normal</option>
                                <option value="bold">bold</option>                                
                        	</select>				
						</td>                
					</tr>
                    <tr>
						<td>
							Title font style <span class="pro_feature"> (pro)</span><span title="Select the title font style" class="desription_class">?</span>
						</td>
						<td>
							<select class="pro_select" id="information_panel_title_font_style">
                                <option selected="selected"  value="normal">normal</option>
                                <option value="italic">italic</option>                                
                        	</select>				
						</td>                
					</tr>
                    <tr>
						<td>
							Title color <span class="pro_feature"> (pro)</span><span title="Set the title color" class="desription_class">?</span>
						</td>
						<td>                            
							<div class="disabled_picker">
                                <div class="wp-picker-container"><a tabindex="0" class="wp-color-result" title="Select Color" data-current="Current Color" style="background-color: rgb(0, 0, 0);"></a></div>
                            </div>						
                        </td>                
					</tr>
                   <tr>
						<td>							
                            Show default description text if image doesn't have title <span title="Show/Hide default description text if image don't have title" class="desription_class">?</span>
						</td>
						<td>
							<select id="information_panel_title_show_if_not">
                                <option <?php selected($page_parametrs['information_panel_title_show_if_not'],'true') ?>  value="true">Show</option>
                                <option <?php selected($page_parametrs['information_panel_title_show_if_not'],'false') ?> value="false">Hide</option>
                        	</select>				
						</td>                
					</tr>
                    
                    <tr>
						<td>
							Default title text if image doesn't have title attribute<span title="Type the default title text if image doesn't have title attribute" class="desription_class">?</span>
						</td>
						<td>                            
							<input type="text" id="information_panel_text_for_no_title" name="information_panel_text_for_no_title"  value="<?php echo $page_parametrs['information_panel_text_for_no_title'] ?>"/>						
                        </td>                
					</tr>
                     <tr>
						<td>
							Information line elements ordering <span title="Set the information line elements ordering" class="desription_class">?</span>
						</td>
						<td>
                        <?php 
							$ordering_info= json_decode(stripslashes($page_parametrs['information_panel_ordering']), true);
                            $ordering_elements=array(
								'count'   =>'Count',
								'title'   => 'Title',	
								'caption' => 'Caption',			
							) ;?>
                            <ul id="wpdevart_lightbox_information_sortable">
                                <?php foreach($ordering_info as $key =>$value){ 
									echo '<li date-value="'.$key.'"  class="ui-state-default '.( $value[0] ? " control_active ":" control_deactive ").'">'.$ordering_elements[$key].'<span class="ui-icon ui-icon-arrowthick-2-n-s"></span></li>';
								 } ?>
                             </ul>
                            <input type="hidden" name="information_panel_ordering" id="information_panel_ordering" value='<?php echo stripslashes($page_parametrs['information_panel_ordering']) ?>'  />
                         </td>                
					</tr>
				</tbody>
					<tfoot>
						<tr>
							<th colspan="2" width="100%"><button type="button" id="information_panel" class="save_section_parametrs button button-primary"><span class="save_button_span">Save Section</span> <span class="saving_in_progress"> </span><span class="sucsses_save"> </span><span class="error_in_saving"> </span></button><span class="error_massage"> </span></th>
						</tr>
					</tfoot>       
				</table>
			</div>     
		</div>        
		<?php		
	}
	
	/*################################## FEATURED PLUGINS FUNCTION #########################################*/
public function featured_plugins(){
		$plugins_array=array(
			'gallery_album'=>array(
						'image_url'		=>	wpdevart_lightbox_plugin_url.'images/featured_plugins/gallery-album-icon.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-gallery-plugin',
						'title'			=>	'WordPress Gallery plugin',
						'description'	=>	'Gallery plugin is an useful tool that will help you to create Galleries and Albums. Try our nice Gallery views and awesome animations.'
						),		
			'countdown-extended'=>array(
						'image_url'		=>	wpdevart_lightbox_plugin_url.'images/featured_plugins/icon-128x128.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-countdown-extended-version/',
						'title'			=>	'WordPress Countdown Extended',
						'description'	=>	'Countdown extended is an fresh and extended version of countdown timer. You can easily create and add countdown timers to your website.'
						),									
			'coming_soon'=>array(
						'image_url'		=>	wpdevart_lightbox_plugin_url.'images/featured_plugins/coming_soon.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-coming-soon-plugin/',
						'title'			=>	'Coming soon and Maintenance mode',
						'description'	=>	'Coming soon and Maintenance mode plugin is an awesome tool to show your visitors that you are working on your website to make it better.'
						),
			'Contact forms'=>array(
						'image_url'		=>	wpdevart_lightbox_plugin_url.'images/featured_plugins/contact_forms.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-contact-form-plugin/',
						'title'			=>	'Contact Form Builder',
						'description'	=>	'Contact Form Builder plugin is an handy tool for creating different types of contact forms on your WordPress websites.'
						),	
			'Booking Calendar'=>array(
						'image_url'		=>	wpdevart_lightbox_plugin_url.'images/featured_plugins/Booking_calendar_featured.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-booking-calendar-plugin/',
						'title'			=>	'WordPress Booking Calendar',
						'description'	=>	'WordPress Booking Calendar plugin is an awesome tool to create a booking system for your website. Create booking calendars in a few minutes.'
						),
			'Pricing Table'=>array(
						'image_url'		=>	wpdevart_lightbox_plugin_url.'images/featured_plugins/Pricing-table.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-pricing-table-plugin/',
						'title'			=>	'WordPress Pricing Table',
						'description'	=>	'WordPress Pricing Table plugin is a nice tool for creating beautiful pricing tables. Use WpDevArt pricing table themes and create tables just in a few minutes.'
						),						
			'chart'=>array(
						'image_url'		=>	wpdevart_lightbox_plugin_url.'images/featured_plugins/chart-featured.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-organization-chart-plugin/',
						'title'			=>	'WordPress Organization Chart',
						'description'	=>	'WordPress organization chart plugin is a great tool for adding organizational charts to your WordPress websites.'
						),						
			'youtube'=>array(
						'image_url'		=>	wpdevart_lightbox_plugin_url.'images/featured_plugins/youtube.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-youtube-embed-plugin',
						'title'			=>	'WordPress YouTube Embed',
						'description'	=>	'YouTube Embed plugin is an convenient tool for adding videos to your website. Use YouTube Embed plugin for adding YouTube videos in posts/pages, widgets.'
						),
            'facebook-comments'=>array(
						'image_url'		=>	wpdevart_lightbox_plugin_url.'images/featured_plugins/facebook-comments-icon.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-facebook-comments-plugin/',
						'title'			=>	'Wpdevart Social comments',
						'description'	=>	'WordPress Facebook comments plugin will help you to display Facebook Comments on your website. You can use Facebook Comments on your pages/posts.'
						),						
			'countdown'=>array(
						'image_url'		=>	wpdevart_lightbox_plugin_url.'images/featured_plugins/countdown.jpg',
						'site_url'		=>	'http://wpdevart.com/wordpress-countdown-plugin/',
						'title'			=>	'WordPress Countdown plugin',
						'description'	=>	'WordPress Countdown plugin is an nice tool for creating countdown timers for your website posts/pages and widgets.'
						),
			'facebook'=>array(
						'image_url'		=>	wpdevart_lightbox_plugin_url.'images/featured_plugins/facebook.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-facebook-like-box-plugin',
						'title'			=>	'Social Like Box',
						'description'	=>	'Facebook like box plugin will help you to display Facebook like box on your wesite, just add Facebook Like box widget to sidebar or insert it into posts/pages and use it.'
						),
			'vertical_menu'=>array(
						'image_url'		=>	wpdevart_lightbox_plugin_url.'images/featured_plugins/vertical-menu.png',
						'site_url'		=>	'https://wpdevart.com/wordpress-vertical-menu-plugin/',
						'title'			=>	'WordPress Vertical Menu',
						'description'	=>	'WordPress Vertical Menu is a handy tool for adding nice vertical menus. You can add icons for your website vertical menus using our plugin.'
						),						
			'poll'=>array(
						'image_url'		=>	wpdevart_lightbox_plugin_url.'images/featured_plugins/poll.png',
						'site_url'		=>	'http://wpdevart.com/wordpress-polls-plugin',
						'title'			=>	'WordPress Polls system',
						'description'	=>	'WordPress Polls system is an handy tool for creating polls and survey forms for your visitors. You can use our polls on widgets, posts and pages.'
						),
						
			
		);
		?>
        <style>
         .featured_plugin_main{
			background-color: #ffffff;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			float: left;
			margin-right: 30px;
			margin-bottom: 30px;
			width: calc((100% - 90px)/3);
			border-radius: 15px;
			box-shadow: 1px 1px 7px rgba(0,0,0,0.04);
			padding: 20px 25px;
			text-align: center;
			-webkit-transition:-webkit-transform 0.3s;
			-moz-transition:-moz-transform 0.3s;
			transition:transform 0.3s;   
			-webkit-transform: translateY(0);
			-moz-transform: translateY0);
			transform: translateY(0);
			min-height: 344px;
		 }
		.featured_plugin_main:hover{
			-webkit-transform: translateY(-2px);
			-moz-transform: translateY(-2px);
			transform: translateY(-2px);
		 }
		.featured_plugin_image{
			max-width: 128px;
			margin: 0 auto;
		}
		.blue_button{
			display: inline-block;
			font-size: 15px;
			text-decoration: none;
			border-radius: 5px;
			color: #ffffff;
			font-weight: 400;
			opacity: 1;
			-webkit-transition: opacity 0.3s;
			-moz-transition: opacity 0.3s;
			transition: opacity 0.3s;
			background-image: linear-gradient(141deg, #32d6db, #00a0d2);
			padding: 10px 22px;
			text-transform: uppercase;
		}
		.blue_button:hover,
		.blue_button:focus {
			color:#ffffff;
			box-shadow: none;
			outline: none;
		}
		.featured_plugin_image img{
			max-width: 100%;
		}
		.featured_plugin_image a{
		  display: inline-block;
		}
		.featured_plugin_information{	

		}
		.featured_plugin_title{
			color: #0073aa;
			font-size: 18px;
			display: inline-block;
		}
		.featured_plugin_title a{
			text-decoration:none;
			font-size: 19px;
			line-height: 22px;
			color: #00a0d2;
					
		}
		.featured_plugin_title h4{
			margin: 0px;
			margin-top: 20px;		
			min-height: 44px;	
		}
		.featured_plugin_description{
			font-size: 14px;
				min-height: 63px;
		}
		@media screen and (max-width: 1460px){
			.featured_plugin_main {
				margin-right: 20px;
				margin-bottom: 20px;
				width: calc((100% - 60px)/3);
				padding: 20px 10px;
			}
			.featured_plugin_description {
				font-size: 13px;
				min-height: 63px;
			}
		}
		@media screen and (max-width: 1279px){
			.featured_plugin_main {
				width: calc((100% - 60px)/2);
				padding: 20px 20px;
				min-height: 363px;
			}	
		}
		@media screen and (max-width: 768px){
			.featured_plugin_main {
				width: calc(100% - 30px);
				padding: 20px 20px;
				min-height: auto;
				margin: 0 auto 20px;
				float: none;
			}	
			.featured_plugin_title h4{
				min-height: auto;
			}	
			.featured_plugin_description{
				min-height: auto;
					font-size: 14px;
			}	
		}

        </style>
      
		<h1>Featured Plugins</h1>
		<?php foreach($plugins_array as $key=>$plugin) { ?>
		<div class="featured_plugin_main">
			<div class="featured_plugin_image"><a target="_blank" href="<?php echo $plugin['site_url'] ?>"><img src="<?php echo $plugin['image_url'] ?>"></a></div>
			<div class="featured_plugin_information">
				<div class="featured_plugin_title"><h4><a target="_blank" href="<?php echo $plugin['site_url'] ?>"><?php echo $plugin['title'] ?></a></h4></div>
				<p class="featured_plugin_description"><?php echo $plugin['description'] ?></p>
				<a target="_blank" href="<?php echo $plugin['site_url'] ?>" class="blue_button">Check The Plugin</a>
			</div>
			<div style="clear:both"></div>                
		</div>
		<?php } 
	
	}
	public function hire_expert(){
		$plugins_array=array(
			'custom_site_dev'=>array(
				'image_url'		=>	wpdevart_lightbox_plugin_url.'images/hire_expert/1.png',
				'title'			=>	'Custom WordPress Development',
				'description'	=>	'Hire a WordPress expert and make any custom development for your WordPress website.'
			),
			'custom_plug_dev'=>array(
				'image_url'		=>	wpdevart_lightbox_plugin_url.'images/hire_expert/2.png',
				'title'			=>	'WordPress Plugin Development',
				'description'	=>	'Our developers can create any WordPress plugin from zero. Also, they can customize any plugin and add any functionality.'
			),
			'custom_theme_dev'=>array(
				'image_url'		=>	wpdevart_lightbox_plugin_url.'images/hire_expert/3.png',
				'title'			=>	'WordPress Theme Development',
				'description'	=>	'If you need an unique theme or any customizations for a ready theme, then our developers are ready.'
			),
			'custom_theme_inst'=>array(
				'image_url'		=>	wpdevart_lightbox_plugin_url.'images/hire_expert/4.png',
				'title'			=>	'WordPress Theme Installation and Customization',
				'description'	=>	'If you need a theme installation and configuration, then just let us know, our experts configure it.'
			),
			'gen_wp_speed'=>array(
				'image_url'		=>	wpdevart_lightbox_plugin_url.'images/hire_expert/5.png',
				'title'			=>	'General WordPress Support',
				'description'	=>	'Our developers can provide general support. If you have any problem with your website, then our experts are ready to help.'
			),
			'speed_op'=>array(
				'image_url'		=>	wpdevart_lightbox_plugin_url.'images/hire_expert/6.png',
				'title'			=>	'WordPress Speed Optimization',
				'description'	=>	'Hire an expert from WpDevArt and let him take care of your website speed optimization.'
			),
			'mig_serv'=>array(
				'image_url'		=>	wpdevart_lightbox_plugin_url.'images/hire_expert/7.png',
				'title'			=>	'WordPress Migration Services',
				'description'	=>	'Our developers can migrate websites from any platform to WordPress.'
			),
			'page_seo'=>array(
				'image_url'		=>	wpdevart_lightbox_plugin_url.'images/hire_expert/8.png',
				'title'			=>	'WordPress On-Page SEO',
				'description'	=>	'On-page SEO is an important part of any website. Hire an expert and they will organize the on-page SEO for your website.'
			)
		);
		$content='';
		
		$content.='<h1 class="wpdev_hire_exp_h1"> Hire an Expert </h1>';
		$content.='<div class="hire_expert_main">';		
		foreach($plugins_array as $key=>$plugin) {
			$content.='<div class="wpdevart_hire_main"><a target="_blank" class="wpdev_hire_buklet" href="https://wpdevart.com/hire-wordpress-developer-dedicated-experts-are-ready-to-help/">';
			$content.='<div class="wpdevart_hire_image"><img src="'.$plugin["image_url"].'"></div>';
			$content.='<div class="wpdevart_hire_information">';
			$content.='<div class="wpdevart_hire_title">'.$plugin["title"].'</div>';			
			$content.='<p class="wpdevart_hire_description">'.$plugin["description"].'</p>';
			$content.='</div></a></div>';		
		} 
		$content.='<div><a target="_blank" class="wpdev_hire_button" href="https://wpdevart.com/hire-wordpress-developer-dedicated-experts-are-ready-to-help/">Start a project</a></div>';
		$content.='</div>';
		
		echo $content;	
	}	
}