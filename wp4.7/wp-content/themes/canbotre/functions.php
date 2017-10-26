<?php
/**
 * Nisarg functions and definitions
 *
 * @package Nisarg
 */


if ( ! function_exists( 'nisarg_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */

/**
 * Nisarg only works in WordPress 4.1 or later.
 */


 // add_action( 'personal_options_update', 'notify_admin_on_update' );
 // add_action( 'edit_user_profile_update','notify_admin_on_update');
 // function notify_admin_on_update(){
 //     global $current_user;
 //     get_currentuserinfo();
 //
 //     if (!current_user_can( 'administrator' )){// avoid sending emails when admin is updating user profiles
 //         $to = 'hunghbm@greenglobal.vn';
 //         $subject = 'user updated profile';
 //         $message = "the user : " .$current_user->display_name . " has updated his profile with:\n";
 //         foreach($_POST as $key => $value){
 //             $message .= $key . ": ". $value ."\n";
 //         }
 //         wp_mail( $to, $subject, $message);
 //     }
 // }


if ( version_compare( $GLOBALS['wp_version'], '4.2', '<' ) ) {
    require get_template_directory() . '/inc/back-compat.php';
}

function nisarg_setup() {
    /*
     * Make theme available for translation.
     * Translations can be filed in the /languages/ directory.
     * If you're building a theme based on Nisarg, use a find and replace
     * to change 'nisarg' to the name of your theme in all the template files
     */
    load_theme_textdomain( 'nisarg', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support( 'title-tag' );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
     */
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 604, 270);
    add_image_size( 'nisarg-full-width', 1038, 576, true );


    function register_nisarg_menus() {
        // This theme uses wp_nav_menu() in one location.
        register_nav_menus( array(
            'primary' => esc_html__( 'Top Primary Menu', 'nisarg' ),
        ) );
    }

    add_action( 'init', 'register_nisarg_menus' );


    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ) );


    /*
     * Enable support for Post Formats.
     * See http://codex.wordpress.org/Post_Formats
     */
    add_theme_support( 'post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery'
    ) );


    // Set up the WordPress core custom background feature.
    add_theme_support( 'custom-background', apply_filters( 'nisarg_custom_background_args', array(
        'default-color' => 'f5f5f5',
        'default-image' => '',
    ) ) );
}
endif; // nisarg_setup
add_action( 'after_setup_theme', 'nisarg_setup' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 */
function nisarg_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'nisarg_content_width', 640 );
}
add_action( 'after_setup_theme', 'nisarg_content_width', 0 );


/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function nisarg_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'nisarg' ),
        'id'            => 'sidebar-1',
        'description'   => '',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'nisarg_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function nisarg_scripts() {
    wp_enqueue_style( 'bootstrap', get_template_directory_uri().'/css/bootstrap.css' );
    wp_enqueue_style('common.ninh', get_template_directory_uri().'/css/common.css' );
    wp_enqueue_style('slick-1.6.0', get_template_directory_uri().'/css/slick-1.6.0/slick/slick.css' );
    // wp_enqueue_style('slick-theme', get_template_directory_uri().'/css/slick-1.6.0/slick/slick-theme.css' );
    wp_enqueue_style( 'nisarg-style', get_stylesheet_uri() );

    wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/font-awesome/css/font-awesome.min.css' );
    wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.js',array(),'',true);

    wp_enqueue_script( 'nisarg-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

    wp_enqueue_script( 'nisarg-js', get_template_directory_uri() . '/js/nisarg.js',array(),'',true);
    wp_enqueue_script( 'gg_add_js_common', get_template_directory_uri() . '/js/common.js',array(),'1.1',true);
    wp_enqueue_script( 'slick.js-1.6.0', get_template_directory_uri() . '/css/slick-1.6.0/slick/slick.min.js',array(),'1.7',true);
    wp_enqueue_script( 'html5shiv', get_template_directory_uri().'/js/html5shiv.js', array(),'3.7.3',true );
    wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'nisarg_scripts' );


/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
/**
 * Load custom nav walker
 */
require get_template_directory() . '/inc/navwalker.php';


function nisarg_google_fonts() {
    $query_args = array(

        'family' => 'Lato:400,300italic,700|Source+Sans+Pro:400,400italic'
    );
    wp_register_style( 'nisarggooglefonts', add_query_arg( $query_args, "//fonts.googleapis.com/css" ), array(), null );
    wp_enqueue_style( 'nisarggooglefonts');
}

