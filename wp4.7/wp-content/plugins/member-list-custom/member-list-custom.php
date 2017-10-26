<?php

/*
Plugin Name: Plugin Manage Member list in the system
Plugin URI: http://canbotredanang.vn
Description: Enable to use
Version: 1.0
Author: cuongnq@greenglobal.vn
Author URI: http://greenglobal.vn
Copyright 2016  Ngo Quang Cuong  (email : cuongnq@greenglobal.vn)
*/
define('Z_IMAGE_PLACEHOLDER', plugins_url('member-list-custom/images/placeholder.png'));

class GG_Member_List {
    /**
     * A Unique Identifier
     */
     protected $plugin_slug;
    /**
     * A reference to an instance of this class.
     */
    private static $instance;
    /**
     * The array of templates that this plugin tracks.
     */
    protected $templates;
    /**
     * Returns an instance of this class.
     */
    public static function get_instance() {
        if( null == self::$instance ) {
                self::$instance = new GG_Member_List();
        }
        return self::$instance;
    }
    /**
     * Initializes the plugin by setting filters and administration functions.
     */
    private function __construct() {
        $this->templates = array();
        // Add a filter to the attributes metabox to inject template into the cache.
        add_filter(
            'page_attributes_dropdown_pages_args',
             array( $this, 'register_project_templates' )
        );
        // Add a filter to the save post to inject out template into the page cache
        add_filter(
            'wp_insert_post_data',
            array( $this, 'register_project_templates' )
        );
        // Add a filter to the template include to determine if the page has our
        // template assigned and return it's path
        add_filter(
            'template_include',
            array( $this, 'view_project_template')
        );
        // Add your templates to this array.
        $this->templates = array(
            'member-list-template.php'  => 'Member List Page Template',
        );

    }
    /**
     * Adds our template to the pages cache in order to trick WordPress
     * into thinking the template file exists where it doens't really exist.
     *
     */
    public function register_project_templates( $atts ) {
        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
        // Retrieve the cache list.
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if ( empty( $templates ) ) {
                $templates = array();
        }
        // New cache, therefore remove the old one
        wp_cache_delete( $cache_key , 'themes');
        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge( $templates, $this->templates );
        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add( $cache_key, $templates, 'themes', 1800 );
        return $atts;
    }

    /**
     * Checks if the template is assigned to the page
     */
    public function view_project_template($template) {
        if(is_page('Thành viên')) {
            global $post;
            if (!isset($this->templates[get_post_meta(
                $post->ID, '_wp_page_template', true
            )])) {
                return $template;
            }
            $file = plugin_dir_path(__FILE__). get_post_meta(
                $post->ID, '_wp_page_template', true
            );
            // Just to be safe, we check if the file exist first
            if(file_exists($file)) {
                return $file;
            } else { echo $file; }
            return $template;
        } else {
            return $template;
        }
    }
}

add_action('plugins_loaded', array('GG_Member_List', 'get_instance'));

function add_page_member_in_system() {
    $the_page_title = 'Thành viên';
    $the_page = get_page_by_title($the_page_title);

    if (!$the_page) {
        // Create post object
        $_p = array();
        $_p['post_title'] = $the_page_title;
        $_p['post_content'] = "";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $_p['post_category'] = array(1); // the default 'Uncategorised'
        // Insert the post into the database
        $thePageId = wp_insert_post($_p);
        add_post_meta($thePageId, '_wp_page_template' , 'member-list-template.php');
    }
}

/*
 * create hook add first table after plugin installed
 */
register_activation_hook(__FILE__,'add_page_member_in_system');

/*
 * add fields custom in users
 */

add_action('show_user_profile', 'gg_custom_fields_users');
add_action('edit_user_profile', 'gg_custom_fields_users');

