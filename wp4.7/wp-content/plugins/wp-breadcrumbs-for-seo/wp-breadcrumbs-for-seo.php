<?php
/*
Plugin Name: WP Breadcrumbs For SEO
Plugin URI: http://wordpress.local
Description: Display breadcrumb's page standar SEO
How to use: Insert this code where you want to display breadcrumb <?php custom_breadcrumbs_for_seo(); ?>
Version: 1.0
Author: cuongnq@greenglobal.vn
Author URI: http://wordpress.local
Text Domain: WP Breadcrumbs For SEO
*/

//override function get_category_parents() in wp-includes/category-template.php
/**
 * Retrieve category parents with separator.
 *
 * @since 1.2.0
 *
 * @param int $id Category ID.
 * @param bool $link Optional, default is false. Whether to format with link.
 * @param string $separator Optional, default is '/'. How to separate categories.
 * @param bool $nicename Optional, default is false. Whether to use nice name for display.
 * @param array $visited Optional. Already linked to categories to prevent duplicates.
 * @return string|WP_Error A list of category parents on success, WP_Error on failure.
 */
function get_category_parents_override( $id, $link = false, $separator = '/', $nicename = false, $visited = array() ) {
    $chain = '';
    $parent = get_term( $id, 'category' );
    if ( is_wp_error( $parent ) )
        return $parent;
    if ( $nicename )
        $name = $parent->slug;
    else
        $name = $parent->name;
    if ( $parent->parent && ( $parent->parent != $parent->term_id ) && !in_array( $parent->parent, $visited ) ) {
        $visited[] = $parent->parent;
        $chain .= get_category_parents_override( $parent->parent, $link, $separator, $nicename, $visited );
    }
    if ( $link )
        $chain .= '<li itemtype="http://data-vocabulary.org/Breadcrumb" itemscope><a itemprop="url" href="' . esc_url( get_category_link( $parent->term_id ) ) . '"><span itemprop="title" class="bread-current bread-archive">'.$name.'</span></a></li>';
    else
        $chain .= $name.$separator;
    return $chain;
}

