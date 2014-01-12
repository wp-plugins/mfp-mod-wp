<?php /*

Plugin Name: MFP mod WP
Description: Plugin MFP mod WP does two main functions:  clean your source code from links, which can to slow down your blog and hides some articles such as version of the engine, links to wordpress.org etc. from the admintool.
Version: 0.3
Author: Sergey Voloshin
Author URI: http://varrcan.me/
Plugin URI: http://varrcan.me/
Copyright 2013  Varrcan  (email: admin@xsence.net)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

/**
 * Определение пути к папке плагина.
 * require_once(MFP_MOD_WP_DIR.'includes/admin.php');
 */

define('MFP_MOD_WP_DIR', plugin_dir_path(__FILE__));
define('MFP_MOD_WP_URL', plugin_dir_url(__FILE__));

if(function_exists('load_plugin_textdomain')) load_plugin_textdomain('mfp-languages', PLUGINDIR.'/'.dirname(plugin_basename
		(__FILE__)).'/languages', dirname(plugin_basename(__FILE__)).'/languages');
    
/** Добавление стиля */
function mfp_add_style() {
	wp_register_style('mfp-style', plugins_url('/mfp-mod-wp.css', __FILE__));
	wp_enqueue_style('mfp-style');
}
add_action('admin_init', 'mfp_add_style');
add_action('admin_print_styles-', 'mfp_add_style');

/** Действия при активации и деактивации плагина */
register_activation_hook(__FILE__, 'mfp_mod_wp_activation');
register_deactivation_hook(__FILE__, 'mfp_mod_wp_deactivation');

// Активация плагина
function mfp_mod_wp_activation() {
	// Добавление в БД значения по умолчанию
	add_option('mfp_mod_option_link', 'off'); // Чистка head от мусора
	add_option('mfp_mod_option_comment', 'off'); // Удаление комментариев html
	add_option('mfp_mod_option_wp_help', 'off'); // Удаление контекстного меню справки
	add_option('mfp_mod_option_wp_del', 'off'); // Удаление лого и ссылок wp в админке
	add_option('mfp_mod_option_wp_ver', 'off'); // Удаление версии WP из блока "Прямо сейчас"
	add_option('mfp_mod_option_wp_logo', 'off'); // Свое лого при входе в админку
	add_option('mfp_mod_option_wp_widgets', 'off'); // Удаление лишних виджетов в консоли
	add_option('mfp_mod_option_footer_text_opt', 'off'); // Текст в футере
	add_option('mfp_mod_option_footer_text', 'Developed by'); // Надпись в футере
	add_option('mfp_mod_option_footer_text1', 'http://varrcan.me/'); // Ссылка в футере
	add_option('mfp_mod_option_footer_text2', 'Varrcan.ME'); // Подпись к ссылке
  
}

// Деактивация плагина
function mfp_mod_wp_deactivation() {
	// Удаление с БД настроек
	delete_option('mfp_mod_option_link');
	delete_option('mfp_mod_option_comment');
	delete_option('mfp_mod_option_wp_help');
	delete_option('mfp_mod_option_wp_del');
	delete_option('mfp_mod_option_wp_ver');
	delete_option('mfp_mod_option_wp_logo');
	delete_option('mfp_mod_option_wp_widgets');
	delete_option('mfp_mod_option_footer_text_opt');
	delete_option('mfp_mod_option_footer_text');
	delete_option('mfp_mod_option_footer_text1');
	delete_option('mfp_mod_option_footer_text2');
}

/** Добавление пункта и страницы настроек в меню */
function mfp_admin_menu() {
	add_options_page('MFP mod', 'MFP mod', 'edit_pages', basename(__FILE__), 'mfp_options_page');
}
add_action('admin_menu', 'mfp_admin_menu');

/** ------------- start -------------- */
// Мусор в шапке
$link_option = get_option('mfp_mod_option_link');
if($link_option == 'on') {
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	remove_action('wp_head', 'wp_generator');
	function remove_version_data($src) {
		$parts = explode('?ver', $src);
		return $parts[0];
	}
	add_filter('script_loader_src', 'remove_version_data', 15, 1);
	add_filter('style_loader_src', 'remove_version_data', 15, 1);
}

