<?php

/*
Plugin Name: Plugin quản lý phản hồi (Feedback)
Plugin URI: http://canbotredanang.vn
Description: Bật để sử dụng chức năng quản lý feedback
Version: 1.0
Author: cuongnq@greenglobal.vn
Author URI: http://greenglobal.vn
Copyright 2016  Ngo Quang Cuong  (email : cuongnq@greenglobal.vn)
*/

global $wpdb;
define("Greenglobal_Feedback_Table", $wpdb->prefix . "feedback_gg");

function greenglobal_feedback_create_table() {
    global $wpdb;
    $sql1 = "CREATE TABLE IF NOT EXISTS `".Greenglobal_Feedback_Table."` (
        `feedback_id` int(11) unsigned NOT NULL auto_increment,
        `name` varchar(255) NULL default NULL,
        `email` varchar(255) NULL default NULL,
        `phone` varchar(255) NULL default NULL,
        `address` varchar(255) NULL default NULL,
        `content` LONGTEXT NULL default NULL,
        `date` int(10) NOT NULL default 0,
        `ip` varchar(255) NULL default NULL,
        PRIMARY KEY (`feedback_id`))
        ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql1);
}

/*
 * create hook add first table after plugin installed
 */
register_activation_hook(__FILE__,'greenglobal_feedback_create_table');

if (! class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class GreenGlobal_Feedback_List extends WP_List_Table {

    /** Class constructor */
    public function __construct() {
        parent::__construct([
            'singular' => __('Feedback', 'sp'), //singular name of the listed records
            'plural'   => __('Feedback', 'sp'), //plural name of the listed records
            'ajax'     => false //should this table support ajax?
        ]);
    }

    /**
    * Retrieve linking’s data from the database
    *
    * @param int $per_page
    * @param int $page_number
    *
    * @return mixed
    */
    public static function get_feedback($per_page = 50, $page_number = 1) {
        global $wpdb;
        $sql = "SELECT * FROM ".Greenglobal_Feedback_Table;
        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order'])? ' ' .esc_sql($_REQUEST['order']) : ' ASC';
        } else {
            $sql .= ' ORDER BY date DESC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;
        $result = $wpdb->get_results($sql, 'ARRAY_A');
        $results = array();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        foreach ($result as $value) {
            $value['date'] = date('d/m/Y H:i:s',$value['date']);
            $content = wpautop($value['content'], '<p>');
            $patterns = array('/(<[^>]+) style=".*?"/i', "/(<[^>]+) style='.*?'/i");
            $content = preg_replace($patterns, '$1', $content);
            $value['content'] = $content;
            $results[] = $value;
        }
        return $results;
    }

    public static function wp_replace_text($text) {
        return str_replace(array('\"',"\'","\&quot;",'\&#039;'), array('"',"'",'"',"'"), $text);
    }

    /**
    * Delete a feedback record.
    *
    * @param int $id Feedback ID
    */
    public static function delete_feedback($id) {
        global $wpdb;
        $wpdb->delete(
            Greenglobal_Feedback_Table,
            [ 'feedback_id' => $id ],
            [ '%d' ]
       );
    }

    /**
    * Returns the count of records in the database.
    *
    * @return null|string
    */
    public static function record_count() {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM ".Greenglobal_Feedback_Table;
        return $wpdb->get_var($sql);
    }

    /** Text displayed when no linking data is available */
    public function no_items() {
      _e('Không có phản hồi nào.', 'sp');
    }

    /**
    * Method for name column
    *
    * @param array $item an array of DB data
    *
    * @return string
    */
    function column_name($item) {
        // create a nonce
        $delete_nonce = wp_create_nonce('sp_delete_customer');
        $title = '<strong>' . $item['email'] . '</strong>';
        $actions = [
        'delete' => sprintf('<a href="?page=%s&action=%s&feedback_id=%s&_wpnonce=%s">Delete</a>', esc_attr($_REQUEST['page']), 'delete', absint($item['feedback_id']), $delete_nonce)
        ];
        return $title . $this->row_actions($actions);
    }

    /**
    * Render a column when no column specific method exists.
    *
    * @param array $item
    * @param string $column_name
    *
    * @return mixed
    */
    public function column_default($item, $column_name) {
        switch ($column_name) {
        case 'feedback_id':
        case 'email':
        case 'phone':
        case 'address':
        case 'ip':
        case 'content':
        case 'date':
          return $item[$column_name];
        default:
          return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    /**
    * Render the bulk edit checkbox
    *
    * @param array $item
    *
    * @return string
    */
    function column_cb($item) {
        return sprintf(
        '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['feedback_id']
       );
    }

    function get_columns() {
        $columns = [
            'cb'      => '<input type="checkbox" />',
            'email'    => __('Email', 'sp'),
            'phone'    => __('Phone', 'sp'),
            'address'    => __('Address', 'sp'),
            'content' => __('Content', 'sp'),
            'ip'    => __('IP Address', 'sp'),
            'date'    => __('Date', 'sp')
        ];
        return $columns;
    }

    /**
    * Columns to make sortable.
    *
    * @return array
    */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'feedback_id' => array('feedback_id', false),
            'email' => array('email', false),
            'date' => array('date', true)
       );
        return $sortable_columns;
    }

    /**
    * Returns an associative array containing the bulk action
    *
    * @return array
    */
    public function get_bulk_actions() {
        $actions = [
            'bulk-delete' => 'Delete'
        ];

        return $actions;
    }

    /**
    * Handles data query and filter, sorting, and pagination.
    */
    public function prepare_items() {
        $this->_column_headers = $this->get_column_info();
        /** Process bulk action */
        $this->process_bulk_action();
        $per_page     = $this->get_items_per_page('customers_per_page', 50);
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();
        $this->set_pagination_args(
            [
                'total_items' => $total_items, //WE have to calculate the total number of items
                'per_page'    => $per_page //WE have to determine how many items to show on a page
            ]
       );
        $this->items = self::get_feedback($per_page, $current_page);
    }

    public function process_bulk_action() {
        //Detect when a bulk action is being triggered...
        if ('delete' === $this->current_action()) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);
            if (! wp_verify_nonce($nonce, 'sp_delete_customer')) {
                die('Go get a life script kiddies');
            } else {
                self::delete_feedback(absint($_GET['feedback_id']));
            }
        }

        // If the delete bulk action is triggered
        if ((isset($_POST['action']) && $_POST['action'] == 'bulk-delete')
           || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')
       ) {
            $delete_ids = esc_sql($_POST['bulk-delete']);
            // loop over the array of record IDs and delete them
            foreach ($delete_ids as $id) {
                self::delete_feedback($id);
            }
        }
    }
}

