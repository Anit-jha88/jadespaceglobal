<?php
/**
 * Template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>



   <?php
	
	$image=get_stylesheet_directory_uri().'/images/inner_img.jpg';	

	?>
     <section class="banner inner_banner" style="background:#000000 url(<?php echo $image ?>);background-repeat: no-repeat;background-size: cover;">
         <div class="container">
            <div class="inneR_con bnr_Con text-center wow fadeInLeft">
               <h1> <?php _e( 'Not Found', 'twentyten' ); ?></h1>
            </div>
         </div>
      </section>

      <section class="inner_btm inner_cmn">
         <div class="container">
            <div class="default_write">
               <div class="def_inn">
                  <h3><?php _e( 'Not Found', 'twentyten' ); ?></h3>
                  <div class="mul_txt">
                     <p> <?php _e( 'Apologies, but the page you requested could not be found. Perhaps searching will help.', 'twentyten' ); ?></p>
					 <?php get_search_form(); ?>
				  </div>
               </div>

               


               



              
            </div>
         </div>
      </section>



	
	<script type="text/javascript">
		// focus on search field after it has loaded
		document.getElementById('s') && document.getElementById('s').focus();
	</script>

<?php get_footer(); ?>