function gg_custom_fields_users($user) {
    if (get_bloginfo('version') >= 3.5)
        wp_enqueue_media();
    else {
        wp_enqueue_style('thickbox');
        wp_enqueue_script('thickbox');
    } ?>
    <?php $_member_of_clb = get_user_meta($user->ID, '_member_of_clb', true);?>
    <?php if($_member_of_clb == "1"):?>
    <h2>Thông tin bổ sung</h2>
    <table class="form-table">
        <tbody>
            <?php gg_user_create_custom_field($user->ID, 'Họ và tên', '_full_name');?>

            <?php gg_user_create_custom_field($user->ID, 'Cơ quan công tác', '_cq_cong_tac');?>
            <?php gg_user_create_custom_field($user->ID, 'Chức vụ tại Cơ quan', '_position_in_cq');?>
            <?php gg_user_create_custom_field($user->ID, 'Chuyên môn', '_chuyen_mon');?>
            <?php gg_user_create_custom_field($user->ID, 'Thành viên tổ', '_thanh_vien_to');?>
            <?php gg_user_create_custom_field($user->ID, 'Chức vụ tại CLB', '_position_in_clb');?>
            <?php gg_user_create_custom_field($user->ID, 'Ngày sinh', '_ngay_sinh', true);?>
            <?php gg_user_create_custom_field($user->ID, 'Quê quán', '_que_quan');?>
            <?php gg_user_create_custom_field($user->ID, 'Nơi ở', '_noi_o');?>
            <?php gg_user_create_custom_field($user->ID, 'Sổ liên lạc', '_so_lien_lac');?>
            <?php gg_user_create_custom_field($user->ID, 'Chính trị', '_chinh_tri');?>
            <?php gg_user_create_custom_field($user->ID, 'Đảng/Đoàn', '_dang_doan_vien');?>
            <?php gg_user_create_custom_field($user->ID, 'ĐT/FAX cơ quan', '_dt_fax_co_quan');?>
        </tbody>
    </table>
    <?php endif;?>
    <?php if(current_user_can('edit_users')):?>
    <table class="form-table">
        <tbody>
            <tr class="form-field">
                <th scope="row"><label for="role">Thành viên trong CLB Cán bộ trẻ Đà Nẵng?:</label></th>
                <td>
                    <select name="_member_of_clb">
                        <option value="2" <?php echo ($_member_of_clb == "2") ? 'selected': '';?>>No</option>
                        <option value="1" <?php echo ($_member_of_clb == "1") ? 'selected': '';?>>Yes</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <?php endif;?>
<?php }

