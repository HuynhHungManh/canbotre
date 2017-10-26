<?php
/**
 * The template for displaying archive pages.
 *
 *
 *
 * @package Nisarg
 */
$current_cat = get_category($cat);
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
                    <h1 class="page-title" property="name"><?php echo get_the_archive_title();?></h1>
                </div>
                <div class="panel-body post-body">
                    <?php if (have_posts()) : ?>
                        <div class="post-listing archive-box">
                            <?php while (have_posts()) :
                                the_post();
                                $post = get_post(get_the_ID());
                                if ($current_cat->taxonomy !== 'category') {
                                    $current_cat = get_the_category();
                                    foreach($current_cat as $current_cat) {
                                        break;
                                    }
                                }
                            ?>
                            <div class="block">
                                <article class="item-list tie_audio">
                                    <a href="<?php echo get_post_link($current_cat->term_id,$post->post_name); ?>" rel="bookmark" class="image">
                                        <img src="<?php echo get_thumbnail_post($post->ID);?>" class="post-image" title="<?php echo $post->post_title;?>" alt="<?php echo $post->post_title;?>"/>
                                    </a>
                                    <div class="content">
                                        <h4 class="title-post">
                                            <a href="<?php echo get_post_link($current_cat->term_id,$post->post_name); ?>" rel="bookmark">
                                                <?php echo $post->post_title;?>
                                            </a>
                                        </h4>
                                        <div class="post-date">
                                            <span class="ficon ficon-time"></span><?php echo format_date_post($post->post_date);?>
                                        </div>
                                        <div class="post-description">
                                            <?php echo cut_string_post($post->post_content, 80);?>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </article>
                            </div>
                            <?php endwhile; ?>
                        </div>
                        <div class="pagination-post">
                            <?php
                                // Previous/next page navigation.
                                $paginate = preg_replace('#<ul.*?>#si', '<ul class="pagination">', paginate_links(
                                        array(
                                            'type' => 'list',
                                            'prev_text'          => __('&nbsp;'),
                                            'next_text'          => __('&nbsp;'),
                                            'mid_size'           => '1'
                                        )
                                    ));
                                echo preg_replace("/<li><span(.*)current\'/", '<li class="active"><span', $paginate);
                            ?>
                        </div>
                        <?php else:?>
                        <div class="post-listing archive-box">
                            <h2 class="padding-15 no-post">Hiện chưa có bài viết nào trong chuyên mục này.</h2>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        </div><!-- #primary -->
        <?php get_sidebar('sidebar-1'); ?>
    </div> <!--.row-->
</div><!--.container-->
<?php get_footer(); ?>
