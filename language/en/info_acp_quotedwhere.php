<?php

/**
 *
 * Quoted Where. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018, Ger, https://github.com/GerB
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}
$lang = array_merge($lang, array(
    'QW_ACP_MODULE_TITLE'     => 'Quoted Where',
    'QW_ACP_MODULE_EXPlAIN'   => 'With the Quoted Where extensie you can check where a user was quoted. For this to work, you need to create an index using the form below. <br> <strong>Note:</strong> If your board was converted or updatet to phpBB 3.2 your must assure yourself that all posts were reparsed. <a target="_blank" href="https://www.phpbb.com/community/viewtopic.php?p=14670841#p14670846">Read more</a>.',
    'QW_INDEX_COUNT'          => 'Number of indexed quotes',
    'QW_INDEX_CREATE'         => 'Create new index',
		));     
