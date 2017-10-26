<?php

/*
Plugin Name: Plugin quản lý liên kết website
Plugin URI: http://canbotredanang.vn
Description: Bật để sử dụng chức năng quản lý liên kết website
Version: 1.0
Author: cuongnq@greenglobal.vn
Author URI: http://greenglobal.vn
Copyright 2016  Ngo Quang Cuong  (email : cuongnq@greenglobal.vn)
*/

global $wpdb;
define("Greenglobal_Linking_Table", $wpdb->prefix . "linking");

function greenglobal_linking_create_table() {
    global $wpdb;
    $sql1 = "CREATE TABLE IF NOT EXISTS `".Greenglobal_Linking_Table."` (
        `linking_id` int(11) unsigned NOT NULL auto_increment,
        `linking_url` varchar(255) NULL default NULL ,
        `linking_name` varchar(255) NULL default NULL ,
        `is_active` int(1) NOT NULL default 1,
        `linking_priority` int(1) NOT NULL default 0,
        PRIMARY KEY  (`linking_id`),
        UNIQUE (`linking_url`))
        ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql1);
}

/*
 * create hook add first table after plugin installed
 */
register_activation_hook(__FILE__,'greenglobal_linking_create_table');

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
* Get linking from linking_url
*/
function gg_get_linking_list() {
    global $wpdb;
    $sql = "SELECT * FROM ".Greenglobal_Linking_Table." WHERE is_active ='1' ORDER BY linking_priority DESC";
    $result = $wpdb->get_results($sql, 'ARRAY_A');
    return $result;
}

class GreenGlobal_Linking_List extends WP_List_Table {

    /** Class constructor */
    public function __construct() {

        parent::__construct( [
            'singular' => __( 'Linking', 'sp' ), //singular name of the listed records
            'plural'   => __( 'Linking', 'sp' ), //plural name of the listed records
            'ajax'     => false //should this table support ajax?

        ] );

    }

    /**
    * Retrieve linking’s data from the database
    *
    * @param int $per_page
    * @param int $page_number
    *
    * @return mixed
    */
    public static function get_linkings($per_page = 50, $page_number = 1 ) {

        global $wpdb;

        $sql = "SELECT * FROM ".Greenglobal_Linking_Table;

        if(isset($_GET['status']) && is_numeric($_GET['status'])) {
            $sql .= " WHERE is_active = ".$_GET['status'];
        }

        if(isset($_GET['s'])) {
            $sql .= " WHERE linking_url LIKE '%".esc_sql($_GET['s'])."%' or linking_name LIKE '%".esc_sql($_GET['s'])."%'";
        }

        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order'])? ' ' .esc_sql( $_REQUEST['order'] ) : ' ASC';
        }

        $sql .= " LIMIT $per_page";
        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
        $result = $wpdb->get_results($sql, 'ARRAY_A');

        $results = array();
        foreach ($result as $value) {
            if($value['is_active'] == '1') {
                $value['is_active'] = 'Active';
            } else {
                $value['is_active'] = 'In Active';
            }
            $linkingName = self::wp_replace_text($value['linking_name']);
            $value['linking_name'] = "<a href=\"/wp-admin/admin.php?page=wp_linking_add_new&id=".$value['linking_id']."\">".$linkingName."</a>";
            $results[] = $value;
        }

