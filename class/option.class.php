<?php
/**
* @author Varrcan
* @e-mail admin@xsence.net
* @copyright 2015
*/

class mainMfp{
	public $mfp_options;
  public $errors = array();
  public $messages = array();
  
  public function __construct(){
    if(get_option('mfp_version') == null){
      $this->delOldSetting();
			$this->mfpReset();
      add_option('mfp_version', MFP_VERSION);
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
													"mfp_mod_option_translit" => "0", // Транслит
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
		
		if(get_option('mfp_version') < MFP_VERSION){
			update_option('mfp_version', MFP_VERSION);
		}
		
		$this->mfp_options = unserialize($this->mfpGetOptions());
		
		$this->functionMFP(); // инициализация функций
    
    if (isset($_POST["mfp-save"])) { // обновить настройки
      $this->mfpSaveOptions();
    }
		if (isset($_POST["mfp-reset"])) { // сбросить настройки
      $this->mfpReset();
    }
		
  } // end __construct
  
  public static function mfp_uninstall(){
		$option_name = 'mfp_mod_options';
    if (!is_multisite()){
      delete_option($option_name);
      delete_option('mfp_version');
    }else {
      global $wpdb;
      $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
      $original_blog_id = get_current_blog_id();
      foreach ($blog_ids as $blog_id){
        switch_to_blog($blog_id);
        delete_site_option($option_name);
      }
      switch_to_blog($original_blog_id);
    }
	}
	
	/** Сброс настроек*/
  public function mfpReset() {
    delete_option('mfp_mod_options');
		delete_option('mfp_mod_image_url');
		delete_option('mfp_mod_logo_url');
    delete_option('mfp_version');
  }
  
  /** Удаление старых опций из БД при первой активации плагина*/
  public function delOldSetting() {
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
  
	/** Добавление стилей на страницу настроек плагина */
  public function mfpAddStyle() {
    wp_register_style('mfp-style', plugins_url('mfp-mod-wp/css/mfp-mod-wp.css'));
    wp_enqueue_style('mfp-style');
  }
	/** Добавление пункта и страницы настроек в меню */
	public function mfpAdminMenu(){
		add_options_page('MFP mod','MFP mod','manage_options', 'mfp-mod-wp', array($this, 'mfpSettingsPage'));
	}
	/** Страница настроек */
	public function mfpSettingsPage(){
		include_once MFP_MOD_WP_DIR.'view.php';
	}
	/** Добавление ссылки Настройки в мета-информации плагина */
	public function setmeta($links,$file=null) {
		static $plugin;
		if(empty($plugin))
			$plugin = plugin_basename(WP_PLUGIN_DIR.'/mfp-mod-wp/mfp-mod-wp.php');
		
		if($file===null) {
			//2.7
			$settings_link = sprintf('<a href="options-general.php?page=mfp-mod-wp">%s</a>', __('Settings'));
			array_unshift($links,$settings_link);
		} else {
			//2.8
			if($file === $plugin) {
				$newlink = array(sprintf('<a href="options-general.php?page=mfp-mod-wp">%s</a>',__('Settings')));
				$links = array_merge($links,$newlink);
			}
		}
		return $links;
	}
  
	/** Получение настроек из БД */
  public function mfpGetOptions() {
    $options = get_option('mfp_mod_options');
    return $options;
  }
								
  /** Обновление настроек */
  public function mfpSaveOptions(){
		$data = array("mfp_mod_option_link" =>
									array("rss" => (isset($_POST['mfp_out_link']['rss'])) ? $_POST['mfp_out_link']['rss'] : '0',
												"wlwmanifest" => (isset($_POST['mfp_out_link']['wlwmanifest'])) ? $_POST['mfp_out_link']['wlwmanifest'] : '0',
												"index_rel" => (isset($_POST['mfp_out_link']['index_rel'])) ? $_POST['mfp_out_link']['index_rel'] : '0',
												"wp_shortlink" => (isset($_POST['mfp_out_link']['wp_shortlink'])) ? $_POST['mfp_out_link']['wp_shortlink'] : '0',
												"wp_generator" => (isset($_POST['mfp_out_link']['wp_generator'])) ? $_POST['mfp_out_link']['wp_generator'] : '0',
											 ),
								"mfp_mod_option_comment" => (isset($_POST['mfp_out_comment'])) ? $_POST['mfp_out_comment'] : '0',
								"mfp_mod_option_version" => (isset($_POST['mfp_out_version'])) ? $_POST['mfp_out_version'] : '0',
								"mfp_mod_option_wp_help" => (isset($_POST['mfp_out_wp_help'])) ? $_POST['mfp_out_wp_help'] : '0',
								"mfp_mod_option_wp_del" => (isset($_POST['mfp_out_wp_del'])) ? $_POST['mfp_out_wp_del'] : '0',
								"mfp_mod_option_wp_logo" => (isset($_POST['mfp_out_wp_logo'])) ? $_POST['mfp_out_wp_logo'] : '0',
								"mfp_mod_option_wp_widgets" =>
								array(
											"quick_press" => (isset($_POST['mfp_out_wp_widgets']['quick_press'])) ? $_POST['mfp_out_wp_widgets']['quick_press'] : '0',
											"activity" => (isset($_POST['mfp_out_wp_widgets']['activity'])) ? $_POST['mfp_out_wp_widgets']['activity'] : '0',
											"right_now" => (isset($_POST['mfp_out_wp_widgets']['right_now'])) ? $_POST['mfp_out_wp_widgets']['right_now'] : '0',
											"primary" => (isset($_POST['mfp_out_wp_widgets']['primary'])) ? $_POST['mfp_out_wp_widgets']['primary'] : '0',
											"welcome" => (isset($_POST['mfp_out_wp_widgets']['welcome'])) ? $_POST['mfp_out_wp_widgets']['welcome'] : '0',
										 ),
								"mfp_mod_option_translit" => (isset($_POST['mfp_out_translit'])) ? $_POST['mfp_out_translit'] : '0',
								"mfp_mod_option_footer_text_opt" => (isset($_POST['mfp_out_foo_text'])) ? $_POST['mfp_out_foo_text'] : '0',
								"mfp_mod_option_footer_text" => (isset($_POST['mfp_out_footer_text'])) ? $_POST['mfp_out_footer_text'] : '',
								"mfp_mod_option_footer_text1" => (isset($_POST['mfp_out_footer_text1'])) ? $_POST['mfp_out_footer_text1'] : '',
								"mfp_mod_option_footer_text2" => (isset($_POST['mfp_out_footer_text2'])) ? $_POST['mfp_out_footer_text2'] : '',
								"mfp_mod_option_metabox" => (isset($_POST['mfp_out_metabox'])) ? $_POST['mfp_out_metabox'] : '0',
								"mfp_mod_option_metabox_title" => (isset($_POST['mfp_out_metabox_title'])) ? $_POST['mfp_out_metabox_title'] : '',
								"mfp_mod_option_metabox_text" => (isset($_POST['mfp_out_metabox_text'])) ? $_POST['mfp_out_metabox_text'] : '',
								"mfp_mod_option_custom_admin" => (isset($_POST['mfp_out_login'])) ? $_POST['mfp_out_login'] : '0'
								);
		//$data = array("mfp_mod_option_link" => $_POST['mfp_out_link'],
		//							"mfp_mod_option_comment" => $_POST['mfp_out_comment'],
		//							"mfp_mod_option_version" => $_POST['mfp_out_version'],
		//							"mfp_mod_option_wp_help" => $_POST['mfp_out_wp_help'],
		//							"mfp_mod_option_wp_del" => $_POST['mfp_out_wp_del'],
		//							"mfp_mod_option_wp_logo" => $_POST['mfp_out_wp_logo'],
		//							"mfp_mod_option_wp_widgets" => $_POST['mfp_out_wp_widgets'],
		//							"mfp_mod_option_translit" => $_POST['mfp_out_translit'],
		//							"mfp_mod_option_footer_text_opt" => $_POST['mfp_out_foo_text'],
		//							"mfp_mod_option_footer_text" => $_POST['mfp_out_footer_text'],
		//							"mfp_mod_option_footer_text1" => $_POST['mfp_out_footer_text1'],
		//							"mfp_mod_option_footer_text2" => $_POST['mfp_out_footer_text2'],
		//							"mfp_mod_option_metabox" => $_POST['mfp_out_metabox'],
		//							"mfp_mod_option_metabox_title" => $_POST['mfp_out_metabox_title'],
		//							"mfp_mod_option_metabox_text" => $_POST['mfp_out_metabox_text'],
		//							"mfp_mod_option_custom_admin" => $_POST['mfp_out_login']
		//							);
		$data = serialize($data);
		$image_url = (isset($_POST['mfp_image_url'])) ? $_POST['mfp_image_url'] : '';
		$logo_url = (isset($_POST['mfp_logo_url'])) ? $_POST['mfp_logo_url'] : '';
			update_option('mfp_mod_image_url', $image_url);
			update_option('mfp_mod_logo_url', $logo_url);
			update_option('mfp_mod_options', $data);
		$this->messages[] = 'Настройки успешно сохранены';
  }
	
	/** Удаление версии в окончании файлов */
	public function removeVersionFile($src) {
		if (strpos($src, 'ver='))
        $src = remove_query_arg('ver', $src);
    return $src;
		//$parts = explode('?ver', $src);
		//return $parts[0];
	}
	
	/** Удаление комментариев с исходного кода, с сохранением if IE */
	public function preg_comment($buffer) {
		$buffer = preg_replace('`<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->`', '', $buffer);
		return $buffer;
	}
	public function bufferStart() {
		ob_start(array($this, 'preg_comment'));
	}
	public function bufferEnd() {
		ob_end_flush();
	}
	
	/** Удаление лого и ссылок wp в панели админа */
	public function mfpDeleteLogoWp() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wp-logo');
		$wp_admin_bar->remove_menu('about');
	}
	
	/** Удаление контекстного меню справки */
	public function mfpRemoveContextHelp() {
		global $current_screen;
		$current_screen->remove_help_tabs();
	}
	/** Удаление виджетов */
	public function dashboardRemove() {
		if($this->mfp_options['mfp_mod_option_wp_widgets']['quick_press'] == '1'){
			remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // Быстрый черновик
		}
		if($this->mfp_options['mfp_mod_option_wp_widgets']['activity'] == '1'){
			remove_meta_box('dashboard_activity', 'dashboard', 'normal' ); // Активность
		}
		if($this->mfp_options['mfp_mod_option_wp_widgets']['right_now'] == '1'){
			remove_meta_box('dashboard_right_now', 'dashboard', 'normal' ); // На виду
		}
		if($this->mfp_options['mfp_mod_option_wp_widgets']['primary'] == '1'){
			remove_meta_box('dashboard_primary', 'dashboard', 'side' ); // Новости WP
			remove_meta_box('dashboard_secondary', 'dashboard', 'side' );
		}
		if($this->mfp_options['mfp_mod_option_wp_widgets']['welcome'] == '1'){
			remove_action('welcome_panel', 'wp_welcome_panel'); // "Добро пожаловать"
		}
	}
	
	/** Замена текста в подвале "Спасибо вам за творчество с WordPress" */
	public function mfpRemoveAdminFooterText() {
		echo $this->mfp_options['mfp_mod_option_footer_text'].' <a href="'.$this->mfp_options['mfp_mod_option_footer_text1'].'" target="_blank">'.$this->mfp_options['mfp_mod_option_footer_text2'].'</a>';
	}
	
	/** Транслитерация заголовков */
	public function titleWithTranslit($title) {
		$word = array(
			"Є"=>"YE","І"=>"I","Ѓ"=>"G","і"=>"i","№"=>"#","є"=>"ye","ѓ"=>"g",
			"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
			"Е"=>"E","Ё"=>"YO","Ж"=>"ZH",
			"З"=>"Z","И"=>"I","Й"=>"J","К"=>"K","Л"=>"L",
			"М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
			"С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"H",
			"Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
			"Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA",
			"а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
			"е"=>"e","ё"=>"yo","ж"=>"zh",
			"з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
			"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
			"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
			"ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
			"ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
			"—"=>"-","«"=>"","»"=>"","…"=>""
		);
		return strtr($title, $word);
	}
	
	public function add_dashboard_widgets() {
		wp_add_dashboard_widget('dashboard_widget', $this->mfp_options['mfp_mod_option_metabox_title'], array($this, 'mfp_widget_function'));
	}
	public function mfp_widget_function() {
		echo $this->mfp_options['mfp_mod_option_metabox_text'];
	}
	
	public function custom_login_css() {
			echo '<style type="text/css">';
			echo 'body.login {background: #fbfbfb url("'.get_option('mfp_mod_image_url').'") no-repeat fixed center;}';
			echo '.login h1 a {background-image: url("'.get_option('mfp_mod_logo_url').'");}';
			echo '</style>';
			echo '<link rel="stylesheet" type="text/css" href="'.MFP_MOD_WP_URL.'css/custom_login.css" />';
	}
	
	
	
	/** action &  filter*/
	public function functionMFP(){
    /** Добавление пункта и страницы настроек в меню */
		add_action('admin_menu',array($this,'mfpAdminMenu'));
    /** Добавление стиля */
    add_action('admin_init', array($this, 'mfpAddStyle'));
    add_action('admin_print_styles-', array($this, 'mfpAddStyle'));
		/** Добавление ссылки Настройки в мета-информации плагина */
		if(function_exists('plugin_row_meta')) {
			//2.8+
			add_filter('plugin_row_meta',array($this,'setmeta'),10,2);
		} elseif(function_exists('post_class')) {
			//2.7
			$plugin = plugin_basename(WP_PLUGIN_DIR.'/mfp-mod-wp/mfp-mod-wp.php');
			add_filter('plugin_action_links_'.$plugin,array($this,'setmeta'));
		}
    /** Удаление версии в окончании файлов */
		if($this->mfp_options['mfp_mod_option_version'] == '1') {
			add_filter('script_loader_src', array(&$this, 'removeVersionFile'), 9999);
			add_filter('style_loader_src', array(&$this, 'removeVersionFile'), 9999);
		}
    /** Удаление комментариев с исходного кода */
		if($this->mfp_options['mfp_mod_option_comment'] == '1') {
			add_action('get_header', array($this, 'bufferStart'));
			add_action('wp_footer', array($this, 'bufferEnd'));
		}
    /** Удаление лого и ссылок wp в панели админа */
		if($this->mfp_options['mfp_mod_option_wp_del'] == '1') {
			add_action('wp_before_admin_bar_render', array(&$this, 'mfpDeleteLogoWp'));
		}
    /** Удаление контекстного меню справки */
		if($this->mfp_options['mfp_mod_option_wp_help'] == '1') {
			add_filter('contextual_help_list', array(&$this, 'mfpRemoveContextHelp'));
		}
    /** Замена текста в подвале "Спасибо вам за творчество с WordPress" */
		if($this->mfp_options['mfp_mod_option_footer_text_opt'] == '1') {
			add_filter('admin_footer_text', array(&$this, 'mfpRemoveAdminFooterText'));
		}
		/** Транслитерация заголовков */
		if($this->mfp_options['mfp_mod_option_translit'] == '1') {
			add_action('sanitize_title', array(&$this, 'titleWithTranslit'), 0);
			add_filter('sanitize_file_name', array(&$this, 'titleWithTranslit'), 0);
		}
		/** Удаление виджетов в консоли */
		add_action('wp_dashboard_setup', array(&$this, 'dashboardRemove'));
		
		/** Свой виджет */
		if($this->mfp_options['mfp_mod_option_metabox'] == '1') {
			add_action('wp_dashboard_setup', array(&$this, 'add_dashboard_widgets'));
		}
		
		/** wp_head */
		if($this->mfp_options['mfp_mod_option_link']['rss'] == '1') {
			remove_action('wp_head', 'feed_links', 2);
			remove_action('wp_head', 'feed_links_extra', 3);
			remove_action('wp_head', 'rsd_link');
		}
		if($this->mfp_options['mfp_mod_option_link']['wlwmanifest'] == '1') {
			remove_action('wp_head', 'wlwmanifest_link');
		}
		if($this->mfp_options['mfp_mod_option_link']['index_rel'] == '1') {
			remove_action('wp_head', 'index_rel_link');
			remove_action('wp_head', 'parent_post_rel_link', 10, 0);
			remove_action('wp_head', 'start_post_rel_link', 10, 0);
			remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
		}
		if($this->mfp_options['mfp_mod_option_link']['wp_shortlink'] == '1') {
			remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
		}
		if($this->mfp_options['mfp_mod_option_link']['wp_generator'] == '1') {
			remove_action('wp_head', 'wp_generator');
		}
		
		if($this->mfp_options['mfp_mod_option_custom_admin'] == '1') {
			add_action('login_enqueue_scripts', array(&$this, 'custom_login_css'), 100); // CSS
			//add_action('login_message', array(&$this, 'custom_login_form')); // Сообщение над формой
			//add_action('login_footer', array(&$this, 'custom_login_footer')); // Футтер
			add_filter('login_headerurl', create_function('', 'return get_home_url();')); // Смена ссылки с wordpress.org на главную сайта
			add_filter('login_headertitle', create_function('', 'return false;')); // Удаление title логотипа
		}
  }
}



?>