<?php
/**
 *
 * @package lasttopicsajax
 * @copyright (c) 2014 alg 
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace alg\lasttopicsajax\event;

/**
* Event listener
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\auth\auth */
	protected $auth;
    
    /** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request_interface */
	protected $request;

    /** @var string phpbb_root_path */
	protected $phpbb_root_path;

	/** @var string phpEx */
	protected $php_ext;
    
	/** @var \phpbb\controller\helper */
	protected $controller_helper;


    /** @var \phpbb\template\context */
	protected $template_context;

	/** @var \phpbb\extension\manager */
	protected $phpbb_extension_manager;

	/** @var array */
	protected $return_error = array();

	/**
	* Constructor
	* @param \phpbb\config\config				        $config				Config object
	* @param \phpbb\db\driver\driver_interface	$db					DBAL object
	* @param \phpbb\auth\auth					        $auth				Auth object
	* @param \phpbb\template\template             $template          Template object
	* @param \phpbb\user						            $user				User object
	* @param \phpbb\request\request_interface   $request            Request object
	* @param string								            $phpbb_root_path	phpbb_root_path
	* @param string								            $php_ext			phpEx
	* @param \phpbb\controller\helper			        $controller_helper	Controller helper object
	 * @param \phpbb\template\context               $template_context   Template object
	 * @param \phpbb\extension\manager            $phpbb_extension_manager
	* @param array								                $return_error		array

	* @access public
	*/

	public function __construct(\phpbb\config\config $config, \phpbb\request\request_interface $request, \phpbb\template\template $template, \phpbb\user $user, \phpbb\extension\manager $phpbb_extension_manager, $lasttopicsajax_handler)
	{
		$this->config = $config;
		$this->request = $request;
		$this->template = $template;
		$this->user = $user;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->lasttopicsajax_handler = $lasttopicsajax_handler;
	}

	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'           => 'user_setup',
			'core.index_modify_page_title'           => 'index_modify_page_title',
            'core.page_header_after'			=> 'page_header_after',
            //'core.ucp_prefs_view_data'			=> 'ucp_prefs_view_data',
            //'core.ucp_prefs_view_update_data'	=> 'ucp_prefs_view_update_data',
		);
	}
	public function user_setup($event) {
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'alg/lasttopicsajax',
			'lang_set' => 'info_acp_lasttopicsajax',
		);
		$event['lang_set_ext'] = $lang_set_ext;
		$this->template->assign_vars(array(
			'S_USER_SHOW_NEWS_ON_INDEX'	=> $this->user->data['lt_show_on_index'],
		));
    }
    
    public function index_modify_page_title($event)
    {
        $rowset = array();
        $show_col2 =  $show_col1 = $show_col0 = false;
        $show_news = true;
        $show_news_on_index = (bool) $this->config['lasttopicsajax_show_on_index'];
        //$set_width_captions = (int) $this->config['lasttopicsajax_set_width_captions'];
      
        if ($this->user->data['user_id'] == ANONYMOUS && !$this->config['lasttopicsajax_show_on_index_for_guests'])
        {
            $show_news_on_index = false;
        }
        if ($show_news_on_index && isset($this->user->data['lt_show_on_index']))
        {
            $show_news_on_index = (bool) $this->user->data['lt_show_on_index'];
        }
        if ($show_news_on_index)
        {
            $this->user->add_lang_ext('alg/lasttopicsajax', 'lasttopicsajax');
            $this->user->add_lang_ext('alg/lasttopicsajax', 'info_acp_lasttopicsajax');
            $start = 0;
            $rowset = $this->lasttopicsajax_handler->get_latest_topics_by_column(2, $start);
            if ($rowset)
            {
                $show_col2 = (bool) sizeof( $rowset) >0;
            }
            $rowset = $this->lasttopicsajax_handler->get_latest_topics_by_column(1, $start);
            if ($rowset)
            {            
                $show_col1 = (bool) sizeof( $rowset) >0;
            }
            $rowset = $this->lasttopicsajax_handler->get_latest_topics_by_column(0, $start);
            if ($rowset)
            {            
                $show_col0 = (bool) sizeof( $rowset) >0;
            }
            $show_news = $show_col0 || $show_col1 || $show_col2;
        }

        $show_news_on_index = $show_news && $show_news_on_index;
        $this->template->assign_vars(array(
			'U_LASTTOPICSAJAX_PATH_POPUP'				=> str_replace('../', '', $this->lasttopicsajax_handler->get_router_path('popup')),
			'NEWEST_POST_IMG'	=> $this->user->img('icon_topic_newest', 'VIEW_NEWEST_POST'),
			'LATEST_POST_IMG'	=> $this->user->img('icon_topic_latest', 'VIEW_LATEST_POST'),
			'L_COL2'	=> $this->config['lasttopicsajax_title_column2'],
			'L_COL1'	=> $this->config['lasttopicsajax_title_column1'],
			'L_COL0'	=> $this->config['lasttopicsajax_title_column0'],
			'SHOW_COL2'	=> $show_col2,
			'SHOW_COL1'	=> $show_col1,
			'SHOW_COL0'	=> $show_col0,
			'S_SHOW_NEWS'	=> $show_news,
			'S_SHOW_NEWS_ON_INDEX'	=> $show_news_on_index,
		));        
    }
    public function page_header_after($event)
    {
        $this->user->add_lang_ext('alg/lasttopicsajax', 'lasttopicsajax');
        $this->user->add_lang_ext('alg/lasttopicsajax', 'info_acp_lasttopicsajax');
        
        $this->template->assign_vars(array(
			'U_LASTTOPICSAJAX_PATH_POPUP'				=> $this->lasttopicsajax_handler->get_router_path('popup'),
			'U_LASTTOPICSAJAX_PATH_MARKREAD'			=> $this->lasttopicsajax_handler->get_router_path('markread'),
 			'U_LASTTOPICSAJAX_PATH_TOPICMARKREAD'			=> $this->lasttopicsajax_handler->get_router_path('topicmarkread'),
 			'U_LASTTOPICSAJAX_PATH_PAGINATION'			=> $this->lasttopicsajax_handler->get_router_path('pagination'),
 			'S_ROWS_AMOUNT'			=> isset($this->config['lasttopicsajax_rows_amount'] ) ? (int) $this->config['lasttopicsajax_rows_amount'] : 5,
			'S_TOPICPREVIEW'	=>  (bool) $this->phpbb_extension_manager->is_enabled('vse/topicpreview'),
		));        
    }
	
	public function ucp_prefs_view_data($event) {
		$data = $event['data'];
		$data = array_merge($data, array(
			'lt_show_on_index'		=> $this->request->variable('lt_show_on_index', (bool) (isset($this->user->data['lt_show_on_index']) ? $this->user->data['lt_show_on_index'] : false))
		));
		$event['data'] = $data;
	}
	
	public function ucp_prefs_view_update_data($event) {
		$data = $event['data'];
		$sql_ary = $event['sql_ary'];
		if (isset($this->user->data['lt_show_on_index']))
		{
			$sql_ary = array_merge($sql_ary, array(
				'lt_show_on_index'	=> ($data['lt_show_on_index']) ? 1 : 0,
			));
		}
		$event['sql_ary'] = $sql_ary;
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
}
