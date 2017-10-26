<?php

/**
 * Template Name: Popup hiển thị chi tiết thành viên
 */

$memberId = $_GET['member_id'];
if(empty($memberId) || !is_numeric($memberId)) {
    wp_die('Trang không hợp lệ');
}

$user = get_user_by('ID', $memberId);

if($user === false) {
    wp_die('Trang không hợp lệ');
}

$member_of_clb = get_user_meta($user->ID, '_member_of_clb', true);
if($member_of_clb != '1') {
    wp_die('Trang không hợp lệ');
}

$_profile_image_custom = get_user_meta($user->ID, '_profile_image_custom', true);
if($_profile_image_custom == false) {
    $_profile_image_custom = plugins_url('member-list-custom/images/not_image.jpg');
}

$fullName = get_user_meta($user->ID, '_full_name', true);

$_cq_cong_tac = get_user_meta($user->ID, '_cq_cong_tac', true);
$_position_in_cq = get_user_meta($user->ID, '_position_in_cq', true);
$_chuyen_mon = get_user_meta($user->ID, '_chuyen_mon', true);
$_thanh_vien_to = get_user_meta($user->ID, '_thanh_vien_to', true);
$_position_in_clb = get_user_meta($user->ID, '_position_in_clb', true);
$_ngay_sinh = get_user_meta($user->ID, '_ngay_sinh', true);
$_que_quan = get_user_meta($user->ID, '_que_quan', true);
$_noi_o = get_user_meta($user->ID, '_noi_o', true);
$_so_lien_lac = get_user_meta($user->ID, '_so_lien_lac', true);
$_chinh_tri = get_user_meta($user->ID, '_chinh_tri', true);
$_dang_doan_vien = get_user_meta($user->ID, '_dang_doan_vien', true);
$_dt_fax_co_quan = get_user_meta($user->ID, '_dt_fax_co_quan', true);
?>

<style>
    body {
        padding: 0px;
        margin: 0px;
        height: auto;
        border-style: groove;
        border-color: #71aef1;
        border-width: 7px;
    }
    .content {
        width: 100%;
        float:left;
        color: #990000;
        margin: 0px;
        padding: 0px;
        height: auto;
    }
    .content .content-body {
        padding: 15px;
    }
    .content b {
        color: #33405b;
    }
    .image {
        max-width: 120px;
        float: left;
        margin-right: 3%;
        width: 25%;
    }
    .image img {
        max-width: 100%;
        width: 100%;
        height: auto;
        object-fit: cover;
    }
    .content-right {
        float: left;
        text-align: left;
        width: 73%;
    }
    .content-right .full-name {
        text-align: center;
        padding: 0px;
        margin: 0px;
        font-size: 20px;
        background: #8091a2;
        color: #33405b;
        margin-bottom: 15px;
    }
    @media(max-width: 545px) {
        .content-right {
            margin-top: 15px;
            width: 100%;
        }
        .image {
            max-width: 100%;
            float: left;
            margin-right: 0%;
            width: 100%;
            text-align: center;
        }
        .image img {
            max-width: 120px;
            height: auto;
            object-fit: cover;
        }
        .content-right {
            padding-bottom: 15px;
        }
        body {
            border-style: inherit;
            border-color: inherit;
            border-width: inherit;
        }
    }
</style>
<div class="content">
    <div class="content-body">
    <div class="image">
            <img src="<?php echo $_profile_image_custom;?>" class="post-image" title="" alt="<?php echo $fullName;?>"/>
        </div>
        <div class="content-right">
            <h1 class="full-name"><?php echo $fullName;?></h1>
            <div><b>- CQ công tác:</b> <?php echo $_cq_cong_tac;?></div>
            <div><b>- Chức vụ tại CQ:</b> <?php echo $_position_in_cq;?></div>
            <div><b>- Chuyên môn:</b> <?php echo $_chuyen_mon;?></div>
            <div><b>- Thành viên tổ:</b> <?php echo $_thanh_vien_to;?></div>
            <div><b>- Chức vụ tại CLB:</b> <?php echo $_position_in_clb;?></div>
            <div><b>- Ngày sinh:</b> <?php echo $_ngay_sinh;?></div>
            <div><b>- Quê quán:</b> <?php echo $_que_quan;?></div>
            <div><b>- Nơi ở:</b> <?php echo $_noi_o;?></div>
            <div><b>- Sổ liên lạc:</b> <?php echo $_so_lien_lac;?></div>
            <div><b>- Đảng/Đoàn:</b> <?php echo $_dang_doan_vien;?></div>
            <div><b>- ĐT/FAX Cơ quan:</b> <?php echo $_dt_fax_co_quan;?></div>
            <div><b>- Email:</b> <?php echo $user->user_email;?></div>
        </div>
    </div>
</div>