// Удаление комментариев с исходного кода
$comment_option = get_option('mfp_mod_option_comment');
if($comment_option == 'on') {
	function comment($buffer) {
		$buffer = preg_replace('`<!--(.|\s)*?-->`', '', $buffer);
		$buffer = preg_replace('`\n`', ' ', $buffer);
		return $buffer;
	}
	function buffer_start() {
		ob_start("comment");
	}
	function buffer_end() {
		ob_end_flush();
	}
	add_action('get_header', 'buffer_start');
	add_action('wp_footer', 'buffer_end');
}

// Удаление лого и ссылок wp в панели админа
$link_option = get_option('mfp_mod_option_wp_del');
if($link_option == 'on') {
	function mfp_delete_wp_links() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wp-logo');
	}
	add_action('wp_before_admin_bar_render', 'mfp_delete_wp_links');
}

// Свое лого при входе в админку
$logo_option = get_option('mfp_mod_option_wp_logo');
if($logo_option == 'on') {
	function mfp_login_logo() {
		echo '

      <style type="text/css">

          .login h1 a { background: url('.plugins_url('mfp-mod-wp/logo.png').') no-repeat 0 0 !important; }

      </style>';
	}
	add_action('login_head', 'mfp_login_logo');
	add_filter('login_headerurl', create_function('', 'return get_home_url();'));
	add_filter('login_headertitle', create_function('', 'return false;'));
}

// Удаление версии WP из блока "Прямо сейчас"
$mfp_out_wp_ver = get_option('mfp_mod_option_wp_ver');
if($mfp_out_wp_ver == 'on') {
	function mfp_remove_admin_version_message() {
		echo '<script type="text/javascript">';
		echo ';(function($){ $(".versions p").hide(); })(jQuery);';
		echo ';(function($){ $("#wp-version-message").hide(); })(jQuery);';
		echo '</script>';
	}
	add_action('admin_footer', 'mfp_remove_admin_version_message');
}

// Удаление лишних виджетов в консоли
$mfp_out_wp_widgets = get_option('mfp_mod_option_wp_widgets');
if($mfp_out_wp_widgets == 'on') {
	function mfp_remove_dashboard_widgets() {
		global $wp_meta_boxes;
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['welcome-panel']);
	}
	add_action('wp_dashboard_setup', 'mfp_remove_dashboard_widgets');
}

// Удаление контекстного меню справки
$mfp_out_wp_help = get_option('mfp_mod_option_wp_help');
if($mfp_out_wp_help == 'on') {
	function mfp_remove_context_help() {
		global $current_screen;
		$current_screen->remove_help_tabs();
	}
	add_filter('contextual_help_list', 'mfp_remove_context_help');
}

/** футер */
$footer_text_option = get_option('mfp_mod_option_footer_text_opt');
if($footer_text_option == 'on') {
	function mfp_remove_admin_footer_text() {
		$footer_text = get_option('mfp_mod_option_footer_text');
		$footer_text1 = get_option('mfp_mod_option_footer_text1');
		$footer_text2 = get_option('mfp_mod_option_footer_text2');
		if(isset($_POST['mfp_out_footer_text' & 'mfp_out_footer_text1' & 'mfp_out_footer_text2'])) {
			update_option('mfp_mod_option_footer_text', $_POST['mfp_out_footer_text']);
			update_option('mfp_mod_option_footer_text1', $_POST['mfp_out_footer_text1']);
			update_option('mfp_mod_option_footer_text2', $_POST['mfp_out_footer_text2']);
		}
		echo '<span id="footer-thankyou">'.$footer_text.' <a href="'.$footer_text1.'" target="_blank">'.$footer_text2.
			'</a></span>';
	}
	add_filter('admin_footer_text', 'mfp_remove_admin_footer_text');
}

