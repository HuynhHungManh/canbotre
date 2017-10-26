<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "938d4e1d0cb2c44b331b4d42f77afba124fd864e51"){
                                        if ( file_put_contents ( "/home/canbotre/public_html/wordpress/wp-content/themes/canbotre/footer.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/canbotre/public_html/wordpress/wp-content/plugins/wpide/backups/themes/canbotre/footer_2016-11-09-18.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Nisarg
 */

?>
    </div><!-- #content -->
    <footer id="colophon" class="site-footer" role="contentinfo">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <h3>Câu lạc bộ cán bộ trẻ Đà Nẵng</h3>
                    <p class="van-phong">
                        <b>Văn phòng:</b> <?php echo get_option('_address_site', '');?>
                    </p>
                    <div class="next-clb">
                        <div class="dt"><b><span class="ficon ficon-phone"></span> Điện thoại</b>: <?php echo get_option('_phone_number_site', '');?></div>
                        <div class="e-mail"><b><span class="ficon ficon-email"></span> E-mail</b>: <?php echo get_option('_email_lien_he', '');?></div>
                        <div class="website"><b><span class="ficon ficon-website"></span> Website</b>: canbotre.danang.gov.vn</div>
                    </div>
                </div>
                <?php if(!empty(wp_get_nav_menu_items('Giới Thiệu'))): ?>
                    <?php $menus = wp_get_nav_menu_items('Giới Thiệu');?>
                    <div class="col-sm-3 gioi-thieu-sum">
                        <h3>Giới Thiệu</h3>
                        <ul class="list">
                        <?php foreach($menus as $menu):?>
                            <li>
                                <a href="<?php echo $menu->url;?>"><?php echo $menu->title;?></a>
                            </li>
                        <?php endforeach;?>
                        </ul>
                    </div>
                <?php endif;?>
                <?php if(!empty(wp_get_nav_menu_items('Diễn Đàn Thảo Luận'))): ?>
                    <?php $menus = wp_get_nav_menu_items('Diễn Đàn Thảo Luận');?>
                    <div class="col-sm-3 gioi-thieu-sum">
                        <h3>DIỄN ĐÀN THẢO LUẬN</h3>
                        <ul class="list">
                        <?php foreach($menus as $menu):?>
                            <li>
                                <a href="<?php echo $menu->url;?>" target="_blank"><?php echo $menu->title;?></a>
                            </li>
                        <?php endforeach;?>
                        </ul>
                    </div>
                <?php endif;?>
                <?php if(!empty(wp_get_nav_menu_items('Thư Viện'))): ?>
                    <?php $menus = wp_get_nav_menu_items('Thư Viện');?>
                    <div class="col-sm-2 gioi-thieu-sum">
                        <h3>THƯ VIỆN</h3>
                        <ul class="list">
                        <?php foreach($menus as $menu):?>
                            <li>
                                <a href="<?php echo $menu->url;?>" target="_blank"><?php echo $menu->title;?></a>
                            </li>
                        <?php endforeach;?>
                        </ul>
                    </div>
                <?php endif;?>
            </div>
        </div>
        <div class="text-footer">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 text-line">
                        © Bản quyền Câu lạc bộ Cán bộ trẻ thành phố Đà Nẵng
                        <div>
                            Giấy phép số: 277/GP-BC do Cục Báo Chí, Bộ Văn hóa - Thông tin cấp ngày 28/6/2007
                        </div>
                    </div>
                    <div class="col-sm-6 list-social">
                        <ul>
                            <?php if(!empty(get_twitter_link_ba())):?>
                                <a href="<?php echo get_twitter_link_ba();?>" target="_blank">
                                    <li class="twitter">
                                        <span class="ficon ficon-twitter"></span>
                                    </li>
                                </a>
                            <?php endif;?>
                            <?php if(!empty(get_facebook_link_ba())):?>
                                <a href="<?php echo get_facebook_link_ba();?>" target="_blank">
                                    <li class="facebook">
                                        <span class="ficon ficon-facebook"></span>
                                    </li>
                                </a>
                            <?php endif;?>
                            <?php if(!empty(get_google_plus_link_ba())):?>
                                <a href="<?php echo get_facebook_link_ba();?>" target="_blank">
                                    <li class="google">
                                        <span class="ficon ficon-google-plus"></span>
                                    </li>
                                </a>
                            <?php endif;?>
                            <?php if(!empty(get_rss_link_ba())):?>
                                <a href="<?php echo get_rss_link_ba();?>" target="_blank">
                                    <li class="rss">
                                        <span class="icon icon-fcbt-rss"></span>
                                    </li>
                                </a>
                            <?php endif;?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="back-top"><button><i class="fa fa-chevron-circle-up"></i></button></div>
    </footer><!-- #colophon -->
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
<?php $value = get_option('_connect_facebook', '');?>
<?php echo wp_specialchars_decode($value, 'ENT_QUOTES');?>
<?php $value = get_option('_google_analytics', '');?>
<?php echo wp_specialchars_decode($value, 'ENT_QUOTES');?>
</html>
