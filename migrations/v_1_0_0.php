<?php

/**
*
* @package lasttopicsajax
* @copyright (c) 2014 Alg
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*/

namespace alg\lasttopicsajax\migrations;


class v_1_0_0 extends \phpbb\db\migration\migration
{
    	const OFF = 0;
	const ON = 1;
	public function effectively_installed()
	{
		return isset($this->config['lasttopicsajax_version']) && version_compare($this->config['lasttopicsajax_version'], '1.0.*', '>=');
	}

	public function update_schema()
	{
		return array(
			'add_columns' => array(
				$this->table_prefix . 'users' => array(
					'lt_show_on_index'	=> array('BOOL', 1),
				),
			),
		);
	}

	public function revert_schema()
	{
			return array(
				'drop_columns'	=> array(
					$this->table_prefix . 'users' => array('lt_show_on_index'),
				),
			);
	}

	public function update_data()
	{
		return array(
			array('config.add', array('lasttopicsajax_version', '1.0.0')),
                    array('config.add', array('lasttopicsajax_rows_amount', '5')),
                    array('config.add', array('lasttopicsajax_colums_amount', '3')),    //not in use now
                     array('config.add', array('lasttopicsajax_show_on_index', '1')),                   
                    array('config.add', array('lasttopicsajax_show_on_index_for_guests', '1')),
                    array('config.add', array('lasttopicsajax_set_width_captions', '0')),
                    array('config.add', array('lasttopicsajax_ids_exclude', '')),
                    array('config.add', array('lasttopicsajax_ids_column2', '')),
                    array('config.add', array('lasttopicsajax_ids_column1', '')),
                    array('config.add', array('lasttopicsajax_ids_column0', '*')),
                    array('config.add', array('lasttopicsajax_title_column2', '')),
                    array('config.add', array('lasttopicsajax_title_column1', '')),
                    array('config.add', array('lasttopicsajax_title_column0', '')),
                    array('config.add', array('lasttopicsajax_with_subforums_column2', v_1_0_0::OFF)),
                    array('config.add', array('lasttopicsajax_with_subforums_column1', v_1_0_0::OFF)),
                    array('config.add', array('lasttopicsajax_with_subforums_column0', v_1_0_0::OFF)),

			// Add ACP modules
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_LAST_TOPICS_AJAX')),

			array('module.add', array('acp', 'ACP_LAST_TOPICS_AJAX', array(
					'module_basename'	=> '\alg\lasttopicsajax\acp\acp_lasttopicsajax_module',
					'module_langname'	=> 'ACP_LAST_TOPICS_AJAX_SETTINGS',
					'module_mode'		=> 'lasttopicsajax',
					'module_auth'		=> 'ext_alg/lasttopicsajax && acl_a_board',
				))),
		);
	}

	public function revert_data()
	{
		return array(
			// remove from configs
				array('config.remove', array('lasttopicsajax_rows_amount')),
				array('config.remove', array('lasttopicsajax_colums_amount')),
				array('config.remove', array('lasttopicsajax_show_on_index')),
				array('config.remove', array('lasttopicsajax_show_on_index_for_guests')),
				array('config.remove', array('lasttopicsajax_set_width_captions')),
				array('config.remove', array('lasttopicsajax_ids_exclude')),
				array('config.remove', array('lasttopicsajax_ids_column2')),
				array('config.remove', array('lasttopicsajax_ids_column1')),
				array('config.remove', array('lasttopicsajax_ids_column0')),
				array('config.remove', array('lasttopicsajax_title_column2')),
				array('config.remove', array('lasttopicsajax_title_column1')),
				array('config.remove', array('lasttopicsajax_title_column0')),				array('config.remove', array('lasttopicsajax_title_column0')),
				array('config.remove', array('lasttopicsajax_with_subforums_column2')),			// Current version
				array('config.remove', array('lasttopicsajax_with_subforums_column1')),				array('config.remove', array('lasttopicsajax_version')),
				array('config.remove', array('lasttopicsajax_with_subforums_column0')),
			// remove from ACP modules
			array('if', array(
				array('module.exists', array('acp', 'ACP_LAST_TOPICS_AJAX', array(
					'module_basename'	=> '\alg\lasttopicsajax\acp\acp_lasttopicsajax_module',
					'module_langname'	=> 'ACP_LAST_TOPICS_AJAX_SETTINGS',
					'module_mode'		=> 'lasttopicsajax',
					'module_auth'		=> 'ext_alg/lasttopicsajax && acl_a_board',
					),
				)),
				array('module.remove', array('acp', 'ACP_LAST_TOPICS_AJAX', array(
					'module_basename'	=> '\alg\lasttopicsajax\acp\acp_lasttopicsajax_module',
					'module_langname'	=> 'ACP_LAST_TOPICS_AJAX_SETTINGS',
					'module_mode'		=> 'lasttopicsajax',
					'module_auth'		=> 'ext_alg/lasttopicsajax && acl_a_board',
					),
				)),
			)),
			array('module.remove', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_LAST_TOPICS_AJAX')),
		);
	}
}
