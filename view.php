<div id="icon-options-general" class="icon32"><br /></div>
<h2><?php echo __('Configuration MFP mod WP', 'mfp-languages'); ?></h2>
<div class="metabox-holder container">
  <div class="sidebar1">
    <div class="postbox">
      <h3 class="hndle">
      <span><?php echo __('About the plugin', 'mfp-languages'); ?></span>
      </h3>
      <div class="inside">
      <hr />
      <p><?php echo __('Plugin MFP mod WP does two main functions:  clean your source code from links, which can to slow down your blog and hides some articles such as version of the engine, links to wordpress.org etc. from the admintool.', 'mfp-languages'); ?></p>
      <p><?php echo __('The plugin is developed now, but opportunities of the plugin are constantly expanding..', 'mfp-languages'); ?></p>
      <p><?php echo __('<em>Attention!</em> Some options may cause unstable operation of your blog. If it’s so, then turn off the option or don’t use the plugin, if you do not sure. The plugin do not change your files, if it will be deactivated, that all settings will be reset.', 'mfp-languages'); ?></p>
      </div>
    </div>
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
  <form method="post" action="" enctype="multipart/form-data" >
  <div class="postbox">
    <h3 class="hndle">
    <span><?php echo __('Settings removal', 'mfp-languages'); ?></span>
    </h3>
    <div class="inside">
      <div class="block clearfloat">
      <div class="h3"><?php echo __('Removing bad code in the header:', 'mfp-languages'); ?>
      <p class="description"><?php echo __('<em>Removes wp_head links: </em> feed, rsd, wlwmanifest, index_rel, parent_post_rel, start_post_rel, adjacent_posts_rel, wp_shortlink and wp_generator.',
'mfp-languages'); ?></p>
      </div>
        <div class="styled-select">
          <select name="mfp_out_link">
          <option value="on" <?php if($mfp_out_link == 'on') { echo ' selected="selected"'; } ?>><?php echo __('Enable', 'mfp-languages'); ?></option>
          <option value="off" <?php if($mfp_out_link == 'off') { echo ' selected="selected"'; } ?>><?php echo __('Disable', 'mfp-languages'); ?></option>
          </select>
        </div>
      </div>
      <div class="block clearfloat">
      <div class="h3"><?php echo __('Removing comments from the code:', 'mfp-languages'); ?>
      <p class="description"><?php echo __('Removes comments html <br /> (type &lt;-- comment --&gt; ) with the source code. Does not alter any files!',
'mfp-languages'); ?></p>
      </div>
        <div class="styled-select">
          <select name="mfp_out_comment">
          <option value="on" <?php if($mfp_out_comment == 'on') { echo ' selected="selected"'; } ?>><?php echo __('Enable', 'mfp-languages'); ?></option>
          <option value="off" <?php if($mfp_out_comment == 'off') { echo ' selected="selected"'; } ?>><?php echo __('Disable', 'mfp-languages'); ?></option>
          </select>
        </div>
      </div>
      <div class="block clearfloat">
        <div class="h3"><?php echo __('Removing the shortcut menu help:', 'mfp-languages'); ?>
        <p class="description"><?php echo __('Hides in the admin panel context menu reference.', 'mfp-languages'); ?></p>
        </div>
        <div class="styled-select">
          <select name="mfp_out_wp_help">
          <option value="on" <?php if($mfp_out_wp_help == 'on') {	echo ' selected="selected"'; } ?>><?php echo __('Enable', 'mfp-languages'); ?></option>
          <option value="off" <?php if($mfp_out_wp_help == 'off') {	echo ' selected="selected"'; } ?>><?php echo __('Disable', 'mfp-languages'); ?></option>
          </select>
        </div>
      </div>
      <div class="block clearfloat">
        <div class="h3"><?php echo __('Removing the logo and links in wp admin panel:', 'mfp-languages'); ?>
        <p class="description"><?php echo __('Removing the logo and links to wordpress.org with admin panel.', 'mfp-languages'); ?></p>
        </div>
        <div class="styled-select">
          <select name="mfp_out_wp_del">
          <option value="on" <?php if($mfp_out_wp_del == 'on') { echo ' selected="selected"';	} ?>><?php echo __('Enable', 'mfp-languages'); ?></option>
          <option value="off" <?php if($mfp_out_wp_del == 'off') { echo ' selected="selected"';	} ?>><?php echo __('Disable', 'mfp-languages'); ?></option>
          </select>
        </div>
      </div>
      <div class="block clearfloat">
        <div class="h3"><?php echo __('Removing WP version of the block "right now":', 'mfp-languages'); ?>
        <p class="description"><?php echo __('On the "Console" removes wordpress version in block "right now".', 'mfp-languages'); ?></p>
        </div>
        <div class="styled-select">
          <select name="mfp_out_wp_ver">
          <option value="on" <?php if($mfp_out_wp_ver == 'on') { echo ' selected="selected"'; } ?>><?php echo __('Enable', 'mfp-languages'); ?></option>
          <option value="off" <?php if($mfp_out_wp_ver == 'off') { echo ' selected="selected"';	} ?>><?php echo __('Disable', 'mfp-languages'); ?></option>
          </select>
        </div>
      </div>
      <div class="block clearfloat">
        <div class="h3"><?php echo __('Removing unnecessary widgets in the console:', 'mfp-languages'); ?>
        <p class="description"><?php echo __('Removes widgets: inbound links, the panel "Welcome", "Blog WordPress", "News WordPress", widget plugins..', 'mfp-languages'); ?></p>
        </div>
        <div class="styled-select">
          <select name="mfp_out_wp_widgets">
          <option value="on" <?php if($mfp_out_wp_widgets == 'on') { echo ' selected="selected"'; } ?>><?php echo __('Enable', 'mfp-languages'); ?></option>
          <option value="off" <?php if($mfp_out_wp_widgets == 'off') { echo ' selected="selected"'; } ?>><?php echo __('Disable', 'mfp-languages'); ?></option>
          </select>
        </div>
      </div>
    </div>
  </div>
  <div class="postbox">
    <h3 class="hndle">
    <span><?php echo __('Text in footer admin panel:', 'mfp-languages'); ?></span>
    </h3>
    <div class="inside">
        <?php echo __('On', 'mfp-languages'); ?>
        <input type="radio" value="on" name="mfp_out_foo_text" <?php if($mfp_out_foo_text == 'on') { echo ' checked="checked"';	} ?> />
        <?php echo __('Off', 'mfp-languages'); ?>
        <input type="radio" value="off" name="mfp_out_foo_text" <?php if($mfp_out_foo_text == 'off') { echo ' checked="checked"';	} ?> />
    </div>
    <div class="inside">
      <input type="text" class="" name="mfp_out_footer_text" value="<?php echo $footer_text; ?>" />
      <input type="text" class="" name="mfp_out_footer_text1" value="<?php echo $footer_text1; ?>" />
      <input type="text" class="" name="mfp_out_footer_text2" value="<?php echo $footer_text2; ?>" />
    </div>
  </div>

  <div class="postbox">
  <h3 class="hndle">
  <span><?php echo __('Upload your logo on the login page', 'mfp-languages'); ?></span>
  </h3>
    <div class="inside">
    <p><?php echo __('Image size should not exceed 64px × 64px', 'mfp-languages'); ?></p>
        <?php echo __('On', 'mfp-languages'); ?>
        <input type="radio" value="on" name="mfp_out_wp_logo" <?php if($mfp_out_wp_logo == 'on') { echo ' checked="checked"';	} ?> />
        <?php echo __('Off', 'mfp-languages'); ?>
        <input type="radio" value="off" name="mfp_out_wp_logo" <?php if($mfp_out_wp_logo == 'off') { echo ' checked="checked"';	} ?> />
    </div>
    <div class="inside">
        <input type="file" name="filename" />
        <input type="submit" class="" value="<?php echo __('Upload', 'mfp-languages'); ?>" />
        <p><?php if(isset($file_upload)) echo $file_upload; ?></p>
    </div>
  </div>
    <input type="submit" class="mfp-button" name="save" value="<?php echo __('Save Configuration', 'mfp-languages'); ?>" />
  </form>
</div>
</div>