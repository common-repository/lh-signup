<form method="post" id="<?php echo self::return_plugin_namespace(); ?>-form" action="<?php echo esc_url($_SERVER['REQUEST_URI']); ?>" data-<?php echo self::return_plugin_namespace(); ?>-nonce="<?php echo wp_create_nonce(self::return_plugin_namespace()."-nonce"); ?>">
<p class="input-container">
<label for="<?php echo self::return_plugin_namespace(); ?>-site_slug"><?php echo $site_slug_label; ?></label>
<input type="text" name="<?php echo self::return_plugin_namespace(); ?>-site_slug" id="<?php echo self::return_plugin_namespace(); ?>-site_slug" size="10" placeholder="<?php echo $site_slug_placeholder; ?>" required="required" pattern="^[a-z0-9_-]*$" autocapitalize="none" title="<?php echo $site_slug_title; ?>" />
<span class="unit"><?php 

$main_site_url = parse_url(get_site_url(get_main_site_id()));
echo ".".$main_site_url['host']; 

?>
</span>
</p>
    
<p><label for="<?php echo self::return_plugin_namespace(); ?>-site_title"><?php echo $site_title_label; ?></label>
<input type="text" name="<?php echo self::return_plugin_namespace(); ?>-site_title" id="<?php echo self::return_plugin_namespace(); ?>-site_title" size="10" placeholder="<?php echo $site_title_placeholder; ?>;" required="required"/>
</p>
<p>
<label for="<?php echo self::return_plugin_namespace(); ?>-site_template"><?php echo $site_template_label; ?></label><br/>
<select name="<?php echo self::return_plugin_namespace(); ?>-site_template" id="<?php echo self::return_plugin_namespace(); ?>-site_template" required="required">
<option value=""><?php _e("Select the type of site you want", self::return_plugin_namespace() ); ?></option>
<?php

   $current_templates = get_site_option(self::return_template_sites_option_name());
    $args = array('site__in' => $current_templates);
    $subsites = get_sites($args);
    foreach ($subsites as $subsite) {
    
        $current_blog_details = get_blog_details( array( 'blog_id' => $subsite->blog_id) );
        
    ?>
    <option value="<?php echo $subsite->blog_id; ?>"><?php echo $current_blog_details->blogname; ?></option>
    <?php          
        
        
    }
    
?>
</select>
</p>
<input type="hidden" id="<?php echo self::return_plugin_namespace(); ?>-nonce" name="<?php echo self::return_plugin_namespace(); ?>-nonce" value="" />
<input type="submit" id="<?php echo self::return_plugin_namespace(); ?>-submit" name="<?php echo self::return_plugin_namespace(); ?>-submit" value="<?php echo $form_submit_button_value; ?>"/>
</form>
<div id="<?php echo self::return_plugin_namespace(); ?>-template_preview">
</div>