        return $results;
    }

    /**
     * Get linking from linking id
     */
    public function get_linking_id($id) {
        global $wpdb;
        $sql = "SELECT * FROM ".Greenglobal_Linking_Table." WHERE linking_id =$id";
        $result = $wpdb->get_results($sql, 'ARRAY_A');
        return $result;
    }

    /**
     * Get linking from linking_url
     */
    public function get_linking_url($linkingUrl) {
        global $wpdb;
        $sql = "SELECT * FROM ".Greenglobal_Linking_Table." WHERE linking_url ='".esc_sql($linkingUrl)."'";
        $result = $wpdb->get_results($sql, 'ARRAY_A');
        return $result;
    }

    public static function wp_replace_text($text) {
        return str_replace(array('\"',"\'","\&quot;",'\&#039;'), array('"',"'",'"',"'"), $text);
    }

    /**
     * Get active linking
     */
    public function get_linking_active() {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM ".Greenglobal_Linking_Table." WHERE is_active =1";
        return $wpdb->get_var($sql);
    }
    /**
    * Delete a customer record.
    *
    * @param int $id linking ID
    */
    public static function delete_linking( $id ) {
        global $wpdb;
        $wpdb->delete(
            Greenglobal_Linking_Table,
            [ 'linking_id' => $id ],
            [ '%d' ]
        );
    }

    public function get_all_linking() {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM ".Greenglobal_Linking_Table;
        return $wpdb->get_var($sql);
    }

    /**
    * Returns the count of records in the database.
    *
    * @return null|string
    */
    public static function record_count() {
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM ".Greenglobal_Linking_Table;
        if(isset($_GET['status']) && is_numeric($_GET['status'])) {
            $sql .= " WHERE is_active = ".$_GET['status'];
        }
        if(isset($_GET['s'])) {
            $sql .= " WHERE linking_url LIKE '%".esc_sql($_GET['s'])."%' or linking_name LIKE '%".esc_sql($_GET['s'])."%'";
        }
        return $wpdb->get_var($sql);
    }

    /** Text displayed when no linking data is available */
    public function no_items() {
      _e( 'Không có liên kết nào.', 'sp' );
    }

    /**
    * Method for name column
    *
    * @param array $item an array of DB data
    *
    * @return string
    */
    function column_name( $item ) {
        // create a nonce
        $delete_nonce = wp_create_nonce( 'sp_delete_customer' );
        $title = '<strong>' . $item['linking_name'] . '</strong>';
        $actions = [
        'delete' => sprintf( '<a href="?page=%s&action=%s&customer=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['linking_id'] ), $delete_nonce )
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
    public function column_default( $item, $column_name ) {
        switch ($column_name) {
        case 'linking_id':
        case 'linking_name':
        case 'linking_url':
        case 'is_active':
        case 'linking_priority':
          return $item[ $column_name ];
        default:
          return print_r( $item, true ); //Show the whole array for troubleshooting purposes
        }
    }

    /**
    * Render the bulk edit checkbox
    *
    * @param array $item
    *
    * @return string
    */
    function column_cb( $item ) {
        return sprintf(
        '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['linking_id']
        );
    }

    function get_columns() {
        $columns = [
            'cb'      => '<input type="checkbox" />',
            'linking_name'    => __( 'Name', 'sp' ),
            'linking_url' => __( 'Url', 'sp' ),
            'is_active'    => __( 'Status', 'sp' ),
            'linking_priority'    => __( 'Priority', 'sp' )
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
            'linking_id' => array('linking_id', true ),
            'linking_name' => array('linking_name', false ),
            'linking_priority' => array('linking_priority', false ),
            'is_active' => array('is_active', false )
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

        $per_page     = $this->get_items_per_page( 'customers_per_page', 50 );
        $current_page = $this->get_pagenum();
        $total_items  = self::record_count();

        $this->set_pagination_args(
            [
                'total_items' => $total_items, //WE have to calculate the total number of items
                'per_page'    => $per_page //WE have to determine how many items to show on a page
            ]
        );
        $this->items = self::get_linkings($per_page, $current_page);
    }

    public function process_bulk_action() {
        //Detect when a bulk action is being triggered...
        if ( 'delete' === $this->current_action() ) {
            // In our file that handles the request, verify the nonce.
            $nonce = esc_attr( $_REQUEST['_wpnonce'] );
            if ( ! wp_verify_nonce( $nonce, 'sp_delete_customer' ) ) {
                die( 'Go get a life script kiddies' );
            } else {
                self::delete_linking(absint($_GET['customer']));
            }
        }

        // If the delete bulk action is triggered
        if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
           || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
        ) {
            $delete_ids = esc_sql( $_POST['bulk-delete'] );
            // loop over the array of record IDs and delete them
            foreach ( $delete_ids as $id ) {
                self::delete_linking( $id );
            }
        }
    }
}

class SP_Plugin {

    // class instance
    static $instance;

    // customer WP_List_Table object
    public $customers_obj;

    // class constructor
    public function __construct() {
        add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
        add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
    }

    public static function set_screen( $status, $option, $value ) {
        return $value;
    }

    public function plugin_menu() {
        $hook = add_menu_page(
            'Quản lý liên kết website',
            'Liên kết website',
            'manage_options',
            'wp_linking_list_table_class',
            [$this, 'plugin_settings_page'],
            plugins_url('linking/images/icon.png'),
            50
        );
        add_action("load-$hook", [ $this, 'screen_option' ]);

        $hooks = add_submenu_page(
            'wp_linking_list_table_class',
            'Thêm mới liên kết website',
            'Thêm mới',
            'manage_options',
            'wp_linking_add_new',
            [$this, 'plugin_linking_rud']
        );
        add_action("load-$hooks", [ $this, 'screen_option' ]);
    }

    /**
    * Screen options
    */
    public function screen_option() {

        $option = 'per_page';
        $args   = [
            'label'   => 'Customers',
            'default' => 5,
            'option'  => 'customers_per_page'
        ];

        add_screen_option( $option, $args );

        $this->customers_obj = new GreenGlobal_Linking_List();
    }

    function admin_notice_success($message = 'Done!') {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e($message, 'sample-text-domain' ); ?></p>
        </div>
        <?php
    }

    function admin_notice_error($message = 'Error!') {
        ?>
        <div class="error notice is-dismissible">
            <p><?php _e($message, 'sample-text-domain' ); ?></p>
        </div>
        <?php
    }

    function wp_insert_linking($data,$validate) {
        global $wpdb;
        $wpdb->insert(
            Greenglobal_Linking_Table,
            $data,
            $validate
        );
    }

    function wp_update_linking($data,$where) {
        global $wpdb;
        $wpdb->update(
            Greenglobal_Linking_Table,
            $data,
            $where
        );
    }

    public function plugin_linking_rud() {
        $linkingName = $linkingUrl = $isActive = $linkingId = $urlConfirm = '';
        $linkingPriority = '0';
        if(isset($_POST['save']) && ($_POST['save'] == 'Lưu')) {
            $linkingName = $_POST['linking_name'];
            $linkingUrl = $_POST['linking_url'];
            $isActive = $_POST['is_active'];
            $linkingPriority = $_POST['linking_priority'];
            if(!empty($_POST['linking_id'])) {
                $linkingId = $_POST['linking_id'];
            }
            $flag = 0;
            if(empty($linkingName)) {
                echo $this->admin_notice_error('Bạn chưa nhập tên liên kết.');
                $flag = 1;
            }

            if(empty($linkingUrl)) {
                echo $this->admin_notice_error('Bạn chưa nhập đường dẫn liên kết.');
                $flag = 1;
            }

            $exist = $this->customers_obj->get_linking_url($_POST['linking_url']);
            if(!empty($exist) && empty($_POST['linking_url_confirm'])) {
                echo $this->admin_notice_error('Đường dẫn liên kết đã tồn tại. <a href="/wp-admin/admin.php?page=wp_linking_add_new&id='.$exist[0]['linking_id'].'">Edit ở đây</a>');
                $flag = 1;
            }
            if($flag == 0) {
                if(!empty($linkingId) && is_numeric($linkingId)) {
                    $flag = 0;
                    $exist = $this->customers_obj->get_linking_url($_POST['linking_url']);
                    if(!empty($exist) && empty($_POST['linking_url_confirm'])) {
                        echo $this->admin_notice_error('Đường dẫn liên kết đã tồn tại. <a href="/wp-admin/admin.php?page=wp_linking_add_new&id='.$exist[0]['linking_id'].'">Edit ở đây</a>');
                        $flag = 1;
                    }
                    if($flag == 0) {
                        $this->wp_update_linking(
                            array(
                                'linking_url'  => $linkingUrl,
                                'linking_name'  => $linkingName,
                                'is_active'  => $isActive,
                                'linking_priority'  => $linkingPriority
                            ),
                            array(
                                'linking_id' => $linkingId
                            )
                        );
                        echo $this->admin_notice_success('Cập nhật liên kết website thành công !');
                    }
                } else {
                    $this->wp_insert_linking(
                        array(
                            'linking_url'     => $linkingUrl,
                            'linking_name'    => $linkingName,
                            'is_active'       => $isActive,
                            'linking_priority'=> $linkingPriority
                        ),
                        array('%s','%s','%d','%d')
                    );
                    echo $this->admin_notice_success('Thêm mới liên kết website thành công !');
                }

            }
        }
        $flag = 0;
        if(isset($_GET['id']) && is_numeric($_GET['id'])) {
            $linking = $this->customers_obj->get_linking_id($_GET['id']);
            if(!empty($linking)) {
                $linkingName = $linking[0]['linking_name'];
                $linkingUrl = $urlConfirm = $linking[0]['linking_url'];
                $isActive = $linking[0]['is_active'];
                $linkingPriority = $linking[0]['linking_priority'];
                $linkingId = $linking[0]['linking_id'];
                $flag = 1;
            }
        }
        $linkingName = $this->customers_obj->wp_replace_text($linkingName);
        $linkingUrl = $this->customers_obj->wp_replace_text($linkingUrl);

        ?>
        <div class="wrap">
            <h1><?php echo ($flag == 0)? 'Thêm mới liên kết website': 'Edit (ID:'.$linkingId.')';?></h1>
            <form name="post" action="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=wp_linking_add_new" method="post" id="post" autocomplete="off" class="validate">
                <?php if($flag == 1): ?>
                    <input value="<?php echo $linkingId;?>" name="linking_id" type="hidden"/>
                    <input type="hidden" name="linking_url_confirm" value="<?php echo $urlConfirm;?>"/>
                <?php endif;?>
                <div id="poststuff">
                    <div id="post-body" class="metabox-holder columns-2">
                        <div id="post-body-content" style="position: relative;">
                            <div id="titlediv">
                                <style>
                                    <?php echo ($flag == 1)? '.current{color:rgba(240,245,250,.7) !important;}':'';?>
                                    #titlewrap {
                                        margin:15px 0px;
                                    }
                                    .title {
                                        font-weight: bold;
                                    }
                                </style>
                                <div id="titlewrap" class="form-field form-required">
                                    <div class="title">Nhập tên liên kết</div>
                                    <input type="text" name="linking_name" size = "30" value="<?php echo htmlentities($linkingName); ?>" id="linking_name" spellcheck="true" autocomplete="off" placeholder="Nhập tên liên kết" aria-required="true">
                                </div>
                                <div id="titlewrap" class="form-field form-required">
                                    <div class="title">Nhập đường dẫn liên kết</div>
                                    <input type="text" name="linking_url" size = "30" value="<?php echo htmlentities($linkingUrl); ?>" id="linking_url" spellcheck="true" autocomplete="off" placeholder="Nhập đường dẫn liên kết" aria-required="true">
                                </div>
                                <div id="titlewrap">
                                    <div class="title">Trạng thái</div>
                                    <select name="is_active">
                                        <option value="1" <?php echo ($isActive == '1')? 'selected': ''; ?>>Active</option>
                                        <option value="0" <?php echo ($isActive == '0')? 'selected': ''; ?>>InActive</option>
                                    </select>
                                </div>
                                <div id="titlewrap">
                                    <div class="title">Độ ưu tiên</div>
                                    <input value="<?php echo $linkingPriority; ?>" type="number" min="0" step="1" name="linking_priority" aria-required="true"/>
                                </div>
                                <div id="titlewrap">
                                    <input value="Lưu" type="submit" name="save" class="button button-primary button-large"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }

    /**
    * Plugin settings page
    */
    public function plugin_settings_page() {
        ?>
        <div class="wrap">
            <h2>Quản lý liên kết website <a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=wp_linking_add_new" class="page-title-action">Add New</a></h2>
            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-3">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <form id="posts-filter" method="get">
                                <ul class="subsubsub">
                                    <li class="all"><a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=wp_linking_list_table_class&all=yes" class="<?php echo isset($_GET['all']) ? 'current': '';?>" >All <span class="count">(<?php echo $this->customers_obj->get_all_linking();?>)</span></a> |</li>
                                    <li class="publish"><a href="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=wp_linking_list_table_class&status=1" class="<?php echo isset($_GET['status']) ? 'current': '';?>">Active <span class="count">(<?php echo $this->customers_obj->get_linking_active();?>)</span></a></li>
                                </ul>
                                <p class="search-box">
                                    <label class="screen-reader-text" for="post-search-input">Search Liên kết:</label>
                                    <input type="search" id="post-search-input" name="s" value="<?php echo isset($_GET['s']) ? $_GET['s'] : '';?>">
                                    <input type="submit" id="search-submit" class="button" value="Tìm kiếm">
                                    <input value="wp_linking_list_table_class" type="hidden" name="page"/>
                                </p>
                            </form>
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
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

add_action( 'plugins_loaded', function () {
    SP_Plugin::get_instance();
} );

