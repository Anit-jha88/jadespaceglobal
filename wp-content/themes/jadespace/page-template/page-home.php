<?php
/**
 * Template Name: Home
 
 */

get_header(); ?>
 

     
    

    <div class="ltn__utilize-overlay"></div>

    <!-- SLIDER AREA START (slider-3) -->
    <div class="ltn__slider-area ltn__slider-3  ">
        <div class="ltn__slide-one-active slick-slide-arrow-1 slick-slide-dots-1">
            <!-- ltn__slide-item -->
     
            <!-- ltn__slide-item -->

             <!-- ltn__slide-item -->
         
            <!-- ltn__slide-item -->

             <!-- ltn__slide-item -->
             <?php
             if( have_rows('home_slider') ):
               while ( have_rows('home_slider') ) : the_row();
               ?>
             <div class="ltn__slide-item ltn__slide-item-2 ltn__slide-item-3">
                <div class="ltn__slide-item-inner">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-6 align-self-center">
                                <div class="slide-item-info">
                                    <div class="slide-item-info-inner ltn__slide-animation">
                                       
                                       
                                        <h1 class="slide-title animated "><?php  the_sub_field('slider_title');?></h1>
                                        <div class="slide-brief animated ">
                                            <p><?php  the_sub_field('slider_content');?></p>
                                        </div>
                                        <div class="btn-wrapper animated">
                                            <a href="<?php  the_sub_field('slider_link');?>" class="theme-btn-1 btn btn-effect-1 text-uppercase">Explore Products</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="slide-item-img">
                                    <img src="<?php  the_sub_field('slider_image');?>" alt="#">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; endif; ?>
           
        </div>
    </div>
    <!-- SLIDER AREA END -->

  
  

      <!-- PRODUCT SLIDER AREA START -->
      <!--
      <div class="ltn__product-slider-area section-bg-1 ltn__product-gutter pt-60 pb-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2--- text-center">
                        <h1 class="section-title">Hardware</h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__product-slider-item-four-active slick-arrow-1">
             <?php
                $category_id = 16;
                $products = wc_get_products(array(
                'limit' => -1,
                'tax_query'             => array(
                array(
                'taxonomy'      => 'product_cat',
                'field'         => 'term_id',
                'terms'         => $category_id,
                'operator'      => 'IN'
                ),
                ),
                ));
                foreach($products as $product)
                {
                   
                    $bannerImage = get_the_post_thumbnail_url( $product->id,'full');
                ?>
                <div class="col-lg-12">
                    <div class="ltn__product-item ltn__product-item-3 text-center">
                        <div class="product-img">
                            <a href="<?php the_permalink($product->id);?>"><img src="<?php echo $bannerImage; ?>" alt="#"></a>
                       
                            <div class="product-hover-action">
                                  <ul>
                                    <li>
                                        <a href="<?php the_permalink($product->id);?>" title="Quick View">
                                            <i class="far fa-eye"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/?add-to-cart=<?php echo $product->id;?>" title="Add to Cart">
                                            <i class="fas fa-shopping-cart"></i>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-ratting">
                                <ul>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                    <li><a href="#"><i class="far fa-star"></i></a></li>
                                </ul>
                            </div>
                            <h2 class="product-title"><a href="<?php the_permalink($product->id);?>"><?php echo  $product->get_title();?></a></h2>
                            <div class="product-price">
                                <span>₹<?php  echo $product->price; ?></span>
                                <?php if($product->regular_price!=''){ ?>
                                <del>₹<?php  echo $product->regular_price; ?></del>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            
             <?php  }  ?>
           
            </div>
        </div>
    </div> -->
    <!-- PRODUCT SLIDER AREA END -->
    
      <!-- PRODUCT SLIDER AREA START -->
    <div class="ltn__product-slider-area section-bg-1 ltn__product-gutter pt-60 pb-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2--- text-center">
                        <h1 class="section-title">Kitchen Hardware Fittings</h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__product-slider-item-four-active slick-arrow-1">
                <!-- ltn__product-item -->
               
                <?php
                $category_id = 18;
                $products = wc_get_products(array(
                'limit' => -1,
                'tax_query'             => array(
                array(
                'taxonomy'      => 'product_cat',
                'field'         => 'term_id',
                'terms'         => $category_id,
                'operator'      => 'IN'
                ),
                ),
                ));
                foreach($products as $product)
                {
                   
                    $bannerImage = get_the_post_thumbnail_url( $product->id,'full');
                ?>
                <div class="col-lg-12">
                    <div class="ltn__product-item ltn__product-item-3 text-center">
                        <div class="product-img">
                            <a href="<?php the_permalink($product->id);?>"><img src="<?php echo $bannerImage; ?>" alt="#"></a>
                       
                            <div class="product-hover-action">
                                  <ul>
                                    <li>
                                        <a href="<?php the_permalink($product->id);?>" title="Quick View">
                                            <i class="far fa-eye"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/?add-to-cart=<?php echo $product->id;?>" title="Add to Cart">
                                            <i class="fas fa-shopping-cart"></i>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-ratting">
                                <ul>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                    <li><a href="#"><i class="far fa-star"></i></a></li>
                                </ul>
                            </div>
                            <h2 class="product-title"><a href="<?php the_permalink($product->id);?>"><?php echo  $product->get_title();?></a></h2>
                            <div class="product-price">
                                <span>₹<?php  echo $product->price; ?></span>
                                <?php if($product->regular_price!=''){ ?>
                                <del>₹<?php  echo $product->regular_price; ?></del>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            
             <?php  }  ?>

            </div>
        </div>
    </div>
    <!-- PRODUCT SLIDER AREA END -->

  <!-- BANNER AREA START -->
  <div class="ltn__banner-area mt-20  pt-40 pb-30 ">
    <div class="container">
        <div class="row ltn__custom-gutter--- justify-content-center">
            <div class="col-lg-4 col-md-6">
                <div class="ltn__banner-item">
                    <div class="ltn__banner-img">
                        <a href="#."><img src="<?php bloginfo( 'template_url' ); ?>/img/banner/newbanner1.png" alt="Banner Image"></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="ltn__banner-item">
                    <div class="ltn__banner-img">
                        <a href="#."><img src="<?php bloginfo( 'template_url' ); ?>/img/banner/newbanner2.png" alt="Banner Image"></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="ltn__banner-item">
                    <div class="ltn__banner-img">
                        <a href="#."><img src="<?php bloginfo( 'template_url' ); ?>/img/banner/moduler-kitchen.png" alt="Banner Image"></a>
                    </div>
                </div>
            </div>
            
             <div class="col-lg-4 col-md-6">
                <div class="ltn__banner-item">
                    <div class="ltn__banner-img">
                        <a href="#."><img src="<?php bloginfo( 'template_url' ); ?>/img/banner/jadespace-banner-1.jpg" alt="Banner Image"></a>
                    </div>
                </div>
            </div>
            
             <div class="col-lg-4 col-md-6">
                <div class="ltn__banner-item">
                    <div class="ltn__banner-img">
                        <a href="#."><img src="<?php bloginfo( 'template_url' ); ?>/img/banner/jadespace-banner-2.jpg" alt="Banner Image"></a>
                    </div>
                </div>
            </div>
            
             <div class="col-lg-4 col-md-6">
                <div class="ltn__banner-item">
                    <div class="ltn__banner-img">
                        <a href="#."><img src="<?php bloginfo( 'template_url' ); ?>/img/banner/jadespace-banner-3.jpg" alt="Banner Image"></a>
                    </div>
                </div>
            </div>
         
        </div>
    </div>
</div>
<!-- BANNER AREA END -->

   

        <!-- CALL TO ACTION START (call-to-action-4) -->
        <div class="ltn__call-to-action-area ltn__call-to-action-4 bg-image pt-60 pb-60" data-bg="<?php bloginfo( 'template_url' ); ?>/img/bg/6.jpg">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="call-to-action-inner call-to-action-inner-4 text-center">
                            <div class="section-title-area ltn__section-title-2">
                                <h6 class="section-subtitle ltn__secondary-color">  any question you have  </h6>
                                <h1 class="section-title white-color"> +91 9108323850</h1>
                            </div>
                            <div class="btn-wrapper">
                                <a href="tel: +91 9108323850" class="theme-btn-1 btn btn-effect-1">MAKE A CALL</a>
                                <a href="<?php bloginfo( 'template_url' ); ?>/pdf/product_catalogue.pdf" target="_blank" class="theme-btn-1 btn btn-effect-2 btn-effect-4 white-color">VIEW ALL PRODUCTS</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          
        </div>
        <!-- CALL TO ACTION END -->


  <!-- PRODUCT SLIDER AREA START -->
     <div class="ltn__product-slider-area section-bg-1 ltn__product-gutter pt-60 pb-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title-area ltn__section-title-2--- text-center">
                        <h1 class="section-title">Modular Furniture </h1>
                    </div>
                </div>
            </div>
            <div class="row ltn__product-slider-item-four-active slick-arrow-1">
                <!-- ltn__product-item -->
              <?php
                $category_id = 17;
                $products = wc_get_products(array(
                'limit' => -1,
                'tax_query'             => array(
                array(
                'taxonomy'      => 'product_cat',
                'field'         => 'term_id',
                'terms'         => $category_id,
                'operator'      => 'IN'
                ),
                ),
                ));
                foreach($products as $product)
                {
                   
                    $bannerImage = get_the_post_thumbnail_url( $product->id,'full');
                ?>
                <div class="col-lg-12">
                    <div class="ltn__product-item ltn__product-item-3 text-center">
                        <div class="product-img">
                            <a href="<?php the_permalink($product->id);?>"><img src="<?php echo $bannerImage; ?>" alt="#"></a>
                       
                            <div class="product-hover-action">
                                  <ul>
                                    <li>
                                        <a href="<?php the_permalink($product->id);?>" title="Quick View">
                                            <i class="far fa-eye"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/?add-to-cart=<?php echo $product->id;?>" title="Add to Cart">
                                            <i class="fas fa-shopping-cart"></i>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </div>
                        </div>
                        <div class="product-info">
                            <div class="product-ratting">
                                <ul>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star"></i></a></li>
                                    <li><a href="#"><i class="fas fa-star-half-alt"></i></a></li>
                                    <li><a href="#"><i class="far fa-star"></i></a></li>
                                </ul>
                            </div>
                            <h2 class="product-title"><a href="<?php the_permalink($product->id);?>"><?php echo  $product->get_title();?></a></h2>
                            <div class="product-price">
                                <span>₹<?php  echo $product->price; ?></span>
                                <?php if($product->regular_price!=''){ ?>
                                <del>₹<?php  echo $product->regular_price; ?></del>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            
             <?php  }  ?>
            </div>
        </div>
    </div>
    <!-- PRODUCT SLIDER AREA END -->
  
<?php get_footer(); ?>
