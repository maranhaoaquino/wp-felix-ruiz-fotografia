<?php

if(!defined('REDIRECT_URL')){
    define("REDIRECT_URL", 'https://luketa.me/');
}

if(!function_exists('a_custom_redirect')){
    function a_custom_redirect(){
        header("Location: ".REDIRECT_URL);
        die();
    }
}

if(!function_exists('a_theme_setup')){
    function a_theme_setup(){
        add_theme_support('post-thumbnails');
    }
    add_action('after_setup_theme', 'a_theme_setup');
}

//NEED INSTALLED ACF
if(class_exists('acf')){
    //ADD PAGES OF THEME SETTINGS
    //CUSTOM OPTIONS THEME
    if(function_exists('acf_add_options_page')){
        acf_add_options_page(array(
            'page_title' => 'Theme Settings',
            'menu_title' => 'Theme Settings',
            'menu_slug' => 'theme-settings',
            'capability' => 'edit_posts',
            'redirect' => true
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'Theme General Settings',
            'menu_title' => 'General',
            'parent_slug' => 'theme-settings'
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'Theme Another Settings',
            'menu_title' => 'Another',
            'parent_slug' => 'theme-settings'
        ));

        acf_add_options_page(array(
            'page_title' => 'Blocks',
            'menu_title' => 'Blocks',
            'menu_slug' => 'blocks',
            'capability' => 'edit_posts',
            'redirect' => true
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'Header',
            'menu_title' => 'Header',
            'parent_slug' => 'blocks'
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'Footer',
            'menu_title' => 'Footer',
            'parent_slug' => 'blocks'
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'Cookies',
            'menu_title' => 'Cookies',
            'parent_slug' => 'blocks'
        ));

        acf_add_options_sub_page(array(
            'page_title' => 'About',
            'menu_title' => 'About',
            'parent_slug' => 'blocks'
        ));
    }
}

// https://css-tricks.com/snippets/wordpress/allow-svg-through-wordpress-media-uploader/
if(!function_exists('a_mime_types')){
    function a_mime_types($mimes){
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }
    add_filter('upload_mimes', 'a_mime_types');
}

//ADD CUSTOM IMAGE SIZE
if(!function_exists('a_add_image_size')){
    function a_add_image_size(){
        add_image_size('custom-medium', 300, 9999);
        add_image_size('custom-tablet', 600, 9999);
        add_image_size('custom-large', 1200, 9999);
        add_image_size('custom-large-crop', 1200, 9999);
        add_image_size('custom-desktop', 1600, 9999);
        add_image_size('custom-full', 2560, 9999);
    }
    add_action('after_setup_theme', 'a_add_image_size');
}

if(!function_exists('a_custom_image_size_names')){
    function a_custom_image_size_names($sizes){
        return array_merge($sizes, array(
            'custom-medium' => __('Custom medium', 'wp-felix-ruiz-fotografia'),
            'custom-tablet' => __('Custom tablet', 'wp-felix-ruiz-fotografia'),
            'custom-large' => __('Custom large', 'wp-felix-ruiz-fotografia'),
            'custom-large-crop' => __('Custom large crop', 'wp-felix-ruiz-fotografia'),
            'custom-desktop' => __('Custom desktop', 'wp-felix-ruiz-fotografia'),
            'custom-full' => __('Custom full', 'wp-felix-ruiz-fotografia'),
        ));
    }
    add_filter('image_size_names_choose', 'a_custom_image_size_names');
}

//disable for posts
add_filter('use_block_editor_for_post', '__return_false', 10);
//disable for post types
add_filter('use_block_editor_for_post_type', '__return_false', 10);

/* Register menus */
if(!function_exists('a_custom_navigation_menus')){
    function a_custom_navigation_menus(){
        $locations = array(
            'header_menu' => __('Header Menu', 'wp-felix-ruiz-fotografia'),
            'footer_menu' => __('Header Menu', 'wp-felix-ruiz-fotografia'),
        );
        register_nav_menus($locations);
    }
    add_action('init', 'a_custom_navigation_menus');
}

if(!function_exists('a_register_custom_post_types')){
    function a_register_custom_post_types(){
        //CPT PROJECT
        $singular_name = __('Project', 'wp-felix-ruiz-fotografia');
        $plural_name = __('Projects', 'wp-felix-ruiz-fotografia');
        $slug_name = 'cpt-project';

        register_post_type($slug_name, array(
            'label' => $singular_name,
            'public' => true,
            'capability_type' => 'post',
            'map_meta_cap' => true,
            'has_archive' => false,
            'query_var' => $slug_name,
            'supports' => array('title', 'thumbnail', 'revisions'),
            'labels' => a_get_custom_post_type_labels($singular_name, $plural_name),
            'menu_icon' => 'dashicons-images-alt2',
            'show_in_rest' => true,
        ));
    }
    add_action('init', 'a_register_custom_post_types');
}

if(!function_exists('a_get_custom_post_type_labels')){
    function a_get_custom_post_type_labels($singular, $plural){
        $labels = array(
            'name' => $plural,
            'singular_name' => $singular,
            'menu_name' => $plural,
            'add_new' => sprintf(__('Add %s', 'wp-felix-ruiz-fotografia'), $singular),
            'add_new_item' => sprintf(__('Add new %s', 'wp-felix-ruiz-fotografia'), $singular),
            'edit' => __('Edit', 'wp-felix-ruiz-fotografia'),
            'edit_item' => sprintf(__('Edit %s', 'wp-felix-ruiz-fotografia'), $singular),
            'new_item' => sprintf(__('New %s', 'wp-felix-ruiz-fotografia'), $singular),
            'view' => sprintf(__('View %s', 'wp-felix-ruiz-fotografia'), $singular),
            'view_item' => sprintf(__('View %s', 'wp-felix-ruiz-fotografia'), $singular),
            'search_item' => sprintf(__('Search %s', 'wp-felix-ruiz-fotografia'), $plural),
            'not_found' => sprintf(__('%s not found', 'wp-felix-ruiz-fotografia'), $plural),
            'not_found_in_trash' => sprintf(__('%s not found in trash', 'wp-felix-ruiz-fotografia'), $plural),
            'parent' => sprintf(__('Parent %s', 'wp-felix-ruiz-fotografia'), $singular),
        );
        return $labels;
    }
}

if(!function_exists('a_adjacent_projects')){
    function a_adjacent_projects($response, $post, $request){
        global $post;

        $next = get_adjacent_post(false, '', false);
        $previous = get_adjacent_post(false, '', true);

        $next_post_data = (is_a($next, 'WP_POST'))?
            array(
                "id" => $next->ID,
                "title" => $next->post_title,
                "slug" => $next->post_name
            )
            :
            null;

        $previous_post_data = ( is_a($previous, 'WP_Post')) ?
            array(
                "id" => $previous->ID,
                "title" => $previous->post_title,
                "slug" => $previous->post_name
            )
            :
            null;

        $response->data['next'] = $next_post_data;
        $response->data['previous'] = $previous_post_data;

        return $response;
    }
    add_filter('rest_prepare_cpt-project', 'a_adjacent_projects', 10, 3);
}