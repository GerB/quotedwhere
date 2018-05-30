<?php
/**
 *
 * Quoted Where. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018, Ger, https://github.com/GerB
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace ger\quotedwhere\event;

/**
 * @ignore
 */
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * [DOI] BBcode Event listener.
 */
class main_listener implements EventSubscriberInterface
{
    protected $handler;
    protected $request;
    protected $template;
    protected $user;
    protected $phpbb_root_path;
    protected $phpEx;


    static public function getSubscribedEvents()
	{
		return array(
            'core.acp_users_overview_before'        => 'set_template_var_acp',
            'core.page_header_after'                => 'set_template_var',
            'core.user_setup'                       => 'load_language_on_setup',
            'core.submit_post_end'                  => 'read_quote_data',
            'core.delete_user_after'                => 'delete_user_action',
            'core.search_modify_submit_parameters'  => 'enforce_submit',
            'core.search_backend_search_after'      => 'search_quoted',
		);
	}
    
    public function __construct(
        \ger\quotedwhere\classes\handler $handler, 
        \phpbb\request\request_interface $request, 
        \phpbb\template\template $template, 
        \phpbb\user $user, 
        $phpbb_root_path,
        $phpEx) 
    {
        $this->handler = $handler;
        $this->request = $request;
        $this->template = $template;
        $this->user = $user;
        $this->phpbb_root_path = $phpbb_root_path;
        $this->phpEx = $phpEx;
    }
    
    /**
     * Add link to profile in header
     * @param \phpbb\event\data	$event	Event object
     */
    public function set_template_var($event)
    {
        $this->template->assign_var('U_SEARCH_QUOTED', append_sid("{$this->phpbb_root_path}search.$this->phpEx", 'search_quoted=' . $this->user->data['user_id']));
    }
    
    
    /**
     * Add link to acp user overview page
     * @param \phpbb\event\data	$event	Event object
     */
    public function set_template_var_acp($event)
    {
        $this->template->assign_var('U_SEARCH_QUOTED', append_sid("{$this->phpbb_root_path}search.$this->phpEx", 'search_quoted=' . $event['user_row']['user_id']));
    }
    
    /**
	 * Load common language file during user setup
	 *
	 * @param \phpbb\event\data	$event	Event object
	 */
	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name'	 => 'ger/quotedwhere',
			'lang_set'	 => 'common',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}
    
    /**
     * Read posts for quotes
     * @param \phpbb\event\data	$event	Event object
     * @return boolean
     */
    public function read_quote_data($event)
    {
        $post_id = $event['data']['post_id'];
        $post_text = $event['data']['message'];
        
        // Whein in edit mode and nothing changed
        if ($event['data']['message_md5'] == $event['data']['post_checksum']) 
        {
            return true;
        }
        
        // Start with clean sheet 
        $this->handler->cleanup_post($post_id);
        if ($event['mode'] != 'delete') 
        {
            // Get new quoted authors
            $users = $this->handler->get_quote_authors($post_text);            
            if (!empty($users)) 
            {
                $this->handler->add_entries($post_id, $users);
            }
        }
        return true;
    }
    
    /**
     * When a user is deleted, remove from table
     * @param \phpbb\event\data	$event	Event object
     */
    public function delete_user_action($event)
    {
        if (!empty($event['user_ids']))
        {
            foreach ($event['user_ids'] as $user_id)
            {
                $this->handler->cleanup_user($user_id);
            }
        }
        return true;
    }

    /**
     * Searching goes without extra form
     * @param \phpbb\event\data	$event	Event object
     */
    public function enforce_submit($event)
    {
        $user_id = $this->request->variable('search_quoted', 0);
        if ($user_id > 0) 
        {    
            $event['submit'] = true;
        }
    }

    /**
     * This is what we've been waiting for
     * @param \phpbb\event\data	$event	Event object
     */
    public function search_quoted($event)
    {
        $user_id = $this->request->variable('search_quoted', 0);
        if ($user_id > 0) 
        {    
            $id_ary = $this->handler->get_quoted_list($user_id);
            $event['id_ary'] = $id_ary;
            $event['total_match_count'] = count($id_ary);
        }
        return true;
    }
}
