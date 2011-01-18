<?php if (! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Matrix URL Title Celltype Class for EE2
 * 
 * @package   Matrix
 * @author    Brandon Kelly <brandon@pixelandtonic.com>
 * @copyright Copyright (c) 2011 Pixel & Tonic, Inc
 */
class Matrix_url_title_ft extends EE_Fieldtype {

	var $info = array(
		'name' => 'Matrix URL Title',
		'version' => '0.1'
	);

	var $default_settings = array(
		'title_col' => '',
		'dir' => 'ltr'
	);

	var $max_length = 75;

	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->EE =& get_instance();

		// -------------------------------------------
		//  Prepare Cache
		// -------------------------------------------

		if (! isset($this->EE->session->cache['matrix_url_title']['celltypes']['text']))
		{
			$this->EE->session->cache['matrix_url_title']['celltypes']['text'] = array();
		}
		$this->cache =& $this->EE->session->cache['matrix_url_title']['celltypes']['text'];
	}

	/**
	 * Prep Settings
	 */
	private function _prep_settings(&$settings)
	{
		$settings = array_merge($this->default_settings, $settings);
	}

	// --------------------------------------------------------------------

	/**
	 * Theme URL
	 */
	private function _theme_url()
	{
		if (! isset($this->cache['theme_url']))
		{
			$theme_folder_url = $this->EE->config->item('theme_folder_url');
			if (substr($theme_folder_url, -1) != '/') $theme_folder_url .= '/';
			$this->cache['theme_url'] = $theme_folder_url.'third_party/matrix_url_title/';
		}

		return $this->cache['theme_url'];
	}

	/**
	 * Include Theme JS
	 */
	private function _include_theme_js($file)
	{
		$this->EE->cp->add_to_foot('<script type="text/javascript" src="'.$this->_theme_url().$file.'"></script>');
	}

	// --------------------------------------------------------------------

	/**
	 * Display Cell Settings
	 */
	function display_cell_settings($data)
	{
		$this->_prep_settings($data);

		// load the language file
		$this->EE->lang->loadfile('matrix_url_title');

		return array(
			array(lang('title_col'), form_input('title_col', $data['title_col'], 'class="matrix-textarea"'))
		);
	}

	/**
	 * Modify exp_matrix_data Column Settings
	 */
	function settings_modify_matrix_column($data)
	{
		return array(
			'col_id_'.$data['col_id'] => array('type' => 'varchar', 'constraint' => $this->max_length)
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Display Cell
	 */
	function display_cell($data)
	{
		$this->_prep_settings($this->settings);

		if (! isset($this->cache['displayed']))
		{
			// include matrix_url_title.js
			$this->_include_theme_js('scripts/matrix_url_title.js');

			$this->cache['displayed'] = TRUE;
		}

		$r['class'] = 'matrix-text';
		$r['data'] = '<input type="text" class="matrix-textarea" name="'.$this->cell_name.'" maxlength="'.$this->max_length.'" dir="'.$this->settings['dir'].'" value="'.$data.'" />';

		return $r;
	}

}