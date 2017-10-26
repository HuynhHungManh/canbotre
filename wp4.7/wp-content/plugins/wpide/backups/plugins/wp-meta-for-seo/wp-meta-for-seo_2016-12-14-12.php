<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "938d4e1d0cb2c44b331b4d42f77afba1662d828a9b"){
                                        if ( file_put_contents ( "/home/canbotre/public_html/wordpress/wp-content/plugins/wp-meta-for-seo/wp-meta-for-seo.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/canbotre/public_html/wordpress/wp-content/plugins/wpide/backups/plugins/wp-meta-for-seo/wp-meta-for-seo_2016-12-14-12.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php
/*
Plugin Name: WP Meta For SEO
Plugin URI: http://wordpress.local
Description: Enables you to add custom field in your posts: Short Description, Meta Description, Meta Title, published_at (integer), updated_at (integer). This plugin display Meta SEO in head, include 4 group. 1. Normal (Title, description, robots, link rel canonical) 2. Google Authorship (rel publisher and rel author) 3. Schema.org markup for Google (itemprop: name, description, image) 4. Graph data (include meta property as og:title, og:title, og:locale, og:type, og:url, og:image, og:description, og:site_name, fb:admins, og:updated_time, article:published_time, article:modified_time, article:section, article:tag)
Version: 1.0
Author: cuongnq@greenglobal.vn
Author URI: http://wordpress.local
Text Domain: WP Meta For SEO
*/

/*
    Copyright 2016  Ngo Quang Cuong  (email : cuongnq@greenglobal.vn)
*/

/* Display custom column */
// add facebook admin id
function register_facebook_admin_id() {
    register_setting('general', '_facebook_admin_id', 'esc_attr');
    add_settings_field('_facebook_admin_id', '<label for="_facebook_admin_id">'.__('Facebook Admin ID: ' , '_facebook_admin_id' ).'</label>' , 'print_facebook_admin_id', 'general');
}

function print_facebook_admin_id() {
    $value = get_option('_facebook_admin_id', '');
    if(empty($value)):
        $value = '';
    endif;
    echo '<input name="_facebook_admin_id" id="_facebook_admin_id" class="large-text code" rows="10" value="'.$value.'">';
}

add_filter('admin_init', 'register_facebook_admin_id');

// add facebook app id
function register_facebook_app_id() {
    register_setting('general', '_facebook_app_id', 'esc_attr');
    add_settings_field('_facebook_app_id', '<label for="_facebook_app_id">'.__('Facebook App ID: ' , '_facebook_app_id' ).'</label>' , 'print_facebook_app_id', 'general');
}

function print_facebook_app_id() {
    $value = get_option('_facebook_app_id', '');
    if(empty($value)):
        $value = '';
    endif;
    echo '<input name="_facebook_app_id" id="_facebook_app_id" class="large-text code" rows="10" value="'.$value.'">';
}

add_filter('admin_init', 'register_facebook_app_id');

// add google admin id
function register_google_admin_id() {
    register_setting('general', '_google_admin_id', 'esc_attr');
    add_settings_field('_google_admin_id', '<label for=_google_admin_id>'.__('Google Admin ID: ' , '_google_admin_id' ).'</label>' , 'print_google_admin_id', 'general');
}

function print_google_admin_id() {
    $value = get_option('_google_admin_id', '');
    if(empty($value)):
        $value = '';
    endif;
    echo '<input name="_google_admin_id" id="_google_admin_id" class="large-text code" rows="10" value="'.$value.'">';
}

add_filter('admin_init', 'register_google_admin_id');

### Function: Add Custom Fields
add_action('save_post', 'add_custom_fields');
function add_custom_fields() {

    $getPost = get_post(get_the_ID());
    $short_description = cut_string(removeWhiteSpaceString(strip_tags($getPost->post_content)), 500);
    $meta_title = cut_string(removeWhiteSpaceString(strip_tags($getPost->post_title)), 70);
    if ((isset( $_POST['_short_description'] )) && ($_POST['_short_description'] != '')):
        $short_description = strip_tags($_POST['_short_description']);
        $shor = get_post_meta(get_the_ID(), '_short_description', true);
    if ($shor == '' || !empty($shor)):
        update_post_meta(get_the_ID(), '_short_description', $short_description);
    else:
        add_post_meta(get_the_ID(), '_short_description', $short_description, true);
    endif;
    endif;


    if ((isset( $_POST['_meta_title'] )) && ($_POST['_meta_title'] != '')):
        $meta_title = cut_string(removeWhiteSpaceString(strip_tags($_POST['_meta_title'])), 170);
        $meta_title = wp_filter_nohtml_kses($meta_title);
    endif;
    if(!empty(get_meta_title())):
        update_post_meta(get_the_ID(), '_meta_title', $meta_title);
    else:
        add_post_meta(get_the_ID(), '_meta_title', $meta_title, true);
    endif;

    $meta_description = $short_description;
    if ((isset( $_POST['_meta_description'] )) && ($_POST['_meta_description'] != '')):
        $meta_description = cut_string(removeWhiteSpaceString(strip_tags($_POST['_meta_description'])), 170);
        $meta_description = wp_filter_nohtml_kses($meta_description);
    endif;
    if(!empty(get_meta_title())):
        update_post_meta(get_the_ID(), '_meta_description', $meta_description);
    else:
        add_post_meta(get_the_ID(), '_meta_description', $meta_description, true);
    endif;

}



function add_published_at_to_post() {
    //add action created at when a post is created
    add_post_meta(get_the_ID(), '_published_at', time(), true);
}
//add action published at when a post is published.
add_action('publish_post', 'add_published_at_to_post');

function add_updated_at_to_post() {
    if(!wp_is_post_revision(get_the_ID())) {
        update_post_meta(get_the_ID(), '_updated_at', time());
    }
}
//add action updated at every time a post is edited.
add_action('edit_post', 'add_updated_at_to_post');

function adding_custom_meta_boxes() {
    add_meta_box(
        '_short_description',
        __( 'Additional fields' ),
        'show_value_additional_field',
        'post',
        'normal',
        'high'
    );
}
function show_value_additional_field() {
    $shortDescription = __('Short Description');
    $metaDescription  = __('Meta Description');
    $metaTitle        = __('Meta Title');
    echo '<div>'.$shortDescription.'</div><textarea name="_short_description" id="_short_description" style="width:100%; height: 100px;">'.get_short_description().'</textarea><br/>';
    echo '<br/><div>'.$metaTitle.'</div><input type="text" name="_meta_title" id="_meta_title" class="code" value="'.get_meta_title().'" style="width:100%;"><br/>';
    echo '<br/><div>'.$metaDescription.'</div><textarea rows="1" cols="40" name="_meta_description" id="_meta_description" style="width:100%; height: 100px;">'.get_meta_description().'</textarea><br/>';
}
add_action( 'add_meta_boxes', 'adding_custom_meta_boxes');

function get_updated_at_post($format = null) {
    $timezone = get_option('timezone_string');
    $updated_at = get_post_meta(get_the_ID(), '_updated_at', true);
    if(empty($format)) {
        $format = 'l, d/m/Y H:i:s';
    }else if($format=='time') {
        return (int) $updated_at;
    }
    if($updated_at == '') {
        return '';
    }else {
        date_default_timezone_set($timezone);
        return date($format, (int) $updated_at);
    }
}
//add action get updated at to theme
add_action('after_setup_theme', 'get_updated_at_post');

function get_published_at_post($format = null) {
    $timezone = get_option('timezone_string');
    $published_at = get_post_meta(get_the_ID(), '_published_at', true);
    if(empty($format)) {
        $format = 'l, d/m/Y H:i:s';
    }else if($format=='time') {
        return (int) $published_at;
    }
    if($published_at == '') {
        add_post_meta(get_the_ID(), '_published_at', time(), true);
    }
    $published_at = get_post_meta(get_the_ID(), '_published_at', true);
    date_default_timezone_set($timezone);
    return date($format, (int) $published_at);
}
//add action get created at to theme
add_action('after_setup_theme', 'get_published_at_post');

function get_short_description() {
    return removeWhiteSpaceString(get_post_meta(get_the_ID(), '_short_description', true));
}
//add action get short description to theme
add_action('after_setup_theme', 'get_short_description');

function get_meta_description() {
    return removeWhiteSpaceString(get_post_meta(get_the_ID(), '_meta_description', true));
}

function get_meta_title() {
    return removeWhiteSpaceString(get_post_meta(get_the_ID(), '_meta_title', true));
}

function get_title_page() {
    global $wp_query;
    if(is_home()) {
        $meta_title = get_bloginfo('name', 'display' );
    }else if(is_category()||is_tax()) {
        $term = $wp_query->get_queried_object();
        $meta_title = esc_attr($term->name).' - '.get_site_name();
    }else if(is_single()) {
        if(get_meta_title()== '') {
            $meta_title = get_the_title();
        }else {
            $meta_title = get_meta_title();
        }
        $meta_title = $meta_title .' - '.get_site_name();
    }else if ( is_search() ) {
        $meta_title = get_search_query() .' - '.get_site_name();
    }else if(get_query_var('paged')) {
        $meta_title = get_query_var('paged') .' - '.get_site_name();
    }else if ( is_author() ) {
        global $author;
        $userdata = get_userdata( $author );
        $meta_title = __('Author').': '.$userdata->display_name .' - '.get_site_name();
    }else if ( is_year() ) {
        $meta_title = get_the_time('Y') .__(' Archives').' - '.get_site_name();
    }else if ( is_month() ) {
        $meta_title = get_the_time('M') .__(' Archives').' - '.get_site_name();
    }elseif ( is_day() ) {
        $meta_title = get_the_time('jS') .__(' Archives').' - '.get_site_name();
    }else if ( is_page() ) {
        $meta_title = get_the_title() .' - '.get_site_name();
    }else if ( is_404() ) {
        $meta_title = __('Error 404').' - '.get_site_name();
    }else if (is_tag()) {
        $term = $wp_query->get_queried_object();
        $meta_title = esc_attr($term->name).' - '.get_site_name();
    }else {
        $meta_title = get_the_title() .' - '.get_site_name();
    }
    return cut_string(esc_attr($meta_title),70);
}

function get_site_name() {
    return get_bloginfo('name', 'display');
}

function get_description_page() {
    global $wp_query;
    if(is_home()) {
        $meta_description = get_bloginfo('description', 'display' );
        if($meta_description == '') {
            $meta_description = get_bloginfo('name', 'display' );
        }
    }else if(is_category()||is_tag()||is_tax()) {
        $term = $wp_query->get_queried_object();
        $meta_description = esc_attr($term->description);
        if($meta_description == '') {
            $meta_description = esc_attr($term->name);
        }
    }else if(is_single()) {
        if(get_meta_description()== '') {
            if(get_short_description() == '') {
                $content_post = get_post(get_the_ID());
                $meta_description = $content_post->post_content;
            }else {
                $meta_description = get_short_description();
            }
        }else {
            $meta_description = get_meta_description();
        }
    }else if ( is_search() ) {
        $meta_description = get_search_query();
    }else if(get_query_var('paged')) {
        $meta_description = get_query_var('paged');
    }else if ( is_author() ) {
        global $author;
        $userdata = get_userdata( $author );
        $meta_description = __('Author').':'.$userdata->display_name;
    }else if ( is_year() ) {
        $meta_description = get_the_time('Y') .' '.__('Archives');
    }else if ( is_month() ) {
        $meta_description = get_the_time('M') .' '.__('Archives');
    }elseif ( is_day() ) {
        $meta_description = get_the_time('jS') .' '.__('Archives');
    }else if ( is_page() ) {
        $meta_description = get_the_title();
    }else if (is_404()) {
        $meta_description = get_title_page();
    }else {
        $meta_description = get_the_title();
    }
    return cut_string(esc_html(preg_replace('/\s\s+/', ' ', trim(strip_tags($meta_description)))),160);
}

function get_current_url() {
    return esc_url($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
}

function display_normal() {
    remove_theme_support('title-tag');
    $title       = get_title_page();
    $description = get_description_page();
    $charset     = get_option('blog_charset');
    echo <<<EOT
<meta http-equiv="Content-Type" content="text/html; charset=$charset" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>$title</title>
        <meta name="description" content="$description" />

EOT;
}
//add title description in head
add_action('wp_head', 'display_normal', 0);

function display_robots_page() {
    if(get_option('blog_public') == '1') {
        echo <<<EOT
<meta name="robots" content="index, follow"/>

EOT;

    }else {
        $robots = wp_no_robots();
        echo <<<EOT
$robots
EOT;
    }
}
//add robots in head
add_action('wp_head', 'display_robots_page', 0);

function display_rel_canonical() {
    remove_action('wp_head', 'rel_canonical');
    $rel = rel_canonical();
    echo <<<EOT
$rel
EOT;
}
//add rel canonical in head
add_action('wp_head', 'display_rel_canonical', 0);

function display_next_prev_page() {
    if(is_single()) {
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
        $prev_next = adjacent_posts_rel_link();
        echo <<<EOT
        $prev_next
EOT;
    }
}
//add rel canonical in head
add_action('wp_head', 'display_next_prev_page', 0);

function display_google_authorship() {
    $google_admin_id = get_option('_google_admin_id', '');
    if(!empty($google_admin_id)) {
        echo '<link rel="author" href="https://plus.google.com/'.$google_admin_id.'/posts"/>';
        if(is_single()) {
            echo '<link rel="publisher" href="https://plus.google.com/'.$google_admin_id.'"/>';
        }
    }
}
//add google authorship in head
add_action('wp_head', 'display_google_authorship', 0);

function display_markup_for_google() {
    $title       = get_title_page();
    $description = get_description_page();
    $image       = get_image_page();
    echo <<<EOT
<meta itemprop="name" content="$title">
        <meta itemprop="description" content="$description">
        <meta itemprop="image" content="$image">

EOT;
}
//add markup for google in head
add_action('wp_head', 'display_markup_for_google', 0);

function get_image_page() {
    $image = '';
    if(is_home()) {
        $image = 'https://s.w.org/about/images/logos/wordpress-logo-stacked-rgb.png';
    }else if(is_category()||is_tag()||is_tax()) {
        $image = 'https://s.w.org/about/images/logos/wordpress-logo-stacked-rgb.png';
    }else if(is_single()) {
        $image = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()) );
    }
    return $image;
}

function display_graph_data() {
    $title       = get_title_page();
    $description = get_description_page();
    $image       = get_image_page();
    $url         = get_current_url();
    $site_name   = get_site_name();
    $locale      = get_locale();
    $type        = 'website';
    if(is_single()) {
        $url     = get_permalink(get_the_ID());
        $type    = 'article';
    }
    if(is_category()||is_tag()||is_tax()) {
        $type    = 'object';
    }

    echo <<<EOT
<meta property="og:title" content="$title" />
        <meta property="og:locale" content="$locale" />
        <meta property="og:type" content="$type" />
        <meta property="og:url" content="$url" />
        <meta property="og:image" content="$image" />
        <meta property="og:description" content="$description" />
        <meta property="og:site_name" content="$site_name" />

EOT;
    if(is_single()) {
        $updated_time = get_updated_at_post('c');
        if($updated_time == '') {
            $updated_time = get_published_at_post('c');
        }
        $published_at = get_published_at_post('c');
        $title       = get_title_page();
        echo <<<EOT
<meta property="og:updated_time" content="$updated_time" />
        <meta property="article:published_time" content="$published_at" />
        <meta property="article:modified_time" content="$updated_time" />
        <meta property="article:section" content="$title" />

EOT;
        $tags = wp_get_post_tags(get_the_ID(), array('fields' => 'names'));
        foreach ($tags as $tag) {
            echo <<<EOT
<meta property="article:tag" content="$tag" />

EOT;
        }
    }
}
//add graph data in head
add_action('wp_head', 'display_graph_data', 0);

function display_facebook_admin() {
    $facebookAdminID = get_option('_facebook_admin_id', '');
    if(!empty($facebookAdminID)) {
       echo <<<EOT
<meta property="fb:admins" content="$facebookAdminID" />

EOT;

    }

}
//add display facebook admin in head
add_action('wp_head', 'display_facebook_admin', 0);

function display_generator() {
    remove_action('wp_head', 'wp_generator');
    $generator = wp_generator();
    echo <<<EOT
        $generator
EOT;
}
//add display generator in head
add_action('wp_head', 'display_generator', 0);

function display_short_link() {
    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
    $short_link = wp_shortlink_wp_head();
    echo <<<EOT
        $short_link
EOT;
}
//add display generator in head
add_action('wp_head', 'display_short_link', 0);


function cut_string($string,$strlen) {
    return mb_strimwidth($string,0,$strlen,'...','utf-8');
}

function removeWhiteSpaceString($str) {
    $str = preg_replace('/\s\s+/','',$str);
    $str = str_replace("\t", ' ', $str);
    $str = str_replace("\n", '', $str);
    $str = str_replace("\r", '', $str);
    while (stristr($str, '  ')) {
        $str = str_replace('  ', ' ', $str);
    }
    return $str;
}