// upload using wordpress upload
function gg_member_list_custom_script() {
    return '<script type="text/javascript">
        jQuery(document).ready(function($) {
            var wordpress_ver = "'.get_bloginfo("version").'", upload_button;
            $(".z_upload_image_button").click(function(event) {
                upload_button = $(this);
                var frame;
                if (wordpress_ver >= "3.5") {
                    event.preventDefault();
                    if (frame) {
                        frame.open();
                        return;
                    }
                    frame = wp.media();
                    frame.on( "select", function() {
                        // Grab the selected attachment.
                        var attachment = frame.state().get("selection").first();
                        frame.close();
                        if (upload_button.parent().prev().children().hasClass("tax_list")) {
                            upload_button.parent().prev().children().val(attachment.attributes.url);
                            upload_button.parent().prev().prev().children().attr("src", attachment.attributes.url);
                        }
                        else
                            $("#taxonomy_image").val(attachment.attributes.url);
                    });
                    frame.open();
                }
                else {
                    tb_show("", "media-upload.php?type=image&amp;TB_iframe=true");
                    return false;
                }
            });

            $(".z_remove_image_button").click(function() {
                $(".taxonomy-image").attr("src", "'.Z_IMAGE_PLACEHOLDER.'");
                $("#taxonomy_image").val("");
                $(this).parent().siblings(".title").children("img").attr("src","' . Z_IMAGE_PLACEHOLDER . '");
                $(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
                return false;
            });

            if (wordpress_ver < "3.5") {
                window.send_to_editor = function(html) {
                    imgurl = $("img",html).attr("src");
                    if (upload_button.parent().prev().children().hasClass("tax_list")) {
                        upload_button.parent().prev().children().val(imgurl);
                        upload_button.parent().prev().prev().children().attr("src", imgurl);
                    }
                    else
                        $("#taxonomy_image").val(imgurl);
                    tb_remove();
                }
            }

            $(".editinline").click(function() {
                var tax_id = $(this).parents("tr").attr("id").substr(4);
                var thumb = $("#tag-"+tax_id+" .thumb img").attr("src");

                if (thumb != "' . Z_IMAGE_PLACEHOLDER . '") {
                    $(".inline-edit-col :input[name=\'taxonomy_image\']").val(thumb);
                } else {
                    $(".inline-edit-col :input[name=\'taxonomy_image\']").val("");
                }

                $(".inline-edit-col .title img").attr("src",thumb);
            });
        });
    </script>';
}

add_action('personal_options_update', 'gg_custom_fields_users_save' );
add_action('edit_user_profile_update', 'gg_custom_fields_users_save' );
add_action('user_new_form', 'gg_custom_field_user_register');
add_action('user_register', 'save_custom_user_profile_fields');

function gg_custom_field_user_register() { ?>
    <table class="form-table">
        <tbody>
            <tr class="form-field">
                <th scope="row"><label for="role">Thành viên trong CLB Cán bộ trẻ Đà Nẵng?:</label></th>
                <td>
                    <select name="_member_of_clb">
                        <option value="1">Yes</option>
                        <option value="2">No</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
<?php }

function save_custom_user_profile_fields($user_id){
    # again do this only if you can
    if(!current_user_can('create_users'))
        return false;
    # save my custom field
    if (!empty($_POST['_member_of_clb'])) {
        $_member_of_clb = get_user_meta($user_id, '_member_of_clb', true);
        if(empty($_member_of_clb)) {
            add_user_meta($user_id, '_member_of_clb', $_POST['_member_of_clb'], true );
        } else {
            update_user_meta($user_id, '_member_of_clb', $_POST['_member_of_clb']);
        }
    }
}

function gg_custom_fields_users_save($user_id) {
    gg_user_save_custom_field($user_id, $_POST['_cq_cong_tac'], '_cq_cong_tac');
    gg_user_save_custom_field($user_id, $_POST['_full_name'], '_full_name');
    gg_user_save_custom_field($user_id, $_POST['_position_in_cq'], '_position_in_cq');
    gg_user_save_custom_field($user_id, $_POST['_chuyen_mon'], '_chuyen_mon');
    gg_user_save_custom_field($user_id, $_POST['_thanh_vien_to'], '_thanh_vien_to');
    gg_user_save_custom_field($user_id, $_POST['_position_in_clb'], '_position_in_clb');
    gg_user_save_custom_field($user_id, $_POST['_profile_image_custom'], '_profile_image_custom');
    gg_save_member_of_clb($user_id, $_POST['_member_of_clb'], '_member_of_clb');
    gg_user_save_custom_field($user_id, $_POST['_ngay_sinh'], '_ngay_sinh');
    gg_user_save_custom_field($user_id, $_POST['_que_quan'], '_que_quan');
    gg_user_save_custom_field($user_id, $_POST['_noi_o'], '_noi_o');
    gg_user_save_custom_field($user_id, $_POST['_so_lien_lac'], '_so_lien_lac');
    gg_user_save_custom_field($user_id, $_POST['_chinh_tri'], '_chinh_tri');
    gg_user_save_custom_field($user_id, $_POST['_dang_doan_vien'], '_dang_doan_vien');
    gg_user_save_custom_field($user_id, $_POST['_dt_fax_co_quan'], '_dt_fax_co_quan');
}

function gg_save_member_of_clb($userId, $value, $metaKey) {
    if (!current_user_can('edit_users')) {
        return false;
    }
    if(!empty($value) && is_numeric($value)) {
        $_cq_cong_tac = get_user_meta($userId, $metaKey, true);
        if(empty($_cq_cong_tac)) {
            add_user_meta($userId, $metaKey, $value, true );
        } else {
            update_user_meta($userId, $metaKey, $value);
        }
    }
}

function gg_user_save_custom_field($userId, $value, $metaKey) {
    if (!current_user_can('edit_user', $userId)) {
        return false;
    }
    if(!empty($value)) {
        $_cq_cong_tac = get_user_meta($userId, $metaKey, true);
        if(empty($_cq_cong_tac)) {
            add_user_meta($userId, $metaKey, $value, true );
        } else {
            update_user_meta($userId, $metaKey, $value);
        }
    }
}

function gg_user_create_custom_field($userId, $textField, $metaKey, $clander = false) { $readonly = '';?>
    <tr class="user-<?php echo $metaKey;?>-wrap">
        <th><label for="email"><?php echo $textField;?>: </label></th>
        <?php if($clander == true):?>
        <?php $readonly = 'readonly';?>
        <link rel="stylesheet" href="/wp-content/themes/canbotre/js/jquery-calendar/jquery-ui.css">
        <script src="/wp-content/themes/canbotre/js/jquery-calendar/jquery-1.12.4.js"></script>
        <script src="/wp-content/themes/canbotre/js/jquery-calendar/jquery-ui.js"></script>
        <script>
        $( function() {
        $( "#<?php echo $metaKey;?>" ).datepicker({ dateFormat: 'dd/mm/yy', changeYear: true,changeMonth: true, yearRange: '1910:2026'});
        } );
        </script>
        <?php endif;?>
        <td>
            <input type="text" name="<?php echo $metaKey;?>" id="<?php echo $metaKey;?>" value="<?php echo esc_attr(get_user_meta($userId, $metaKey, true)); ?>" class="regular-text ltr" <?php echo $readonly;?>>
        </td>
    </tr>
<?php }

function gg_get_list_member_clb($paged = 1) {
    global $wpdb;
    $limit = (int) get_option('posts_per_page');
    if($limit <= 0) {
        $limit = 5;
    }
    $offset = (((int)$paged - 1) * $limit);
    $result = $wpdb->get_results("
        SELECT $wpdb->users.ID, $wpdb->users.user_email
        FROM $wpdb->users
        INNER JOIN $wpdb->usermeta on $wpdb->users.ID=$wpdb->usermeta.user_id
        WHERE $wpdb->usermeta.meta_key='_member_of_clb'
        AND $wpdb->usermeta.meta_value ='1'
        ORDER BY $wpdb->users.ID DESC
        LIMIT $offset, $limit
        ");
    $rs = array();
    foreach ($result as $value) {
        $array = array();
        $array['user_id'] = $value->ID;
        $array['user_email'] = $value->user_email;
        $userMeta = $wpdb->get_results("
        SELECT *
        FROM $wpdb->usermeta
        WHERE $wpdb->usermeta.user_id = '".$value->ID."'
        ");
        $array['user_meta'] = $userMeta;
        $rs[] = $array;
    }
    return $rs;
}

function gg_get_user_meta($valueCondition, $userMeta) {
    if(!empty($userMeta->meta_key) && ($userMeta->meta_key == $valueCondition)) {
        return $userMeta->meta_value;
    } else {
        return '';
    }
}

function gg_get_all_member_list() {
    global $wpdb;
    $result = $wpdb->get_results("
        SELECT COUNT(*) as count
        FROM $wpdb->users
        INNER JOIN $wpdb->usermeta on $wpdb->users.ID=$wpdb->usermeta.user_id
        WHERE $wpdb->usermeta.meta_key='_member_of_clb'
        AND $wpdb->usermeta.meta_value ='1'
        ");
    $limit = (int) get_option('posts_per_page');
    if($limit <= 0) {
        $limit = 5;
    }
    $count = $result[0]->count;
    $pages = ($count + $limit - 1)/$limit;
    return $pages;
}
