<?php

/**
 * Template Name: Trang RSS
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
                        <?php while (have_posts()) : the_post(); ?>
                        <div class="panel-heading"><?php echo the_title();?></div>
                        <div class="panel-body gopy-hd rss">
                            <?php echo the_content();?>
                        </div>
                        <?php endwhile;?>
                    </div>
                </div>
            </div>
        </div><!-- #primary -->
        <?php get_sidebar('sidebar-1'); ?>
    </div><!--row-->
</div><!--.container-->
<?php get_footer(); ?>
