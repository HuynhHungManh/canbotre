<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Nisarg
 */
require get_template_directory() . '/captcha.php';
?>
<div class="col-md-3 right-content">
    <div class="row">
        <?php if(!empty(get_category_by_slug('thong-bao'))): ?>
        <?php $cat = get_category_by_slug('thong-bao');?>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a href="<?php echo get_category_link($cat->term_id);?>">
                        <?php echo $cat->name;?>
                    </a>
                </div>
                <section class="panel-body list-notify slider">
                <?php $posts = get_post_via_category($cat->term_id,3);?>
                <?php foreach($posts as $post):?>
                    <div class="thong-bao notify">
                        <a href="<?php echo get_post_link($cat->term_id,$post->post_name); ?>" rel="bookmark" class="image img-notify">
                            <img src="<?php echo get_thumbnail_post($post->ID);?>" class="post-image" title="<?php echo $post->post_title;?>" alt="<?php echo $post->post_title;?>"/>
                        </a>
                        <div class="content">
                            <div class="title-post">
                                <a href="<?php echo get_post_link($cat->term_id,$post->post_name); ?>" rel="bookmark">
                                    <?php echo cut_string_post($post->post_content,20);?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach;?>
                </section>
            </div>
        </div>
        <?php endif;?>
    </div>
    <!-- Góp ý -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Góp Ý</div>
                <div class="panel-body gopy">
                    <?php
                    $args = array(
                        'posts_per_page' => 8,
                        'orderby' => 'rand',
                        'post_type' => 'slick_slider',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'wpsisac_slider-category',
                                'field' => 'ID',
                                'terms' => '36'
                            )
                        ),
                        'post_status' => 'publish'
                    );
                    $posts_array = get_posts($args);
                    foreach ($posts_array as $post):?>
                        <?php $sliderurl = get_post_meta($post->ID,'wpsisac_slide_link', true );?>
                        <?php if(empty($sliderurl)):?>
                            <?php $sliderurl = '#'; ?>
                            <?php $onClick = 'onClick = "return false;"';?>
                        <?php else: ?>
                            <?php $onClick = '';?>
                        <?php endif;?>
                        <div class="banner-faq">
                            <a href="<?php echo $sliderurl;?>" <?php echo $onClick;?>>
                                <img src="<?php echo get_thumbnail_post($post->ID);?>" class="post-image" title="<?php echo $post->post_title;?>" alt="<?php echo $post->post_title;?>"/>
                            </a>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
    <!-- Góc thư giãn -->
    <!-- <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Góc thư giãn</div>
                <div class="panel-body media">
                    <ul class="list-media">
                        <li><span class="ficon ficon-media-1"></span> Thơ - Nhạc</li>
                        <li><span class="ficon ficon-media-2"></span> Phim - Ảnh vui</li>
                        <li><span class="ficon ficon-media-3"></span> Truyện cười</li>
                    </ul>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Liên kết website -->
    <div class="row">
        <div class="col-md-12">
            <div class="link-website">
                <h3 class="title">Liên kết website</h3>
                <div class="select">
                    <select class="linking" id="linking" onchange="linkingWebsite(this.value)">
                        <option value="">-Chọn website đơn vị-</option>
                        <?php if(function_exists('gg_get_linking_list')):?>
                            <?php if(!empty($linking_list = gg_get_linking_list())):?>
                                <?php foreach($linking_list as $link):?>
                                    <option value="<?php echo $link['linking_url'];?>"><?php echo $link['linking_name'];?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        <?php endif;?>
                    </select>
                    <script>
                        function linkingWebsite(linking) {
                            if(linking !== '---') {
                                window.open(linking, '_blank');
                            }
                            return false;
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
    <!-- Quảng cáo -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default bd0">
                <?php
                    $args = array(
                        'posts_per_page' => 8,
                        'orderby' => 'rand',
                        'post_type' => 'slick_slider',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'wpsisac_slider-category',
                                'field' => 'ID',
                                'terms' => '35'
                            )
                        ),
                        'post_status' => 'publish'
                    );
                    $posts_array = get_posts($args);
                    foreach ($posts_array as $post):?>
                        <?php $sliderurl = get_post_meta($post->ID,'wpsisac_slide_link', true );?>
                        <?php if(empty($sliderurl)):?>
                            <?php $sliderurl = '#'; ?>
                            <?php $onClick = 'onClick = "return false;"';?>
                        <?php else: ?>
                            <?php $onClick = '';?>
                        <?php endif;?>
                        <div class="banner-ads-right">
                            <a href="<?php echo $sliderurl;?>" target="_blank" <?php echo $onClick;?>>
                                <img src="<?php echo get_thumbnail_post($post->ID);?>" class="post-image" title="<?php echo $post->post_title;?>" alt="<?php echo $post->post_title;?>"/>
                            </a>
                        </div>
                    <?php endforeach;?>

            </div>
        </div>
    </div>
    <!-- banner ads -->
    <div class="row">
        <div class="col-md-12">
        <div class="banner-bottom">
            <?php echo do_shortcode('[slick-carousel-slider  design="design-6" category="33"
     slidestoshow="1" slidestoscroll="1" dots="true"
     arrows="true" autoplay="true"  autoplay_interval="5000"
     speed="1000" centermode="true" variablewidth="true"]');?>
            </div>
        </div>
    </div>
    <!-- Statictis -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default statictis">
                <div class="count">
                    Lượt truy cập: <b><?php echo wp_statistics_visit ('time');?></b>
                </div>
            </div>
        </div>
    </div>
</div>
