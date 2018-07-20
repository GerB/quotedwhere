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
    'QW_ACP_MODULE_TITLE'               => 'Quote index',
    'QW_ACP_MODULE_EXPlAIN'             => 'Met de <i>Find and anonymize quotes</i> extensie kun je zien in welke berichten een gebruiker geciteerd is en dit automatisch aanpassen. Om deze functie te laten werken moet eerst een index opgebouwd worden via het formulier hieronder.',
    'QW_ANONYMIZE'                      => 'Vervang naam in citaten en bij berichten',
    'QW_ANONYMIZE_EXPLAIN'              => 'Vervang referenties naar deze gebruikersnaam in kopteksten van citaten met opgegeven tekst en vervang naam bij eigen berichten door Gast. Laat leeg om referenties intact te laten.',
    'QW_INDEX_COUNT'                    => 'Aantal geïndexeerde citaten',
    'QW_INDEX_CREATE'                   => 'Nieuwe index aanmaken',
    'QW_INDEX_DONE'                     => 'Indexeren gereed',
    'QW_REPARSE_EXPLAIN'                => 'Het lijkt erop dat je forum geconverteerd is van een eerdere versie dan phpBB 3.2. phpBB 3.2 maakt gebruik van een nieuwe manier om berichten op te slaan. Deze conversie vind in stappen plaats en je moet er zeker van zijn dat al je berichten opnieuw geparsed zijn. <a target="_blank" href="https://www.phpbb.com/support/docs/en/3.2/kb/article/phpbb-32%2B-text-reparser/">Lees meer</a>.<br><br>Er zijn ongeveer <strong>%d</strong> berichten op je forum die nog geconverteerd moeten worden om op de juiste manier geïndexeerd te kunnen worden. Het is beter om eerst te reparsen en daarna een goede index op te bouwen.',
    'QW_SEARCH_INDEX_CREATE_REDIRECT'   => 'Ongeveer %d berichten verwerkt, verlaat deze pagina niet...',
    ));    