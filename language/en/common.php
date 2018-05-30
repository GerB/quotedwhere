<?php

/**
 *
 * Magic OGP parser. An extension for the phpBB Forum Software package.
 * 
 *
 * @copyright (c) 2017, Ger, https://github.com/GerB
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
    'QW_MISSING_REQUIREMENTS'      => 'The extension requires DOMDocument and DOMXPath to read the contents of quotes. At least one of these is not available on your server and therefore the extension cannot be installed.',
    'QW_SEARCH_ME'                 => 'Your quoted posts',
    'QW_SEARCH_USER'               => 'Search quotes from user',
		));    