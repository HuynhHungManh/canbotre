<?php
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

function get_title_page() {
    global $post,$wp_query;
    if(is_home()) {
        $meta_title = get_bloginfo('name', 'display');
    } else if ( is_archive() && !is_tax() && !is_category() && !is_tag() && !is_author() &&!is_month() &&!is_day() && !is_year() ) {
        $meta_title = post_type_archive_title($prefix, false). ' - '.get_site_name();
    } else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {
        $meta_title = '';
        $post_type = get_post_type();
        if ( get_query_var('paged') ) {
            $meta_title = ': '.__('Page') . ' ' . get_query_var('paged');
        }
        // If it is a custom post type display name and link
        if($post_type != 'post') {
            $post_type_object = get_post_type_object($post_type);
            $meta_title = $post_type_object->labels->name.$meta_title. ' - '.get_site_name();
        } else {
            $custom_tax_name = get_queried_object()->name;
            $meta_title = $custom_tax_name.$meta_title.' - '.get_site_name();
        }
    } else if ( is_single() ) {
        $meta_title = '';
        $post_type = get_post_type();
        if ( get_query_var('paged') ) {
            $meta_title = ': '.__('Page') . ' ' . get_query_var('paged');
        }
        if($post_type != 'post') {
            $post_type_object = get_post_type_object($post_type);
            $meta_title = $post_type_object->labels->name.$meta_title. ' - '.get_site_name();

        } else {
            $meta_title = get_the_title().$meta_title. ' - '.get_site_name();
        }
    } else if ( is_category() ) {
        $meta_title = '';
        if ( get_query_var('paged') ) {
            $meta_title = ': '.__('Page') . ' ' . get_query_var('paged');
        }
        $custom_tax_name = get_queried_object()->name;
        $meta_title = $custom_tax_name.$meta_title. ' - '.get_site_name();
    } else if ( is_page() ) {
        $pageNew = '';
        if ( get_query_var('paged') ) {
            $pageNew = ': '.__('Page') . ' ' . get_query_var('paged');
        }
        $meta_title = '';
        if( $post->post_parent ){
            // If child page, get parents
            $anc = get_post_ancestors( $post->ID );
            // Get parents in the right order
            $anc = array_reverse($anc);
            // Parent page loop
            foreach ( $anc as $ancestor ) {
                $meta_title .= get_the_title($ancestor).' - ';
            }
            $meta_title .= get_the_title().' - ';
        } else {
            $meta_title .= get_the_title().' - ';
        }
        $meta_title .= get_site_name();
    } else if ( is_tag() ) {
        // Tag page
        // Get tag information
        $term_id        = get_query_var('tag_id');
        $taxonomy       = 'post_tag';
        $args           = 'include=' . $term_id;
        $terms          = get_terms( $taxonomy, $args );
        $get_term_name  = $terms[0]->name;
        // Display the tag name
        $meta_title = '';
        if ( get_query_var('paged') ) {
            $meta_title = ': '.__('Page') . ' ' . get_query_var('paged');
        }
        $meta_title = $get_term_name.$meta_title. ' - '.get_site_name();
    } elseif ( is_day() ) {
        $meta_title = get_the_time('jS') .__(' Archives').' - '.get_site_name();
    } else if ( is_month() ) {
        $meta_title = get_the_time('M') .__(' Archives').' - '.get_site_name();
    } else if ( is_year() ) {
        $meta_title = get_the_time('Y') .__(' Archives').' - '.get_site_name();
    } else if ( is_author() ) {
        $meta_title = '';
        if ( get_query_var('paged') ) {
            $meta_title = ': '.__('Page') . ' ' . get_query_var('paged');
        }
        // Auhor archive
        // Get the author information
        global $author;
        $userdata = get_userdata( $author );
        $meta_title = $userdata->display_name.$meta_title. ' - '.get_site_name();
    } else if (get_query_var('paged')) {
        $text = '';
        $meta_title = '';
        if(!empty(get_search_query())) {
            $meta_title .= __('Search').': ' . get_search_query();
        }
        $meta_title .= ' - '.__('Page') . ' ' . get_query_var('paged');
        $meta_title .= ' - '.get_site_name();
    } else if ( is_search() ) {
        $meta_title = '';
        $meta_title .= __('Search').': '. get_search_query();
        if ( get_query_var('paged') ) {
            $meta_title .= __('Page') . ' ' . get_query_var('paged');
        }
        $meta_title .= ' - '.get_site_name();
    } elseif ( is_404() ) {
        $meta_title = __('Error 404') .' - '.get_site_name();
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
    }else if(is_category()||is_tag()||is_tax()||is_archive()) {
        $term = $wp_query->get_queried_object();
        $meta_description = esc_attr($term->description);
        if($meta_description == '') {
            $meta_description = esc_attr($term->name);
        }
    }else if(is_single() || is_page()) {
        $content_post = get_post(get_the_ID());
        $meta_description = $content_post->post_content;
    }else if (is_search()) {
        $meta_description = get_search_query();
    }else if(get_query_var('paged')) {
        $text = '';
        if(!empty(get_search_query())) {
            $text = get_search_query().__('Page').':';
        }
        $meta_description = get_the_title().' '.$text.get_query_var('paged');
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
    if(is_single()) {
        if(has_post_thumbnail()) {
            $image = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
        } else {
            $image = get_site_url().'/wp-content/themes/canbotre/images/not_image.jpg';
        }
    } else {
        $image = get_site_url().'/wp-content/themes/canbotre/images/logo-cbt.png';
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
    if(is_single()||is_page()) {
        $post = get_post();
        $format = 'l, d/m/Y H:i:s';
        $updated_time = date($format, $post->post_modified);
        $published_at = date($format, $post->post_date_gmt);
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

