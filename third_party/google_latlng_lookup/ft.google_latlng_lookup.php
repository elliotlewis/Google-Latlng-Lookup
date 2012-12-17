<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Google_latlng_lookup_ft Class
 *
 * @package			ExpressionEngine
 * @category		Fieldtype
 * @author			Elliot Lewis
 * @copyright		Copyright (c) 2012, No Two The Same Ltd.
 * @link			http://devot-ee.com/add-ons/google-latlng-lookup/
 */


// Version History
// ---------------
//  * __1.0.3 27/11/12__
//    - Altered validate method so empty values validate (thanks to Creative Lynx)
//    - Added SafeCracker compatibility
//  * __1.0.2, 31/08/2012__
//    - Allowed fine tuning of co-ordinates by adjusting form values
//  * __1.0.1, 15/06/2012__  
//    - Fixed silly error where I was directly accessing the LatLng object instead of using built in function to return the co-ordinates
//    - Added more error feedback
//    - Feedback on type of result returned, Eg. Approximate location
//  * __1.0, 12/06/2012__  
//     1st release. Global and Channel Field default settings

 
class Google_latlng_lookup_ft extends EE_Fieldtype {
	
	var $info = array(
		'name'		=> 'Google Maps Lat Lng Lookup',
		'version'	=> '1.0.3'
	);
	
	var $prefix = 'google_latlng_lookup_';
	
	// --------------------------------------------------------------------
	