//add_action('wp_enqueue_scripts', 'nisarg_google_fonts');


function nisarg_new_excerpt_more( $more ) {
    return '...<p class="read-more"><a class="btn btn-default" href="'. esc_url(get_permalink( get_the_ID() )) . '">' . __(' Read More', 'nisarg') . '<span class="screen-reader-text"> '. __(' Read More', 'nisarg').'</span></a></p>';
}
add_filter( 'excerpt_more', 'nisarg_new_excerpt_more' );

function custom_excerpt_length( $length ) {
    return 80;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

/**
 * Return the post URL.
 *
 * @uses get_url_in_content() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 *  * @return string The Link format URL.
 */
function nisarg_get_link_url() {
    $nisarg_content = get_the_content();
    $nisarg_has_url = get_url_in_content( $nisarg_content );

    return ( $nisarg_has_url ) ? $nisarg_has_url : apply_filters( 'the_permalink', get_permalink() );
}

/*
* cuongnq@greenglobal.vn
 */

add_action('init', 'sessionRelatedPostsStart', 1);
function sessionRelatedPostsStart() {
    if(!session_id()) {
        session_start();
    }
}

function set_session_related_posts() {
    if(isset($_SESSION['_related_start'])) {
        $value = (int)get_option( '_session_related_posts', '');
        $experied = time() - $_SESSION['_related_start'];
        if($value <=0 ){
            $value = 300;
        }
        if($experied >= $value) {
            session_unset('_session_related_posts');
            $_SESSION['_session_related_posts'] = array((int)  get_the_ID()) ;
        }else {
            $array = get_session_related_posts();
            $array[] = (int)  get_the_ID();
            $_SESSION['_session_related_posts'] = array_unique($array);
        }
    }else {
        $_SESSION['_related_start'] = time();
        $_SESSION['_session_related_posts'] = array((int)  get_the_ID()) ;

    }
}

function get_session_related_posts() {
    $arrays = $_SESSION['_session_related_posts'];
    return array_unique($arrays);
}

function register_session_related_timeout() {
    register_setting('general', '_session_related_posts', 'esc_attr');
    add_settings_field('_session_related_posts', '<label for="_session_related_posts">'.__('Session Timeout Related Posts' , '_session_related_posts' ).'</label>' , 'print_session_related_posts', 'general');
}

function print_session_related_posts() {
    $value = (int) get_option( '_session_related_posts', '');
    if($value <=0) {
        $value = 300;
    }
    echo '<input type="number" id="_session_related_posts" name="_session_related_posts" value="' . $value . '" />';
}

add_filter('admin_init', 'register_session_related_timeout');

//hook archive title
add_filter('get_the_archive_title', function ($title) {
    if(is_category()) {
        $title = single_cat_title( '', false );
    }
    return $title;
});

// get post via category
function get_post_via_category($categoryId, $per_page = 3, $notIn = null) {
    $args = array(
        'posts_per_page' => $per_page,
        'offset'=> 0,
        'category' => $categoryId,
        'orderby' => 'modified',
        'order' => 'DESC',
        'post_status' => 'publish'
    );
    if(!empty($notIn) && is_array($notIn)) {
        $args['post__not_in'] = $notIn;
    }
    return get_posts($args);
}

function format_date_post($date) {
    return date('d/m/Y',strtotime($date));
}

function removeWhiteSpaceInStringPost($str) {
    $str = preg_replace('/\s\s+/','',$str);
    $str = str_replace("\t", ' ', $str);
    $str = str_replace("\n", '', $str);
    $str = str_replace("\r", '', $str);
    while (stristr($str, '  ')) {
        $str = str_replace('  ', ' ', $str);
    }
    return $str;
}

add_action( 'admin_menu', 'adjust_the_wp_menu', 999 );
function adjust_the_wp_menu() {
  remove_submenu_page('email-subscribers', 'es-notification');
  remove_submenu_page('email-subscribers', 'es-cron');
  remove_submenu_page('email-subscribers', 'es-settings');
  remove_submenu_page('email-subscribers', 'es-roles');
  remove_submenu_page('email-subscribers', 'es-general-information');
  remove_submenu_page('edit.php?post_type=slick_slider', 'wpsisac-designs');
}

function add_extra_user_column($columns) {
    unset($columns['name']);
    $array = array();
    foreach ($columns as $key => $value) {
        $array[$key] = $value;
        if($key == 'username') {
            $array['full_name'] = 'Họ và tên';
            $array['member_clb'] = 'Thành viên câu lạc bộ';
        }
    }
    return $array;
}
add_filter('manage_users_columns' , 'add_extra_user_column');

add_action('manage_users_custom_column', 'kjl_user_posts_count_column_content', 10, 3);
function kjl_user_posts_count_column_content($value, $column_name, $user_id) {
  $user = get_userdata($user_id);
  if ('full_name' == $column_name) {
    return get_the_author_meta('_full_name', $user_id );
  }
  if ('member_clb' == $column_name) {
    $member_clb = get_the_author_meta('_member_of_clb', $user_id);
    return ($member_clb == '1') ? 'Có' : 'Không';
  }
  return $value;
}

add_action( 'pre_user_query', function( $uqi ) {
    global $wpdb;

    $search = '';
    if ( isset( $uqi->query_vars['search'] ) )
        $search = trim( $uqi->query_vars['search'] );

    if ($search) {
        $search = trim($search, '*');
        $the_search = '%'.$search.'%';

        $search_meta = $wpdb->prepare("
        ID IN ( SELECT user_id FROM {$wpdb->usermeta}
        WHERE ( ( meta_key='first_name' OR meta_key='last_name' OR meta_key='_full_name' OR meta_key='_cq_cong_tac' OR meta_key='_position_in_cq' OR meta_key='_chuyen_mon' OR meta_key='_thanh_vien_to' OR meta_key='_position_in_clb' OR meta_key='_que_quan' OR meta_key='_noi_o' OR meta_key='_so_lien_lac' OR meta_key='_chinh_tri' OR meta_key='_dang_doan_vien' OR meta_key='_dt_fax_co_quan')
            AND {$wpdb->usermeta}.meta_value LIKE '%s' )
        )", $the_search);

        $uqi->query_where = str_replace(
            'WHERE 1=1 AND (',
            "WHERE 1=1 AND (" . $search_meta . " OR ",
            $uqi->query_where );
    }
});

function substrwords($text, $maxchar, $end='...') {
    $string = explode(' ',$text);
    $arg = '';
    for($i=0; $i<count($string); $i++) {
        if($i<=$maxchar) {
            $arg .= $string[$i].' ';
        }
    }
    if(!empty($arg) && $maxchar < count($string)) {
        $arg = $arg.$end;
    }
    return $arg;
}

function cut_string_post($string,$strlen) {
    $string = strip_tags($string,'<br/><br><br />');
    return substrwords(removeWhiteSpaceInStringPost($string),$strlen,'...');
}

function get_thumbnail_post($postId = null) {
    if(empty($postId)) {
        if (has_post_thumbnail()) {
            $thumbnail = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));
        }else {
            $thumbnail = get_template_directory_uri().'/images/not_image.jpg';
        }
    }else {
        if (has_post_thumbnail($postId)) {
            $thumbnail = wp_get_attachment_url(get_post_thumbnail_id($postId));
        }else {
            $thumbnail = get_template_directory_uri().'/images/not_image.jpg';
        }
    }
    return $thumbnail;
}
/**
 * RSS function get list
 */
