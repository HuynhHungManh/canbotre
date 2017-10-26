<?php
/**
 * The template for displaying all single posts.
 *
 * @package Nisarg
 */
get_header();

?>
<div class="container">
    <div class="breadcrumb-list">
        <?php if(function_exists('custom_breadcrumbs_for_seo')):?>
            <?php echo custom_breadcrumbs_for_seo(); ?>
        <?php endif;?>
    </div>
    <div class="row">
        <div id="primary" class="col-md-9 content-area">
            <?php if(have_posts()):?>
                <?php
                // Get post category info
                $category = get_the_category();
                $categoryId = 1;
                if(!empty($category)) {
                    if(!empty(get_query_var('category_name'))) {
                       //get category by path
                        $category = get_category_by_path(get_query_var('category_name'),false);
                        //get category id;
                        $categoryId = $category->cat_ID;
                    }else {
                        $categories = get_the_category();
                        if(!empty($categories)){
                            foreach($categories as $category) {
                                //get last category id
                            }
                            // display breadcrumb list via category id by path
                            $categoryId = $category->cat_ID;
                        }
                    }
                }
                $getPost = get_post(get_the_ID());
                $url_news = get_post_link($categoryId,$post->post_name);
                $content = $getPost->post_content;
                $content = wpautop($content, '<p>');
                $patterns = array('/(<[^>]+) style=".*?"/i', "/(<[^>]+) style='.*?'/i");
                $content = preg_replace($patterns, '$1', $content);
                ?>
                <div class="popup-chia-se">
                    <form action="" id="chia-se-bv-form" class="share-email-form">
                        <div class="popup-heading">
                            <h3>Chia sẻ link bài viết qua email</h3>
                            <div class="close-popup">
                                <img src="" alt="close" class="close-popup-img" />
                            </div>
                        </div>
                        <div class="popup-content">
                            <div>Tiêu đề bài viết:</div>
                            <h4><?php echo $getPost->post_title;?></h4>
                            <div class="form-group">
                                <input name="name-cs" id="name" value="" placeholder="Nhập tên của bạn" class="required name-cs" type="text" autocomplete="true" />
                                <input value="<?php echo $getPost->post_title;?>" id="title" type="hidden"/>
                                <input value="<?php echo $url_news;?>" id="link" type="hidden"/>
                                <input value="<?php echo cut_string_post($content,300);?>" id="content" type="hidden"/>
                            </div>
                            <div class="form-group">
                                <input name="email-cs" id="email" value="" placeholder="Nhập địa chỉ email bạn muốn chia sẻ bài viết" type="email" class="required" autocomplete="true"/>
                            </div>
                            <div class="captcha">
                                <?php $rand = function_gg_feedback_generateRandomString();?>
                                <span class="captcha_image"><?php echo $rand;?></span>
                                <input type="hidden" name="capt" value="<?php echo $rand;?>" id="capt">
                                <img src="<?php echo plugins_url('share-post-via-email/images/refresh_captcha.png');?>" width="20" height="20" id="captcha_refresh_share" class="bt-capcha-refresh" alt="captcha refresh share post via email"/><input value="" name="captcha" id="captcha" size="5" class="required captcha" type="input" placeholder="Mã xác nhận" />
                            </div>
                            <div class="group-btn">
                                <button type="submit" name="send" id="send">
                                    <span>Gửi</span>
                                    <div class="loading-container">
                                        <div class="loading-content"><div class="spinner"></div></div>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel panel-default post-details">
                    <div class="panel-heading post-heading">
                        <h1 itemprop="headline" id="title" class="page-title"><?php echo $getPost->post_title;?></h1>

                        <div class="post-date"><span class="ficon ficon-time"></span><?php echo format_date_post($getPost->post_date);?></div>
                        <ul class="list-tool">
                            <li class="share_facebook">
                                <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url_news;?>&amp;display=popup&amp;ref=plugin" title="Facebook">
                                    <span class="ficon ficon-facebook"></span>
                                </a>
                            </li>
                            <li class="share_google">
                                <a target="_blank" href="https://plus.google.com/share?url=<?php echo $url_news;?>" title="google plus">
                                    <span class="ficon ficon-google-plus"></span>
                                </a>
                            </li>
                            <li class="share_email">
                                <a href="javascript:void(0);" title="Chia sẻ qua email" id="share_email">
                                    <span class="ficon ficon-email"></span>
                                </a>
                            </li>
                            <li class="print">
                                <a href="<?php echo $url_news;?>?print" title="In bài viết">
                                    <span class="ficon ficon-print"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="panel-body post-body">
                    <?php if (has_post_thumbnail($getPost->ID)): ?>
                        <div class="img-poster"><img src="<?php echo get_thumbnail_post($getPost->ID);?>" class="post-image" title="<?php echo $getPost->post_title;?>" alt="<?php echo $getPost->post_title;?>"/></div>
                    <?php endif;?>
                        <div class="content-details">
                            <?php echo $content; ?>
                        </div>
                        <div class="like-print-region">
                            <div class="fb-like" data-href="<?php echo $url_news;?>" data-layout="button_count" data-action="like" data-size="small" data-show-faces="true" data-share="false"></div>
                        </div>
                        <div class="list-other-posts">
                        <?php set_session_related_posts(); ?>
                        <?php $post_not_in = get_session_related_posts();?>
                        <?php $posts = get_post_via_category($categoryId, 3, $post_not_in);?>
                        <?php if(!empty($posts)):?>
                            <h3 class="title"><span>Tin khác</span></h3>
                            <div class="list">
                                <?php foreach ($posts as $post):?>
                                    <div class="tin-khac-details-post block">
                                        <a href="<?php echo get_post_link($categoryId,$post->post_name); ?>" rel="bookmark" class="image">
                                            <img src="<?php echo get_thumbnail_post($post->ID);?>" class="post-image" title="<?php echo $post->post_title;?>" alt="<?php echo $post->post_title;?>"/>
                                        </a>
                                        <div class="content">
                                            <h4 class="title-post">
                                                <a href="<?php echo get_post_link($categoryId,$post->post_name); ?>" rel="bookmark">
                                                    <?php echo cut_string_post($post->post_title, 200);?>
                                                </a>
                                            </h4>
                                        </div>
                                    </div>
                                <?php endforeach;?>
                            </div>
                        <?php endif;?>
                        </div>
                        <div class="binh-luan comments">
                            <h3 class="title"><span>Bình luận</span></h3>
                            <div class="form-comment">
                                <div class="fb-comments" data-href="<?php echo $url_news;?>" data-numposts="10" data-width="100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif;?>
        </div><!-- #primary -->
        <?php get_sidebar('sidebar-1'); ?>
    </div> <!--.row-->
</div><!--.container-->
<?php get_footer(); ?>