	/**
	 * Display Field on Publish
	 * This handles both displaying default values from Channel Fields > Edit Field OR Global Settings
	 * And reading saved data from channel entry. $data contains entry saved data with existing entry 
	 *
	 * @access	public
	 * @param	existing data
	 * @return	field html
	 *
	 */
	function display_field($data)
	{
		$data_points = array('address', 'latitude', 'longitude');
		
		if ($data)
		{
			list($address, $latitude, $longitude) = explode('|', $data);
		}
		else
		{
			foreach($data_points as $key)
			{
				$$key = $this->settings[$key];
			}
		}
		
		// override values from POST if present
		if(!empty($_POST)) {
			
			foreach($data_points as $key) {
				
				if(!empty($_POST[$this->prefix.$key])) {
					$$key = $this->EE->input->post($this->prefix.$key,TRUE);
				}
			}
		}

		$options = compact($data_points);
		
		$this->_cp_js();
		
		$form = '';
		
		$form .= '<div style="float:left;width:50%;">';
		
		$form .= form_label('Address', $this->prefix.'address').form_input($this->prefix.'address', $address, 'style="margin-bottom:10px;"');
		$form .= form_button(array('name' => $this->prefix.'lookup_button', 'class' => 'submit'), 'Find Lat / Lng', 'style="cursor:pointer;margin-bottom:20px;"');
		
		$form .= '<br />';
		
		$form .= form_label('Latitude', $this->prefix.'latitude');
		$form .= form_input($this->prefix.'latitude', $latitude, ' class="geocode_latlng geocode_lat" style="border-color:white;"');
		
		$form .= form_label('Longitude', $this->prefix.'longitude');
		$form .= form_input($this->prefix.'longitude', $longitude, ' class="geocode_latlng geocode_lng" style="border-color:white;"');
		
		$form .= '<br /><span class="'.$this->prefix.'feedback" style="display:block;margin-top:5px;color:steelBlue;"></span>';
		
		$form .= '</div>';
		
		$markers = 'markers=color:blue%7Csize:mid%7Clabel:A%7C'.$latitude.','.$longitude;
		$form .= '<img src="http://maps.googleapis.com/maps/api/staticmap?size=170x170&zoom=13&maptype=roadmap&'.$markers.'&sensor=false" alt="Preview map of address" class="'.$this->prefix.'preview_map" style="margin:20px; border:#5f6c74 1px solid;" />';
		
		$form .= form_input($this->field_name, implode('|', array_values($options)), 'id="'.$this->field_name.'"readonly="readonly" style="display:none;"');
		
		return $form;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Prep data for saving
	 *
	 * @access	public
	 * @param	posted data
	 * @return	string
	 *
	 */
	function save($data)
	{
	
		$data = $this->EE->input->post($this->prefix.'address', TRUE) . '|' . $this->EE->input->post($this->prefix.'latitude', TRUE) . '|' . $this->EE->input->post($this->prefix.'longitude', TRUE);
		
		return $data;
	}
	
	
	// --------------------------------------------------------------------
	
	function validate($data) {
		
		$lat = $this->EE->input->post($this->prefix.'latitude', TRUE);
		$lng = $this->EE->input->post($this->prefix.'longitude', TRUE);
		
		if(!empty($lat) || !empty($lng)){
			if(!is_numeric($lat) || !is_numeric($lng)) {
				return 'Latitude and Longitude must be numbers';
			}
		}
		
		return TRUE;
		
	}
	 
	// --------------------------------------------------------------------
		
	/**
	 * Replace tag
	 *
	 * @access	public
	 * @param	field contents
	 * @return	replacement text
	 *
	 */
	function replace_tag($data, $params = array(), $tagdata = FALSE)
	{

		list($address, $latitude, $longitude) = explode('|', $data);
		
		return $latitude . ',' . $longitude;
		
	}
	
	// --------------------------------------------------------------------
		
	/**
	 * Replace tag modifiers
	 *
	 * @access	public
	 * @param	1 vairable from field contents
	 * @return	replacement text
	 *
	 */
	function replace_address($data, $params = array(), $tagdata = FALSE)
	{

		list($address, $latitude, $longitude) = explode('|', $data);
		
		return $address;
	}
	
	function replace_latitude($data, $params = array(), $tagdata = FALSE)
	{

		list($address, $latitude, $longitude) = explode('|', $data);
		
		return $latitude;
	}
	
	function replace_longitude($data, $params = array(), $tagdata = FALSE)
	{

		list($address, $latitude, $longitude) = explode('|', $data);
		
		return $longitude;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Display Global Settings
	 * View Addons > Fieldtypes > Google Maps Lat Lng Lookup 
	 *
	 * @access	public
	 * @return	form contents
	 *
	 */
	function display_global_settings()
	{
		$val = array_merge($this->settings, $_POST);
		
		// Add script tags
		$this->_cp_js(FALSE);
		
		$form = '';
		
		$form .= '<h3>Default Details</h3>';
		
		$form .= '<p>';
		$form .= form_label('Address', 'address').form_input('address', $val['address']);
		$form .= '</p>';
		$form .= '<p>';
		$form .= form_button(array('name' => 'lookup_button', 'class' => 'submit'), 'Find Lat / Lng', 'style="cursor:pointer;"');
		$form .= '</p>';
		$form .= '<p>';
		$form .= form_label('Latitude', 'latitude').form_input('latitude', $val['latitude']);
		$form .= form_label('Longitude', 'longitude').form_input('longitude', $val['longitude']);
		$form .= '</p>';
		$form .= '<p>';
		$markers = 'markers=color:blue%7Csize:mid%7Clabel:A%7C'.$val['latitude'].','.$val['longitude'];
		$form .= '<img src="http://maps.googleapis.com/maps/api/staticmap?size=170x170&zoom=13&maptype=roadmap&'.$markers.'&sensor=false" alt="Preview map of address" class="preview_map" style="margin:20px; border:#5f6c74 1px solid;" />';
		$form .= '<br /><span class="feedback"></span>';
		$form .= '</p>';

		return $form;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Save Global Settings
	 *
	 * @access	public
	 * @return	global settings
	 *
	 */
	function save_global_settings()
	{
		return array_merge($this->settings, $_POST);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Display Settings Screen
	 *
	 * @access	public
	 * @return	default global settings
	 *
	 */
	function display_settings($data)
	{
		$address	= isset($data['address']) ? $data['address'] : $this->settings['address'];
		$latitude	= isset($data['latitude']) ? $data['latitude'] : $this->settings['latitude'];
		$longitude	= isset($data['longitude']) ? $data['longitude'] : $this->settings['longitude'];
		
		$this->EE->table->add_row(
			lang('Address', 'address'),
			form_input('address', $address)
		);
		
		$this->EE->table->add_row(
			lang('Latitude', 'latitude'),
			form_input('latitude', $latitude)
		);
		
		$this->EE->table->add_row(
			lang('Longitude', 'longitude'),
			form_input('longitude', $longitude)
		);

	}
	
	// --------------------------------------------------------------------

	/**
	 * Save Settings
	 *
	 * @access	public
	 * @return	field settings
	 *
	 */
	function save_settings($data)
	{
		return array(
			'address'	=> $this->EE->input->post('address'),
			'latitude'	=> $this->EE->input->post('latitude'),
			'longitude'	=> $this->EE->input->post('longitude')
		);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Install Fieldtype
	 *
	 * @access	public
	 * @return	default global settings
	 *
	 */
	function install()
	{
		return array(
			'address'	=> 'Trafalgar Square, City of Westminster, WC2N 5DN',
			'latitude'	=> '',
			'longitude'	=> ''
		);
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Control Panel Javascript
	 *
	 * @access	public
	 * @return	void
	 *
	 */
	function _cp_js( $use_prefix = TRUE )
	{
		// This js is used on all cp pages
		
		$use_prefix ? $prefix = $this->prefix : $prefix = '';
		
		$this->EE->cp->add_to_head('<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>');
		
		if(REQ == 'CP')
		{
			$this->EE->cp->load_package_js('cp');
		}
		else
		{
			if($this->EE->config->item('url_third_themes'))
			{
				$this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$this->EE->config->item('url_third_themes').'google_latlng_lookup/js/google_latlng_lookup.js"></script>');
			}
			else
			{
				$this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$this->EE->config->item('theme_folder_url').'third_party/google_latlng_lookup/js/google_latlng_lookup.js"></script>');
			}
		}
		
		$this->EE->javascript->output('google_lat_lng_lookup_map("'.$prefix.'");'); // initialize map obj		

	}
}

/* End of file ft.google_maps.php */
/* Location: ./system/expressionengine/third_party/google_latlng_lookup/ft.google_latlng_lookup_ft.php */