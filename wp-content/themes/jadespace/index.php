<?php
/**
 * Main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

<div class="lifestyle_sec">
<div class="container">
<?php
 query_posts('page_id=11'); 
while (have_posts ()): the_post();  
	
   $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');

?>
<?php endwhile;?>
<h1><?php the_title();?></h1>
<p><?php echo get_the_content();?></p>

</div>
</div>
<div class="living_sec">
<div class="container">
<div class="row">
<div class="col-sm-9">
  <div class="row">
  <div class="col-sm-4">
  <div class="prt_list">
<?php 

                            global $post;

							
							$myposts=get_posts($args);
							$myposts=get_posts('numberposts=9&post_type=news&order=DESC');
							foreach($myposts as $post){
							setup_postdata($post);
							
							
							$image=wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full'); 

						    
							
						

				?>
 <div class="media">
  <div class="media-left">
    <a href="<?php the_permalink();?>">
      <img src="<?php echo $image[0]; ?>" alt="">
    </a>
  </div>
  <div class="media-body">
   
    <h3><?php the_title();?></h3>
  </div>
</div>
	

<?php } ?>





	 </div>
  </div>
  <div class="col-sm-8">
  <div class="living_vdo mbtm20px">
  
  <img src="<?php echo $image[0]; ?>" alt="">
 
  </div>
  

	 <div class="row">
	<?php 

                            global $post;

							
							$myposts=get_posts($args);
							$myposts=get_posts('numberposts=2&post_type=news&order=ASC');
							foreach($myposts as $post){
							setup_postdata($post);
							
							
							$image=wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full'); 

						    
							
						

				?>
	 <div class="col-sm-6">
	   <div class="b2sec">
	   <a href="<?php the_permalink();?>"> <img src="<?php echo $image[0]; ?>" alt=""></a>
	   <div class="grt_box">
	 
	   <h3><?php the_title();?></h3>
	   </div>
	   </div>
	 </div>
	 <?php } ?>
	 </div>
	
  
  
</div>
  
  </div>
</div>
<div class="col-sm-3">
  <div class="cenTer_add"><?php dynamic_sidebar('Add  section');?></div>
</div>
</div>

</div>

</div>






<div class="renoVet">
<div class="container">
   <div class="row">
   <div class="col-sm-12 mbtm_rt">
	 <h2>Editor’s Picks</h2>
	 <div class="row">
	 <?php 
	$args = array(
    'post_type' => 'news',
    'tax_query' => array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'news_category',
            'field'    => 'slug',
            'terms'    => 'act',
        ),
        array(
            'taxonomy' => 'news_sub_category',
            'field'    => 'slug',
            'terms'    => 'editors-picks',
        ),
    ),
);
$query = new WP_Query( $args );
while ( $query->have_posts() ) {
		   $query->the_post();
		    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
		?>
	 
	 <div class="col-sm-3">
	   <div class="b2sec">
	   <img src="<?php echo $image[0]; ?>" alt="">
	   <div class="grt_box">
	  
	   <h3><?php the_title();?></h3>
	   <div align="center"><a href="<?php the_permalink();?>">Read &amp; More</a></div>
	   </div>
	   </div>
	 </div>
	 
	
<?php } ?>
	
	
	
	
	
	
	 
	 </div>
	 
	 </div>
	  <hr class="dotHr">
   

   
   
     
   <div class="col-sm-12 mbtm_rt">
	 <h2>Property Market Trends</h2>
	 <div class="row">
	 
	 
	 
	 
	
	 
	 <?php 
	$args = array(
    'post_type' => 'news',
    'tax_query' => array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'news_category',
            'field'    => 'slug',
            'terms'    => 'act',
        ),
        array(
            'taxonomy' => 'news_sub_category',
            'field'    => 'slug',
            'terms'    => 'property-market-trends',
        ),
    ),
);
$query = new WP_Query( $args );
while ( $query->have_posts() ) {
		   $query->the_post();
		    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
		?>
	 
	 <div class="col-sm-3">
	   <div class="b2sec">
	   <img src="<?php echo $image[0]; ?>" alt="">
	   <div class="grt_box">
	  
	   <h3><?php the_title();?></h3>
	   <div align="center"><a href="<?php the_permalink();?>">Read &amp; More</a></div>
	   </div>
	   </div>
	 </div>
	 
	
<?php } ?>
	  
	
	
	
	
	 
	 </div>
	 
	 </div>
	 <hr class="dotHr">
	 
	 <div class="col-sm-12 mbtm_rt">
	 <h2>Celebrity Homes</h2>
	 <div class="row">
	 
	 
	 <?php 
	$args = array(
    'post_type' => 'news',
    'tax_query' => array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'news_category',
            'field'    => 'slug',
            'terms'    => 'act',
        ),
        array(
            'taxonomy' => 'news_sub_category',
            'field'    => 'slug',
            'terms'    => 'celebrity-homes',
        ),
    ),
);
$query = new WP_Query( $args );
while ( $query->have_posts() ) {
		   $query->the_post();
		    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
		?>
	 
	 <div class="col-sm-3">
	   <div class="b2sec">
	   <img src="<?php echo $image[0]; ?>" alt="">
	   <div class="grt_box">
	  
	   <h3><?php the_title();?></h3>
	   <div align="center"><a href="<?php the_permalink();?>">Read &amp; More</a></div>
	   </div>
	   </div>
	 </div>
	 
	
<?php } ?>
	 
	
	 
	
	  
	
	
	
	
	 
	 </div>
	 
	 </div>
	 <hr class="dotHr">
	 
	 
	 

	 
	
   </div>
    
</div>
</div>


<?php get_footer(); ?>
