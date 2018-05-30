<?php

/**
 *
 * Quoted Where. An extension for the phpBB Forum Software package.
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
    'QW_ACP_MODULE_TITLE'     => 'Quoted Where',
    'QW_ACP_MODULE_EXPlAIN'   => 'Met de Quoted Where extensie kun je zien in welke berichten een gebruiker geciteerd is. Om deze functie te laten werken moet eerst een index opgebouwd worden via het formulier hieronder. <br> <strong>Let op:</strong> Indien je forum geconverteerd is van een eerdere versie dan phpBB 3.2 moet je er zeker van zijn dat al je berichten opnieuw geparsed zijn. <a target="_blank" href="https://www.phpbb.com/community/viewtopic.php?p=14670841#p14670846">Lees meer</a>.',
    'QW_INDEX_COUNT'          => 'Aantal geïndexeerde citaten',
    'QW_INDEX_CREATE'         => 'Nieuwe index aanmaken',
    ));    