<?php
/**
*
* @author Alg
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

namespace alg\lasttopicsajax\controller;

class lasttopicsajax_handler
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\user */
	protected $user;

	/** @var string phpbb_root_path */
	protected $phpbb_root_path;

	/** @var string phpEx */
	protected $php_ext;

	/** @var \phpbb\controller\helper */
	protected $controller_helper;

/** @var \phpbb\template\context */
	protected $template_context;

	/** @var array */
	protected $return_error = array();

	/**
	* Constructor
	* @param \phpbb\config\config				$config			Config object
	* @param \phpbb\db\driver\driver_interface		$db				DBAL object
	* @param \phpbb\auth\auth					$auth			Auth object
	* @param \phpbb\template\template			$template  Template object
	* @param \phpbb\user						$user			User object
	* @param \phpbb\request\request_interface		$request   Request object
	* @param string							$phpbb_root_path	phpbb_root_path
	* @param string							$php_ext			phpEx
	* @param \phpbb\controller\helper				$controller_helper	Controller helper object
	* @param array							$return_error		array

	* @access public
	*/
	const COLUMN_LAST= 0;
	const COLUMN_MIDDLE = 1;
	const COLUMN_FIRST = 2;

	public function __construct(\phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\auth\auth $auth, \phpbb\template\template $template, \phpbb\user $user, \phpbb\request\request_interface $request, $phpbb_root_path, $php_ext, \phpbb\controller\helper $controller_helper, \phpbb\content_visibility $content_visibility, \phpbb\event\dispatcher_interface $dispatcher, \phpbb\pagination $pagination, \phpbb\template\context $template_context, \phpbb\extension\manager $phpbb_extension_manager)
	{
		$this->config = $config;
		$this->db = $db;
		$this->auth = $auth;
		$this->template = $template;
		$this->user = $user;
		$this->request = $request;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
		$this->controller_helper = $controller_helper;
		$this->content_visibility = $content_visibility;
		$this->dispatcher = $dispatcher;
		$this->pagination =  $pagination;
		$this->template_context = $template_context;
		$this->phpbb_extension_manager = $phpbb_extension_manager;

		$this->return = array(); // save returned data in here
		$this->error = array(); // save errors in here
	}
	public function save_options()
	{
		$this->user->add_lang_ext('alg/lasttopicsajax', 'info_acp_lasttopicsajax');
		$lasttopicsajax_rows_amount = $this->request->variable('lasttopicsajax_rows_amount', 0);
		//$lasttopicsajax_colums_amount = $this->request->variable('lasttopicsajax_colums_amount', 0);
		$lasttopicsajax_colums_amount = 3;
		$lasttopicsajax_show_on_index = $this->request->variable('lasttopicsajax_show_on_index', 1);
		$lasttopicsajax_show_on_index_for_guests = $this->request->variable('lasttopicsajax_show_on_index_for_guests', 1);
		$lasttopicsajax_set_width_captions = $this->request->variable('lasttopicsajax_set_width_captions', 0);
		$lasttopicsajax_ids_exclude = $this->request->variable('lasttopicsajax_ids_exclude', '');
		$lasttopicsajax_ids_column2 = $this->request->variable('lasttopicsajax_ids_column2', '');
		$lasttopicsajax_ids_column1 = $this->request->variable('lasttopicsajax_ids_column1', '');
		$lasttopicsajax_ids_column0 = $this->request->variable('lasttopicsajax_ids_column0', '');
		$lasttopicsajax_with_subforums_column2 = $this->request->variable('with_subforums_column2', '');
		$lasttopicsajax_with_subforums_column1 = $this->request->variable('with_subforums_column1', '');
		$lasttopicsajax_with_subforums_column0 = $this->request->variable('with_subforums_column0', '');
		$lasttopicsajax_title_column2 = utf8_normalize_nfc($this->request->variable('lasttopicsajax_title_column2', '',true));
		$lasttopicsajax_title_column1 = utf8_normalize_nfc($this->request->variable('lasttopicsajax_title_column1', '',true));
		$lasttopicsajax_title_column0 = utf8_normalize_nfc($this->request->variable('lasttopicsajax_title_column0', '',true));
		$validate = true;
		//todo validate data
		if (!$validate)
		{
			$message = sprintf($this->user->lang['ACP_CLOSETOPICCONDITION_NO_USERS'], $noty_sender_name);
			$this->error[] = array('error' => sprintf($this->user->lang['ACP_CLOSETOPICCONDITION_NO_USERS'], $noty_sender_name));
			$json_response = new \phpbb\json_response;
			$json_response->send($this->error);
		}
 
		$save_data = array(
			'lasttopicsajax_rows_amount'	=> $lasttopicsajax_rows_amount,
			'lasttopicsajax_colums_amount'	=>$lasttopicsajax_colums_amount,
			'lasttopicsajax_show_on_index'	=>$lasttopicsajax_show_on_index,
			'lasttopicsajax_show_on_index_for_guests'	=>$lasttopicsajax_show_on_index_for_guests,
			'lasttopicsajax_set_width_captions'  =>$lasttopicsajax_set_width_captions,
			'lasttopicsajax_ids_exclude'	=> $lasttopicsajax_ids_exclude,
			'lasttopicsajax_ids_column2'	=> $lasttopicsajax_ids_column2,
			'lasttopicsajax_ids_column1'	=> $lasttopicsajax_ids_column1,
			'lasttopicsajax_ids_column0'	=> $lasttopicsajax_ids_column0,
			'lasttopicsajax_title_column2'	=> $lasttopicsajax_title_column2,
			'lasttopicsajax_title_column1'	=> $lasttopicsajax_title_column1,
			'lasttopicsajax_title_column0'	=> $lasttopicsajax_title_column0,
			'$lasttopicsajax_with_subforums_column2'	=> $lasttopicsajax_with_subforums_column2,
			'$lasttopicsajax_with_subforums_column1'	=> $lasttopicsajax_with_subforums_column1,
			'$lasttopicsajax_with_subforums_column0'	=> $lasttopicsajax_with_subforums_column0,

		);

		//add to config
		$this->config->set('lasttopicsajax_rows_amount', $lasttopicsajax_rows_amount);
		$this->config->set('lasttopicsajax_colums_amount', $lasttopicsajax_colums_amount);
		$this->config->set('lasttopicsajax_show_on_index', $lasttopicsajax_show_on_index);
		$this->config->set('lasttopicsajax_show_on_index_for_guests', $lasttopicsajax_show_on_index_for_guests);
		$this->config->set('lasttopicsajax_set_width_captions', $lasttopicsajax_set_width_captions);
		$this->config->set('lasttopicsajax_ids_exclude', $lasttopicsajax_ids_exclude);
		$this->config->set('lasttopicsajax_ids_column2', $lasttopicsajax_ids_column2);
		$this->config->set('lasttopicsajax_ids_column1', $lasttopicsajax_ids_column1);
		$this->config->set('lasttopicsajax_ids_column0', $lasttopicsajax_ids_column0);
		$this->config->set('lasttopicsajax_title_column2', $lasttopicsajax_title_column2);
		$this->config->set('lasttopicsajax_title_column1', $lasttopicsajax_title_column1);
		$this->config->set('lasttopicsajax_title_column0', $lasttopicsajax_title_column0);
		$this->config->set('lasttopicsajax_with_subforums_column2', $lasttopicsajax_with_subforums_column2);
		$this->config->set('lasttopicsajax_with_subforums_column1', $lasttopicsajax_with_subforums_column1);
		$this->config->set('lasttopicsajax_with_subforums_column0', $lasttopicsajax_with_subforums_column0);

		$this->return = array(
			'MESSAGE'		=> $this->user->lang['ACP_LASTTOPICSAJAX_SAVED'] ,
		);
		$json_response = new \phpbb\json_response;
		$json_response->send($this->return);
	}
	private function get_forums_by_column($num)
	{
		$ids_by_column = $this->config['lasttopicsajax_ids_column' . $num];
		$with_subforums=$this->config['lasttopicsajax_with_subforums_column' . $num];

		// Get a list of forums the user cannot read
		$ex_fid_ary = array();
		$col_forums = array();
		$ex_fid_ary = array_unique(array_keys($this->auth->acl_getf('!f_read', true)));

		if ($this->config['lasttopicsajax_ids_exclude'])
		{
			$exclude_forums = explode(',', $this->config['lasttopicsajax_ids_exclude']);
			if (sizeof($exclude_forums))
			{
				$ex_fid_ary = array_merge($ex_fid_ary, $exclude_forums);
				$ex_fid_ary = array_unique($ex_fid_ary);
			}
		}
		if ($ids_by_column)
		{
			if ($num == 0 && $ids_by_column == '*')
			{
				$col_forums = $this->get_all_remaining_forums($ex_fid_ary);
			}
			else
			{
				$col_forums = explode(',', $ids_by_column);
			}
			if (sizeof($col_forums))
			{
				$col_forums = array_unique($col_forums);
				if ($with_subforums && $ids_by_column != '*')
				{
					foreach ($col_forums as $fid)
					{
						$sql = 'SELECT left_id, right_id  FROM ' . FORUMS_TABLE . ' WHERE forum_id =' . $fid;
						$result = $this->db->sql_query($sql);
						$row_res = $this->db->sql_fetchrow($result);
						$this->db->sql_freeresult($result); 
						$left_id = $row_res['left_id'];
						$sql = 'SELECT forum_id  FROM ' . FORUMS_TABLE . 
								' WHERE left_id >=' . $row_res['left_id'] . ' AND right_id <=' . $row_res['right_id'];
						$result = $this->db->sql_query($sql);
						while ($row = $this->db->sql_fetchrow($result))
						{
							array_push($col_forums, $row['forum_id']);
						}
						$this->db->sql_freeresult($result); 
 					}
				}
				$col_forums = array_unique($col_forums);
			}
		}
		return $col_forums;
	}
	public function get_latest_topics_by_column($num, $start)
	{
		$user_id = (int) $this->user->data['user_id'];
		$col_num = $this->config['lasttopicsajax_ids_column' . $num];
		$col_name = 'lasttopicsajax_ids_column' . $num;
		$with_subforums = $this->config['lasttopicsajax_with_subforums_column' . $num];
		$colums_amount = $this->config['lasttopicsajax_colums_amount'];
		$howmany =  $this->config['lasttopicsajax_rows_amount'];
		$per_page = $howmany;

		$tpl_ary =  array();

		// Get a list of forums the user cannot read
		$ex_fid_ary = array();
		$col_forums = array();
		$rowset_dst = array();
		$ex_fid_ary = array_unique(array_keys($this->auth->acl_getf('!f_read', true)));

		if ($this->config['lasttopicsajax_ids_exclude'])
		{
			$exclude_forums = explode(',', $this->config['lasttopicsajax_ids_exclude']);
			if (sizeof($exclude_forums))
			{
				$ex_fid_ary = array_merge($ex_fid_ary, $exclude_forums);
				$ex_fid_ary = array_unique($ex_fid_ary);
			}
		}

		if ($this->config[$col_name])
		{
			if ($num == 0 && $this->config[$col_name] == '*')
			{
				$col_forums = $this->get_all_remaining_forums($ex_fid_ary);
			}
			else
			{
				$col_forums = explode(',', $this->config[$col_name]);
			}
			if (sizeof($col_forums))
			{
				$col_forums = array_unique($col_forums);
				if ($with_subforums)
				{
					foreach ($col_forums as $fid)
					{
						$sql = 'SELECT left_id, right_id  FROM ' . FORUMS_TABLE . ' WHERE forum_id =' . $fid;
						$result = $this->db->sql_query($sql);
						$row_res = $this->db->sql_fetchrow($result);
						$this->db->sql_freeresult($result); 
						$left_id = $row_res['left_id'];
						$sql = 'SELECT forum_id  FROM ' . FORUMS_TABLE .  ' WHERE left_id >=' . $row_res['left_id'] . ' AND right_id <=' . $row_res['right_id'];
						$result = $this->db->sql_query($sql);
						while ($row = $this->db->sql_fetchrow($result))
						{
							array_push($col_forums, $row['forum_id']);
						}
						$this->db->sql_freeresult($result); 
					}
				}  
				$col_forums = array_unique($col_forums); 
				$where = ' topic_status <> ' . ITEM_MOVED  . '  AND t.topic_visibility = ' .  ITEM_APPROVED .  ' AND ' . $this->db->sql_in_set('f.forum_id', $col_forums, false);
				if (sizeof($ex_fid_ary))
				{
					$where .= ' AND ' . $this->db->sql_in_set('f.forum_id', $ex_fid_ary, true);
				}
				$select = 'u.user_id, u.username, u.user_colour, t.topic_title, t.forum_id, t.topic_id, t.topic_first_post_id, t.topic_last_post_id, topic_time, t.topic_last_post_time, t.topic_last_poster_name,  t.topic_posts_approved, t.topic_posts_unapproved, t.topic_posts_softdeleted, t.topic_status, t.topic_type, t.poll_start, f.forum_name, tt.mark_time, ft.mark_time as f_mark_time';
				//TODO
				if (0)
				{
					$select .= ', fp.post_text AS first_post_text';
				}
				//TODO
				if (0)
				{
					$select .= ', lp.post_text AS last_post_text';
				}
				//TODO
				if (0)
				{
					$select .= ', fpu.user_avatar AS fp_avatar,
						fpu.user_avatar_type AS fp_avatar_type,
						fpu.user_avatar_width AS fp_avatar_width,
						fpu.user_avatar_height AS fp_avatar_height';

					if (0)
					{
						$select .= ', lpu.user_avatar AS lp_avatar,
							lpu.user_avatar_type AS lp_avatar_type,
							lpu.user_avatar_width AS lp_avatar_width,
							lpu.user_avatar_height AS lp_avatar_height';
					}
				}
				$sql_array = array(
					'SELECT'	=> $select,

					'FROM'		=> array(TOPICS_TABLE => 't'),
					'LEFT_JOIN'	=> array(
						array(
							'FROM'	=> array(FORUMS_TABLE => 'f'),
							'ON'	=> 'f.forum_id = t.forum_id',
						),
						array(
							'FROM'	=> array(TOPICS_TRACK_TABLE => 'tt'),
							'ON'	=> 'tt.user_id = ' . $user_id .  ' AND t.topic_id = tt.topic_id' ,
						),
						array(
							'FROM'	=> array(FORUMS_TRACK_TABLE => 'ft'),
							'ON'	=> 'ft.user_id = ' . $user_id .  ' AND  ft.forum_id = f.forum_id' ,
						),
						array(
							'FROM'	=> array(USERS_TABLE => 'u'),
							'ON'	=> 't.topic_last_poster_id = u.user_id',
						),
					),
					'WHERE'		=> $where , 
					'ORDER_BY'	=> 't.topic_last_post_time DESC',
				);
			/**
			* Event to modify the SQL query before the topics data is retrieved
			*
			* @event alg.lasttopicsajax.sql_latest_topics_col_ . $num
			* @var	array	sql_array		The SQL array
			* @since 1.0.0
			*/
			$vars = array('sql_array');
			extract($this->dispatcher->trigger_event('alg.lasttopicsajax.sql_latest_topics_col_' . $num, compact($vars)));	
			$result = $this->db->sql_query_limit($this->db->sql_build_query('SELECT', $sql_array),  $howmany, $start); 

			$row_count = 0;
			while ($row = $this->db->sql_fetchrow($result))
			{
				$forum_id = (int) $row['forum_id'];
				$topic_id = (int) $row['topic_id'];
				$rowset[$topic_id] = $row;
				if ($this->auth->acl_get('f_read',$forum_id))
				{
					$row_count++;
					// Get topic tracking info
					if ($this->user->data['is_registered'] && $this->config['load_db_lastread'] && !$this->config['ls_topics_cache'])   //todo ls_topics_cache
					{
						$topic_tracking_info = get_topic_tracking($forum_id, $topic_id, $rowset, array($forum_id => $row['f_mark_time']));
					}
					else if ($this->config['load_anon_lastread'] || $this->user->data['is_registered'])
					{
						$topic_tracking_info = get_complete_topic_tracking($forum_id, $topic_id);
						if (!$this->user->data['is_registered'])
						{
							$this->user->data['user_lastmark'] = (isset($tracking_topics['l'])) ? (int) (base_convert($tracking_topics['l'], 36, 10) + $this->config['board_startdate']) : 0;
						}
					}
					$replies = $this->content_visibility->get_count('topic_posts', $row, $forum_id) - 1;
					$folder_img = $folder_alt = $topic_type = '';
					$unread_topic = (isset($topic_tracking_info[$row['topic_id']]) && $row['topic_last_post_time'] > $topic_tracking_info[$row['topic_id']]) ? true : false;
					if (!function_exists('topic_status'))
					{
						include($this->phpbb_root_path . 'includes/functions_display.' . $this->php_ext);
					}
					topic_status($row, $replies, $unread_topic, $folder_img, $folder_alt, $topic_type);
					$title = $row['topic_title'] ;
					if (sizeof($col_forums) > 1)
					{
						$title .=  '(' . $row['forum_name'] . ')';
					}
					$set_with_captions = (int) $this->config['lasttopicsajax_set_width_captions'];
					if ($set_with_captions > 0 && strlen($title) > $set_with_captions)
					{
						$title = mb_substr($title, 0, $set_with_captions) . '...';
					}
					$is_guest = $row['user_id'] != ANONYMOUS ? false : true;
					$view_topic = append_sid("{$this->phpbb_root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;t=' . $row['topic_id']);
					$view_topic = str_replace('../', '', $view_topic);

					$last_post = append_sid("{$this->phpbb_root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;t=' . $row['topic_id'] . '&amp;p=' . $row['topic_last_post_id']) . '#p' . $row['topic_last_post_id'];
					$last_post = str_replace('../', '', $last_post);

					$newest_post = append_sid("{$this->phpbb_root_path}viewtopic.$this->php_ext", 'f=' . $row['forum_id'] . '&amp;t=' . $row['topic_id'] . '&amp;view=unread') . '#unread';
					$newest_post = str_replace('../', '', $newest_post);
					$first_post_preview_text = '';
					$tpl_ary = array(
						'FORUM_ID'	=> $forum_id,
						'TOPIC_ID'	=> $topic_id,
						'TITLE'	 		=> $this->character_limit($title,60), //todo
											'USERNAME_FULL'		=> $is_guest ? $this->user->lang['POST_BY_AUTHOR'] . ' ' . get_username_string('no_profile', $row['user_id'], $row['username'], $row['user_colour'], $row['topic_last_poster_name']) : $this->user->lang['POST_BY_AUTHOR'] . ' ' . get_username_string('full', $row['user_id'], $row['username'], $row['user_colour']),
						'U_VIEW_TOPIC'	=> $view_topic,
						'TOPIC_REPLIES'	=> $replies,
						'TOPIC_TIME_CREATE'	=>  $this->user->format_date($row['topic_time']),
						'U_NEWEST_POST'	=> $newest_post,
						'U_LAST_POST'=> $last_post,
											'S_UNREAD_TOPIC'		=> $unread_topic,
					);
					/**
					* Modify the topic data before it is assigned to the template
					*
					* @event alg.lasttopicssajax.modify_tpl_ary_latest_general_topics
					* @var	array	row			Array with topic data
					* @var	array	tpl_ary		Template block array with topic data
					* @since 1.0.0
					*/
					$vars = array('row', 'tpl_ary');
					extract($this->dispatcher->trigger_event('alg.lasttopicssajax.modify_tpl_ary_latest_topics_' . $num, compact($vars)));
					$rowset_dst[] = $tpl_ary;
					$this->template->assign_block_vars('latest_topics_' . $num, $tpl_ary);  
				}//end if
			}//end while
			//pagination
			$sql = "SELECT count(t.topic_id) as total_count" .
						" FROM " .TOPICS_TABLE . " t LEFT JOIN " . FORUMS_TABLE . " f ON (f.forum_id = t.forum_id) WHERE " ;
			$sql .= $where;
					$result = $this->db->sql_query($sql);
					$row = $this->db->sql_fetchrow($result);
					$total_count = (int) $row['total_count'];
			$pagination_url = str_replace('../', '', $this->get_router_path('pagination'));
					$this->pagination->generate_template_pagination($pagination_url, 'lt_' . $num  , 'start', $total_count, $per_page, $start);
			return $rowset_dst;
		}
	}
}//end function
	public function last_in_popup()
	{
		$this->user->add_lang_ext('alg/lasttopicsajax', 'lasttopicsajax');
		$start=0;
		$rowset =  $this->get_latest_topics_by_column(2, $start);
		$show_col2 = is_array($rowset) ? (bool) sizeof( $rowset) >0 : false;
		$rowset = $this->get_latest_topics_by_column(1, $start);
		$show_col1 = is_array($rowset) ? (bool) sizeof( $rowset) >0 : false;
		$rowset = $this->get_latest_topics_by_column(0, $start);
		$show_col0 = is_array($rowset) ? (bool) sizeof( $rowset) >0 : false;

		$this->template->assign_vars(array(
			'NEWEST_POST_IMG'	=> $this->user->img('icon_topic_newest', 'VIEW_NEWEST_POST'),
			'LATEST_POST_IMG'		=> $this->user->img('icon_topic_latest', 'VIEW_LATEST_POST'),
			'L_COL2'				=> $this->config['lasttopicsajax_title_column2'],
			'L_COL1'				=> $this->config['lasttopicsajax_title_column1'],
			'L_COL0'				=> $this->config['lasttopicsajax_title_column0'],
			'SHOW_COL2'			=> $show_col2,
			'SHOW_COL1'			=> $show_col1,
			'SHOW_COL0'			=> $show_col0,
			'SHOW_NEWS'			=> $show_col0 || $show_col1 || $show_col2,
			'S_TOPICPREVIEW'		=>  $this->phpbb_extension_manager->is_enabled('vse/topicpreview'),
			'TOPICPREVIEW_DELAY'	=> (isset($this->config['topic_preview_delay'])) ? $this->config['topic_preview_delay'] : 1000,
			'TOPICPREVIEW_DRIFT'	=> (isset($this->config['topic_preview_drift'])) ? $this->config['topic_preview_drift'] : 15,
			'TOPICPREVIEW_WIDTH'	=> (!empty($this->config['topic_preview_width'])) ? $this->config['topic_preview_width'] : 360,
			'TOPICPREVIEW_THEME'	=> (!empty($this->user->style['topic_preview_theme'])) ? $this->user->style['topic_preview_theme'] : 'light',
		)); 
		return $this->controller_helper->render('lasttopicsajax_body.html');
	}
	public function last_col_pagination()
	{
		$this->user->add_lang_ext('alg/lasttopicsajax', 'lasttopicsajax');
		$rootref = &$this->template_context->get_root_ref();
		$dataref = &$this->template_context->get_data_ref();
		$col_num = $this->request->variable('col', 0);
		$start = $this->request->variable('start', 0);
		$old_page = $this->request->variable('old_page', 0);
		$rowset = $this->get_latest_topics_by_column($col_num, $start);
		$old_start = ($old_page - 1) * (int) $rootref['LT_' . $col_num . '_PER_PAGE'];
		$u_old_page = $rootref['LT_' . $col_num . '_BASE_URL'];
		if ($old_start)
		{
			$u_old_page .= '?start=' . $old_start;
		}
		$pagination_block = $this->build_pagination($col_num, $rootref, $dataref);
		$topics_block = $this->build_topics($col_num, $rowset);
		$data = array(
			'col_num'=> $col_num,
			'start'=> $start,
			'rowset'=> $rowset,
			'rootref'=> $rootref,
			'dataref'=> $dataref,
			'arr_paging'=> $dataref['lt_' .  $col_num],
			'pagination_block'=> $pagination_block,
			'topics_block'=> $topics_block,
			'U_OLD_PAGE'=> $u_old_page,
			'U_CURRENT_PAGE'=> '<span>' .  $rootref['LT_' . $col_num . '_CURRENT_PAGE'] . '</span>',
			'U_PREV_PAGE'=> $rootref['U_LT_' . $col_num . '_PREVIOUS_PAGE'],
			'U_NEXT_PAGE'=> $rootref['U_LT_' . $col_num . '_NEXT_PAGE'] ,
			'BASE_URL'=> $rootref['LT_' . $col_num . '_BASE_URL'] ,
		);
		$json_response = new \phpbb\json_response;
		$json_response->send($data);
	}
	public function last_col_markread()
	{
		$this->user->add_lang_ext('alg/lasttopicsajax', 'lasttopicsajax');
		$rootref = &$this->template_context->get_root_ref();
		$col_num = $this->request->variable('col', 0);
		$forums_ary = $this->get_forums_by_column($col_num);
		markread('topics', $forums_ary);
		$curr_page = $this->request->variable('curr_page', 0);
		$per_page =  $this->config['lasttopicsajax_rows_amount'];
		$start = ($curr_page - 1) * (int) $per_page;
		$rowset = $this->get_latest_topics_by_column($col_num, $start);
		$data = array(
			'col_num'=> $col_num,
			'start'=> $start,
			'rowset'=> $rowset,
			'rootref'=> $rootref,
			'forums_ary'=> $forums_ary,
			'NEWEST_POST_IMG'	=> $this->user->img('icon_topic_newest', 'VIEW_NEWEST_POST'),
			'LATEST_POST_IMG'	=> $this->user->img('icon_topic_latest', 'VIEW_LATEST_POST'),
		);
		$json_response = new \phpbb\json_response;
		$json_response->send($data);
	}
	public function last_topic_markread()
	{
		$this->user->add_lang_ext('alg/lasttopicsajax', 'lasttopicsajax');
		$forum_id = $this->request->variable('forum_id', 0);
		$topic_id = $this->request->variable('topic_id', 0);
		$sql = "SELECT topic_last_post_id FROM " . TOPICS_TABLE . " WHERE topic_id=" . $topic_id;
		$result = $this->db->sql_query($sql);
		$topic_last_post_id = (int) $this->db->sql_fetchfield('topic_last_post_id');
		$this->db->sql_freeresult($result);
		$last_post = append_sid("{$this->phpbb_root_path}viewtopic.$this->php_ext", 'f=' . $forum_id . '&amp;t=' . $topic_id . '&amp;p=' . $topic_last_post_id)  . '#p' . $topic_last_post_id;
		$last_post = str_replace('../', '', $last_post);
		markread('topic', $forum_id, $topic_id);
		$data = array(
			'FORUM_ID'=> $forum_id,
			'TOPIC_ID'=> $topic_id,
			'U_LAST_POST'=> $last_post,
			'LATEST_POST_IMG'	=> $this->user->img('icon_topic_latest', 'VIEW_LATEST_POST'),
		);
		$json_response = new \phpbb\json_response;
		$json_response->send($data);
	}
	private function build_pagination($col_num, $rootref, $dataref)
	{
		$pagination_arr = $dataref['lt_' .  $col_num];
		$base_url = $rootref['LT_' . $col_num . '_BASE_URL'];
		$total_pages = $rootref['LT_' . $col_num . '_TOTAL_PAGES'];
		$str = '<ul>';
		if ($base_url && $total_pages > 6)
		{
			$str .= '<li class="dropdown-container dropdown-button-control dropdown-page-jump page-jump">';
			$str .= '<a class="button button-icon-only dropdown-trigger  dropdown-toggle" href="#" title="' . $this->user->lang['JUMP_TO_PAGE_CLICK'] . '" role="button"><i class="icon fa-level-down fa-rotate-270" aria-hidden="true"></i><span class="sr-only">' .  $rootref['LT_' . $col_num . '_PAGE_NUMBER'] . '</span></a>';
			$str .= '<div class="dropdown hidden">';
			$str .= '<div class="pointer"><div class="pointer-inner"></div></div>';
			$str .= '<ul class="dropdown-contents">';
			$str .= '<li>' . $this->user->lang['JUMP_TO_PAGE'] .  $this->user->lang['COLON'] . '</li>';
			$str .= '<li class="page-jump-form">';
			$str .= '<input type="number" name="page-number" min="1" max="999999" title="' . $this->user->lang['JUMP_PAGE'] .  '" class="inputbox tiny" data-per-page="'.  $rootref['LT_' . $col_num . '_PER_PAGE'] . '" data-base-url="' .  $rootref['LT_' . $col_num . '_BASE_URL']  . '" data-start-name="' .  $rootref['LT_' . $col_num . '_START_NAME'] . '" />';
//			$str .= '<input class="button2" value="' . $this->user->lang['JUMP_TO_PAGE'] . '" type="button" />';
			$str .= '<a data-col="' . $col_num . '" data-total-pages="' . $total_pages .  '" class="button2 lt-pagination-go" href="' . $base_url . '" title="' . $this->user->lang['GO'] .  '" role="button">' . $this->user->lang['GO'] . '</a>';
//			<a data-col="2" data-total-pages="{{ LT_2_TOTAL_PAGES }}" class="button2 lt-pagination-go" href="{{ LT_2_BASE_URL }}" title="{{ lang('GO') }}" role="button">{{ lang('GO') }}</a>
			$str .= '</li>';
			$str .= '</ul>';
			$str .= '</div>';
			$str .= '</li>';
		}
		foreach ($pagination_arr as $item)
		{
		if($item['S_IS_PREV'])
		{
			$str .= 	'<li  data-col-prev="' . $col_num .'" class="arrow previous"><a class="button button-icon-only" href="' . $item['PAGE_URL'] .'" rel="prev" role="button"><i class="icon fa-chevron-left fa-fw" aria-hidden="true"></i><span class="sr-only">{L_PREVIOUS}</span></a></li>';
		}
		else if($item['S_IS_CURRENT'])
			{
			 $str .= '<li data-col-pagenum="' . $col_num .'" class="active"><span>' . $item['PAGE_NUMBER'] . '</span></li>';
			}
			else if($item['S_IS_ELLIPSIS'])
				{
				 $str .= '<li class="ellipsis" role="separator"><span>' . $this->user->lang['ELLIPSIS'] . '</span></li>';
				}
				else if($item['S_IS_NEXT'])
					{
						$str .= 	'<li  data-col-next="' . $col_num .'" class="arrow next"><a class="button button-icon-only" href="' . $item['PAGE_URL'] .'" rel="next" role="button"><i class="icon fa-chevron-right fa-fw" aria-hidden="true"></i><span class="sr-only">{L_NEXT}</span></a></li>';
					}
					else
					{
						$str .= 	'<li><a class="button" href="' . $item['PAGE_URL'] .'" role="button">' . $item['PAGE_NUMBER'] .'</a></li>';
					}
		}
		$str .= 	'</ul>';
		return $str;
	}
	private function build_topics($col_num, $rowset)
	{
		$str = htmlentities('<ul style="list-style:none;">');
		foreach ($rowset as $item)
		{
			$str .= 	htmlentities('<li>');
			if ($item['S_UNREAD_TOPIC'])
			{
				$str .= 	htmlentities('<span data-topic_id="' . $item['TOPIC_ID'] . '" data-forum_id="' . $item['FORUM_ID'] . '" class="lt-marktopicread" title="' . $this->user->lang['MARK_READ'] . '"><i class="fa fa-check-square-o" aria-hidden="true"></i></span>');
				$str .= 	htmlentities('<a data-lt1_column="' + $col_num . '" class="unread" href="' . $item['U_NEWEST_POST'] . '">');
				$str .= 	htmlentities('<i class="icon fa-file fa-fw icon-red icon-md" aria-hidden="true"></i>');
				$str .= 	htmlentities('</a>');
				$str .= 	htmlentities('<a data-lt2_column="' . $col_num . '" class="topictitle" href="' . $item['U_NEWEST_POST'] . '">' . $item['TITLE'] . '</a>');
			}
			else
			{
				$str .= 	htmlentities('<span  style="visibility:hidden;"   data-topic_id="'.  $item['TOPIC_ID'] .  '" data-forum_id="' . $item['FORUM_ID'] . '" class="lt-marktopicread" title="' . $this->user->lang['MARK_READ'] .  '"><i class="fa fa-check-square-o" aria-hidden="true"></i></span>');
				$str .= 	htmlentities('<a data-lt1_column="' . $col_num . '"href="' . $item['U_LAST_POST'] . '">');
				$str .= 	htmlentities('<i class="icon fa-file fa-fw icon-lightgray icon-md" aria-hidden="true"></i>');
				$str .= 	htmlentities('</a>');
				$str .= 	htmlentities('<a data-lt2_column="' . $col_num . '" class="topictitle" href="' . $item['U_LAST_POST'] . '">' . $item['TITLE'] . '</a>');
			}
			$is_topicpreview = $this->phpbb_extension_manager->is_enabled('vse/topicpreview');
			//append for preview
			 if($is_topicpreview)
			{
				$str .= 	htmlentities('<div class="topic_preview_content" style="display:none;">');
				$str .= 	htmlentities('<strong>' . $this->user->lang['FIRST_POST']  . '<hr>');
				$str .= 	htmlentities('<div class="topic_preview_avatar">' . $item['TOPIC_PREVIEW_FIRST_AVATAR'] . '</div>');
				$str .= 	htmlentities('<div class="topic_preview_first">' . $item['TOPIC_PREVIEW_FIRST_POST'] .'</div>');
				$str .= 	htmlentities('<div class="topic_preview_break"></div>');
				$str .= 	htmlentities('<strong>' . $this->user->lang['LAST_POST'] . '</strong><hr>');
				$str .= 	htmlentities('<div class="topic_preview_avatar">' . $item['TOPIC_PREVIEW_LAST_AVATAR'] .'</div>');
				$str .= 	htmlentities('<div class="topic_preview_last">' . $item['TOPIC_PREVIEW_LAST_POST'] .'</div>');
				$str .= 	htmlentities('</div>');
			}
			$str .= 	htmlentities('</li>');
		}
		$str .= 	htmlentities('</ul>');
		return $str;
	}

	public function get_router_path($action)
	{
		$action_path = 'alg_lasttopicsajax_controller_' . $action;
		return $this->controller_helper->route($action_path);
	}
	private function character_limit(&$title, $limit = 0)
	{
	   $title = censor_text($title);
	   if ($limit > 0)
	   {
		  return (utf8_strlen($title) > $limit + 3) ? truncate_string($title, $limit) . '...' : $title;
	   }
	   else
	   {
		  return $title;
	   }
	}

	private function get_all_remaining_forums($ex_fid_ary)
	{
		$lasttopicsajax_ids_column2 = $this->config['lasttopicsajax_ids_column2'];
		$lasttopicsajax_ids_column1 = $this->config['lasttopicsajax_ids_column1'];
		$col_forums2 = array();
		$col_forums1 = array();
		$col_forums_exclude = array();
		$col_forums_include = array();
		if ($lasttopicsajax_ids_column2)
		{
			$col_forums2 = explode(',', $lasttopicsajax_ids_column2);
		}
		if ($lasttopicsajax_ids_column1)
		{
			$col_forums1 = explode(',', $lasttopicsajax_ids_column1);
		}
		$col_forums_exclude = array_merge($ex_fid_ary, $col_forums2, $col_forums1);
		$col_forums_include = array();
		$col_forums_include = array_unique(array_keys($this->auth->acl_getf('f_read', true)));
		if (sizeof($col_forums_include))
		{
			$col_forums_include = array_diff($col_forums_include, $col_forums_exclude, $col_forums1, $col_forums2);
			$col_forums_include = array_unique($col_forums_include);
		}
		return $col_forums_include;
	}

	protected function trim_topic_preview($text)
	{
		$text = $this->remove_markup($text);
		if (utf8_strlen($text) <= $this->config['topic_preview_limit'])
		{
			return $this->tp_nl2br($text);
		}
		// trim the text to the last whitespace character before the cut-off
		$text = preg_replace('/\s+?(\S+)?$/', '', utf8_substr($text, 0, $this->config['topic_preview_limit']));
		return $this->tp_nl2br($text) . '...';
	}
}