function cbt_rss_func($atts) {
    if(!empty($menus = wp_get_nav_menu_items('RSS Menu'))) {
        $args = array(
            'menu' => 38,
            'echo' => false
        );
    ?>
        <?php return wp_nav_menu($args); ?>
        <?php
    } else {
        return 'Không tồn tại RSS nào trong danh sách.';
    }
}
add_shortcode('cbt_rss', 'cbt_rss_func');

function get_post_link($categoryId,$post_name) {
    return get_category_link($categoryId).'/'.$post_name.'.html';
}

// add dia chi van phong
function register_address_site() {
    register_setting('general', '_address_site', 'esc_attr');
    register_setting('general', '_twitter_link', 'esc_attr');
    register_setting('general', '_facebook_link', 'esc_attr');
    register_setting('general', '_google_plus_link', 'esc_attr');
    register_setting('general', '_rss_page', 'esc_attr');
    add_settings_field('_address_site', '<label for="_address_site">'.__('Địa chỉ văn phòng: ' , '_address_site' ).'</label>' , 'print_address_site', 'general');
    add_settings_field('_google_plus_link', '<label for="_google_plus_link">Google+ link: </label>' , 'print_google_plus_link', 'general');
    add_settings_field('_facebook_link', '<label for="_facebook_link">Facebook link: </label>' , 'print_facebook_link', 'general');
    add_settings_field('_twitter_link', '<label for="_twitter_link">Twitter link: </label>' , 'print_twitter_link', 'general');
    add_settings_field('_rss_link', '<label for="_rss_link">RSS link: </label>' , 'print_rss_link', 'general');
}

