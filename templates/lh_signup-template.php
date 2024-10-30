<?php

    if (!empty($local_options[self::return_local_image_field_name()]) && wp_get_attachment_image_src($local_options[self::return_local_image_field_name()], 'full')){
        
        echo "\n".'<link rel="preload" as="image" href="'.wp_get_attachment_image_src($local_options[self::return_local_image_field_name()], 'full')[0].'" />'."\n";
    
    }


    echo '<template class="'.self::return_plugin_namespace().'-template" id="'.self::return_plugin_namespace().'-template-'.$subsite->blog_id.'">'."\n";
?>
<h2 class="<?php echo self::return_plugin_namespace(); ?>-template_heading"><a href="<?php echo get_site_url(); ?>" target="_blank"><?php echo $current_blog_details->blogname; ?></a></h2>
<p class="<?php echo self::return_plugin_namespace(); ?>-template_description"><?php echo $local_options[self::return_local_description_name()]; ?></p>
<?php
    
    

    if (!empty($local_options[self::return_local_image_field_name()]) && wp_get_attachment_image_src($local_options[self::return_local_image_field_name()], 'full')){
        
        $attr =  array(
            'class' => self::return_plugin_namespace().'-template_image',
        );
    
        echo wp_get_attachment_image( $local_options[self::return_local_image_field_name()], 'full', false, $attr);
    
    }

    echo  "\n";
    echo '</template>'."\n";

?>