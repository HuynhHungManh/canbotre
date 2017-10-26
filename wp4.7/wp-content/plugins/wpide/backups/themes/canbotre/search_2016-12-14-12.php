<?php /* start WPide restore code */
                                    if ($_POST["restorewpnonce"] === "938d4e1d0cb2c44b331b4d42f77afba15c875b00a9"){
                                        if ( file_put_contents ( "/home/canbotre/public_html/wordpress/wp-content/themes/canbotre/search.php" ,  preg_replace("#<\?php /\* start WPide(.*)end WPide restore code \*/ \?>#s", "", file_get_contents("/home/canbotre/public_html/wordpress/wp-content/plugins/wpide/backups/themes/canbotre/search_2016-12-14-12.php") )  ) ){
                                            echo "Your file has been restored, overwritting the recently edited file! \n\n The active editor still contains the broken or unwanted code. If you no longer need that content then close the tab and start fresh with the restored file.";
                                        }
                                    }else{
                                        echo "-1";
                                    }
                                    die();
                            /* end WPide restore code */ ?><?php
/**
 * The template for displaying search results pages.
 *
 * @package Nisarg
 */
global $paged;
if($paged <= 0) {
    $paged = 1;
}
global $posts;
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
                    <h3 itemprop="headline" id="title" class="page-title">Tìm kiếm</h3>
                </div>
                <div class="panel-body post-body">
                    <div class="block">
                        <form class="form-inline form-search-page" action="<?php echo get_site_url();?>" method="get">
                            <div class="form-group">
                                <label class="sr-only" for="exampleInputEmail3">Từ khóa tìm kiếm</label>
                                <input type="text" class="form-control" id="exampleInputEmail3" placeholder="Nhập từ khóa cần tìm" name="s" value="<?php echo get_search_query();?>">
                            </div>
                            <button type="submit" class="btn btn-primary"><span class="ficon ficon-search"></span>Tìm kiếm</button>
                        </form>
                        <?php $count = count($posts);?>
                        <?php if(!empty($count)):?>
                        <div class="result-search">
                            <?php
                                $limit = get_limit_option();
                                if($paged == 1):
                                    $start = 1;
                                    if($count > $limit):
                                        $end = $limit;
                                    else:
                                        $end = $count;
                                    endif;
                                else:
                                    $start = ($paged-1)*$limit+1;
                                    $tam = $count - ($start-1);
                                    if($tam > $limit):
                                        $end = $limit*$paged;
                                    else:
                                        $end = $count;
                                    endif;
                                endif;
                            ?>
                            Kết quả từ <?php echo $start;?> - <?php echo $end;?> trong khoảng <?php
                            echo number_format($count, 0, ',', '.');?> cho.
                        </div>
                        <?php endif;?>
                    </div>
                    <?php if (have_posts()) : ?>
                        <div class="post-listing archive-box">
                            <?php while (have_posts()) :
                                the_post();
                                $post = get_post(get_the_ID());
                            ?>
                            <div class="block">
                                <article class="item-list tie_audio">
                                    <a href="<?php echo get_permalink($post); ?>" rel="bookmark" class="image">
                                        <img src="<?php echo get_thumbnail_post($post->ID);?>" class="post-image" title="<?php echo $post->post_title;?>" alt="<?php echo $post->post_title;?>"/>
                                    </a>
                                    <div class="content">
                                        <h4 class="title-post">
                                            <a href="<?php echo get_permalink($post); ?>" rel="bookmark">
                                                <?php echo $post->post_title;?>
                                            </a>
                                        </h4>
                                        <div class="post-date">
                                            <?php echo format_date_post($post->post_modified);?>
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
                    <?php else: ?>
                        <h2 class="no-post">Không tìm thấy kết quả nào tương ứng với từ khóa: <?php echo get_search_query();?></h2>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php get_sidebar('sidebar-1'); ?>
    </div>
</div><!--.container-->
<?php get_footer(); ?>