// Breadcrumbs
function custom_breadcrumbs_for_seo() {
    global $cat;
    // Settings
    $separator          = '&gt;';
    $breadcrums_id      = 'breadcrumbs';
    $breadcrums_class   = 'breadcrumb zf2-cms-breadcrumb';
    $home_title         = __('Home');
    // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
    $custom_taxonomy    = 'product_cat';
    // Get the query & post information
    global $post,$wp_query;
    // Do not display on the homepage
    if ( !is_front_page() ) {
        // Build the breadcrums
        echo '<ol id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';
        // Home page
        echo '<li itemtype="http://data-vocabulary.org/Breadcrumb" itemscope><a itemprop="url" href="' . get_home_url() . '" title="' . $home_title . '"><span itemprop="title">' . $home_title . '</a></a></li>';
        if ( is_archive() && !is_tax() && !is_category() && !is_tag() && !is_author() &&!is_month() &&!is_day() && !is_year() ) {
            echo '<li itemtype="http://data-vocabulary.org/Breadcrumb" itemscope class="item-current item-archive"><strong itemprop="title" class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</strong></li>';
        } else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {
            // If post is a custom post type
            $post_type = get_post_type();
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
                echo '<li itemtype="http://data-vocabulary.org/Breadcrumb" itemscope><a itemprop="url" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '"><span itemprop="title">' . $post_type_object->labels->name . '</span></a></li>';
            }
            $custom_tax_name = get_queried_object()->name;
            echo '<li><strong class="bread-current bread-archive">' . $custom_tax_name . '</strong></li>';
        } else if ( is_single() ) {
            // If post is a custom post type
            $post_type = get_post_type();
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
                echo '<li itemtype="http://data-vocabulary.org/Breadcrumb" itemscope class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '" itemprop="url"><span itemprop="title">' . $post_type_object->labels->name . '</span></a></li>';

            }
            // Get post category info
            $category = get_the_category();
            $last_category = array();
            if(!empty($category)) {
                if(!empty(get_query_var('category_name'))) {
                   //get category by path
                    $category = get_category_by_path(get_query_var('category_name'),false);
                    //get category id;
                    $categoryId = $category->cat_ID;
                    // display breadcrumb list via category id by path
                    echo get_category_parents_override($categoryId, true, '/', false);
                }else {
                    $categories = get_the_category();
                    if(!empty($categories)){
                        foreach($categories as $category) {
                            //get last category id
                        }
                        // display breadcrumb list via category id by path
                        echo get_category_parents_override($category, true, '/', false);
                    }
                }
            }
            // If it's a custom post type within a custom taxonomy
            $taxonomy_exists = taxonomy_exists($custom_taxonomy);
            if(empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {
                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
                $cat_id         = $taxonomy_terms[0]->term_id;
                $cat_nicename   = $taxonomy_terms[0]->slug;
                $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                $cat_name       = $taxonomy_terms[0]->name;
            }
            // Check if the post is in a category
            if(!empty($last_category)) {
                echo $cat_display;
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
            // Else if post is in a custom taxonomy
            } else if(!empty($cat_id)) {
                echo '<li itemtype="http://data-vocabulary.org/Breadcrumb" itemscope class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a itemprop="url" class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '"><span itemprop="title">' . $cat_name . '</span></a></li>';
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
            } else {
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
            }
        } else if ( is_category() ) {
            // display list category breadcrumb
            echo get_category_parents_override($cat, true, '/', false);
        } else if ( is_page() ) {
            // Standard page
            if( $post->post_parent ){
                // If child page, get parents
                $anc = get_post_ancestors( $post->ID );
                // Get parents in the right order
                $anc = array_reverse($anc);
                // Parent page loop
                foreach ( $anc as $ancestor ) {
                    $parents .= '<li itemtype="http://data-vocabulary.org/Breadcrumb" itemscope class="item-parent item-parent-' . $ancestor . '"><a itemprop="url" class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '"><span itemprop="title">' . get_the_title($ancestor) . '</span></a></li>';
                }
                // Display parent pages
                echo $parents;
                // Current page
                echo '<li class="item-current item-' . $post->ID . '"><strong title="' . get_the_title() . '" > ' . get_the_title() . '</strong></li>';
            } else {
                // Just display current page if not parents
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" > ' . get_the_title() . '</strong></li>';
            }
        } else if ( is_tag() ) {
            // Tag page
            // Get tag information
            $term_id        = get_query_var('tag_id');
            $taxonomy       = 'post_tag';
            $args           = 'include=' . $term_id;
            $terms          = get_terms( $taxonomy, $args );
            $get_term_id    = $terms[0]->term_id;
            $get_term_slug  = $terms[0]->slug;
            $get_term_name  = $terms[0]->name;
            // Display the tag name
            echo '<li class="item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '" itemtype="http://data-vocabulary.org/Breadcrumb" itemscope><a itemprop="url" href="'.get_tag_link($get_term_id).'"><strong itemprop="title">'. $get_term_name . '</strong></a></li>';
        } elseif ( is_day() ) {
            // Day archive
            // Year link
            echo '<li itemtype="http://data-vocabulary.org/Breadcrumb" itemscope class="item-year item-year-' . get_the_time('Y') . '"><a itemprop="url" class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' <span itemprop="title">Archives</span></a></li>';
            // Month link
            echo '<li itemtype="http://data-vocabulary.org/Breadcrumb" itemscope class="item-month item-month-' . get_the_time('m') . '"><a itemprop="url" class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '" >' . get_the_time('M') . ' <span itemprop="title">Archives</span></a></li>';
            // Day display
            echo '<li itemtype="http://data-vocabulary.org/Breadcrumb" itemscope class="item-current item-' . get_the_time('j') . '"><strong class="bread-current bread-' . get_the_time('j') . '" itemprop="title"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></li>';
        } else if ( is_month() ) {
            // Month Archive
            // Year link
            echo '<li itemtype="http://data-vocabulary.org/Breadcrumb" itemscope class="item-year item-year-' . get_the_time('Y') . '"><a itemprop="url" class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' <span itemprop="title">Archives</span></a></li>';
            // Month display
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><strong class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '" >' . get_the_time('M') . ' Archives</strong></li>';
        } else if ( is_year() ) {
            // Display year archive
            echo '<li class="item-current item-current-' . get_the_time('Y') . '"><strong class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '" >' . get_the_time('Y') . ' Archives</strong></li>';
        } else if ( is_author() ) {
            // Auhor archive
            // Get the author information
            global $author;
            $userdata = get_userdata( $author );
            // Display author name
            echo '<li class="item-current item-current-' . $userdata->user_nicename . '"><strong class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '" >' .__('Author').': ' . $userdata->display_name . '</strong></li>';
        } else if (get_query_var('paged')) {
            $text = '';
            if(!empty(get_search_query())) {
                echo '<li itemtype="http://data-vocabulary.org/Breadcrumb" itemscope=""><a itemprop="url" href="'.get_site_url().'/?s='.get_search_query().'"><span itemprop="title" class="bread-current bread-archive">Tìm kiếm: ' . get_search_query() . '</span></a></li>';
            }
            // Paginated archives
            echo '<li class="item-current item-current-' . get_query_var('paged') . '"><strong class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '" >'.__('Page') . ' ' . get_query_var('paged') . '</strong></li>';
        } else if ( is_search() ) {
            // Search results page
            echo '<li class="item-current item-current-' . get_search_query() . '"><strong class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '" >Tìm kiếm: ' . get_search_query() . '</strong></li>';
        } elseif ( is_404() ) {
            // 404 page
            echo '<li><span>' . __('Error 404') . '</span></li>';
        }
        echo '</ol>';
    }
}



