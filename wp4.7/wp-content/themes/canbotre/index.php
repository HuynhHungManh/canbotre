<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Nisarg
 */

get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col-md-12 banner-slider">
            <?php echo do_shortcode('[slick-slider category="32" autoplay="true" sliderheight="auto" autoplay_interval="2000" speed="1000" fade="true" centermode="true" show_content="true"]');?>
        </div>
        <div id="primary" class="col-md-9 content-area content-home">
            <div class="row">
            <div class="col-md-12 banner-ads-top">
                <?php
                    $args = array(
                        'posts_per_page' => 8,
                        'orderby' => 'rand',
                        'post_type' => 'slick_slider',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'wpsisac_slider-category',
                                'field' => 'ID',
                                'terms' => '34'
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
                    <div class="banner-ads-top-home">
                        <a href="<?php echo $sliderurl;?>" target="_blank" <?php echo $onClick;?>>
                            <img src="<?php echo get_thumbnail_post($post->ID);?>" class="post-image" title="<?php echo $post->post_title;?>" alt="<?php echo $post->post_title;?>"/>
                        </a>
                    </div>
                <?php endforeach;?>
            </div>
            <?php if(!empty(get_category_by_slug('tin-hoat-dong'))): ?>
                <?php $cat = get_category_by_slug('tin-hoat-dong');?>
                <div class="col-md-6">
                    <div class="panel panel-default panel-color-blue panel-color-newest">
                        <div class="panel-heading">
                            <a href="<?php echo get_category_link($cat->term_id);?>">
                                <?php echo $cat->name;?>
                            </a>
                        </div>
                        <div class="panel-body">
                        <?php $posts = get_post_via_category($cat->term_id);?>
                        <?php if(!empty($first = $posts[0])):?>
                            <div class="first block news-image">
                                <a href="<?php echo get_post_link($cat->term_id,$first->post_name); ?>" rel="bookmark" class="image">
                                    <img src="<?php echo get_thumbnail_post($first->ID);?>" class="post-image" title="<?php echo $first->post_title;?>" alt="<?php echo $first->post_title;?>"/>
                                </a>
                                <div class="content">
                                    <div class="title-post">
                                        <?php
                                            $now = time(); // or your date as well
                                            $my_date = strtotime($first->post_date);
                                            $datediff = $now - $my_date;
                                            $day = floor($datediff / (60 * 60 * 24));
                                        ?>
                                        <?php if($day <= 5):?>
                                            <?php $new = '<span class="icon-fcbt-new">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span>
                </span>';?>
                                        <?php endif;?>
                                        <a href="<?php echo get_post_link($cat->term_id,$first->post_name); ?>" rel="bookmark">
                                            <?php echo cut_string_post($first->post_title,20);?>
                                            <?php echo $new;?>
                                        </a>
                                    </div>
                                    <div class="post-date">
                                        <span class="ficon ficon-time"></span><?php echo format_date_post($first->post_modified);?>
                                    </div>
                                    <div class="post-description">
                                        <?php echo cut_string_post($first->post_content, 20);?>
                                    </div>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if(count($posts) >=1): ?>
                            <?php for($i=1; $i<count($posts); $i++):?>
                                <?php if(!empty($posts[$i])): ?>
                                    <div class="block">
                                        <a href="<?php echo get_post_link($cat->term_id,$posts[$i]->post_name); ?>" rel="bookmark" class="image">
                                            <img src="<?php echo get_thumbnail_post($posts[$i]->ID);?>" class="post-image" title="<?php echo $posts[$i]->post_title;?>" alt="<?php echo $posts[$i]->post_title;?>"/>
                                        </a>
                                        <div class="title-post">
                                            <?php
                                                $new = '';
                                                $now = time(); // or your date as well
                                                $my_date = strtotime($posts[$i]->post_date);
                                                $datediff = $now - $my_date;
                                                $day = floor($datediff / (60 * 60 * 24));
                                            ?>
                                            <?php if($day <= 5):?>
                                                <?php $new = '<span class="icon-fcbt-new">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span>
                </span>';?>
                                            <?php endif;?>
                                            <a href="<?php echo get_post_link($cat->term_id,$posts[$i]->post_name); ?>" rel="bookmark">
                                                <?php echo cut_string_post($posts[$i]->post_title, 20);?>
                                                <?php echo $new;?>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif;?>
                            <?php endfor;?>
                        <?php endif;?>
                        <div class="bottom">
                            <a href="<?php echo get_category_link($cat->term_id);?>" class="bt-view-all">Xem tất cả <span class="ficon ficon-arrow"></span></a>
                        </div>
                        </div>
                    </div>
                </div>
            <?php endif;?>
            <!-- Góc nhìn cán bộ trẻ -->
            <?php if(!empty(get_category_by_slug('goc-nhin-can-bo-tre'))): ?>
                <?php $cat = get_category_by_slug('goc-nhin-can-bo-tre');?>
                <div class="col-md-6">
                    <div class="panel panel-default panel-color-green panel-color-newest">
                        <div class="panel-heading">
                            <a href="<?php echo get_category_link($cat->term_id);?>">
                                <?php echo $cat->name;?>
                            </a>
                        </div>
                        <div class="panel-body">
                        <?php $posts = get_post_via_category($cat->term_id,1);?>
                        <?php if(!empty($first = $posts[0])):?>
                            <div class="first block news-image">
                                <a href="<?php echo get_post_link($cat->term_id,$first->post_name); ?>" rel="bookmark" class="image">
                                    <img src="<?php echo get_thumbnail_post($first->ID);?>" class="post-image" title="<?php echo $first->post_title;?>" alt="<?php echo $first->post_title;?>"/>
                                </a>
                                <div class="content">
                                    <div class="title-post">
                                        <?php
                                            $new = '';
                                            $now = time(); // or your date as well
                                            $my_date = strtotime($first->post_date);
                                            $datediff = $now - $my_date;
                                            $day = floor($datediff / (60 * 60 * 24));
                                        ?>
                                        <?php if($day <= 5):?>
                                            <?php $new = '<span class="icon-fcbt-new">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span>
                </span>';?>
                                        <?php endif;?>
                                        <a href="<?php echo get_post_link($cat->term_id,$first->post_name); ?>" rel="bookmark">
                                            <?php echo $first->post_title;?>
                                            <?php echo $new;?>
                                        </a>
                                    </div>
                                    <div class="post-date">
                                        <span class="ficon ficon-time"></span><?php echo format_date_post($first->post_modified);?>
                                    </div>
                                    <div class="post-description">
                                        <?php echo cut_string_post($first->post_content, 130);?>
                                    </div>
                                </div>
                            </div>
                        <?php endif;?>
                        <div class="bottom">
                            <a href="<?php echo get_category_link($cat->term_id);?>" class="bt-view-all">Xem tất cả <span class="ficon ficon-arrow"></span></a>
                        </div>
                        </div>
                    </div>
                </div>
            <?php endif;?>
            </div>
            <div class="row">
            <!-- Chuyên đề-->
            <?php if(!empty(get_category_by_slug('chuyen-de'))): ?>
                <?php $cat = get_category_by_slug('chuyen-de');?>
                <div class="col-md-6">
                    <div class="panel panel-default panel-color-pink panel-color-newest">
                        <div class="panel-heading">
                            <a href="<?php echo get_category_link($cat->term_id);?>">
                                <?php echo $cat->name;?>
                            </a>
                        </div>
                        <div class="panel-body">
                        <?php $posts = get_post_via_category($cat->term_id);?>
                        <?php if(!empty($first = $posts[0])):?>
                            <div class="first block news-image">
                                <a href="<?php echo get_post_link($cat->term_id,$first->post_name); ?>" rel="bookmark" class="image">
                                    <img src="<?php echo get_thumbnail_post($first->ID);?>" class="post-image" title="<?php echo $first->post_title;?>" alt="<?php echo $first->post_title;?>"/>
                                </a>
                                <div class="content">
                                    <div class="title-post">
                                        <?php
                                            $new = '';
                                            $now = time(); // or your date as well
                                            $my_date = strtotime($first->post_date);
                                            $datediff = $now - $my_date;
                                            $day = floor($datediff / (60 * 60 * 24));
                                        ?>
                                        <?php if($day <= 5):?>
                                            <?php $new = '<span class="icon-fcbt-new">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span>
                </span>';?>
                                        <?php endif;?>
                                        <a href="<?php echo get_post_link($cat->term_id,$first->post_name); ?>" rel="bookmark">
                                            <?php echo $first->post_title;?>
                                            <?php echo $new;?>
                                        </a>
                                    </div>
                                    <div class="post-date">
                                        <span class="ficon ficon-time"></span><?php echo format_date_post($first->post_modified);?>
                                    </div>
                                    <div class="post-description">
                                        <?php echo cut_string_post($first->post_content, 20);?>
                                    </div>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if(count($posts) >=1): ?>
                            <?php for($i=1; $i<count($posts); $i++):?>
                                <?php if(!empty($posts[$i])): ?>
                                    <div class="block">
                                        <a href="<?php echo get_post_link($cat->term_id,$posts[$i]->post_name); ?>" rel="bookmark" class="image">
                                            <img src="<?php echo get_thumbnail_post($posts[$i]->ID);?>" class="post-image" title="<?php echo $posts[$i]->post_title;?>" alt="<?php echo $posts[$i]->post_title;?>"/>
                                        </a>
                                        <div class="title-post">
                                            <?php
                                                $new = '';
                                                $now = time(); // or your date as well
                                                $my_date = strtotime($posts[$i]->post_date);
                                                $datediff = $now - $my_date;
                                                $day = floor($datediff / (60 * 60 * 24));
                                            ?>
                                            <?php if($day <= 5):?>
                                                <?php $new = '<span class="icon-fcbt-new">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span>
                </span>';?>
                                            <?php endif;?>
                                            <a href="<?php echo get_post_link($cat->term_id,$posts[$i]->post_name); ?>" rel="bookmark">
                                                <?php echo cut_string_post($posts[$i]->post_title, 7);?>
                                                <?php echo $new;?>
                                            </a>
                                        </div>
                                        <div class="post-description">
                                            <?php echo cut_string_post(strip_tags($first->post_content), 7);?>
                                        </div>
                                    </div>
                                <?php endif;?>
                            <?php endfor;?>
                        <?php endif;?>
                        <div class="bottom">
                            <a href="<?php echo get_category_link($cat->term_id);?>" class="bt-view-all">Xem tất cả <span class="ficon ficon-arrow"></span></a>
                        </div>
                        </div>
                    </div>
                </div>
            <?php endif;?>
            <!-- Thông tin tham khảo-->
            <?php if(!empty(get_category_by_slug('thong-tin-tham-khao'))): ?>
                <?php $cat = get_category_by_slug('thong-tin-tham-khao');?>
                <div class="col-md-6">
                    <div class="panel panel-default panel-color-orange panel-color-newest">
                        <div class="panel-heading">
                            <a href="<?php echo get_category_link($cat->term_id);?>">
                                <?php echo $cat->name;?>
                            </a>
                        </div>
                        <div class="panel-body">
                        <?php $posts = get_post_via_category($cat->term_id);?>
                        <?php if(!empty($first = $posts[0])):?>
                            <div class="first block news-image">
                                <a href="<?php echo get_post_link($cat->term_id,$first->post_name); ?>" rel="bookmark" class="image">
                                    <img src="<?php echo get_thumbnail_post($first->ID);?>" class="post-image" title="<?php echo $first->post_title;?>" alt="<?php echo $first->post_title;?>"/>
                                </a>
                                <div class="content">
                                    <div class="title-post">
                                        <?php
                                            $new = '';
                                            $now = time(); // or your date as well
                                            $my_date = strtotime($first->post_date);
                                            $datediff = $now - $my_date;
                                            $day = floor($datediff / (60 * 60 * 24));
                                        ?>
                                        <?php if($day <= 5):?>
                                            <?php $new = '<span class="icon-fcbt-new">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span>
                </span>';?>
                                        <?php endif;?>
                                        <a href="<?php echo get_post_link($cat->term_id,$first->post_name); ?>" rel="bookmark">
                                            <?php echo cut_string_post($first->post_title,20);?>
                                            <?php echo $new;?>
                                        </a>
                                    </div>
                                    <div class="post-date">
                                        <span class="ficon ficon-time"></span><?php echo format_date_post($first->post_modified);?>
                                    </div>
                                    <div class="post-description">
                                        <?php echo cut_string_post($first->post_content, 20);?>
                                    </div>
                                </div>
                            </div>
                        <?php endif;?>
                        <?php if(count($posts) >=1): ?>
                            <?php for($i=1; $i<count($posts); $i++):?>
                                <?php if(!empty($posts[$i])): ?>
                                    <?php if($i==1):?>
                                        <?php $limit = 20;?>
                                    <?php else: ?>
                                        <?php $limit = 7;?>
                                    <?php endif;?>
                                    <div class="block">
                                        <a href="<?php echo get_post_link($cat->term_id,$posts[$i]->post_name); ?>" rel="bookmark" class="image">
                                            <img src="<?php echo get_thumbnail_post($posts[$i]->ID);?>" class="post-image" title="<?php echo $posts[$i]->post_title;?>" alt="<?php echo $posts[$i]->post_title;?>"/>
                                        </a>
                                        <div class="title-post">
                                            <?php
                                                $new = '';
                                                $now = time(); // or your date as well
                                                $my_date = strtotime($posts[$i]->post_date);
                                                $datediff = $now - $my_date;
                                                $day = floor($datediff / (60 * 60 * 24));
                                            ?>
                                            <?php if($day <= 5):?>
                                                <?php $new = '<span class="icon-fcbt-new">
                <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span>
                </span>';?>
                                            <?php endif;?>
                                            <a href="<?php echo get_post_link($cat->term_id,$posts[$i]->post_name); ?>" rel="bookmark">
                                                <?php echo cut_string_post($posts[$i]->post_title, $limit);?>
                                                <?php echo $new;?>
                                            </a>
                                            <?php if($i>1):?>
                                                <div class="post-description">
                                                    <?php echo cut_string_post(strip_tags($first->post_content), 7);?>
                                                </div>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                <?php endif;?>
                            <?php endfor;?>
                        <?php endif;?>
                        <div class="bottom">
                            <a href="<?php echo get_category_link($cat->term_id);?>" class="bt-view-all">Xem tất cả <span class="ficon ficon-arrow"></span></a>
                        </div>
                        </div>
                    </div>
                </div>
            <?php endif;?>
            </div>
        </div><!-- #primary -->
        <?php get_sidebar('sidebar-1'); ?>
    </div><!--row-->
</div><!--.container-->
<?php get_footer(); ?>



