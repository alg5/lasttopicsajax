<?php
/** 
*
* lasttopics [Russian]
*
* @package lasttopics
* @copyright (c) 2014 alg
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'ACP_LAST_TOPICS_AJAX'						=> 'Последние темы из каждой страницы',
	'ACP_LAST_TOPICS_AJAX_EXPLAIN'				=> 'Вывод последних тем форума сверху на главной странице или из любой страницы в плавающем окне',
	'ACP_LAST_TOPICS_LINK'						=> 'Последние темы',
	'ACP_LAST_TOPICS_AJAX_SETTINGS'				=> 'Настройки',
	'ACP_LAST_TOPICS_SETTINGS_COMMON'				=> 'Общие настройки',
	'ACP_LAST_TOPICS_SETTINGS_COLUMNS'				=> 'Настройки колонок',
	'ACP_LAST_TOPICS_ROWS_NUMBER'				=> 'Максимальное число выводимых строк',
	'ACP_LAST_TOPICS_COLUMNS_NUMBER'				=> 'Число выводимых колонок',
	'ACP_LAST_TOPICS_COLUMNS_NUMBER_EXPLAIN'				=> 'Последние темы могут быть размещены в нескольких колонках, <br /> максимальное число колонок - 3',
	'ACP_LAST_TOPICS_LEFT_COLUMN'				=> 'Левая колонка',
	'ACP_LAST_TOPICS_LEFT_COLUMN_EXPLAIN'				=> 'Темы из этих форумов будут показываться в левой колонке<br /> Релевантно при выводе тем в 3 колонки',
	'ACP_LAST_TOPICS_MIDDLE_COLUMN'				=> 'Средняя колонка ',
	'ACP_LAST_TOPICS_MIDDLE_COLUMN_EXPLAIN'				=> 'Темы из этих форумов будут показываться в средней колонке<br /> Релевантно при выводе тем в 3 или в 2 колонки',
	'ACP_LAST_TOPICS_RIGHT_COLUMN'				=> 'Правая колонка ',
	'ACP_LAST_TOPICS_RIGHT_COLUMN_EXPLAIN'				=> 'Темы из этих форумов будут показываться в правой колонке<br /> Введите "*" для отображения тем всех остальных форумов или номера форумов',
	'ACP_LAST_TOPICS_EXCLUDE'				=> 'ID форумов, исключенных из показа',
	'ACP_LAST_TOPICS_EXCLUDE_EXPLAIN'				=> 'Темы из этих форумов не будут показываться в блоке новых тем<br /> Номера должны разделяться запятыми.',
	'ACP_LAST_TOPICS_IDFORUMS'				=> 'ID форумов',
	'ACP_LAST_TOPICS_IDFORUMS_EXPLAIN'				=> 'ID форумов должны разделяться запятыми',
	'ACP_LAST_TOPICS_TITLE'				=> 'Заголовок колонки',
	'ACP_LASTTOPICSAJAX_SAVED'				=> 'Настройки сохранены успешно',
	'ACP_LASTTOPICSAJAX_SHOW_ON_INDEX'				=> 'Показывать на главной странице',
	'ACP_LASTTOPICSAJAX_SHOW_ON_INDEX_EXPLAIN'				=> 'Если опция выбрана, блок с последними темами будет показываться вверху главной страницы форума',
	'ACP_LASTTOPICSAJAX_SHOW_ON_INDEX_FOR_GUESTS'				=> 'Показывать на главной странице для гостей',
	'ACP_LASTTOPICSAJAX_SHOW_ON_INDEX_FOR_GUESTS_EXPLAIN'				=> 'Релевантно при выборе опции "Показывать на главной странице"',
            'ACP_LASTTOPICSAJAX_SET_WIDTH_CAPTIONS'				=> 'Ограничить число символов в заголовке',
	'ACP_LASTTOPICSAJAX_SET_WIDTH_CAPTIONS_EXPLAIN'				=> '0 - длина заголовка не ограничивается',	
            
            'UCP_LASTTOPICSAJAX_SHOW_ON_INDEX'						=> 'Показывать последние темы на главной странице',
));
