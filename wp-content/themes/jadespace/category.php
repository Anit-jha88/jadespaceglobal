<?php
/**
 * Template for displaying Category Archive pages
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

 <div class="ltn__breadcrumb-area ltn__breadcrumb-area-2 ltn__breadcrumb-color-white bg-overlay-theme-black-90 bg-image" data-bg="<?php bloginfo( 'template_url' ); ?>/img/banner/hardware-banner.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner ltn__breadcrumb-inner-2 justify-content-between">
                        <div class="section-title-area ltn__section-title-2">
                          
                            <h1 class="section-title white-color"><?php	printf( __( 'Category : %s', 'twentyten' ), '<span>' . single_cat_title( '', false ) . '</span>' );
				?></h1>
                        </div>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                 <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                                <li><?php	printf( __( 'Category : %s', 'twentyten' ), '<span>' . single_cat_title( '', false ) . '</span>' );
				?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
        <div class="ltn__blog-area section-bg-1 ltn__blog-item-3-normal pt-80 pb-70">
        <div class="container">
            <div class="row">

    <?php
$current_cat_id  = get_query_var('cat');
$showposts = 10;
$args = array('cat' => $current_cat_id, 'orderby' => 'post_date', 'order' => 'DESC', 'posts_per_page' => $showposts,'post_status' => 'publish');
query_posts($args);
    if (have_posts()) : while (have_posts()) : the_post(); 
   
    $bannerImage = get_the_post_thumbnail_url( get_the_ID(),'full');
    ?>
     <div class="col-lg-4 col-sm-6 col-12">
                    <div class="ltn__blog-item ltn__blog-item-3">
                        <div class="ltn__blog-img">
                            <a href="<?php the_permalink(); ?>"><img src="<?php echo $bannerImage; ?>" alt="#"></a>
                        </div>
                        <div class="ltn__blog-brief">
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#"><i class="far fa-user"></i>by: Admin</a>
                                    </li>
                                 
                                </ul>
                            </div>
                            <h3 class="ltn__blog-title"><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h3>
                            <div class="ltn__blog-meta-btn">
                                <div class="ltn__blog-meta">
                                    <ul>
                                        <li class="ltn__blog-date"><i class="far fa-calendar-alt"></i><?php echo get_the_date('F m,Y')?></li>
                                    </ul>
                                </div>
                                <div class="ltn__blog-btn">
                                    <a href="<?php the_permalink(); ?>">Read more</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
	<?php endwhile; endif; ?>
</div>
    </div>
    </div>
<?php get_footer(); ?>
