<?php

/**
*
* @package lasttopicsajax
* @copyright (c) 2014 Alg
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2

*/

namespace alg\lasttopicsajax\acp;

/**
 * @ignore
 */

class acp_lasttopicsajax_module
{
	//public $u_action;

	public function main($id, $mode)
	{
		global $user, $template, $config, $phpbb_container;
		$controller = $phpbb_container->get('alg.lasttopicsajax.lasttopicsajax_handler');

		$user->add_lang('acp/common');
		$this->tpl_name = 'acp_lasttopicsajax';
		$this->page_title = $user->lang('ACP_LAST_TOPICS_AJAX');
		$template->assign_vars(array(
			'S_LASTTOPICSAJAX_PAGE'	=> true,
			'ROWS_AMOUNT'			=> $config['lasttopicsajax_rows_amount'],
			'COLUMNS_AMOUNT'		=> $config['lasttopicsajax_colums_amount'],
			'S_SHOW_ON_INDEX'	=> (bool) $config['lasttopicsajax_show_on_index'],
			'S_SHOW_ON_INDEX_FOR_GUESTS'	=> (bool) $config['lasttopicsajax_show_on_index_for_guests'],
			'SET_WIDTH_CAPTIONS'			    => $config['lasttopicsajax_set_width_captions'],
			'IDS_EXCLUDE'			    => $config['lasttopicsajax_ids_exclude'],
			'IDS_COLUMN2'			    => $config['lasttopicsajax_ids_column2'],
			'IDS_COLUMN1'			    => $config['lasttopicsajax_ids_column1'],
			'IDS_COLUMN0'			    => $config['lasttopicsajax_ids_column0'],
			'TITLE_COLUMN2'			=> $config['lasttopicsajax_title_column2'],
			'TITLE_COLUMN1'			=> $config['lasttopicsajax_title_column1'],
			'TITLE_COLUMN0'			=> $config['lasttopicsajax_title_column0'],
			'WITH_SUBFORUMS_COLUMN2'	=> $config['lasttopicsajax_with_subforums_column2']  ? 'checked' : '',
			'WITH_SUBFORUMS_COLUMN1'	=> $config['lasttopicsajax_with_subforums_column1'] ? 'checked' : '',
			'WITH_SUBFORUMS_COLUMN0'	=> $config['lasttopicsajax_with_subforums_column0'] ? 'checked' : '',
			'U_LASTTOPICSAJAX_PATH_SAVE'	=> $controller->get_router_path('save'),
		));
	}
}
