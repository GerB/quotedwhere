<?php

/**
 *
 * Quoted Where
 *
 * @copyright (c) 2018 Ger Bruinsma
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace ger\quotedwhere\migrations;

use phpbb\db\migration\container_aware_migration;

class v1 extends container_aware_migration
{

	static public function depends_on()
	{
        return array('\phpbb\db\migration\data\v320\v320');
	}

	public function update_schema()
	{
		return array(
			'add_tables' => array(
				$this->table_prefix . 'user_quoted'	 => array(
					'COLUMNS'		 => array(
						'user_id'	 => array('UINT:10', 0),
						'post_id'	 => array('UINT:10', 0),
					),
					'KEYS'			 => array(
						'user_post'  => array('UNIQUE', array('user_id', 'post_id')),
						'user_id'  => array('INDEX', 'user_id'),
						'post_id'  => array('INDEX', 'post_id'),
					),
				),
			),
		);
	}
	public function update_data()
	{
		return array(
			array('module.add', array(
					'acp',
					'ACP_CAT_DATABASE',
					array(
						'module_basename'	 => '\ger\quotedwhere\acp\main_module',
						'modes'				 => array('index'), // Should correspond to ./acp/main_info.php modes
					),
				)),
            array('config.add', array('quotedwhere_cron_last_run', 0)),
			
		);
	}
    
    
	public function revert_schema()
	{
		return array(
			'drop_tables' => array(
				$this->table_prefix . 'user_quoted',
			),
		);
	}
}
