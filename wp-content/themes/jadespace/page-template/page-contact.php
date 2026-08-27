<?php
/**
 * Template Name: Contact
 
 */

get_header();

if( !empty(get_the_post_thumbnail_url( get_the_ID(),'full')) ):
	$bannerImage = get_the_post_thumbnail_url( get_the_ID(),'full');
else:
	$bannerImage = get_template_directory_uri().'/images/dealerBannerImg.png';
endif;
 
?>

  <div class="ltn__breadcrumb-area ltn__breadcrumb-area-2 ltn__breadcrumb-color-white bg-overlay-theme-black-90 bg-image" data-bg="<?php echo get_template_directory_uri()?>/img/banner/hardware-banner.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner ltn__breadcrumb-inner-2 justify-content-between">
                        <div class="section-title-area ltn__section-title-2">
                          
                            <h1 class="section-title white-color">Contact Us</h1>
                        </div>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                 <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                                <li>Contact Us</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ltn__contact-address-area pt-70 mb-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                        <div class="ltn__contact-address-icon">
                            <img src="<?php echo get_template_directory_uri()?>/img/icons/11.png" alt="Icon Image">
                        </div>
                        <h3>Connect With Us</h3>
                        <p><?php echo get_field('email_addredd');?></p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                        <div class="ltn__contact-address-icon">
                            <img src="<?php echo get_template_directory_uri()?>/img/icons/12.png" alt="Icon Image">
                        </div>
                        <h3>Production Site</h3>
                        <p><?php echo get_field('phone_number');?></p><br />
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                        <div class="ltn__contact-address-icon">
                            <img src="<?php echo get_template_directory_uri()?>/img/icons/12.png" alt="Icon Image">
                        </div>
                        <h3>Mumbai Office</h3>
                        <p><?php echo get_field('office_address');?>

                        </p><br />
                    </div>
                </div>
				
				 <div class="col-lg-4">
                    <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                        <div class="ltn__contact-address-icon">
                            <img src="<?php echo get_template_directory_uri()?>/img/icons/12.png" alt="Icon Image">
                        </div>
                        <h3>Bangalore Office</h3>
                        <p>
F Wing, 203, Bren Trillium, Doddanagamangala Road, Naganathapura, Bangalore,
Karnataka, India - 560100
                        </p>
                    </div>
                </div>
				
				 <div class="col-lg-4">
                    <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                        <div class="ltn__contact-address-icon">
                            <img src="<?php echo get_template_directory_uri()?>/img/icons/12.png" alt="Icon Image">
                        </div>
                        <h3>Display Center Pune</h3>
                        <p>
Sr. No. 65, Chordiya Complex, Gangadham - Shatrunjay Mandir Road, Near Salve Garden, Kondhwa BK,<br /> Pune, Maharashtra - 411048
                        </p>
                    </div>
                </div>
				
				 <div class="col-lg-4">
                    <div class="ltn__contact-address-item ltn__contact-address-item-3 box-shadow">
                        <div class="ltn__contact-address-icon">
                            <img src="<?php echo get_template_directory_uri()?>/img/icons/12.png" alt="Icon Image">
                        </div>
                        <h3>Display Center Mumbai</h3>
                        <p>
Shakti Calista, Shop No-5, <br />Plot no-15, Sec-8, Opp D Mart, Ghansoli, <br />Navi Mumbai, Maharashtra - 400701
                        </p> <br />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTACT ADDRESS AREA END -->
    
    <!-- CONTACT MESSAGE AREA START -->
    <div class="ltn__contact-message-area section-bg-1 pt-70 pb-70  ">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__form-box contact-form-box box-shadow white-bg">
                        <h4 class="title-2">Connect with us</h4>
                        <?php echo do_shortcode('[contact-form-7 id="951a188" title="Contact form 1" html_class="contact-form"]'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTACT MESSAGE AREA END -->

    <!-- GOOGLE MAP AREA START -->
    <div class="google-map mb-0">
		
		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3769.857432358184!2d72.91561287466607!3d19.113909350782453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7c7ea47000001%3A0xbf9b67a96d96bb05!2sJade%20Space%20Global%20Pvt%20Ltd.!5e0!3m2!1sen!2sin!4v1750580505069!5m2!1sen!2sin" width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
		
		
       
        

    </div>
    <!-- GOOGLE MAP AREA END -->
<?php get_footer(); ?>
