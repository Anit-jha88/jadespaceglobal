<?php
/**
 * Template Name: Api
 
 */

header('Access-Control-Allow-Origin: *'); 
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
header('Content-type: application/json');

if($_GET['qrcode']=='qrcode'){

echo 'anit';

}

if($_GET['category']=='category'){
 $cat = array();
 $taxonomy = 'product-category';
 $parent_categories = get_terms(array(
        'taxonomy' => $taxonomy,
        'parent'   => 0, // 0 to get only top-level terms (i.e., parents)
        'hide_empty' => false, // Change to true to hide empty terms
        'orderby' => 'term_id', // Order by term ID
        'order' => 'ASC' 
    ));
    
 foreach ($parent_categories as $category) {
   $id=$category->term_id;
   $name=$category->name;     
  $cat[] = array("id" => $id, 'name' =>$name); 
 }
$json =	$cat;
echo json_encode(array('cat'=>$json));
}

if($_GET['cpost']=='cpost' && $_GET['id']!=''){
 $term_id =$_GET['id']; // Replace with your term ID

// The custom taxonomy you are using
$taxonomy = 'product-category'; // Replace with your custom taxonomy slug

// WP_Query arguments
$args = array(
    'post_type' => 'product', // Change to your custom post type if needed
    'posts_per_page'=>-1,
    'tax_query' => array(
        array(
            'taxonomy' => $taxonomy,
            'field'    => 'term_id',
            'terms'    => $term_id,
        ),
    ),
);

$term = get_term($term_id, $taxonomy);
$termname= $term->name;
// The Query
$query = new WP_Query($args);

 $catpostall = array();
   while ($query->have_posts()) {
        $query->the_post();
        $id=get_the_ID();
        $title=get_the_title();
        $image=wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()),'full');
        $image2=get_field('product_image_2');
        
         $catpostall[] = array("id" => $id, 'name' => $title, 'image'=>$image[0], 'image2'=>$image2); 

}

$json =	$catpostall;
echo json_encode(array('info'=>$termname,'catall'=>$json));

}




if($_GET['singlepost']=='singlepost' && $_GET['id']!=''){
    
    
   $id=$_GET['id'];
   $name=get_the_title($id);
   $dimension= get_field('dimension',$id);
   $material= get_field('material',$id);
   $singlepost = array();
   $images = get_field('product_gallery',$id);
    foreach( $images as $image_id ):    
      $singlepost[] = array('image'=>esc_url($image_id));   
    endforeach;    
    
$json =	$singlepost;
echo json_encode(array('name'=>$name,'dimension'=>$dimension,'material'=>$material,'singlepost'=>$json));
    
}



?>