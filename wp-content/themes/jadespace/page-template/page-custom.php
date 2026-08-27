<?php
/**
 * Template Name: Custom
 
 */

get_header(); 
if( !empty(get_the_post_thumbnail_url( get_the_ID(),'full')) ):
	$bannerImage = get_the_post_thumbnail_url( get_the_ID(),'full');
else:
	$bannerImage = get_template_directory_uri().'/images/dealerBannerImg.png';
endif;

?>
 
  <div class="comingSoon">
   <section class="footerSec commingS">
      <!-- <div class="ftrImg">
         <img src="images/footerImg.png" alt="">
      </div> -->
      <div class="container">
         <div class="logoBox">
            <img src="<?php bloginfo( 'template_url' ); ?>/images/commingLogo.png" alt="">
         </div>
         <div class="headingComing">
             <?php the_content();?>
			</div>
         <form method="post" action="https://powerarmory.com/?na=s">
            <div class="form_group">
			   <input type="hidden" name="nlang" value="">
               <input  placeholder="Email address..." type="email" name="ne" value="" required>
			    
               <input type="submit" class="btn" value="Shop Now">
            </div>
         </form>
      </div>               
   
   </section>
   <div class="ftrBottom">
      <div class="socialIcons">
         <a href="https://www.facebook.com/powerarmory" target="_blank"><i class="fab fa-facebook-f"></i></a>
         <!--<a href="#"> <i class="fab fa-youtube"></i> </a>
         <a href="#"><i class="fab fa-instagram"></i></a>-->
      </div>
      <div class="btmTxt">
         <p>© 2024 Jadespace Global All Rights Reserved.</p>                  
      </div>
     </div>
</div>
     
      <span id="scroll" style="display: none;"><img src="<?php bloginfo( 'template_url' ); ?>/images/up-arrow-angle.svg" alt="arrow"></span>
                      
                