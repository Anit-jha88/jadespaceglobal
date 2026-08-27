<?php
/**
 * The loop that displays posts
 *
 * The loop displays the posts and the post content. See
 * https://codex.wordpress.org/The_Loop to understand it and
 * https://codex.wordpress.org/Template_Tags to understand
 * the tags used in it.
 *
 * This can be overridden in child themes with loop.php or
 * loop-template.php, where 'template' is the loop context
 * requested by a template. For example, loop-index.php would
 * be used if it exists and we ask for the loop with:
 * <code>get_template_part( 'loop', 'index' );</code>
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>


<?php 
while ( have_posts() ) : the_post(); 
$image=wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
global $product;
?>
    <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                    <div class="ltn__product-item ltn__product-item-3 text-left">
                        <div class="product-img">
                            <a href="#."><img src="<?php echo $image[0];?>" alt="#"></a>
                           
                            <div class="product-hover-action">
                                <ul>
                                    <li>
                                        <a href="<?php the_permalink();?>" title="Quick View">
                                            <i class="far fa-eye"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/?add-to-cart=<?php echo get_the_ID();?>" title="Add to Cart">
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
                            <h2 class="product-title"><a href="#."><?php echo get_the_title();?></a></h2>
                            <div class="product-price">
                                <span>₹<?php echo $product->get_regular_price();?></span>
                                <del>₹<?php echo $product->get_sale_price();?></del>
                            </div>
                        </div>
                    </div>
                </div>

<?php endwhile; // End the loop. Whew. ?>