function get_rss_link_ba() {
    return get_option('_rss_page', '');
}

function get_twitter_link_ba() {
    return get_option('_twitter_link', '');
}

function get_facebook_link_ba() {
    return get_option('_facebook_link', '');
}

function get_google_plus_link_ba() {
    return get_option('_google_plus_link', '');
}

function print_rss_link() {
    $value = get_option('_rss_page', '');
    if(empty($value)):
        $value = '';
    endif;
    echo '<input name="_rss_page" id="_rss_page" class="large-text code" rows="10" value="'.$value.'">';
}

function print_twitter_link() {
    $value = get_option('_twitter_link', '');
    if(empty($value)):
        $value = '';
    endif;
    echo '<input name="_twitter_link" id="_twitter_link" class="large-text code" rows="10" value="'.$value.'">';
}

function print_facebook_link() {
    $value = get_option('_facebook_link', '');
    if(empty($value)):
        $value = '';
    endif;
    echo '<input name="_facebook_link" id="_facebook_link" class="large-text code" rows="10" value="'.$value.'">';
}

function print_google_plus_link() {
    $value = get_option('_google_plus_link', '');
    if(empty($value)):
        $value = '';
    endif;
    echo '<input name="_google_plus_link" id="_google_plus_link" class="large-text code" rows="10" value="'.$value.'">';
}

function print_address_site() {
    $value = get_option('_address_site', '');
    if(empty($value)):
        $value = '';
    endif;
    echo '<input name="_address_site" id="_address_site" class="large-text code" rows="10" value="'.$value.'">';
}

add_filter('admin_init', 'register_address_site');

// add phone number
function register_phone_number_site() {
    register_setting('general', '_phone_number_site', 'esc_attr');
    add_settings_field('_phone_number_site', '<label for=_phone_number_site>'.__('Số ĐT liên hệ: ' , '_phone_number_site' ).'</label>' , 'print_phone_number_site', 'general');
}

function print_phone_number_site() {
    $value = get_option('_phone_number_site', '');
    if(empty($value)):
        $value = '';
    endif;
    echo '<input name="_phone_number_site" id="_phone_number_site" class="large-text code" rows="10" value="'.$value.'">';
}

add_filter('admin_init', 'register_phone_number_site');

// add email liên hệ
function register_email_lien_he() {
    register_setting('general', '_email_lien_he', 'esc_attr');
    add_settings_field('_email_lien_he', '<label for=_email_lien_he>'.__('E-mail liên hệ: ' , '_email_lien_he' ).'</label>' , 'print_email_lien_he', 'general');
}

function print_email_lien_he() {
    $value = get_option('_email_lien_he', '');
    if(empty($value)):
        $value = '';
    endif;
    echo '<input name="_email_lien_he" id="_email_lien_he" class="large-text code" rows="10" value="'.$value.'">';
}

add_filter('admin_init', 'register_email_lien_he');

// add google analytics field to setting in general
function register_google_analytics() {
   register_setting('general', '_google_analytics', 'esc_attr');
   add_settings_field('_google_analytics', '<label for="_facebook_connect">'.__('Google Analytics' , '_google_analytics' ).'</label>' , 'print_google_analytics', 'general');
}

function print_google_analytics() {
    $value = get_option('_google_analytics', '');
    if(empty($value)):
        $value = '';
    endif;
    echo '<textarea name="_google_analytics" id="_google_analytics" class="large-text code" rows="10">' . $value . '</textarea>';
}

add_filter('admin_init', 'register_google_analytics');

// add facebook lib
function register_connect_facebook() {
   register_setting('general', '_connect_facebook', 'esc_attr');
   add_settings_field('_connect_facebook', '<label for="_facebook_connect">'.__('Facebook Connect' , '_facebook_connect' ).'</label>' , 'print_connect_facebook', 'general');
}