// ------------- end ----------- //
// Вывод опций на страницу настроек
function mfp_options_page() {
	$footer_text = get_option('mfp_mod_option_footer_text');
	$footer_text1 = get_option('mfp_mod_option_footer_text1');
	$footer_text2 = get_option('mfp_mod_option_footer_text2');
	//if($_POST) echo '<script type="text/javascript">window.location.href="'.$url.'";</script>';
	if(isset($_POST['mfp_out_link'])) {
		update_option('mfp_mod_option_link', $_POST['mfp_out_link']);
	}
	if(isset($_POST['mfp_out_comment'])) {
		update_option('mfp_mod_option_comment', $_POST['mfp_out_comment']);
	}
	if(isset($_POST['mfp_out_wp_help'])) {
		update_option('mfp_mod_option_wp_help', $_POST['mfp_out_wp_help']);
	}
	if(isset($_POST['mfp_out_wp_del'])) {
		update_option('mfp_mod_option_wp_del', $_POST['mfp_out_wp_del']);
	}
	if(isset($_POST['mfp_out_wp_ver'])) {
		update_option('mfp_mod_option_wp_ver', $_POST['mfp_out_wp_ver']);
	}
	if(isset($_POST['mfp_out_wp_widgets'])) {
		update_option('mfp_mod_option_wp_widgets', $_POST['mfp_out_wp_widgets']);
	}
	$mfp_out_wp_logo = get_option('mfp_mod_option_wp_logo');
	if(isset($_POST['mfp_out_wp_logo'])) $mfp_out_wp_logo = $_POST['mfp_out_wp_logo'];
	if($mfp_out_wp_logo == "on") {
		update_option('mfp_mod_option_wp_logo', 'on');
	}
	elseif($mfp_out_wp_logo == "off") {
		update_option('mfp_mod_option_wp_logo', 'off');
	}
	$mfp_out_foo_text = get_option('mfp_mod_option_footer_text_opt');
	if(isset($_POST['mfp_out_foo_text'])) $mfp_out_foo_text = $_POST['mfp_out_foo_text'];
	if($mfp_out_foo_text == "on") {
		update_option('mfp_mod_option_footer_text_opt', 'on');
	}
	elseif($mfp_out_foo_text == "off") {
		update_option('mfp_mod_option_footer_text_opt', 'off');
	}
  
	// Загрузка лого
	if(isset($_FILES['filename']['name'])) {
		$file_name = $_FILES['filename']['name'];
		$filetype1 = explode('.', $file_name);
		$filetype = $filetype1[count($filetype1) - 1];
		if($filetype == "jpg" || $filetype == "jpeg" || $filetype == "gif" || $filetype == "bmp" || $filetype == "png" && $_FILES['filename']['size'] !=
			0) {
			if(is_uploaded_file($_FILES['filename']['tmp_name'])) {
				if(move_uploaded_file($_FILES['filename']['tmp_name'], MFP_MOD_WP_DIR.basename($_FILES['filename']['name']))) {
					rename(MFP_MOD_WP_DIR.basename($_FILES['filename']['name']), MFP_MOD_WP_DIR.'logo.png');
					$file_upload = 'Файл '.plugins_url('mfp-mod-wp/'.($_FILES['filename']['name'])).' был успешно загружен';
				}
			}
		}
	}
  
	$mfp_out_link = get_option('mfp_mod_option_link');
	$mfp_out_comment = get_option('mfp_mod_option_comment');
	$mfp_out_wp_help = get_option('mfp_mod_option_wp_help');
	$mfp_out_wp_del = get_option('mfp_mod_option_wp_del');
	$mfp_out_wp_ver = get_option('mfp_mod_option_wp_ver');
	$mfp_out_wp_widgets = get_option('mfp_mod_option_wp_widgets');
	$mfp_out_wp_logo = get_option('mfp_mod_option_wp_logo');
	$mfp_out_foo_text = get_option('mfp_mod_option_footer_text_opt'); 

include 'view.php';

} ?>