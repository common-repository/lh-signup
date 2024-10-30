<?php

    echo '<h1>'.esc_html(get_admin_page_title()).'</h1>'."\n";
    echo '<form name="'.self::return_plugin_namespace().'-network_backend_form" method="post" action="">'."\n";
    wp_nonce_field( self::return_plugin_namespace()."-network_nonce", self::return_plugin_namespace()."-network_nonce", false );
    echo '<table class="form-table">'."\n";
    echo '<tr valign="top">'."\n";
    echo '<th scope="row">'."\n";
    echo '<label for="'.self::return_allowed_roles_option_name().'">'.__("Allowed Roles;", self::return_plugin_namespace()).'</label>'."\n";
    echo '</th>'."\n";
    echo '<td>'."\n";
    echo '<select id="'.self::return_allowed_roles_option_name().'" name="'.self::return_allowed_roles_option_name().'[]" multiple="multiple">'."\n";

    $roles = self::get_editable_roles(); 

    $currently_allowed_roles = get_site_option(self::return_allowed_roles_option_name());

    foreach ($roles as $role => $value) {

        if ($role != "administrator"){
            
            echo '<option value="'.$role.'"';
            
            if (is_array($currently_allowed_roles) && in_array( $role , $currently_allowed_roles )){ 
            
                echo 'selected="selected"';
            
            }
            
            echo ' >'.$value['name'].'</option>'."\n";
 
        }

    }
    
    echo '</select>'."\n";
    echo '</td>'."\n";
    echo '</tr>'."\n";
    echo '<tr valign="top">'."\n";
    echo '<th scope="row">'."\n";
    echo '<label for="'.self::return_template_sites_option_name().'">'.__("Allowed Templates;", self::return_plugin_namespace()).'</label>'."\n";
    echo '</th>'."\n";
    echo '<td>'."\n";

    $args = array(
        'site__not_in' =>  get_main_site_id(),   
    );
    
    $subsites = get_sites($args);

    $current_templates = get_site_option(self::return_template_sites_option_name());

    echo '<select id="'.self::return_template_sites_option_name().'" name="'.self::return_template_sites_option_name().'[]" multiple="multiple">'."\n";


    foreach ($subsites as $subsite) {
        
        $current_blog_details = get_blog_details( array( 'blog_id' => $subsite->blog_id) );
        
        echo '<option value="'.$subsite->blog_id.'"  ';
    
        if (is_array($current_templates) && in_array( $subsite->blog_id , $current_templates )){
        
            echo 'selected="selected"';
            
        }
        
        echo ' >'.$current_blog_details->blogname.'</option>'."\n";
      
    }
    
    echo '</select>'."\n";
    echo '</td>'."\n";
    echo '</tr>'."\n";
    echo '<tr valign="top">'."\n";
    echo '<th scope="row">'."\n";
    echo '<label for="'.self::return_network_signup_page_option_name().'">'.__("Network Signup Page;", self::return_plugin_namespace()).'</label>'."\n";
    echo '</th>'."\n";
    echo '<td>'."\n";

    $network_signup_page_id = get_site_option(self::return_network_signup_page_option_name());

    if (!empty($network_signup_page_id) && get_permalink($network_signup_page_id)){
        
        $selected = $network_signup_page_id;
        
        
    } else {
        
        $selected = false;
        
    }

    $args = array(
        'selected'              => $selected,
        'echo'                  => 1,
        'name'                  => self::return_network_signup_page_option_name(),
        'show_option_none'      => __( '&mdash; Select a signup page &mdash;' ) // string
    ); 


 
    wp_dropdown_pages( $args );

    if (!empty($network_signup_page_id) && get_permalink($network_signup_page_id)){
    
        echo '<a href="'.get_permalink($network_signup_page_id).'">'. __("Link", self::return_plugin_namespace()).'</a>'."\n";
        echo '<a href="'.get_edit_post_link($network_signup_page_id).'">'.__("Edit", self::return_plugin_namespace()).'</a>'."\n";
        echo '</td>'."\n";
        echo '</tr>'."\n";
        
    } 


    echo '<tr valign="top">'."\n";
    echo '<th scope="row">'."\n";
    echo '<label for="'.self::return_email_on_new_blog_active_option_name().'">'.__("Enable new site emails;", self::return_plugin_namespace()).'</label>'."\n";
    echo '</th>'."\n";
    echo '<td>'."\n";

    $email_on_new_blog_active = get_site_option( self::return_email_on_new_blog_active_option_name() );

    echo '<select name="'.self::return_email_on_new_blog_active_option_name().'" id="'.self::return_email_on_new_blog_active_option_name().'">'."\n";
    echo '<option value="1" ';
    if (!empty($email_on_new_blog_active) && ($email_on_new_blog_active == 1)){
        
        echo 'selected="selected"';
        
    } 
    echo ' >';
    _e( 'Yes', self::return_plugin_namespace() );
    
    echo '<option value="0" ';
    if (empty($email_on_new_blog_active) or ($email_on_new_blog_active == 0)){
        
        echo 'selected="selected"';
        
    }
    echo ' >';
    _e( 'No', self::return_plugin_namespace() );
    echo '</option>'."\n";
    echo '</select>'."\n";
    echo '</td>'."\n";
    echo '</tr>'."\n";
    
    if (!empty($email_on_new_blog_active) && ($email_on_new_blog_active == 1)){
        
        echo '<tr valign="top">'."\n";
        echo '<th scope="row">'."\n";
        echo '<label for="'.self::return_new_blog_email_subject_option_name().'">'.__("New site email subject;", self::return_plugin_namespace()).'</label>'."\n";
        echo '</th>'."\n";
        echo '<td>'."\n";
        echo '<input name="'.self::return_new_blog_email_subject_option_name().'" id="'.self::return_new_blog_email_subject_option_name().'" type="text" value="'.self::return_new_blog_email_raw_subject().'">'."\n";
        echo '</td>'."\n";
        echo '</tr>'."\n";
        echo '<tr valign="top">'."\n";
        echo '<th scope="row">'."\n";
        echo '<label for="'.self::return_new_blog_email_content_option_name().'">'.__("New site email content;", self::return_plugin_namespace()).'</label>'."\n";
        echo '</th>'."\n";
        echo '<td>'."\n";
        $settings = array(
            'media_buttons' => false,
            'textarea_name' => self::return_new_blog_email_content_option_name(),
        );
            
        wp_editor( self::return_new_blog_email_raw_content(), self::return_new_blog_email_content_option_name(), $settings);
        echo '<p>Available template tags include: {{recipient.first_name}}, {{recipient.last_name}}, {{recipient.display_name}}, {{recipient.email}}, {{user.user_login}}, {{site.name}}, {{site.url}}, and {{login.url}}.</p>'."\n";
        echo '<p>'.__('Delete the saved values to revert to the default subject and content.', self::return_plugin_namespace()).'</p>'."\n";
        
        echo '</td>'."\n";
        echo '</tr>'."\n";
        
        
        
    }
    
    echo '</table>'."\n";
    submit_button( 'Save Changes');
    echo '</form>'."\n";


?>