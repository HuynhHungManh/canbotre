<?php
/*
Plugin Name: Plugin chia sẻ bài viết qua email.
Plugin URI: http://greenglobal.vn
Description: Bật để sử dụng chức năng chia sẻ bài viết qua email.
Version: 1.0
Author: cuongnq@greenglobal.vn
Author URI: http://greenglobal.vn
Copyright 2016  Ngo Quang Cuong  (email : cuongnq@greenglobal.vn)
*/

global $wpdb;
define("gg_share_post_email_table", $wpdb->prefix . "shared_post_email");

function gg_share_post_via_email_create_table() {
    global $wpdb;
    $sql1 = "CREATE TABLE IF NOT EXISTS `".gg_share_post_email_table."` (
        `shared_id` int(11) unsigned NOT NULL auto_increment,
        `name` varchar(255) NULL default NULL,
        `email` varchar(255) NULL default NULL,
        `link` LONGTEXT NULL default NULL,
        `date` int(10) NOT NULL default 0,
        `ip` varchar(255) NULL default NULL,
        PRIMARY KEY (`shared_id`))
        ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql1);
}

/*
 * create hook add first table after plugin installed
 */
register_activation_hook(__FILE__,'gg_share_post_via_email_create_table');

function gg_share_post_via_email($email,$name,$content,$title,$link) {
    $subject = 'Người dùng có tên ('.$name.') chia sẻ nội dung bài viết này với bạn.';
    $h1 = 'Chi tiết xem tại: <h1 itemprop="headline"><a href="'.$link.'">'.$title.'</a></h1>';
    $content = $h1.$content;
    add_filter( 'wp_mail_content_type', 'set_content_type' );
    wp_mail($email, $subject, $content);
    $ipAddress = get_the_access_user_ip();
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $time = time();
    $data = array(
        'email' => $email,
        'name' => $name,
        'ip' => $ipAddress,
        'date' => $time,
        'link' => $link
    );
    global $wpdb;
    $wpdb->insert(
        gg_share_post_email_table,
        $data,
        array('%s','%s','%s','%d','%s')
    );
}

function set_content_type( $content_type ) {
    return 'text/html';
}

function removeWhiteSpaceInStringPostSharePostViaEmail($str) {
    $str = preg_replace('/\s\s+/','',$str);
    $str = str_replace("\t", ' ', $str);
    $str = str_replace("\n", '', $str);
    $str = str_replace("\r", '', $str);
    while (stristr($str, '  ')) {
        $str = str_replace('  ', ' ', $str);
    }
    return $str;
}

function gg_share_post_via_email_cut_string($string,$strlen) {
    return mb_strimwidth(removeWhiteSpaceInStringPostSharePostViaEmail($string),0,$strlen,'...','utf-8');
}

//add jax function adding feedback from front-end
function gg_share_post_via_email_ajax() {
    if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
        header('Allow: POST');
        header('HTTP/1.1 405 Method Not Allowed');
        header('Content-Type: text/plain');
        exit;
    }
    $std = new stdClass();
    $std->type= 'inform';
    $std->id = '1';
    $result = new stdClass();
    if(isset($_POST['email'])
        && !empty($email = $_POST['email'])
        && !empty($name = $_POST['name'])
        && !empty($link = $_POST['link'])
        && !empty($title = $_POST['title'])
        && !empty($content = $_POST['content'])
        && !empty($captcha = $_POST['captcha'])
    ) {
        $flag = 0;
        $ipAddress = get_the_access_user_ip();
        if(!is_email($email)) {
            $std->message = '<div class="required-label">Địa chỉ email không hợp lệ.</div>';
            $flag = 1;
        } else if(!empty($exist = check_exist_shared_post($ipAddress,$email,$link))) {
            $std->message = '<div class="required-label">Bạn đã chia sẻ bài viết này tới địa chỉ email này rồi.</div>';
            $flag = 1;
        } else if(!gg_check_exist_shared_post_time($ipAddress)) {
            $std->message = '<div class="required-label">Bạn chỉ được phép sử dụng chức năng này mỗi '.gg_get_time_limit_share_post_email().' giây một lần.</div>';
            $flag = 1;
        }
        if($flag == 0) {
            gg_share_post_via_email($email,$name,$content,$title,$link);
            $std->message = '<div class="success feedback">Nội dung chia sẻ đã được gửi tới email '.$email.'.</div>';
        }
        $result->data = array($std);
        echo json_encode($result);
    } else {
        $std->message = 'Có lỗi xảy ra.';
        $result->data = array($std);
        echo json_encode($result);
    }
    exit();
}
add_action('wp_ajax_nopriv_gg_share_post_via_email_ajax', 'gg_share_post_via_email_ajax');
add_action('wp_ajax_gg_share_post_via_email_ajax', 'gg_share_post_via_email_ajax');

