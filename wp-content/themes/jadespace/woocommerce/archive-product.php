<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

/**
 * Hook: woocommerce_shop_loop_header.
 *
 * @since 8.6.0
 *
 * @hooked woocommerce_product_taxonomy_archive_header - 10
 */
do_action( 'woocommerce_shop_loop_header' );
?>
    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area ltn__breadcrumb-area-2 ltn__breadcrumb-color-white bg-overlay-theme-black-90 bg-image plr--9---" data-bg="<?php echo get_template_directory_uri()?>/img/banner/hardware-banner.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner ltn__breadcrumb-inner-2 justify-content-between">
                        <div class="section-title-area ltn__section-title-2">
                          
                            <h1 class="section-title white-color">
                                <?php
                                if($_GET['s']==''){
                                printf( __( ' %s', 'twentyten' ), '<span>' . single_cat_title( '', false ) . '</span>' );
                                }else{
                                    
                                    echo 'Search: '.$_GET['s'];
                                }
                                
                                ?>
                            </h1>
                        </div>
                        <div class="ltn__breadcrumb-list">
                            <ul>			
                                <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                                <li> <?php
                                if($_GET['s']==''){
                                printf( __( ' %s', 'twentyten' ), '<span>' . single_cat_title( '', false ) . '</span>' );
                                }else{
                                    
                                    echo 'Search: '.$_GET['s'];
                                }
                                
                                ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ltn__product-area  section-bg-1 ltn__product-gutter pt-80 pb-80 ">
        <div class="container">
            <div class="row ltn__tab-product-slider-one-active--- slick-arrow-1">
                <div class="col-lg-9 order-lg-2 mb-120">
                    <div class="row">
<?php
if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
//	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();
	
	?>

          
	<?php

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}
?>
	 </div>
            </div>
            
  <div class="col-lg-3  mb-120">
                    <aside class="sidebar ltn__shop-sidebar">
                        <!-- Category Widget -->
                        <div class="widget ltn__menu-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Product categories</h4>
                            <ul>
                               
                            <?php
                            $term = get_queried_object();
                            $category_id = $term->term_id;
                             
                            $category = get_term($category_id, 'product_cat');
                            
                            if ($category->parent != 0) {
                            $term = get_term($category_id, 'product_cat');
                            $finalcatid= $term->parent;

                            } else {
                            $finalcatid=$category_id;
                            }
                           
                            $product_categories = get_terms(array(
                            'taxonomy'   => 'product_cat',
                            'parent'     => $finalcatid,
                            'hide_empty' => false, // Set to true if you want to hide empty subcategories
                            ));
                            
                            if (!empty($product_categories) && !is_wp_error($product_categories)) {
                                
                                foreach ($product_categories as $category) {
                            ?>
                                <li><a href="<?php echo get_term_link($category);?>"><?php echo $category->name;?> <span><i class="fas fa-long-arrow-alt-right"></i></span></a></li>
                            
                            <?php  } } ?>
                               
                            </ul>
                        </div>
                        <!-- Price Filter Widget -->
                        <div class="widget ltn__price-filter-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">Filter by price</h4>
                            <div class="price_filter">
                                <div class="price_slider_amount">
                                    <input type="submit"  value="Your range:"/> 
                                    <input type="text" class="amount" name="price"  placeholder="Add Your Price" /> 
                                </div>
                                <div class="slider-range"></div>
                            </div>
                        </div>
						
						    <div class="widget ltn__price-filter-widget">
                            <h4 class="ltn__widget-title ltn__widget-title-border">View All Product</h4>
                           
                               
                                   <a href="https://jadespaceglobal.com/wp-content/themes/jadespace/pdf/product_catalogue.pdf" target="_blank" class="theme-btn-1 btn btn-effect-2 btn-effect-4 white-color">Download Catalogue</a>
							   
							  
                            
                                                           
                           
                        </div>
                      
                      

                    </aside>
                </div>
	

<?php
/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );
?>


            </div>
        </div>
    </div>
<?php
/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
//do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
