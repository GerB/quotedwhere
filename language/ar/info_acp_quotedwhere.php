<?php

/**
 *
 * Quoted Where. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018, Ger, https://github.com/GerB
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
    'QW_ACP_MODULE_TITLE'               => 'فهرسة الاقتباس',
    'QW_ACP_MODULE_EXPlAIN'             => 'مع الإضافة <i>“العثور على الإقتباسات”</i> تستطيع التحقق من أين أقتبس العضو مشاركاتك مع امكانية استبداله. لكي يعمل هذا بشكل صحيح, يجب عليك إنشاء الفهرسة بإستخدام النموذج أدناه.',
    'QW_ANONYMIZE'                      => 'استبدال إسم العضو',
    'QW_ANONYMIZE_EXPLAIN'              => 'سوف يتم استبدال الإسم في الاقتباسات فقط بالإسم الذي تحدده هنا بينما سيتم استبدال اسم العضو في المشاركات الخاصة به بإسم “زائر”. اتركه فارغاً لتعطيل هذا الخيار.',
    'QW_INDEX_COUNT'                    => 'عدد الإقتباسات التي تم فهرستها',
    'QW_INDEX_CREATE'                   => 'إنشاء فهرسة جديدة',
    'QW_INDEX_DONE'                     => 'تمت عملية الفهرسة بنجاح',
    'QW_REPARSE_EXPLAIN'                => 'يبدوا أن منتداك تم تحويله من نسخة أخرى غير النسخة phpBB 3.2. فالنسخة phpBB 3.2 تستخدم طريقة جديدة لتنسيق المشاركات. وعملية التحويل هذه تمت بخطوات قليلة وبالتالي أنت بحاجة إلى التأكد من أنه تم إعادة تحليل جميع الرسائل. <a target="_blank" href="https://www.phpbb.com/support/docs/en/3.2/kb/article/phpbb-32%2B-text-reparser/">اقرأ المزيد</a>.<br><br>يوجد حوالي <strong>%d</strong> مشاركات في منتداك بحاجة إلى أن يتم إعادة تحليلها من أجل فهرستها بشكل دقيق. ننصح بإعادة تحليل المشاركات أولاً قبل إنشاء الفهرسة.',
    'QW_SEARCH_INDEX_CREATE_REDIRECT'   => 'تم معالجة حوالي %d رسائل, لا تترك هذه الصفحة...',
		));