function check_exist_shared_post($ip,$email,$link) {
    global $wpdb;
    $sql = "SELECT * FROM ".gg_share_post_email_table;
    $sql .= " WHERE email = '".esc_sql($email)."' and ip ='".esc_sql($ip)."' and link ='".esc_sql($link)."'";
    $result = $wpdb->get_results($sql, 'ARRAY_A');
    return $result;
}

function gg_get_time_limit_share_post_email() {
    $time_limit = get_option('_time_limit_use_share_email', '');
    if(empty($value) || !is_numeric($value) || $value <= 0):
        $time_limit = '60';
    endif;
    return $time_limit;
}

function gg_check_exist_shared_post_time($ip) {
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $time = time();
    $time_limit = gg_get_time_limit_share_post_email();
    global $wpdb;
    $sql = "SELECT date FROM ".gg_share_post_email_table;
    $sql .= " WHERE ip ='".esc_sql($ip)."' ORDER BY shared_id DESC LIMIT 0,1";
    $result = $wpdb->get_results($sql, 'ARRAY_A');
    $current = 0;
    if(!empty($result)) {
        $current = $time - $result[0]['date'];
        if($current < $time_limit) {
            return false;
        }
    }
    return true;
}

//add js to front-end
add_action('wp_enqueue_scripts', 'gg_share_post_via_email_ajax_enqueue_scripts','9998');
function gg_share_post_via_email_ajax_enqueue_scripts() {
    wp_enqueue_script( 'gg_share_post_via_email_ajax_enqueue_scripts', plugins_url( '/share_post_via_email.js', __FILE__ ), array(), '1.1', true );
}

// add option time limit
function register_time_limit_use_share_email() {
    register_setting('general', '_time_limit_use_share_email', 'esc_attr');
    add_settings_field('_time_limit_use_share_email', '<label for="_time_limit_use_share_email">'.__('Giới hạn time chia sẻ bài viết bởi email: ' , '_time_limit_use_share_email' ).'</label>' , 'print_time_limit_use_share_email', 'general');
}

function print_time_limit_use_share_email() {
    $value = get_option('_time_limit_use_share_email', '');
    if(empty($value) || !is_numeric($value) || $value <= 0) {
        $value = '0';
    }
    echo '<input name="_time_limit_use_share_email" id="_time_limit_use_share_email" class="large-text code" rows="3" type = "number" value="'.$value.'"> (Tính bằng giây)';
}

add_filter('admin_init', 'register_time_limit_use_share_email');


function gg_share_post_generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function gg_share_post_get_captcha_ajax() {
    if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
        header('Allow: POST');
        header('HTTP/1.1 405 Method Not Allowed');
        header('Content-Type: text/plain');
        exit;
    }
    $digit = gg_share_post_generateRandomString();
    $std = new stdClass();
    $data = new stdClass();
    $data->type = 'image';
    $data->id = '100';
    $data->img = $digit;
    $std->data = array(
        $data
    );
    echo json_encode($std);
    exit();
}

add_action('wp_ajax_nopriv_gg_share_post_get_captcha_ajax', 'gg_share_post_get_captcha_ajax');
add_action('wp_ajax_gg_share_post_get_captcha_ajax', 'gg_share_post_get_captcha_ajax');
