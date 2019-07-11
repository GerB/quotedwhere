<?php

/**
 *
 * Magic OGP parser. An extension for the phpBB Forum Software package.
 * 
 *
 * @copyright (c) 2017, Ger, https://github.com/GerB
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 * Translated By : Bassel Taha Alhitary <http://alhitary.net>
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
    'QW_MISSING_REQUIREMENTS'      => 'الإضافة تتطلب وجود الـ DOMDocument و DOMXPath من أجل قراءة محتوى الإقتباسات. واحد منهما على الأقل غير موجود في الخادم لديك وبالتالي لا يمكن تنصيب الإضافة.',
    'QW_SEARCH_ME'                 => 'مُشاركاتك المقتبسة',
    'QW_SEARCH_USER'               => 'البحث عن مشاركاته المقتبسة',
		));
