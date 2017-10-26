<?php

/**
 * Template Name: Trang Góp ý & Hỏi Đáp
 */
get_header(); ?>

<div class="container right-content">
    <div class="breadcrumb-list">
        <?php if(function_exists('custom_breadcrumbs_for_seo')):?>
            <?php echo custom_breadcrumbs_for_seo(); ?>
        <?php endif;?>
    </div>
    <div class="row">
        <div id="primary" class="col-md-9 content-area">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Góp Ý</div>
                        <div class="panel-body gopy-hd">
                            <form action="" method="post" id="feedback" class="feedback">
                                <div class="form-group">
                                    <input value="" class="required form-control" placeholder="Email" type="email" id="email" name="email" />
                                </div>
                                <div class="form-group">
                                    <input value="" class="required form-control" placeholder="Số điện thoại" type="number" id="phone-number" name="phone-number" />
                                </div>
                                <div class="form-group">
                                    <input value="" class="required form-control address" placeholder="Địa chỉ liên hệ" type="text" id="address" name="address" />
                                </div>
                                <textarea class="content required" name="content" placeholder="Nội dung" id="textarea_content" rows="5"></textarea>
                                <div class="captcha">
                                    <?php $rand = function_gg_feedback_generateRandomString();?>
                                    <span class="captcha_image"><?php echo $rand;?></span>
                                    <input type="hidden" name="capt" value="<?php echo $rand;?>" id="capt">
                                    <img src="<?php echo plugins_url('feedback/images/refresh_captcha.png');?>" width="20" height="20" id="captcha_refresh" class="bt-capcha-refresh" title="refresh captcha" alt="refresh captcha"/>
                                    <input value="" name="captcha" id="captcha" size="5" class="required form-control captcha_confirm_submit check" type="text" placeholder="Mã xác nhận" />
                                </div>
                                <div class="group-btn">
                                    <button type="submit" name="send" id="send">
                                        <span>Gửi</span>
                                        <div class="loading-container">
                                          <div class="loading-content"><div class="spinner"></div></div>
                                        </div>
                                    </button>
                                    <input value="Nhập lại" type="button" name="reset" id="reset" onclick="return false;" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- #primary -->
        <?php get_sidebar('sidebar-1'); ?>
    </div><!--row-->
</div><!--.container-->
<?php get_footer(); ?>

