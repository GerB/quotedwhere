<?php

/**
 *
 * Magic OGP parser. An extension for the phpBB Forum Software package.
 * [Dutch]
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
    'QW_MISSING_REQUIREMENTS'       => 'De extensie DOMDocument en DOMXPath nodig om de inhoud van citaten uit te lezen. Minstens een onderdeel mist, waardoor de extensie niet geïnstalleerd kan worden.',
    'QW_SEARCH_ME'                  => 'Je geciteerde berichten',
    'QW_SEARCH_USER'                => 'Zoek citaten van gebruiker',
		));    