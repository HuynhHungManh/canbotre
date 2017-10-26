<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Nisarg
 */

get_header(); ?>
<div class="container page-404">
    <div class="row">
        <div id="primary" class="col-md-12 content-area content-404-page">
            <div class="container panel panel-default archive-list">
                <div class="panel-body post-body row">
                    <div class="col-md-5">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/404.png" class="img-error-404">
                    </div>
                    <div class="col-md-7 text-error">
                        <h3>KHÔNG TÌM THẤY TRANG</h3>
                        <div class="line1">Trang bạn đã yêu cầu không thể được hiển thị</div>
                        <div class="line2">Trang này không tồn tại hoặc do lỗi tạm thời của máy chủ</div>
                        <div class="back-to">
                            <a href="<?php echo get_site_url();?>" title="Trở lại trang chủ" class="back-home"><span class="ficon ficon-arrow-back"></span>Trở lại trang chủ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- #primary -->
    </div> <!--.row-->
</div><!--.container-->
<?php get_footer(); ?>
