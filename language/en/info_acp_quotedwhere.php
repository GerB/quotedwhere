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
    'QW_ACP_MODULE_TITLE'               => 'Quote index',
    'QW_ACP_MODULE_EXPlAIN'             => 'With the <i>Find and anonymize quotes</i> extension you can check where a user was quoted and replace occurences. For this to work, you need to create an index using the form below.',
    'QW_ANONYMIZE'                      => 'Replace quoted and poster name',
    'QW_ANONYMIZE_EXPLAIN'              => 'Replace references to this username in quotes messages with given replacement and set poster name of own messages to Guest. Leave empty to keep references intact.',
    'QW_INDEX_COUNT'                    => 'Number of indexed quotes',
    'QW_INDEX_CREATE'                   => 'Create new index',
    'QW_INDEX_DONE'                     => 'The indexing is finished',
    'QW_REPARSE_EXPLAIN'                => 'It seems that your board was converted from another version than phpBB 3.2. phpBB 3.2 uses a new way to format messages. This conversion is done in small steps and you need to be sure that all messages have been reparsed. <a target="_blank" href="https://www.phpbb.com/support/docs/en/3.2/kb/article/phpbb-32%2B-text-reparser/">Read more</a>.<br><br>There are approximately <strong>%d</strong> posts on your board that need to be reparsed in order to be indexed properly. It is recommended to reparse first before creating an index.',
    'QW_SEARCH_INDEX_CREATE_REDIRECT'   => 'Approximately %d messages processed, do not leave this page...',
		));    