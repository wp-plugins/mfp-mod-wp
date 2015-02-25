<?php /*

Plugin Name: MFP mod WP
Description: Plugin MFP mod WP does two main functions:  clean your source code from links, which can to slow down your blog and hides some articles such as version of the engine, links to wordpress.org etc. from the admintool.
Version: 0.3.2
Author: Sergey Voloshin
Author URI: https://varrcan.me/
Plugin URI: https://varrcan.me/
Copyright 2015  Varrcan  (email: admin@xsence.net)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

if(!defined('ABSPATH')) exit;

define('MFP_MOD_WP_DIR', plugin_dir_path(__FILE__));
define('MFP_MOD_WP_URL', plugin_dir_url(__FILE__));
define('MFP_VERSION', '0.3.2');

if(function_exists('load_plugin_textdomain')) load_plugin_textdomain('mfp-languages', PLUGINDIR.'/'.dirname(plugin_basename
		(__FILE__)).'/languages', dirname(plugin_basename(__FILE__)).'/languages');

/** Действия при активации и деактивации плагина */
register_activation_hook(__FILE__, 'mfp_mod_wp_activation');
//register_deactivation_hook(__FILE__, 'mfp_mod_wp_deactivation');

/** Действия при удалении плагина */
register_uninstall_hook( __FILE__, array( 'mainMfp', 'mfp_uninstall' ) );

// Активация плагина
function mfp_mod_wp_activation() {
	if(get_option('mfp_mod_options') == null){
		$mfp_options = array("mfp_mod_option_link" => array(
																									 "rss" => "0",
																									 "wlwmanifest" => "0",
																									 "index_rel" => "0",
																									 "wp_shortlink" => "0",
																									 "wp_generator" => "0",
																									), // Чистка head от мусора
												"mfp_mod_option_comment" => "0", // Удаление комментариев html
												"mfp_mod_option_version" => "0", // Удаление версии
												"mfp_mod_option_wp_help" => "0", // Удаление контекстного меню справки
												"mfp_mod_option_wp_del" => "0", // Удаление лого и ссылок wp в админке
												"mfp_mod_option_wp_logo" => "0", // Свое лого при входе в админку
												"mfp_mod_option_wp_widgets" => array(
																									 "quick_press" => "0",
																									 "activity" => "0",
																									 "right_now" => "0",
																									 "primary" => "0",
																									 "welcome" => "0",
																									), // Удаление виджетов
												"mfp_mod_option_wp_translit" => "0", // Транслит
												"mfp_mod_option_footer_text_opt" => "0", // Текст в футере
												"mfp_mod_option_footer_text" => "Developed by", // Надпись в футере
												"mfp_mod_option_footer_text1" => "https://varrcan.me/", // Ссылка в футере
												"mfp_mod_option_footer_text2" => "Varrcan.ME", // Подпись к ссылке
												"mfp_mod_option_metabox" => "0", // Метабокс
												"mfp_mod_option_metabox_title" => "", // Заголовок Метабокса
												"mfp_mod_option_metabox_text" => "", // Текст Метабокса
												"mfp_mod_option_custom_admin" => "0" // Страница входа
												);
		$mfp_options = serialize($mfp_options);
		// Добавление в БД значения по умолчанию
		add_option("mfp_mod_options", "$mfp_options");
	}
}

include_once 'class/option.class.php';

new mainMfp();

?>