<?php

/**
*
* @package lasttopicsajax
* @copyright (c) 2017 Алг
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace alg\lasttopicsajax\acp;

class lasttopicsajax_info
{

	function module()
	{
		return array(
			'filename'		=> '\alg\lasttopicsajax\acp\acp_lasttopicsajax_module',
			'title'			=> 'ACP_LAST_TOPICS_AJAX',
			'version'		=> '1.0.0',
			'modes'		=> array(
			'lasttopicsajax'	=> array('title' => 'ACP_LAST_TOPICS_AJAX_SETTINGS', 'auth' => 'ext_alg/lasttopicsajax && acl_a_board', 'cat' => array('ACP_LAST_TOPICS_AJAX')),
			),
		);
	}
}
