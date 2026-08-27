<?php
/**
 * Template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); 

?>

	
  <?php
	while ( have_posts() ) : the_post();
	$image=wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full'); 
	if($image!=''){
		$image=$image[0];
	}else{
	$image=get_stylesheet_directory_uri().'/images/bannerImg.jpg';	
	}
	?>
	
	<div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area ltn__breadcrumb-area-2 ltn__breadcrumb-color-white bg-overlay-theme-black-90 bg-image" data-bg="<?php echo get_template_directory_uri()?>/img/banner/hardware-banner.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner ltn__breadcrumb-inner-2 justify-content-between">
                        <div class="section-title-area ltn__section-title-2">
                          
                            <h1 class="section-title white-color"><?php the_title();?></h1>
                        </div>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                                <li><?php the_title();?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

	 <div class="ltn__about-us-area pt-50 pb-50 <?php if(is_page(136) || is_page(134) ||  is_page(139) || is_page(142) || is_page(145)){ echo 'cmsclass'; }?>">
        <div class="container">
            <div class="row">
          <?php the_content();?>
	   </div>
	  </div>
    </div>
    <?php endwhile; ?> 
    
  
<?php get_footer(); ?>
