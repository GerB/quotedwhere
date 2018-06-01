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
 * Quoted Where ACP module info.
 */
class main_info
{

    public function module()
    {
        return array(
            'filename' => '\ger\quotedwhere\acp\main_module',
            'title' => 'QW_ACP_MODULE_TITLE',
            'modes' => array(
                'index' => array(
                    'title' => 'QW_ACP_MODULE_TITLE',
                    'auth' => 'ext_ger/quotedwhere && acl_a_search',
                    'cat' => array('ACP_CAT_DATABASE')
                ),
            ),
        );
    }

}
