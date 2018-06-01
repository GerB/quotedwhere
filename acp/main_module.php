<?php
/**
 *
 * Quoted Where. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018, Ger, https://github.com/GerB
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace ger\quotedwhere\acp;

/**
 * Feed post bot ACP module.
 */
class main_module
{
	public $u_action;

	public function main($id, $mode)
	{
            global $request, $template, $user, $phpbb_container, $config;
            $handler = $phpbb_container->get('ger.quotedwhere.classes.handler');

            $this->tpl_name     = 'acp_quotedwhere_body';
            $this->page_title	= $user->lang('QW_ACP_MODULE_TITLE');
            add_form_key('ger/quotedwhere');
            $action = $request->variable('action', '');
            $submit = $request->is_set_post('submit', false);

            
            if ($action == 'create')
            {
                if (!check_link_hash($request->variable('hash', ''), 'ger_acp_quoted_where'))
                {
                    trigger_error($user->lang['FORM_INVALID'] . adm_back_link($this->u_action), E_USER_WARNING);
                }
                $start = $request->variable('qwist', 0);
                $done = $handler->create_index($start); 
                if ($done === true)
                {
                    trigger_error($user->lang('QW_INDEX_DONE') . adm_back_link($this->u_action));
                }
                
                meta_refresh(1, append_sid($this->u_action . '&amp;action=create&amp;qwist=' . ($done + 1) . '&amp;hash=' . generate_link_hash('ger_acp_quoted_where')));
                trigger_error($user->lang('SEARCH_INDEX_CREATE_REDIRECT', (int) $done));
            }
            
            // Show form
            $template->assign_vars(array(
                'U_ACTION'          => $this->u_action . '&amp;hash=' . generate_link_hash('ger_acp_quoted_where'),
                'S_INDEXED_QUOTES'  => $handler->count_index(),
            ));
            
	}

}