function print_connect_facebook() {
    $value = get_option('_connect_facebook', '');
    if(empty($value)):
        $value = '';
    endif;
    echo '<textarea name="_connect_facebook" id="_connect_facebook" class="large-text code" rows="10">' . $value . '</textarea>';
}

add_filter('admin_init', 'register_connect_facebook');

// Only here we have a valid is_404() to check if occurs.
add_action('wp',
    function() {
        if(!is_404()) return; // Bail if not a 404 (WordPress has got your back)
        if (get_option('permalink_structure')) {
            $permalink_structure = get_option('permalink_structure');
            // Extract the URI and the Query-String (which is used later again)
            list($uri, $qs) = explode('?', $_SERVER['REQUEST_URI']);
            if(preg_match('/\.html/',$permalink_structure) && is_single()) {
                // Bail if current URL contains a . in the last segment of the URI
                if(!preg_match('~/[^/\.]+/?$~', $uri)) return;
                // Right-trim the last ./ and append the .html to it
                $uri = rtrim($uri, '/.').".html".(!empty($qs) ? "?{$qs}" : null);
                // Redirect to the new URL (with a 301 to keep link juice flowing) and hope it works :)
                wp_redirect($uri, 301); die; // Over and out!
                return ;
            } else if(preg_match('~/[^/\.]+/?$~', $permalink_structure) && is_single()) {
                $uri = rtrim($uri, '.html')."/".(!empty($qs) ? "?{$qs}" : null);
                // Redirect to the new URL (with a 301 to keep link juice flowing) and hope it works :)
                wp_redirect($uri, 301); die; // Over and out!
                return ;
            }
        }

    }
);

function count_result_searching($text) {
    $allsearch = new WP_Query("s=$text&showposts=-1");
    return $allsearch->post_count;
}

function get_limit_option() {
    $limit = (int) get_option('posts_per_page');
    if($limit <= 0) {
        $limit = 5;
    }
    return $limit;
}

function gg_cbt_login_logo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/logo.png);
            background-size: inherit;
            height: 95px;
            width: 294px;
        }
        #login p.forgetmenot {
            margin-top: 7px;
        }
        #login p#nav, #login p#backtoblog {
            text-align:center;
        }
    </style>
<?php }
add_action('login_enqueue_scripts', 'gg_cbt_login_logo');

function gg_cbt_login_logo_url() {
    return get_site_url();
}
add_filter('login_headerurl', 'gg_cbt_login_logo_url' );

function function_gg_feedback_generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Move jQuery and jQuery migrate to the footer
function move_jquery_migrate( &$scripts ) {
  if(!is_user_logged_in()) {
      $scripts->remove( 'jquery');
      $scripts->remove( 'jquery-core');
      $scripts->remove( 'jquery-migrate');
  }
}
add_filter( 'wp_default_scripts', 'move_jquery_migrate' );

add_action( 'wp_print_styles',     'my_deregister_styles', 100);
function my_deregister_styles() {
    if(!is_user_logged_in()) {
        wp_deregister_style('dashicons');
    }
}

// filter search
function gg_wp_filter_search($query) {
  global $typenow;
  global $pagenow;
  if($pagenow == 'index.php' && $typenow == '') {
    if($query->is_search && is_search() && $query->is_main_query()) {
      $postTypes = $query->query_vars['post_type'];
      if (is_array($postTypes))
      {
        $postTypes[] = 'post';     // modify to your Custom Post Type slug
        $query->set('post_type', $postTypes);
      } else if(empty($postTypes)) {
        $query->set('post_type', array('post'));
      }
    }
  }
  $category = $query->query_vars['cat'];
  if($postTypes == "post" && !empty($category)) {
    $query->query_vars['category__in'] = array($category);
  }
  return $query;
}
add_filter('pre_get_posts', 'gg_wp_filter_search');

//Translate text username and email in Vietnamese
function cbt_login_head() {
  function cbt_username_label_login( $translated_text, $text, $domain ) {
    if ($text === 'Username or Email') {
      $translated_text = __('Username');
    }
    return $translated_text;
  }
  add_filter('gettext', 'cbt_username_label_login', 20, 3 );
}
add_action( 'login_head', 'cbt_login_head' );
