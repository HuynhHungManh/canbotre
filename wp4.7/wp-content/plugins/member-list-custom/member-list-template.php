<?php
/**
 * The template for displaying member list pages.
 *
 *
 *
 * @package member-list-custom
 */
global $paged;
if($paged == 0) {
    $paged = 1;
}
get_header(); ?>
<div class="container">
    <div class="breadcrumb-list">
        <?php if(function_exists('custom_breadcrumbs_for_seo')):?>
            <?php echo custom_breadcrumbs_for_seo(); ?>
        <?php endif;?>
    </div>
    <div class="row">
        <div id="primary" class="col-md-9 content-area">
            <div class="panel panel-default archive-list">
                <div class="panel-heading archive-heading">
                    <h1 class="page-title" property="name">Danh sách thành viên của CLB Cán bộ trẻ Đà Nẵng</h1>
                </div>
                <div class="panel-body post-body">
                    <?php $memberList = gg_get_list_member_clb($paged);?>
                    <?php if (!empty($memberList)): ?>
                        <div class="post-listing archive-box">
                            <?php foreach ($memberList as $member): ?>
                                <?php $fullName = $_cq_cong_tac = $_position_in_cq = $_chuyen_mon = $_thanh_vien_to = $_position_in_clb = ''; ?>
                                <?php $_profile_image_custom = '';?>
                                <?php $userMetas = $member['user_meta'];?>
                                <?php foreach($userMetas as $userMeta):?>
                                    <?php
                                    if(!empty(gg_get_user_meta('_full_name', $userMeta))) {
                                        $fullName = gg_get_user_meta('_full_name', $userMeta);
                                    }
                                    if(!empty(gg_get_user_meta('_cq_cong_tac', $userMeta))) {
                                        $_cq_cong_tac = gg_get_user_meta('_cq_cong_tac', $userMeta);
                                    }
                                    if(!empty(gg_get_user_meta('_position_in_cq', $userMeta))) {
                                        $_position_in_cq = gg_get_user_meta('_position_in_cq', $userMeta);
                                    }
                                    if(!empty(gg_get_user_meta('_chuyen_mon', $userMeta))) {
                                        $_chuyen_mon = gg_get_user_meta('_chuyen_mon', $userMeta);
                                    }
                                    if(!empty(gg_get_user_meta('_thanh_vien_to', $userMeta))) {
                                        $_thanh_vien_to = gg_get_user_meta('_thanh_vien_to', $userMeta);
                                    }
                                    if(!empty(gg_get_user_meta('_position_in_clb', $userMeta))) {
                                        $_position_in_clb = gg_get_user_meta('_position_in_clb', $userMeta);
                                    }
                                    if(!empty(gg_get_user_meta('_profile_image_custom', $userMeta))) {
                                        $_profile_image_custom = gg_get_user_meta('_profile_image_custom', $userMeta);
                                    }
                                    ?>
                                <?php endforeach;
                                if(empty($_profile_image_custom)) {
                                        $_profile_image_custom = plugins_url('member-list-custom/images/not_image.jpg');
                                    }
                                ?>
                                <div class="block">
                                    <article class="item-list tie_audio item-list-member">
                                            <div class="image popup-detail-member">
                                                <a href="<?php echo get_site_url();?>/popup-chi-tiet-thanh-vien?member_id=<?php echo $member['user_id'];?>"><img src="<?php echo $_profile_image_custom;?>" class="post-image" title="" alt="<?php echo $fullName;?>"/>
                                                </a>
                                            </div>
                                            <div class="content">
                                                <h4 class="full-name popup-detail-member"><a href="<?php echo get_site_url();?>/popup-chi-tiet-thanh-vien?member_id=<?php echo $member['user_id'];?>"><?php echo $fullName;?></a></h4>
                                                <div><b>- CQ công tác:</b> <?php echo $_cq_cong_tac;?></div>
                                                <div><b>- Chức vụ tại CQ:</b> <?php echo $_position_in_cq;?></div>
                                                <div><b>- Chuyên môn:</b> <?php echo $_chuyen_mon;?></div>
                                                <div><b>- Thành viên tổ:</b> <?php echo $_thanh_vien_to;?></div>
                                                <div><b>- Chức vụ tại CLB:</b> <?php echo $_position_in_clb;?></div>
                                                <div><b>- Email:</b> <span class="email"><?php echo $member['user_email'];?></span></div>
                                            </div>
                                        <div class="clear"></div>
                                    </article>
                                </div>
                            <?php endforeach;?>
                        </div>
                        <div class="pagination-post">
                            <?php
                                $total = gg_get_all_member_list();
                                $args = array(
                                    'type' => 'list',
                                    'prev_text'          => __('&nbsp;'),
                                    'next_text'          => __('&nbsp;'),
                                    'current'            => $paged,
                                    'total'              => $total,
                                    'mid_size'           => '1'
                                );
                                $paginate = preg_replace('#<ul.*?>#si', '<ul class="pagination">', paginate_links( $args ));
                            echo preg_replace("/<li><span(.*)current\'/", '<li class="active"><span', $paginate); ?>
                        </div>
                    <?php else:?>
                        <div class="post-listing archive-box">
                            <h2>Dữ liệu không có.</h2>
                        </div>
                    <?php endif;?>

                </div>
            </div>
        </div><!-- #primary -->
        <?php get_sidebar('sidebar-1'); ?>
    </div> <!--.row-->
</div><!--.container-->
<?php get_footer(); ?>
