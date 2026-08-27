<?php
/**
 * Template Name: Qrcode generate
 
 */

get_header(); 

//include 'phpqrcode/qrlib.php'; 

  


?>
    <div class="ltn__utilize-overlay"></div>

    <!-- BREADCRUMB AREA START -->
    <div class="ltn__breadcrumb-area ltn__breadcrumb-area-2 ltn__breadcrumb-color-white bg-overlay-theme-black-90 bg-image" data-bg="<?php echo get_template_directory_uri()?>/img/banner/hardware-banner.png">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ltn__breadcrumb-inner ltn__breadcrumb-inner-2 justify-content-between">
                        <div class="section-title-area ltn__section-title-2">
                          
                            <h1 class="section-title white-color">Qr  Code</h1>
                        </div>
                        <div class="ltn__breadcrumb-list">
                            <ul>
                                <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                                <li>Qr  Code</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="container">
    <div class="row"></br>
    <?php 
    if($_POST['qrcodename']!='' && $_POST['amount']!='' ){
        
        $text = $_POST['amount']; 
        $path = 'images/'; 
        $file = $path.uniqid().".png"; 
        $ecc = 'L'; 
        $pixel_Size = 10; 
        $frame_Size = 10; 
        
        
        QRcode::png($text, $file, $ecc, $pixel_Size, $frame_Size); 
        
 $new_post = array(
     
      'post_type' => 'qrcodes', // Custom Post Type Slug
      'post_status' => 'publish',
      'post_title' => $_POST['qrcodename'],
    );

$post_id = wp_insert_post($new_post);

update_post_meta( $post_id, 'amount', sanitize_text_field($_POST['amount']) );
update_post_meta( $post_id, 'qr_code', sanitize_text_field($file) );
$msg="Qr Code Generaed sucessfully Done !";
    
    }
    ?>
        <p style="color:green"><?php echo $msg;?></p>
        <div class="col-lg-12">
        <form action="" method="post">
  <div class="form-group">
    <label for="exampleInputEmail1">Qr Code Name</label>
    <input type="text" class="form-control" name="qrcodename" aria-describedby="qrcodename" placeholder="Enter qr code name" required>
  
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Amount</label>
    <input type="text" class="form-control" name="amount" placeholder="Amount" required>
  </div>

  <button type="submit" class="btn btn-primary">Submit</button>
</form>
       </div> 
    </div>
</div>

<!--<img src='https://jadespaceglobal.com/images/6682e96057c0d.png'>-->

<?php get_footer(); ?>