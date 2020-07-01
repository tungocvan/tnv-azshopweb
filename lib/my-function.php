<?php 

function createSlug($str) {
	$str = trim(mb_strtolower($str));
	$str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
	$str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
	$str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
	$str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
	$str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
	$str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
	$str = preg_replace('/(đ)/', 'd', $str);
	$str = preg_replace('/[^a-z0-9\s]/', '', $str);
	$str = preg_replace('/([\s]+)/', '-', $str);
	return $str;
}
function getAllProduct($args = '') {
	if($args == ''){
		$args = array(
			'post_status' => 'publish', // Chỉ lấy những bài viết được publish
			'showposts' => -1, // số lượng bài viết, -1 lấy tất cả.
			'post_type' => 'product', // page: trang, post: bài viết, product: sản phẩm
		); 
	}        
	$vnkings = new WP_query($args);
	$s = array();$item=array();
	while ( $vnkings->have_posts() ) : $vnkings->the_post();
		$id=get_the_ID();
		$author=get_the_author();                  
		$item = getProduct($id);
		$item[0]["author"] =  $author;     
		array_push($s,$item[0]);
	endwhile;		

	return $s;
}
function getProduct($id) {
	$danhmuc = array();
	$attrib = array();
	$_product = wc_get_product($id); // https://businessbloomer.com/woocommerce-easily-get-product-info-title-sku-desc-product-object/
	// get attributes
	$attributes = $_product->get_attributes();
		 foreach ($attributes as $key => $value){
			 array_push($attrib,array("id"=>$value['id'],"name"=>$value['name'],"options"=>$value['options']));
   }            
   // get album images
   $gallery_image_ids = $_product->get_gallery_image_ids();
   $gallery_image = [];
   for($i = 0, $j = count($gallery_image_ids); $i < $j;$i++ ){
		 $image_query = wp_get_attachment_image_src($gallery_image_ids[$i]);
		 array_push($gallery_image, $image_query[0]);
   }
   $_product_tag_list = strip_tags(wc_get_product_tag_list($id));

   $cateSlug = array();$tax_terms = get_terms($taxonomy);
   $category_ids = $_product->get_category_ids();
	   foreach ($category_ids as $category_id){
		  foreach($tax_terms as $value ){
			 if($value->term_id==$category_id){
				array_push($cateSlug,$value->slug);
			 }
		  }
   }
   $title = get_the_title($id);
   $regular_price = $_product->get_regular_price();
   $price = $_product->get_price();
   $short_description = $_product->get_short_description();
   $slug = $_product->get_slug();
   $sku = $_product->get_sku();
   $average_rating = $_product->get_average_rating();
   $status = $_product->get_status();
   $get_the_permalink = get_the_permalink();
   $img = get_the_post_thumbnail_url($id, 'post-thumbnail'); 
   $description = $_product->get_description();
   $date_created = $_product->get_date_created()->date('F d, Y g:i a');
   $date_modified = $_product->get_date_modified()->date('F d, Y g:i a');

   array_push($danhmuc,array("gallery_image" => $gallery_image,"attrib" => $attrib,"tag_list" =>$_product_tag_list,"cateSlug" => $cateSlug, 
   "regular_price" => $regular_price, "price" => $price, "short_description" =>$short_description, "description" => $description,
   "slug" => $slug, "sku" => $sku, "average_rating" => $average_rating, "status" => $status, "get_the_permalink" => $get_the_permalink, "img" =>$img,
   "date_created" => $date_created , "date_modified" => $date_modified,"id" => $id ,"title" => $title
   ));

   return $danhmuc;
} 
function layMotDanhMuc($taxonomy) {
	$args = array(
	  'taxonomy' => $taxonomy,
	  'hide_empty' => false,
	  'parent' => $parent,        
	); 
  
	$tax_terms = get_terms($args);
	return $tax_terms;
}
function getNavMenu($taxonomy='nav_menu') {
	$dm_menu = layMotDanhMuc($taxonomy);
	$menu = array();
	foreach($dm_menu as $key => $value){
	   $menu[$value->slug] = menu_route($value->name);
	}
	return $menu;            
}

function getAllpost($args = '') {
    
    return array("id" => "1");
}

function menu_route($menuname){
	$menu_items = wp_get_nav_menu_items($menuname); 
	$menu = array();
	foreach ($menu_items as $item ){
		if($item->thumbnail_id){
			$id = $item->thumbnail_id; 
			$image = wp_get_attachment_image_src($id, 'thumbnail', false );
			$idHover = $item->thumbnail_hover_id;          
			$imageHover = wp_get_attachment_image_src($idHover, 'thumbnail', false );
		}else{
			$image=[];
			$imageHover=[];
		}  
		
		$menuItem = array(
			"id"=>$item->ID,
			"menu_order"=>$item->menu_order,
			"title"=>$item->title,
			"dom_id"=>preg_replace('/[^a-z]+/i',"_",$item->title),
			"parent" => $item->menu_item_parent,
			"url"=>$item->url,
			"slug" => createSlug($item->title),
			"imgMenu" => $image[0],
			"imgMenuHover" => $imageHover[0]
		);
		array_push($menu,$menuItem);
	}
	return $menu; 
}

function save_file_json($filename,$data) {
 
// 	$myfile = fopen($filename, "w") or die("Unable to open file!");
// 	fwrite($myfile, json_encode($data, TRUE)); 
// 	fclose($myfile);
}

?>