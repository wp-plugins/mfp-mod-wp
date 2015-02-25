<?php
/**
 * @author Varrcan
 * @e-mail admin@xsence.net
 * @copyright 2015
 */

// Подключение Media Uploader
wp_enqueue_script('jquery');
wp_enqueue_media();
 
ob_start();
  if ($this->errors) {
    foreach ($this->errors as $error) {
      echo '<div id="notice" class="alert alert-danger">'.$error.'</div>';
    }
  }
  if ($this->messages) {
    foreach ($this->messages as $message) {
      echo '<div id="notice" class="alert alert-success">'.$message.'</div>';
    }
  }

$notice=ob_get_clean();

$this->mfp_options = unserialize($this->mfpGetOptions());


?>

<script type="text/javascript">
jQuery(document).ready(function($){
    $('#upload-btn-img').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Загрузить фон',
            multiple: false
        }).open()
        .on('select', function(e){
            var uploaded_image = image.state().get('selection').first();
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            $('#image_url').val(image_url);
        });
    });
});
jQuery(document).ready(function($){
    $('#upload-btn-logo').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Загрузить логотип',
            multiple: false
        }).open()
        .on('select', function(e){
            var uploaded_image = image.state().get('selection').first();
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            $('#logo_url').val(image_url);
        });
    });
});
</script>

<form method="post" action="" enctype="multipart/form-data" >
<h2><?php echo __('Configuration MFP mod WP', 'mfp-languages'); ?>
  <small> ver.<?php echo MFP_VERSION; ?></small></h2>