class GG_Feedback_Plugin {

    // class instance
    static $instance;

    // customer WP_List_Table object
    public $customers_obj;

    // class constructor
    public function __construct() {
        add_filter('set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3);
        add_action('admin_menu', [ $this, 'plugin_menu' ]);
    }

    public static function set_screen($status, $option, $value) {
        return $value;
    }

    public function plugin_menu() {
        $hook = add_menu_page(
            'Quản lý Feedback',
            'Góp Ý',
            'manage_options',
            'wp_feedback_list_table_class',
            [$this, 'plugin_settings_page'],
            plugins_url('feedback/images/icon.png'),
            50
       );
        add_action("load-$hook", [ $this, 'screen_option' ]);
    }

    /**
    * Screen options
    */
    public function screen_option() {
        $option = 'per_page';
        $args   = [
            'label'   => 'Customers',
            'default' => 10,
            'option'  => 'customers_per_page'
        ];
        add_screen_option($option, $args);
        $this->customers_obj = new GreenGlobal_Feedback_List();
    }

    /**
    * Plugin settings page
    */
    public function plugin_settings_page() {
        ?>
        <div class="wrap">
            <h2>Quản lý phản hồi (Feedback)</h2>
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-3">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <style>
                                .column-email, .column-ip {
                                    width: 15% !important;
                                }
                            </style>
                            <form method="post">
                                <?php
                                $this->customers_obj->prepare_items();
                                $this->customers_obj->display(); ?>
                            </form>
                        </div>
                    </div>
                </div>
                <br class="clear">
            </div>
        </div>
    <?php
    }

    /** Singleton instance */
    public static function get_instance() {
        if (! isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

add_action('plugins_loaded', function () {
    GG_Feedback_Plugin::get_instance();
});

//add jax function adding feedback from front-end
function gg_add_feedback() {
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
    if(isset($_POST['email']) && !empty($email = $_POST['email']) && !empty($content = $_POST['content']) && !empty($captcha = $_POST['captcha']) && !empty($phone = $_POST['phone']) && !empty($address = $_POST['address'])) {
        $flag = 0;
        if(!is_email($email)) {
            $std->message = '<div class="required-label">Địa chỉ email không hợp lệ.</div>';
            $flag = 1;
        } else if(!empty(check_exist_content($email,$content))) {
            $std->message = '<div class="required-label">Nội dung góp ý được gửi bị trùng lặp.</div>';
            $flag = 1;
        } else if(! is_numeric($_POST['phone'])) {
            $std->message = '<div class="required-label">Số điện thoại bạn nhập không hợp lệ. (Exp: 0975797899) </div>';
            $flag = 1;
        } else if(empty($_POST['address'])) {
            $std->message = '<div class="required-label">Vui long nhập địa chỉ liên hệ</div>';
            $flag = 1;
        }
        if($flag == 0) {
            $ipAddress = get_the_access_user_ip();
            $data = array(
                'email' => esc_html($email),
                'phone' => esc_html($phone),
                'address' => esc_html($address),
                'content' => esc_html($content),
                'ip' => $ipAddress,
                'date' => time()
            );
            global $wpdb;
            $wpdb->insert(
                Greenglobal_Feedback_Table,
                $data,
                array('%s','%s','%s','%s','%s','%d')
            );
            $std->message = '<div class="success feedback">Gửi góp ý thành công.</div>';
            $email_to = get_option('_email_lien_he', '');
            if(empty($email_to)) {
                $email_to = get_option('admin_email', '');
            }
            $subject = 'Người dùng có địa chỉ email ('.$email.') gửi thư góp ý trên website';
            $_content = '<p><b>Email: </b> '.esc_html($email).'</p>';
            $_content .= '<p><b>Phone: </b>'.esc_html($phone).'</p>';
            $_content .= '<p><b>Address: </b>'.esc_html($address).'</p>';
            $_content .= "<p><b>Nội dung góp ý: </b><br/>".esc_html($content);
            $message = wpautop($_content, '<p>');
            $headers = 'Reply-To: <'.$email.'>';
            add_filter('wp_mail_content_type', 'gg_fback_set_content_type_send_email');
            wp_mail($email_to, $subject, $message, $headers);
        }
        $result->data = array($std);
        echo json_encode($result);
    } else {
        $std->message = 'Có lỗi xảy ra.';
        $result->data = $std;
        echo json_encode($result);
    }
    exit();
}

function gg_fback_set_content_type_send_email( $content_type ) {
    return 'text/html';
}
add_action('wp_ajax_nopriv_gg_add_feedback', 'gg_add_feedback');
add_action('wp_ajax_gg_add_feedback', 'gg_add_feedback');

//add js to front-end
add_action('wp_enqueue_scripts', 'gg_feedback_ajax_enqueue_scripts','9998');
function gg_feedback_ajax_enqueue_scripts() {
    wp_enqueue_script( 'gg_feedback_ajax_enqueue_scripts', plugins_url( '/feedback.js', __FILE__ ), array(), '1.1', true );
}

function get_the_access_user_ip() {
    if (!empty( $_SERVER['HTTP_CLIENT_IP'])) {
    //check ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty( $_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //to check ip is pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function check_exist_content($email,$content) {
    global $wpdb;
    $sql = "SELECT * FROM ".Greenglobal_Feedback_Table;
    $sql .= " WHERE email = '".esc_sql($email)."' and content ='".esc_sql($content)."'";
    $result = $wpdb->get_results($sql, 'ARRAY_A');
    return $result;
}

function gg_feedback_generateRandomString($length = 5) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function gg_feedback_captcha_generate() {
    $digit = gg_feedback_generateRandomString();
    // record digits in session variable
    $_SESSION['digits'] = $digit;
    return $_SESSION['digits'];
}

function gg_get_captcha_ajax() {
    if ( 'POST' != $_SERVER['REQUEST_METHOD'] ) {
        header('Allow: POST');
        header('HTTP/1.1 405 Method Not Allowed');
        header('Content-Type: text/plain');
        exit;
    }
    $digit = gg_feedback_captcha_generate();
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

add_action('wp_ajax_nopriv_gg_get_captcha_ajax', 'gg_get_captcha_ajax');
add_action('wp_ajax_gg_get_captcha_ajax', 'gg_get_captcha_ajax');
