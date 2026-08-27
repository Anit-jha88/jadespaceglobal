<?php
/**
 * Template Name: Blog
 
 */

get_header();
if( !empty(get_the_post_thumbnail_url( get_the_ID(),'full')) ):
	$bannerImage = get_the_post_thumbnail_url( get_the_ID(),'full');
else:
	$bannerImage = get_template_directory_uri().'/images/dealerBannerImg.png';
endif;

 ?>

<!-- Utilize Mobile Menu End -->

    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area ltn__breadcrumb-area-2 ltn__breadcrumb-color-white bg-overlay-theme-black-90 bg-image" data-bg="<?php bloginfo( 'template_url' ); ?>/img/banner/hardware-banner.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner ltn__breadcrumb-inner-2 justify-content-between">
                        <div class="section-title-area ltn__section-title-2">
                        
                            <h1 class="section-title white-color">Blogs</h1>
                        </div>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                                <li>Blogs</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- BLOG AREA START -->
    <div class="ltn__blog-area section-bg-1 ltn__blog-item-3-normal pt-80 pb-70">
        <div class="container">
            <div class="row">
                <!-- Blog Item -->
              
                <!-- Blog Item -->
                
                <!-- Blog Item -->
                <?php
                $args = array(
                'post_type' => 'post', // Replace with your post type
                'posts_per_page' => 6, // Number of posts to display
                'paged' => get_query_var('paged') ? get_query_var('paged') : 1 // Enable pagination
                );
                
                $custom_query = new WP_Query($args);
                
                if ($custom_query->have_posts()) {
                while ($custom_query->have_posts()) : $custom_query->the_post();
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
             
              <?php  endwhile; } ?>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__pagination-area text-center">
                        <div class="ltn__pagination">
                            <?php $big = 999999999; // need an unlikely integer
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $custom_query->max_num_pages
    )); ?>
                            <!--<ul>-->
                            <!--    <li><a href="#"><i class="fas fa-angle-double-left"></i></a></li>-->
                            <!--    <li><a href="#">1</a></li>-->
                            <!--    <li class="active"><a href="#">2</a></li>-->
                            <!--    <li><a href="#">3</a></li>-->
                            <!--    <li><a href="#">...</a></li>-->
                            <!--    <li><a href="#">10</a></li>-->
                            <!--    <li><a href="#"><i class="fas fa-angle-double-right"></i></a></li>-->
                            <!--</ul>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BLOG AREA END -->
<?php get_footer(); ?>
