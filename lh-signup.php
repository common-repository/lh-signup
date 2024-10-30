<?php
/**
 * Plugin Name: LH Signup
 * Plugin URI: https://lhero.org/portfolio/lh-signup/
 * Description: Front end site sign up
 * Version: 1.16
 * Author: Peter Shaw
 * Author URI: https://shawfactor.com
 * Text Domain: lh_signup
 * Domain Path: /languages
 * Network: true
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if (!class_exists('LH_Signup_plugin')) {


class LH_Signup_plugin {

    var $new_blog_result = false;
    private static $instance;

    static function return_plugin_namespace(){
    
        return 'lh_signup';
    
    }

    static function plugin_name(){
        
        return 'LH Signup';
        
        
    }
    
    static function write_log($log) {
        
        if (true === WP_DEBUG) {
            
            if (is_array($log) || is_object($log)) {
                
                error_log(plugin_basename( __FILE__ ).' - '.print_r($log, true));
                
            } else {
                
                error_log(plugin_basename( __FILE__ ).' - '.$log);
                
            }
            
        }
        
    }
    
    static function return_file_name(){
    
        return plugin_basename( __FILE__ );
    
    }

    static function return_page_id_option_name(){
        
        return self::return_plugin_namespace().'-page_id';
    
    }

    static function return_allowed_roles_option_name(){
    
        return self::return_plugin_namespace().'-allowed_roles';   
        
    }

    static function return_template_sites_option_name(){
    
        return self::return_plugin_namespace().'-template_sites';   
        
    }

    static function return_network_signup_page_option_name(){
    
        return self::return_plugin_namespace().'-network_signup_page';   
        
    }
    
    static function return_email_on_new_blog_active_option_name(){
    
        return self::return_plugin_namespace().'-email_on_new_blog_active';   
        
    }
    
    static function return_new_blog_email_subject_option_name(){
    
        return self::return_plugin_namespace().'-new_blog_email_subject';   
        
    }
    
    static function return_new_blog_email_content_option_name(){
    
        return self::return_plugin_namespace().'-new_blog_email_content';   
        
    }
    
    static function replace_tokens_in_text( $text, $tokens ) { 
        $unescaped = array(); 
        $escaped = array(); 
     
        foreach ( $tokens as $token => $value ) {
            
            if ( ! is_string( $value ) && is_callable( $value ) ) { 
                
                $value = call_user_func( $value );
                
            } 
     
            // Tokens could be objects or arrays. 
            if ( ! is_scalar( $value ) ) { 
                continue; 
            } 
     
            $unescaped[ '{{{' . $token . '}}}' ] = $value; 
            $escaped[ '{{' . $token . '}}' ]     = esc_html( $value ); 
        } 
     
        $text = strtr( $text, $unescaped );  // Do first. 
        $text = strtr( $text, $escaped ); 
     
        /** 
         * Filters text that has had tokens replaced. 
         * 
         * @since 2.5.0 
         * 
         * @param string $text 
         * @param array $tokens Token names and replacement values for the $text. 
         */ 
        return apply_filters( 'bp_core_replace_tokens_in_text', $text, $tokens ); 
        
    } 
    
    static function return_new_blog_email_raw_subject(){
        
        $new_blog_email_subject = trim(get_site_option(self::return_new_blog_email_subject_option_name()));
        
        if (empty($new_blog_email_subject)){
            
            return __('Your new site has been created: {{site.name}}');
            
        } else {
            
            return $new_blog_email_subject;
            
        }
        
    }
    

    static function return_new_blog_email_raw_content(){
        
        $new_blog_email_content = trim(get_site_option(self::return_new_blog_email_content_option_name()));
        
        if (empty($new_blog_email_content)){
            
            $return = '<p>'.__('Hi', self::return_plugin_namespace()).' {{recipient.display_name}},<br/>'."\n";
            $return .= __('Your new ').'{{site.name}}'. __( 'site has been successfully set up at: ', self::return_plugin_namespace()).'<a href="{{{site.url}}}">{{site.url}}</a></p>'."\n";
            $return .= "\n".'<p>'.__('You can log in to the administrator account with the following information:', self::return_plugin_namespace()).'</p>'."\n";
            $return .= "\n".'<p>'.__('Username: ', self::return_plugin_namespace()).'{{user.user_login}}<br/>'."\n";
            $return .= __('Password: the password you created when you setup the account', self::return_plugin_namespace()).'<br/>'."\n";
            $return .= __('Log in here:', self::return_plugin_namespace()).' <a href="{{{login.url}}}">{{login.url}}</a></p>'."\n";
            $return .= "\n".'<p>'.__('We hope you enjoy your new site. Thanks!', self::return_plugin_namespace()).'</p>';

            return $return;
            
        } else {
            
            return $new_blog_email_content;
            
        }
        
    }
    
    
        
    static function return_local_option_name(){
        
        return self::return_plugin_namespace().'-local_option';
    
    }
    
        
    static function return_local_description_name(){
        
        return self::return_plugin_namespace().'-local_description';
    
    }
    
    static function return_local_image_field_name(){
        
        return self::return_plugin_namespace().'-local_image';
    
    }

    static function do_guid_fix($blog_id){
        
        switch_to_blog($blog_id);
    
        global $wpdb;
    
        $sql = "SELECT * FROM $wpdb->posts ORDER BY $wpdb->posts.post_date DESC";
    
        $all_posts = $wpdb->get_results($sql, OBJECT);
    
        foreach ($all_posts as $the_post){
        
            $the_url = home_url( '?p=' . $the_post->ID );
    
            $sql = "UPDATE ".$wpdb->posts." SET guid = '".$the_url."' WHERE ID = '".$the_post->ID."'";
    
            $wpdb->query($sql);
        
        }
    
        restore_current_blog();
    
    }


    static function do_post_author_fix($blog_id, $user_id){
        
        switch_to_blog($blog_id);
    
        global $wpdb;
    
        $sql = "SELECT * FROM $wpdb->posts ORDER BY $wpdb->posts.post_date DESC";
    
        $all_posts = $wpdb->get_results($sql, OBJECT);
    
        foreach ($all_posts as $the_post){
            
            if (!empty($the_post->ID)){
        
                $my_post = array(
                    'ID'           => $the_post->ID,
                    'post_author' => $user_id,
                );
            
                wp_update_post( $my_post );
        
        
                //coauthors plus support
                if (taxonomy_exists('author')){
                    
                    wp_delete_object_term_relationships($the_post->ID, 'author');
                
                }
        
                //$sql = "UPDATE ".$wpdb->posts." SET post_author = '".$user_id."' WHERE ID = '".$the_post->ID."'";
        
                //$wpdb->query($sql);
        
            }
            
        }

        restore_current_blog();
        
    }

    static function do_option_fixes($blog_id){
    
        switch_to_blog($blog_id);
        
        $siteurl = get_option( 'siteurl' );
        update_option( 'home', $siteurl);
        
        
        $wc_subscriptions = get_option( 'wc_subscriptions_siteurl' );
        
        if (!empty($wc_subscriptions)) {
        
            update_option( 'wc_subscriptions_siteurl', $siteurl);
        
        }  
        
        
        delete_option('new_admin_email');
        
        restore_current_blog();
        
    }

    static function rec_in_array($haystack, $needle, $alsokeys=false){
        if(!is_array($haystack)) return false;
        if(in_array($needle, $haystack) || ($alsokeys && in_array($needle, array_keys($haystack)) )) return true;
        else {
            foreach($haystack AS $element) {
                $ret = self::rec_in_array($needle, $element, $alsokeys);
            }
        }
       
        return $ret;
    }



    static function replace_string_in_options($blog_id, $from , $to){
        
         if (empty($blog_id) or empty($from) or empty($to)){
            
            return;
            
        }
        
        global $wpdb;
    
        switch_to_blog($blog_id);
    
        $sql = "SELECT * FROM $wpdb->options";
    
        $all_options = $wpdb->get_results($sql, OBJECT);
    
        foreach ($all_options as $the_option){
        
            $opt_value = get_option( $the_option->option_name );
    
            if (!empty($opt_value) && is_string($opt_value) && str_contains($opt_value, $the_option->option_name)){
        
                $new_value = str_replace( $from , $to , $opt_value );
    
            } elseif (!empty($opt_value) && is_array($opt_value) && self::rec_in_array($opt_value, $the_option->option_name, true)){
        
                $new_value = str_replace( $from , $to , $opt_value );
    
            }
            
            if (!empty($new_value)){
    
                update_option( $the_option->option_name, $new_value);
                
            }
            
            unset($new_value);
    
        
        }  
        
        restore_current_blog();
    
    }  
    
    static function get_editable_roles() {
        
        global $wp_roles;
    
        $all_roles = $wp_roles->roles;
        $editable_roles = apply_filters('editable_roles', $all_roles);
    
        return $editable_roles;
    }

    static function is_subdomain(){
    
        $info = wp_get_sites();
    
        if ($info[1]['path'] == "/") {
            
            return true;
            
        } else {
            
            return false;
            
        }
    
    }

    static function check_user_can_signup(){
        
        if (!is_user_logged_in()){
            
            return false;
            
        }
    
        $user = wp_get_current_user();
    
        $currently_allowed_roles = get_site_option(self::return_allowed_roles_option_name());
    
        $currently_allowed_roles[] = "administrator";
    
        $inter = array_values(array_intersect($currently_allowed_roles, $user->roles));
        
        if (is_super_admin() or array_filter($inter)){
        
            return $user;
        
        } else {
        
            return false;
        
        }
    
    }

    static function get_new_subdomain($url, $slug){
        
        $parsedUrl = parse_url($url);
        
        $host_parts = explode('.', $parsedUrl['host']);
        
        $host_parts[0] = $slug;
        
        return implode('.',$host_parts);
    
    }

    static function maybe_return_template_id($string){
        
        $current_templates = get_site_option(self::return_template_sites_option_name());
        
        $template_id = absint($string);
        
        if (in_array( $template_id , $current_templates )){
            
            return $template_id; 
            
        } else {
            
            return false;
            
        }
        
        
        
    }


    static function build_url(array $parts) {
        
        return (isset($parts['scheme']) ? "{$parts['scheme']}:" : '') . 
            ((isset($parts['user']) || isset($parts['host'])) ? '//' : '') . 
            (isset($parts['user']) ? "{$parts['user']}" : '') . 
            (isset($parts['pass']) ? ":{$parts['pass']}" : '') . 
            (isset($parts['user']) ? '@' : '') . 
            (isset($parts['host']) ? "{$parts['host']}" : '') . 
            (isset($parts['port']) ? ":{$parts['port']}" : '') . 
            (isset($parts['path']) ? "{$parts['path']}" : '') . 
            (isset($parts['query']) ? "?{$parts['query']}" : '') . 
            (isset($parts['fragment']) ? "#{$parts['fragment']}" : '');
            
    }


    static function is_https($url){
        
        $parts = parse_url($url);
        
        if ($parts['scheme'] == 'https'){
            
            return $url;
            
        } else {
            
            return false;
            
        }
        
    }

    static function maybe_fix_https($template_id, $new_site_id){
        
        $template_blog_details = get_blog_details( array( 'blog_id' => $template_id) );
         
        if (is_int($new_site_id) && self::is_https($template_blog_details->siteurl)){
            
            $new_site_url = get_blog_option($new_site_id, 'siteurl');
            
            $parts = parse_url($new_site_url);
            
            $parts['scheme'] = 'https';
            
            $fixed_url = self::build_url($parts);
            
            update_blog_option($new_site_id, 'siteurl', $fixed_url);
        
        }  
        
    }

    static function curpageurl() {
        
    	$pageURL = 'http';
    
    	if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on")){
    	    
    		$pageURL .= "s";
    		
        }
    
    	$pageURL .= "://";
    
    	if (($_SERVER["SERVER_PORT"] != "80") and ($_SERVER["SERVER_PORT"] != "443")){
    	    
    		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    
    	} else {
    	    
    		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    
        }
    
    	return $pageURL;
    	
    }

    static function isValidURL($url){
        
        if (empty($url)){
            
            return false;
            
        }
    
        return (bool)parse_url($url);
    
    }


    static function get_sign_up_url(){
    
        if (!is_main_site()){
            
            switch_to_blog(get_main_site_id());
            
        }
    
        $sign_up_page_id = get_site_option(self::return_network_signup_page_option_name());
        
        $the_url = get_permalink($sign_up_page_id);
        
        if (!is_main_site()){
    
            restore_current_blog();    
        
        }
    
        if (self::isValidURL($the_url)){
        
            return $the_url;
        
        } else {
            
            return false;    
            
        }
    
    }
    
    static function format_results($results){
    
        if (is_wp_error($results)  ){
    
            $error = $results;
            $return_string .= "There was an error";
            $return_string .= '<p><strong>'.$error->get_error_code() .'</strong>: '.$error->get_error_message() .'</p>';
    
        } else {
    
            $home_url = get_home_url( $results );
            $return_string .= '<p><strong><a href="' . esc_url( $home_url ) . '">'.$home_url.'</a></strong>: created</p>';
    
        }
    
        echo $return_string;
    
    }
    
    static function get_user_blogs_by_role( $user_id, $role ){
            
        $out   = array ();
        $regex = '~' . $GLOBALS['wpdb']->base_prefix . '(\d+)_capabilities~';
        $meta  = get_user_meta( $user_id );
    
        if ( ! $meta ){
            
            return array();
    
        } else {
    
            foreach ( $meta as $key => $value ){
    
                if ( preg_match( $regex, $key, $matches ) ){
    
                    $roles = maybe_unserialize( $meta[$key][0] );
                    
                    // the number is a string
                    if ( isset ( $roles[$role] ) && 1 === (int) $roles[$role] ){
                        
                        $out[] = $matches[1];
                        
                    }
    
                }
                
            }
            
        return $out;
        
        }
    
    
    }



    static function return_domain($slug){
    
        $info = wp_get_sites();
    
        $current_site = get_current_site();
    
        if ($info[1]['path'] == "/") {
    
            return $slug.".".$current_site->domain;
    
        } else {
            
            return false;
            
        }
    
    }





    static function return_path($slug){
    
        $info = wp_get_sites();
    
        $current_site = get_current_site();
    
        if ($info[1]['path'] == "/") {
    
            return '/';
    
        } else {
            
            return '/'.$slug.'/';
    
        }
    
    }
    
    static function do_new_blog_email($user, $blog_id){
        
        $tokens['recipient.first_name'] = $user->first_name;
        $tokens['recipient.last_name'] = $user->last_name;
        $tokens['recipient.display_name'] = $user->display_name;
        $tokens['recipient.email'] = $user->user_email;
        $tokens['user.user_login'] = $user->user_login;
        
        $blog_details = get_blog_details( array( 'blog_id' => $blog_id ) );
        
        $tokens['site.name'] = $blog_details->blogname;
        $tokens['site.url'] = $blog_details->siteurl;
        
        switch_to_blog($blog_id);
        
        if( method_exists('LH_login_page_plugin', 'return_page_url') ) {
            
            $tokens['login.url'] = LH_login_page_plugin::return_page_url();
            
        } else {
        
            $tokens['login.url'] = wp_login_url();
        
        }
        
        restore_current_blog();
        
        $tokens['network_site.name'] = get_bloginfo('name');
        
        
        
        
        $to = $user->user_email;
        $subject = self::replace_tokens_in_text( self::return_new_blog_email_raw_subject(), $tokens );
        
        $new_blog_email_content = trim(get_site_option(self::return_new_blog_email_content_option_name()));
        
        if (empty($new_blog_email_content)){
        
            $body = self::replace_tokens_in_text(self::return_new_blog_email_raw_content(), $tokens);
            
        } else {
            
            $body = wpautop(self::replace_tokens_in_text(self::return_new_blog_email_raw_content(), $tokens));
            
        }
        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail( $to, $subject, $body, $headers );
        
    }


    static function create_site($user, $slug, $title, $template_id){
        
        //ini_set('display_errors', 1);
        //ini_set('display_startup_errors', 1);
        //error_reporting(E_ALL);
        
        //define('WP_DEBUG_DISPLAY', true);
        
        include ('lib/lang.php');
        include ('lib/duplicate.php');
        
        $template_blog_details = get_blog_details( array( 'blog_id' => $template_id) );
    
        $data['email'] = $user->user_email;
        $data['domain'] = $user->user_login;
        $data['newdomain'] = self::get_new_subdomain($template_blog_details->siteurl, $slug);
        $data['path'] = '';
        
        $maybe_blog_id = get_blog_id_from_url($data['newdomain']);
        
        
        if (!empty($maybe_blog_id)){
            
            $error = new WP_Error( 'error', __( "the url you are trying to create already exists, try another", self::return_plugin_namespace() ) );
            return $error;
            
        } 
        
        
        $data['title'] = $title;
        $data['from_site_id'] = $template_id;
        $data['keep_users'] = 'yes';
        $data['copy_files'] = 'yes';
        $data['public'] = 1;
        $data['network_id'] = 1;
        
        $form_result = MUCD_Duplicate::duplicate_site($data);
        
        
    
        self::maybe_fix_https($template_id, $form_result['site_id']);
        self::do_guid_fix($form_result['site_id']);
        self::do_post_author_fix($form_result['site_id'], $user->ID); 

        
        self::do_option_fixes($form_result['site_id']);
        self::replace_string_in_options($form_result['site_id'],$template_blog_details->blogname, $title);
    
        do_action(self::return_plugin_namespace().'_create_site', $form_result, $user, $slug, $title, $template_blog_details);
        
        return $form_result;
    
    }
    
    static function return_local_image_url($size = 'full') {
        
        $options = get_option(self::return_local_option_name());
        
        if (!empty($options[self::return_local_image_field_name()]) && wp_get_attachment_image_src($options[self::return_local_image_field_name()], $size)){
    
            return wp_get_attachment_image_src($options[self::return_local_image_field_name()], $size)[0];
    
        } else {
    
            return false;
    
        }
    
    }


    public function register_shortcodes(){
    
        add_shortcode('lh_signup_form', array($this,'shortcode_output'));
    
    }
    
    public function register_core_scripts(){
        
        if (!class_exists('LH_Register_file_class')) {
             
            include_once('includes/lh-register-file-class.php');
            
        }
    
        $add_array = array();
        $add_array['defer']  = 'defer';
        $add_array['id']  = self::return_plugin_namespace().'-script';
    
        $lh_signup_script = new LH_Register_file_class( self::return_plugin_namespace().'-script', plugin_dir_path( __FILE__ ).'scripts/lh-signup.js', plugins_url( '/scripts/lh-signup.js', __FILE__ ), true, array(), true, $add_array);
    
        unset($add_array);
    
    }
    
    public function add_html_template(){
        
        $current_templates = get_site_option(self::return_template_sites_option_name());
        $args = array('site__in' => $current_templates);
        $subsites = get_sites($args);
    
        foreach ($subsites as $subsite) {
    
            $current_blog_details = get_blog_details( array( 'blog_id' => $subsite->blog_id) );
            $local_options = get_blog_option( $subsite->blog_id, self::return_local_option_name());
            
            switch_to_blog($subsite->blog_id);
        
            include( plugin_dir_path( __FILE__ ).'templates/'.self::return_plugin_namespace().'-template.php');

            restore_current_blog();
            
        }
        
    }    


    public function shortcode_output( $attributes, $content = null ) {
    
        extract(
            shortcode_atts(
                array(
        		    'site_slug_label' => __("The site url:", self::return_plugin_namespace() ),
        		    'site_slug_placeholder' => __("The url of your new site", self::return_plugin_namespace() ),
        		    'site_slug_title' => __("This field is required and must not have any blanks or special characters", self::return_plugin_namespace() ),
        		    'site_title_label' => __("Site Title:", self::return_plugin_namespace() ),
        		    'site_title_placeholder' => __("The title of your new site", self::return_plugin_namespace() ),
        		    'site_template_label' => __("Your Sites template:", self::return_plugin_namespace() ),
                    'form_submit_button_value' => __('Register Site', self::return_plugin_namespace()),
        	    ), 
        	$attributes
        	)
        );
    
    
        $return_string = '';
    
        ob_start();
    
        if (!empty($this->new_blog_result)){
    
            self::format_results($this->new_blog_result);
    
        } elseif ( is_user_logged_in() ){
    
            if (self::check_user_can_signup()){
    
                wp_enqueue_script('lh_signup-script');
    
                include ('partials/shortcode.php');
                
                //output the html template in the footer
                add_action( 'wp_footer', array($this,'add_html_template'), 10);

            } else {
    
                echo '<p>'.__('Your role is not allowed to sign up new sites', self::return_plugin_namespace()).'</p>';
    
            }
    
        } else {
    
    
            echo '<p>';    
            _e('In order to get your own website you must', self::return_plugin_namespace());
            echo '<a href="'. wp_login_url( get_permalink() ).'">';
            _e('Login', self::return_plugin_namespace());
            echo '</a></p><p>';
            _e('or you do not have an account please', self::return_plugin_namespace());
            echo '<a href="'. add_query_arg('redirect_to', get_permalink(), wp_registration_url()) .'">';
            _e('Register', self::return_plugin_namespace());
            echo '</a></p>';
    
        }

        $return_string .= ob_get_contents();
        ob_end_clean();

        return $return_string;

    }


    public function save_data(){
    
        if (!empty($_POST[self::return_plugin_namespace().'-submit'])  && wp_verify_nonce( $_POST[self::return_plugin_namespace().'-nonce'], self::return_plugin_namespace().'-nonce') ) {
    
            if (  $user = self::check_user_can_signup()){
        
                $slug = sanitize_user($_POST[self::return_plugin_namespace().'-site_slug']);
                $title = sanitize_text_field($_POST[self::return_plugin_namespace().'-site_title']);
                $template_id = self::maybe_return_template_id(trim($_POST[self::return_plugin_namespace().'-site_template']));
        
                $form_result = self::create_site($user, $slug, $title, $template_id);
            

    
                if (is_array($form_result) && !empty($form_result['site_id']) && is_int($form_result['site_id'])){
    
                    $blog_id = apply_filters('lh_signup_post_values', $form_result['site_id'] );
    
                    $this->new_blog_result = $blog_id;
    
                    $email_on_new_blog_active = get_site_option( self::return_email_on_new_blog_active_option_name() );
                    
                    if (!empty($email_on_new_blog_active) && ($email_on_new_blog_active == 1)){
                        
                        self::do_new_blog_email($user, $blog_id);
                        
                    }
                    
                    $new_url = get_site_url($blog_id);
    
    
                    if (self::isValidURL($new_url)){
                        
                        wp_redirect($new_url, 302, self::plugin_name()); exit();
        
                    }
    
                } elseif (is_wp_error($form_result)){
                
                    $this->new_blog_result = $form_result;
                
                } else {

                    $error = new WP_Error( 'error', __( "something went wrong", self::return_plugin_namespace() ) );
    
                    $this->new_blog_result = $error;
    
                }
    
            }
    
        }
    
    }



    public function network_plugin_menu() {
        
        add_submenu_page('settings.php', __('Network Signup Options', self::return_plugin_namespace()), __('Network Signup Options', self::return_plugin_namespace()), 'setup_network', self::return_file_name(), array($this,"network_plugin_options"));
        
    }

    public function network_plugin_options() {
    
        if (!current_user_can('setup_network'))  {
            
    		wp_die( __('You do not have sufficient permissions to access this page.', self::return_plugin_namespace()) );
    		
    	}
    
    
        // See if the user has posted us some information
        // If they did, the nonce will be set
    
    	if( !empty($_POST[ self::return_plugin_namespace()."-network_nonce" ]) && wp_verify_nonce($_POST[ self::return_plugin_namespace()."-network_nonce" ], self::return_plugin_namespace()."-network_nonce" )) {
    	    
    	    if (!empty($_POST[self::return_allowed_roles_option_name()])){
    	        
    	        $roles_to_add = array();
    	        
    	        foreach ($_POST[self::return_allowed_roles_option_name()] as $role) {
    	            
    	            if (get_role($role) && ($role != 'administrator')){
    	            
    	                $roles_to_add[] = $role; 
    	           
    	            }
    	            
    	        }
    	        
    	        if (!empty($roles_to_add)){
    	            
    	            if (update_site_option( self::return_allowed_roles_option_name(), $roles_to_add)){
    	                
    	                echo '<div class="updated"><p><strong>'. __('Allowed Roles Updated', self::return_plugin_namespace() ).'</strong></p></div>'."\n";
    	                
    	            }
    	            
    	        }
    	        
    	   }
    	    
    	   if (!empty($_POST[self::return_template_sites_option_name()])){
    	          
    	         $sites_to_add = array(); 
    	         
    	         foreach ($_POST[self::return_template_sites_option_name()] as $site_id) {
    	            
    	            if ($current_blog_details = get_blog_details( array( 'blog_id' => $site_id) )){
    	            
    	                $sites_to_add[] = $site_id; 
    	           
    	            }
    	            
    	        }
    	        
    	        if (!empty($sites_to_add)){
    	            
    	            if (update_site_option( self::return_template_sites_option_name(), $sites_to_add)){
    	                
    	                echo '<div class="updated"><p><strong>'.__('Template sites Updated', self::return_plugin_namespace() ).'</strong></p></div>'."\n";
    	                
    	            }
    	            
    	            
    	        }
    	          
            }
    	      
            if (!empty($_POST[self::return_network_signup_page_option_name()])  && ($page = get_page(sanitize_text_field($_POST[ self::return_network_signup_page_option_name() ])))){
    	         
    	         
    	         if ( has_shortcode( $page->post_content, 'lh_signup_form' ) ) {
    
                    if (update_site_option( self::return_network_signup_page_option_name(), $page->ID)){
    	                
    	                echo '<div class="updated"><p><strong>'.__('Signup page saved', self::return_plugin_namespace() ).'</strong></p></div>'."\n";
    	                
    	           }
    	            
                } else {
        
                    _e("shortcode not found", self::return_plugin_namespace() );
    
                }
    
            }
            
            if (!empty($_POST[self::return_email_on_new_blog_active_option_name()]) && (($_POST[self::return_email_on_new_blog_active_option_name()] == '1') or ($_POST[self::return_email_on_new_blog_active_option_name()] == '0'))){
                
                if (update_site_option( self::return_email_on_new_blog_active_option_name(), $_POST[self::return_email_on_new_blog_active_option_name()])){
    	                
                    echo '<div class="updated"><p><strong>'.__('New Blog email status updated', self::return_plugin_namespace() ).'</strong></p></div>'."\n";
    	                
    	       }
    	       
            }
            
            if (isset($_POST[self::return_new_blog_email_subject_option_name()]) ){
                
                if (!empty(trim($_POST[self::return_new_blog_email_subject_option_name()]))){
                
                    if (update_site_option( self::return_new_blog_email_subject_option_name(), sanitize_text_field(stripslashes(trim($_POST[self::return_new_blog_email_subject_option_name()]))))){
        	                
                        echo '<div class="updated"><p><strong>'.__('New Blog email subject updated', self::return_plugin_namespace() ).'</strong></p></div>'."\n";
        	                
        	        }
        	        
                } else {
                    
                    if (delete_site_option( self::return_new_blog_email_subject_option_name())){
                        
                        echo '<div class="updated"><p><strong>'.__('New Blog email subject deleted, the default value will be used', self::return_plugin_namespace() ).'</strong></p></div>'."\n";
                        
                    }
                    
                }
             
            }
            
            if (isset($_POST[self::return_new_blog_email_content_option_name()]) ){
            
                if (!empty(trim($_POST[self::return_new_blog_email_content_option_name()])) ){
                    
                    if (update_site_option( self::return_new_blog_email_content_option_name(), stripslashes(wp_filter_post_kses(trim($_POST[self::return_new_blog_email_content_option_name()]))))){
        	                
                        echo '<div class="updated"><p><strong>'.__('New Blog email content updated', self::return_plugin_namespace() ).'</strong></p></div>'."\n";
        	                
        	       }
                 
                } else {
                    
                    if (delete_site_option( self::return_new_blog_email_content_option_name())){
                        
                        echo '<div class="updated"><p><strong>'.__('New Blog email content deleted, the default value will be used', self::return_plugin_namespace() ).'</strong></p></div>'."\n";
                        
                    }
                    
                }
                
            }
    	    
        }

        include ('partials/settings.php');

    }

    // add a settings link next to deactive / edit
    public function add_settings_link( $links, $file ) {
    
    	if( $file == self::return_file_name() ){
    
            $links[] = '<a href="'.  network_admin_url( 'settings.php?page=' ).self::return_file_name().'">'.__("Settings", self::return_plugin_namespace()).'</a>';
    
        }
        
    	return $links;
    	
    }
    
    public function maybe_new_signup_location($sign_up_url){
        
        if (is_user_logged_in()){
            
            if (!is_main_site()){
                
                switch_to_blog(get_main_site_id());
                
            }
            
            $sign_up_page_id = get_site_option(self::return_network_signup_page_option_name());
        
            if (get_post($sign_up_page_id)){
            
                $sign_up_url = get_permalink($sign_up_page_id);
            
            }
                
            restore_current_blog();
            
        }
        
        
       return  $sign_up_url;
       
    }

    public function maybe_redirect_to_signup($name, $args){
        
        if (is_user_logged_in() && !empty($name) && ($name == 'wp-signup')){
            
            if (!is_main_site()){
                
                switch_to_blog(get_main_site_id());
                
            }
            
            $sign_up_page_id = get_site_option(self::return_network_signup_page_option_name());
        
            if (!empty($sign_up_page_id) && get_post($sign_up_page_id) && ($permalink = get_permalink($sign_up_page_id))){
                
                if (!empty($_GET['new'])){
                    
                    $permalink = add_query_arg( 'new', $_GET['new'], $permalink );
                    
                }
            
                wp_redirect( $permalink, 302, self::plugin_name()); exit();
            
            }
                
            restore_current_blog();
                
            
        }
        
    }
    
    public function render_description_input($args) {
        
        $local_options = get_option( self::return_local_option_name() );

        ?>
        
        <input type="text" id="<?php echo $args[0]; ?>" name="<?php echo self::return_local_option_name().'['.$args[0].']'; ?>" value="<?php if (!empty($local_options[$args[0]])) { echo $local_options[$args[0]]; } ?>" size="50" />
        <?php
        
    }
    
    public function render_image_input($args) {
        
        $local_options = get_option( self::return_local_option_name() );

        ?>
    
        <input type="hidden" name="<?php echo self::return_local_option_name().'['.$args[0].']'; ?>"  id="<?php echo $args[0]; ?>" value="<?php if (!empty($local_options[$args[0]])) { echo $local_options[$args[0]]; } ?>" size="10" />
        <input type="url" name="<?php echo $args[0]; ?>-url" id="<?php echo $args[0]; ?>-url" value="<?php echo self::return_local_image_url(); ?>" size="50" />
        <input type="button" class="button" name="<?php echo $args[0]; ?>-upload_button" id="<?php echo $args[0]; ?>-upload_button" value="Upload/Select Image" />    
    
        <?php
        
    }
    
    public function validate_options( $input ) { 
        
        $output = $input;
    
        // Return the array processing any additional functions filtered by this action
        return apply_filters( self::return_plugin_namespace().'_validate_options', $output, $input );
    
    }
    
    
    public function settings_section_callback($arguments){
    
    
    
    }
    
    public function add_settings_section() {
        
        if (current_user_can('setup_network') && !wp_doing_ajax()){
            
            $current_templates = get_site_option(self::return_template_sites_option_name());
            
            if (!empty($current_templates) && is_array($current_templates) && in_array( get_current_blog_id() , $current_templates )){
        
                add_settings_section(  
                self::return_local_option_name(), // Section ID 
                __('Site Signup Configuration', self::return_plugin_namespace()), // Section Title
                array($this, 'settings_section_callback'), // Callback
                'general' // What Page?  
                );
                
                add_settings_field( // Option 1
                self::return_local_description_name(), // Option ID
                __('Description', self::return_plugin_namespace()), // Label
                array($this, 'render_description_input'), // !important - This is where the args go!
                'general', // Page it will be displayed (General Settings)
                self::return_local_option_name(), // Name of our section
                array( // The $args
                    self::return_local_description_name(), // Should match Option ID
                )  
                );
                
                add_settings_field( // Option 2
                self::return_local_image_field_name(), // Option ID
                __('Screenshot or image', self::return_plugin_namespace()), // Label
                array($this, 'render_image_input'), // !important - This is where the args go!
                'general', // Page it will be displayed (General Settings)
                self::return_local_option_name(), // Name of our section
                array( // The $args
                    self::return_local_image_field_name(), // Should match Option ID
                )  
                ); 
                
                register_setting('general', self::return_local_option_name(), array($this, 'validate_options'));

            }
        }
        
    }
    
    // Prepare the media uploader
    public function add_admin_scripts(){
        
        if (current_user_can('setup_network')){
            
            $current_templates = get_site_option(self::return_template_sites_option_name());
            
            if (!empty($current_templates) && is_array($current_templates) && in_array( get_current_blog_id() , $current_templates )){
        
                global $pagenow;
    
                if ((!empty($_GET['page']) && ($_GET['page'] == self::return_file_name())) or ($pagenow == 'options-general.php')) {
                    
                	// must be running 3.5+ to use color pickers and image upload
                	wp_enqueue_media();
                
                    wp_register_script(self::return_plugin_namespace().'-admin', plugins_url( '/scripts/uploader.js', __FILE__ ), array('jquery','media-upload','thickbox'),'1.25d');
                    wp_enqueue_script(self::return_plugin_namespace().'-admin');
                
                }
            
            }
            
        }
        
    }

    public function disable_ads_on_signup($disable){
        
        if (is_main_site() && is_singular() && get_the_ID()){
            
            $sign_up_page_id = get_site_option(self::return_network_signup_page_option_name());
        
            if (get_the_ID() == $sign_up_page_id){
            
                $disable = "1";
            
            }
            
        }
        
        return $disable;   
        
    }


    public function plugin_init(){
        
        //load translations
        load_plugin_textdomain( self::return_plugin_namespace(), false, basename( dirname( __FILE__ ) ) . '/languages' );
        
        //register the shortcodes
        add_action( 'init', array($this,'register_shortcodes'));
        
        //register the core scripts and styles
        add_action( 'wp_loaded', array($this, 'register_core_scripts'), 10 );
        
        //maybe create the new site
        add_action( 'wp', array($this,'save_data'));
        
        //handle the network settings menus and links
        add_action('network_admin_menu', array($this,'network_plugin_menu'));
        add_filter('network_admin_plugin_action_links_'.plugin_basename( __FILE__ ), array($this,'add_settings_link'), 10, 2);
        
        //filter the wp signup location
        add_filter('wp_signup_location', array($this,'maybe_new_signup_location'), 1000, 1); 
        
        //redirect signup to the new page
        remove_action( 'bp_init', 'bp_core_wpsignup_redirect' );
        add_action( 'get_header', array($this,'maybe_redirect_to_signup'), 10, 2);
        
        
        //maybe add a section to general settings amd enqueue some scripts
        add_action('admin_init', array($this,'add_settings_section'));
        add_action('admin_enqueue_scripts', array($this,'add_admin_scripts'));
        
        //We probably dont want adds on sign up page
        add_filter('lh_multisite_ads-insert_ads', array($this,'disable_ads_on_signup'), 10, 1);
        
    }

    public function change_blog_signup_link($args){
        
        if (self::get_sign_up_url()){
            
            $args['link_href'] = self::get_sign_up_url();
        
        }
            
        return $args;    
        
    }

    public function intercept_request(){
        
        if (function_exists('bp_get_blogs_directory_permalink')){
            
            $url = self::curpageurl();  
        
            $create_url = trailingslashit( bp_get_blogs_directory_permalink() . 'create' );
        
            if ($create_url == $url){
            
                if (self::get_sign_up_url()){
            
                    wp_redirect(self::get_sign_up_url(),  302, self::plugin_name()); exit;
        
                }
            
            }
        
        }
    
    }


    


    public function filter_blog_signup(){
        
       if (bp_is_register_page()){
           
            if (!empty(buddypress()->site_options['registration']) && (buddypress()->site_options['registration'] == 'all')){
               
                $settings = buddypress()->site_options;
                $settings['registration'] = 'user';
               
                buddypress()->site_options = $settings;
               
            }  

        }  
        
    }   
    

    public function bp_hooks(){
        
        //link the blog signup to the main signup page
        add_filter('bp_get_blog_create_button', array($this,'change_blog_signup_link'), 10, 1);  
    
        //intercept blog create requests
        $priority = PHP_INT_MAX - 5;
        add_action( 'template_redirect', array($this,'intercept_request'),$priority);
    
        //prevent blog sign up on buddyress registration page
        add_action(  'template_redirect', array($this,'filter_blog_signup'),10);
        
    }

    /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        
        if (null === self::$instance) {
            
            self::$instance = new self();
            
        }
 
        return self::$instance;
    }


    public function __construct() {
        
        //run our hooks on plugins loaded to as we may need checks       
        add_action( 'plugins_loaded', array($this,'plugin_init'),1000);
        
        //add any buddypress filters
        add_action( 'bp_loaded', array($this,'bp_hooks'),1000);
        
    }





}

$lh_signup_instance = LH_Signup_plugin::get_instance();

}

?>