<div class="metabox-holder container">
  <?php echo $notice; ?>
  <div class="sidebar1">
    <div class="postbox">
      <h3 class="hndle"><span><?php echo __('About the plugin', 'mfp-languages'); ?></span></h3>
      <div class="inside">
        <hr />
        <p><?php echo __('Plugin MFP mod WP does two main functions:  clean your source code from
                         links, which can to slow down your blog and hides some articles such as
                         version of the engine, links to wordpress.org etc. from the admintool.',
                         'mfp-languages'); ?></p>
        <p><?php echo __('The plugin is developed now, but opportunities of the plugin are
                         constantly expanding..', 'mfp-languages'); ?></p>
        <p><?php echo __('<em>Attention!</em> Some options may cause unstable operation of your
                         blog. If it’s so, then turn off the option or don’t use the plugin,
                         if you do not sure. The plugin do not change your files, if it will be
                         deactivated, that all settings will be reset.', 'mfp-languages'); ?></p>
      </div>
    </div>
    <input type="submit" class="btn btn-danger" name="reset"
           value="<?php echo __('Reset all settings', 'mfp-languages'); ?>" />
    <!--
    <div class="postbox">
      <h3 class="hndle">
      <span><?php //echo __('','mfp-languages'); ?></span>
      </h3>
      <div class="inside">
      <hr />
      <p><?php //echo __('','mfp-languages'); ?></p>
      </div>
    </div>
    -->
  </div>
  <div class="content">
    
  <div class="postbox">
    <h3 class="hndle"><span><?php echo __('Settings removal', 'mfp-languages'); ?></span></h3>
    <div class="inside">
      <div class="mfp-block clearfloat">
        <div class="h3"><?php echo __('Removing comments from the code:', 'mfp-languages'); ?>
        <p class="description"><?php echo __('Removes comments html <br /> (type &lt;-- comment --&gt; )
                                             with the source code. Does not alter any files!','mfp-languages'); ?></p>
        </div>
        <div class="">
          <input type="checkbox" value="1" name="mfp_out_comment"
            <?php echo $this->mfp_options['mfp_mod_option_comment'] == '1'?'checked="checked" ':''; ?>/>
        </div>
      </div>
      <div class="mfp-block clearfloat">
        <div class="h3"><?php echo __('Hide WordPress version in JavaScript and CSS:', 'mfp-languages'); ?>
          <p class="description"><?php echo __('Hides WordPress version with source code','mfp-languages'); ?></p>
        </div>
        <div class="">
          <input type="checkbox" value="1" name="mfp_out_version"
            <?php echo $this->mfp_options['mfp_mod_option_version'] == '1'?'checked="checked" ':''; ?>/>
        </div>
      </div>
      <div class="mfp-block clearfloat">
        <div class="h3"><?php echo __('Removing the shortcut menu help:', 'mfp-languages'); ?>
          <p class="description"><?php echo __('Hides in the admin panel context menu reference.', 'mfp-languages'); ?></p>
        </div>
        <div class="">
          <input type="checkbox" value="1" name="mfp_out_wp_help"
            <?php echo $this->mfp_options['mfp_mod_option_wp_help'] == '1'?'checked="checked" ':''; ?>/>
        </div>
      </div>
      <div class="mfp-block clearfloat">
        <div class="h3"><?php echo __('Removing the logo and links in wp admin panel:', 'mfp-languages'); ?>
          <p class="description"><?php echo __('Removing the logo and links to wordpress.org with admin panel.', 'mfp-languages'); ?></p>
        </div>
        <div class="">
          <input type="checkbox" value="1" name="mfp_out_wp_del"
            <?php echo $this->mfp_options['mfp_mod_option_wp_del'] == '1'?'checked="checked" ':''; ?>/>
        </div>
      </div>
    </div>
  </div>
  <div class="postbox">
      <h3 class="hndle"><span><?php echo __('Removing links from wp_head, slowing the work site',
                                            'mfp-languages'); ?></span></h3>
      <div class="inside">
        <div class="mfp-block clearfloat">
          <div class="h3"><?php echo __('Removing rss, rds:', 'mfp-languages'); ?>
            <p class="description"><?php echo __('Links to rss, atom, trackbacks','mfp-languages'); ?></p>
          </div>
          <div class="">
            <input type="checkbox" value="1" name="mfp_out_link[rss]"
              <?php echo $this->mfp_options['mfp_mod_option_link']['rss'] == '1'?'checked="checked" ':''; ?>/>
          </div>
        </div>
      <div class="mfp-block clearfloat">
        <div class="h3"><?php echo __('Removing wlwmanifest:', 'mfp-languages'); ?>
          <p class="description"><?php echo __('Link to editor Windows Live Writer', 'mfp-languages'); ?></p>
        </div>
        <div class="">
          <input type="checkbox" value="1" name="mfp_out_link[wlwmanifest]"
            <?php echo $this->mfp_options['mfp_mod_option_link']['wlwmanifest'] == '1'?'checked="checked" ':''; ?>/>
        </div>
      </div>
      <div class="mfp-block clearfloat">
        <div class="h3"><?php echo __('Removing relink pages:', 'mfp-languages'); ?>
          <p class="description"><?php echo __('index_rel_link, parent_post_rel_link,
                                               start_post_rel_link, adjacent_posts_rel_link', 'mfp-languages'); ?></p>
        </div>
        <div class="">
          <input type="checkbox" value="1" name="mfp_out_link[index_rel]"
            <?php echo $this->mfp_options['mfp_mod_option_link']['index_rel'] == '1'?'checked="checked" ':''; ?>/>
        </div>
      </div>
      <div class="mfp-block clearfloat">
        <div class="h3"><?php echo __('Removing wp_shortlink:', 'mfp-languages'); ?>
          <p class="description"><?php echo __('Removes duplicates links site.ru/?p=111', 'mfp-languages'); ?></p>
        </div>
        <div class="">
          <input type="checkbox" value="1" name="mfp_out_link[wp_shortlink]"
            <?php echo $this->mfp_options['mfp_mod_option_link']['wp_shortlink'] == '1'?'checked="checked" ':''; ?>/>
        </div>
      </div>
      <div class="mfp-block clearfloat">
        <div class="h3"><?php echo __('Removing wp_generator:', 'mfp-languages'); ?>
          <p class="description"><?php echo __('Removes WordPress version of html code', 'mfp-languages'); ?></p>
        </div>
        <div class="">
          <input type="checkbox" value="1" name="mfp_out_link[wp_generator]"
            <?php echo $this->mfp_options['mfp_mod_option_link']['wp_generator'] == '1'?'checked="checked" ':''; ?>/>
        </div>
      </div>
    </div>
  </div>
  <div class="postbox">
      <h3 class="hndle"><span><?php echo __('Removing widgets in the console', 'mfp-languages'); ?></span></h3>
      <div class="inside">
        <div class="mfp-block clearfloat">
          <div class="h3"><?php echo __('Remove widget Quick Press:', 'mfp-languages'); ?>
            <p class="description"><?php echo __('dashboard_quick_press','mfp-languages'); ?></p>
          </div>
          <div class="">
            <input type="checkbox" value="1" name="mfp_out_wp_widgets[quick_press]"
              <?php echo $this->mfp_options['mfp_mod_option_wp_widgets']['quick_press'] == '1'?'checked="checked" ':''; ?>/>
          </div>
        </div>
        <div class="mfp-block clearfloat">
          <div class="h3"><?php echo __('Remove widget Activity:', 'mfp-languages'); ?>
            <p class="description"><?php echo __('dashboard_activity','mfp-languages'); ?></p>
          </div>
          <div class="">
            <input type="checkbox" value="1" name="mfp_out_wp_widgets[activity]"
              <?php echo $this->mfp_options['mfp_mod_option_wp_widgets']['activity'] == '1'?'checked="checked" ':''; ?>/>
          </div>
        </div>
        <div class="mfp-block clearfloat">
          <div class="h3"><?php echo __('Remove widget Right now:', 'mfp-languages'); ?>
            <p class="description"><?php echo __('dashboard_right_now','mfp-languages'); ?></p>
          </div>
          <div class="">
            <input type="checkbox" value="1" name="mfp_out_wp_widgets[right_now]"
              <?php echo $this->mfp_options['mfp_mod_option_wp_widgets']['right_now'] == '1'?'checked="checked" ':''; ?>/>
          </div>
        </div>
        <div class="mfp-block clearfloat">
          <div class="h3"><?php echo __('Remove widget News Wordpress:', 'mfp-languages'); ?>
            <p class="description"><?php echo __('dashboard_primary dashboard_secondary','mfp-languages'); ?></p>
          </div>
          <div class="">
            <input type="checkbox" value="1" name="mfp_out_wp_widgets[primary]"
              <?php echo $this->mfp_options['mfp_mod_option_wp_widgets']['primary'] == '1'?'checked="checked" ':''; ?>/>
          </div>
        </div>
        <div class="mfp-block clearfloat">
          <div class="h3"><?php echo __('Remove widget Welcome:', 'mfp-languages'); ?>
            <p class="description"><?php echo __('welcome_panel','mfp-languages'); ?></p>
          </div>
          <div class="">
            <input type="checkbox" value="1" name="mfp_out_wp_widgets[welcome]"
              <?php echo $this->mfp_options['mfp_mod_option_wp_widgets']['welcome'] == '1'?'checked="checked" ':''; ?>/>
          </div>
        </div>
        
    </div>
  </div>
  <div class="postbox">
    <h3 class="hndle"><span><?php echo __('Transliteration while downloading the file, create a new record or page',
                                          'mfp-languages'); ?></span></h3>
    <div class="inside mfp-translit">
      <div class="mfp-block clearfloat">
        <div class="h3"><?php echo __('Enable transliteration:', 'mfp-languages'); ?>
          <p class="description"><?php echo __('When you create a new entry link will
                                                automatically transferred to the transliteration.
                                                There will also be translated Cyrillic names of uploaded files.',
                                               'mfp-languages'); ?></p>
        </div>
        <div class="">
          <input type="checkbox" value="1" name="mfp_out_translit"
            <?php echo $this->mfp_options['mfp_mod_option_translit'] == '1'?'checked="checked" ':''; ?>/>
        </div>
      </div>
    </div>
  </div>
  <div class="postbox">
    <h3 class="hndle"><span><?php echo __('Text in footer admin panel:', 'mfp-languages'); ?></span></h3>
    <div class="inside">
      <div class="mfp-block clearfloat">
        <div class="h3"><?php echo __('Enable display text:', 'mfp-languages'); ?>
          <p class="description"><?php echo __('Enter the text link and the title links ', 'mfp-languages'); ?></p>
        </div>
        <input type="checkbox" value="1" name="mfp_out_foo_text"
            <?php echo $this->mfp_options['mfp_mod_option_footer_text_opt'] == '1'?'checked="checked" ':''; ?>/>
      </div>
        <div class="inside">
          <input type="text" class="" name="mfp_out_footer_text" placeholder="Developed by"
                 value="<?php echo $this->mfp_options['mfp_mod_option_footer_text']; ?>" />
          <input type="text" class="" name="mfp_out_footer_text1" placeholder="http://"
                 value="<?php echo $this->mfp_options['mfp_mod_option_footer_text1']; ?>" />
          <input type="text" class="" name="mfp_out_footer_text2" placeholder="My site"
                 value="<?php echo $this->mfp_options['mfp_mod_option_footer_text2']; ?>" />
        </div>
      
    </div>
  </div>
  <div class="postbox">
    <h3 class="hndle"><span><?php echo __('Metaboxes Console',
                                          'mfp-languages'); ?></span></h3>
    <div class="inside mfp-box">
      <div class="mfp-block clearfloat">
        <div class="h3"><?php echo __('Enable Metaboxes:', 'mfp-languages'); ?>
          <p class="description"><?php echo __('Adding a metaboxes (widget)<br/> to the Console', 'mfp-languages'); ?></p>
        </div>
        <div class="">
          <input type="checkbox" value="1" name="mfp_out_metabox"
            <?php echo $this->mfp_options['mfp_mod_option_metabox'] == '1'?'checked="checked" ':''; ?>/>
        </div>
        <div class="h3 clearfloat">
          <input type="text" name="mfp_out_metabox_title" placeholder="<?php echo __('Title widget', 'mfp-languages'); ?>"
                 value="<?php echo $this->mfp_options['mfp_mod_option_metabox_title']; ?>" />
        </div>
        <div class="h3 clearfloat">
          <textarea class="mfp-textarea" placeholder="<?php echo __('The main text of the widget', 'mfp-languages'); ?>"
            name="mfp_out_metabox_text"><?php echo $this->mfp_options['mfp_mod_option_metabox_text']; ?></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="postbox">
    <h3 class="hndle"><span><?php echo __('Changing the appearance of the login page',
                                          'mfp-languages'); ?></span></h3>
    <div class="inside">
      <div class="mfp-block clearfloat">
        <div class="h3"><?php echo __('Make beautiful:', 'mfp-languages'); ?>
          <p class="description"><?php //echo __('', 'mfp-languages'); ?></p>
        </div>
        <div class="">
          <input type="checkbox" value="1" name="mfp_out_login"
            <?php echo $this->mfp_options['mfp_mod_option_custom_admin'] == '1'?'checked="checked" ':''; ?>/>
        </div><br/>
        <div class="clearfloat">
          <input type="text" name="mfp_image_url" id="image_url" class="regular-text"
                 placeholder="<?php echo __('background image', 'mfp-languages'); ?>"
                 value="<?php echo get_option('mfp_mod_image_url'); ?>"/>
          <input type="button" name="upload-btn" id="upload-btn-img" class="button-secondary"
                 value="<?php echo __('Select background', 'mfp-languages'); ?>" />
        </div>
        <div class="clearfloat">
          <input type="text" name="mfp_logo_url" id="logo_url" class="regular-text"
                 placeholder="<?php echo __('logo', 'mfp-languages'); ?>"
                 value="<?php echo get_option('mfp_mod_logo_url'); ?>"/>
          <input type="button" name="upload-btn" id="upload-btn-logo" class="button-secondary"
                 value="<?php echo __('Select logo', 'mfp-languages'); ?>" />
        </div>
      </div>
    </div>
  </div>
  
    <input type="submit" class="btn btn-success" name="save"
           value="<?php echo __('Save Configuration', 'mfp-languages'); ?>" />
  </div>
</div>
</form>


