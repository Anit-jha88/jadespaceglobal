<?php
/**
 * Template Name: About
 
 */

get_header(); 
if( !empty(get_the_post_thumbnail_url( get_the_ID(),'full')) ):
	$bannerImage = get_the_post_thumbnail_url( get_the_ID(),'full');
else:
	$bannerImage = get_template_directory_uri().'/images/wardrobes.png';
endif;

?>
 
     <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area ltn__breadcrumb-area-2 ltn__breadcrumb-color-white bg-overlay-theme-black-90 bg-image" data-bg="<?php echo get_template_directory_uri()?>/img/banner/hardware-banner.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner ltn__breadcrumb-inner-2 justify-content-between">
                        <div class="section-title-area ltn__section-title-2">
                          
                            <h1 class="section-title white-color">About Us</h1>
                        </div>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                                <li>About Us</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BREADCRUMB AREA END -->

    <!-- ABOUT US AREA START -->
    <div class="ltn__about-us-area pt-50 pb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="about-us-img-wrap about-img-left">
                        <img src="<?php echo $bannerImage;?>" width="100%" alt="About Us Image">
                    </div>
                </div>
                <div class="col-lg-7 align-self-center">
                    <div class="about-us-info-wrap">
                       <?php the_content();?>
                      

            <div class="row">
              
                <div class="col-lg-12 ">
                    <div class="about-us-info-wrap">
                     
                     <?php echo get_field('about_content');?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ABOUT US AREA END -->



    <!-- CALL TO ACTION START (call-to-action-5) -->
    <div class="call-to-action-area call-to-action-5 bg-image bg-overlay-theme-90 pt-40 pb-25 " data-bg="img/bg/13.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="call-to-action-inner call-to-action-inner-5 text-center">
                        <h2 class="white-color text-decoration">24/7 Availability, Make <a href="<?php echo get_page_link(74);?>">An Appointment</a></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CALL TO ACTION END -->
                 

            
<?php get_footer(); ?>
