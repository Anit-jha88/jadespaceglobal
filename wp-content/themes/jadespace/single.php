<?php
/**
 * The template for displaying all single posts
 */

get_header(); ?>
    <div class="ltn__breadcrumb-area ltn__breadcrumb-area-2 ltn__breadcrumb-color-white bg-overlay-theme-black-90 bg-image" data-bg="<?php bloginfo( 'template_url' ); ?>/img/banner/hardware-banner.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner ltn__breadcrumb-inner-2 justify-content-between">
                        <div class="section-title-area ltn__section-title-2">
                          
                            <h1 class="section-title white-color">Blog Detail</h1>
                        </div>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                 <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                                <li><?php the_title() ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $bannerImage = get_the_post_thumbnail_url( get_the_ID(),'full'); ?>
    
     <div class="ltn__page-details-area ltn__blog-details-area section-bg-1 pt-80 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="ltn__blog-details-wrap">
                        <div class="ltn__page-details-inner ltn__blog-details-inner">
                          
                            <h2 class="ltn__blog-title"><?php the_title() ?></h2>
                            <div class="ltn__blog-meta">
                                <ul>
                                    <li class="ltn__blog-author">
                                        <a href="#">By: Admin</a>
                                    </li>
                                    <li class="ltn__blog-date">
                                        <i class="far fa-calendar-alt"></i><?php echo get_the_date('F m,Y')?>
                                    </li>
                                  
                                </ul>
                            </div>
                             <?php the_content();?>
                            <img src="<?php echo $bannerImage; ?>" alt="Image">
                           
                        </div>

                      
                    </div>
                </div>
                <div class="col-lg-4">
                    <aside class="sidebar-area blog-sidebar ltn__right-sidebar sidebar2">
                       
                        <!-- Search Widget -->
                        <div class="widget ltn__search-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Search Objects</h4>
                           <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                                <input type="text"  placeholder="Search your keyword..." value="<?php echo get_search_query(); ?>" name="s">
                                <button type="submit"><i class="fas fa-search"></i></button>
                            </form>
                        </div>
                       
                        <!-- Menu Widget (Category) -->
                        <div class="widget ltn__menu-widget ltn__menu-widget-2 ltn__menu-widget-2-color-2">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Categories</h4>
                            
            <?php
            $categories = get_categories(array(
            'orderby' => 'name',
             'hide_empty' => false,
            'order'   => 'ASC'
            ));
            
            if ($categories){
            ?>
    <ul>
        <?php foreach ($categories as $category) : ?>
            <li>
                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                    <?php echo esc_html($category->name); ?>
               
                <span><?php echo esc_html($category->count); ?></span> </a> 
            </li>
        <?php endforeach; ?>
    </ul>
  <?php } ?>
                           
                        </div>
                        <!-- Social Media Widget -->
                      
                       
                     
                        
                    </aside>
                </div>
            </div>
        </div>
    </div>
    <!-- PAGE DETAILS AREA END -->
  		
<?php get_footer(); ?>