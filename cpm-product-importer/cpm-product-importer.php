<?php
/*
Plugin Name: CPM Product Importer
Plugin URI: https://codepixelzmedia.com/
Description: Custom type (products) product importer
Version: 1.0.0
Author: Cpm
Author URI: https://codepixelzmedia.com/
*/

if (!defined('ABSPATH')) {
   exit;
}

/*Require some Wordpress core files for processing images*/
require_once(ABSPATH . 'wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');
/* require plugin loder file */
$init_file = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . "cpm-product-importer" . DIRECTORY_SEPARATOR  . "cpm-product-importer-loader.php";
require $init_file;



/*Create product custom fields to add Manufacture Name of Products*/



function cpm_product()
{
   $labels = array(
      'name'               => _x('products', 'post type general name'),
      'singular_name'      => _x('product', 'post type singular name'),
      'add_new'            => _x('Add New', 'book'),
      'add_new_item'       => __('Add New Products'),
      'edit_item'          => __('Edit product'),
      'new_item'           => __('New product'),
      'all_items'          => __('All product'),
      'view_item'          => __('View product'),
      'search_items'       => __('Search Products'),
      'not_found'          => __('No product found'),
      'not_found_in_trash' => __('No Products found in the Trash'),
      'parent_item_colon'  => '',
      'menu_name'          => 'Products'
   );
   $args = array(
      'labels'        => $labels,
      'description'   => 'Holds our Products and product specific data',
      'public'        => true,
      'menu_position' => 5,
      'supports'      => array('title', 'editor', 'thumbnail', 'excerpt', 'comments', 'gallery'),
      'has_archive'   => true,
   );
   register_post_type('products', $args);
}
add_action('init', 'cpm_product');



/**
 * It creates a new taxonomy called "product Types" that can be used with the "testimonial" post type.
 */
add_action('init', 'product_category_taxonomy', 0);
function product_category_taxonomy()
{

   $labels = array(
      'name' => _x('product category', 'taxonomy general name'),
      'singular_name' => _x('product category', 'taxonomy singular name'),
      'search_items' =>  __('Search product category'),
      'all_items' => __('All product category'),
      'parent_item' => __('Parent product category'),
      'parent_item_colon' => __('Parent product category:'),
      'edit_item' => __('Edit product category'),
      'update_item' => __('Update product category'),
      'add_new_item' => __('Add New product category'),
      'new_item_name' => __('New product category Name'),
      'menu_name' => __('product category'),
   );

   // Now register the taxonomy
   register_taxonomy('product category', array('products'), array(
      'hierarchical' => true,
      'labels' => $labels,
      'show_ui' => true,
      'show_in_rest' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array('slug' => 'product category'),
   ));
}

function meta_box_for_product_url_function()
{
   add_meta_box('products-url-id', 'URL', 'product_url_field_function', 'products', 'normal', 'high');
   add_meta_box('products-price-id', 'Product Price', 'product_price_function', 'products', 'normal', 'high');
   add_meta_box('products-delivery-cost-id', 'Delivery Cost', 'product_delivery_cost_function', 'products', 'normal', 'high');
   add_meta_box('products-delivery-time-id', 'Delivery Time', 'product_delivery_time_function', 'products', 'normal', 'high');
   add_meta_box('products-currency', 'Currency', 'product_currency_function', 'products', 'normal', 'high');
   add_meta_box('products-brand', 'Brand', 'product_brand_function', 'products', 'normal', 'high');
   add_meta_box('products-stock', 'Stock', 'product_stock_function', 'products', 'normal', 'high');
   add_meta_box('products-color', 'Color', 'product_color_function', 'products', 'normal', 'high');
   add_meta_box('products-campaign-id', 'Campaign ID', 'product_campaign_id_function', 'products', 'normal', 'high');
   add_meta_box('products-ean', 'EAN', 'product_ean_function', 'products', 'normal', 'high');
   add_meta_box('products-from-price', 'From Price', 'product_from_price_function', 'products', 'normal', 'high');
}
add_action('add_meta_boxes', 'meta_box_for_product_url_function');

function product_url_field_function($post)
{
   $url = get_post_meta( $post->ID, 'product_url_data',true);
?>
   <input type="url" id="product_url" name="product_url" value="<?php echo esc_attr($url); ?>">
<?php
}


function product_price_function($post)
{
   $price = get_post_meta( $post->ID, 'product_price_data');
?>
   <input type="number" id="product_price" name="product_price" value="<?php echo isset($price) ? $price[0] : ''; ?>">
<?php
}

function product_delivery_cost_function($post)
{
   $delivery_cost = get_post_meta($post->ID, 'product_delivery_cost_data', false);
?>
   <input type="number" id="product_delivery_cost" name="product_delivery_cost" value="<?php echo isset($delivery_cost) ? $delivery_cost[0] : ''; ?>">
<?php
}

function product_delivery_time_function($post)
{
   $delivery_time = get_post_meta($post->ID, 'product_delivery_time_data', false);
?>
   <input type="time" id="product_delivery_time" name="product_delivery_time" value="<?php echo isset($delivery_time) ? $delivery_time[0] : ''; ?>">
<?php
}

function product_currency_function($post){
   $currency = get_post_meta($post->ID ,'product_currency_data',true);
?>
   <input type="text" id="product_currency" name="product_currency" value="<?php echo esc_attr($currency);?>" >
<?php
}

function product_brand_function($post){
   $brand = get_post_meta($post->ID, 'product_brand_data', true);
?>
   <input type="text" id="product_brand" name="product_brand" value="<?php echo esc_attr($brand) ;?>" >
<?php
}

function product_stock_function($post){
   $stock = get_post_meta($post->ID, 'product_stock_data', false);
   ?>
      <input type="number" id="product_stock" name="product_stock" value="<?php echo isset($stock) ? $stock[0] : '';?>" >
   <?php
}

function product_color_function($post){
   $color = get_post_meta($post->ID, 'product_color_data', true);
   ?>
      <input type="text" id="product_color" name="product_color" value="<?php echo esc_attr($color);?>" >
   <?php
}

function product_campaign_id_function($post){
   $campaignID = get_post_meta($post->ID, 'product_campaign_id_data', false);
   ?>
      <input type="number" id="product_campaign_id" name="product_campaign_id" value="<?php echo isset($campaignID) ? $campaignID[0] : '';?>" >
   <?php
}

function product_ean_function($post){
   $ean = get_post_meta($post->ID, 'product_ean_data', false);
   ?>
      <input type="number" id="product_ean" name="product_ean" value="<?php echo isset($ean) ? $ean[0] : '';?>" >
   <?php
}

function product_from_price_function($post){
   $product_from_price = get_post_meta($post->ID, 'product_from_price_data', false);
   ?>
      <input type="text" id="product_from_price" name="product_from_price" value="<?php echo isset($product_from_price[0]) ? $product_from_price[0] : '';?>" >
   <?php
}


function property_gallery_add_metabox()
{
   add_meta_box(
      'post_custom_gallery',
      'Gallery',
      'property_gallery_metabox_callback',
      'products', // Change post type name
      'normal',
      'core'
   );
}
add_action('admin_init', 'property_gallery_add_metabox');

function property_gallery_metabox_callback()
{
   wp_nonce_field(basename(__FILE__), 'sample_nonce');
   global $post;
   $gallery_data = get_post_meta($post->ID, 'gallery_data', true);
?>
   <div id="gallery_wrapper">
      <div id="img_box_container">
         <?php
         if (isset($gallery_data['image_url'])) {
            for ($i = 0; $i < count($gallery_data['image_url']); $i++) {
         ?>
               <div class="gallery_single_row dolu">
                  <div class="gallery_area image_container ">
                     <img class="gallery_img_img" src="<?php esc_html_e($gallery_data['image_url'][$i]); ?>" height="55" width="55" onclick="open_media_uploader_image_this(this)" />
                     <input type="hidden" class="meta_image_url" name="gallery[image_url][]" value="<?php esc_html_e($gallery_data['image_url'][$i]); ?>" />
                  </div>
                  <div class="gallery_area">
                     <span class="button remove" onclick="remove_img(this)" title="Remove" /><i class="fas fa-trash-alt"></i></span>
                  </div>
                  <div class="clear" />
               </div>
      </div>
<?php
            }
         }
?>
   </div>
   <div style="display:none" id="master_box">
      <div class="gallery_single_row">
         <div class="gallery_area image_container" onclick="open_media_uploader_image(this)">
            <input class="meta_image_url" value="" type="hidden" name="gallery[image_url][]" />
         </div>
         <div class="gallery_area">
            <span class="button remove" onclick="remove_img(this)" title="Remove" /><i class="fas fa-trash-alt"></i></span>
         </div>
         <div class="clear"></div>
      </div>
   </div>
   <div id="add_gallery_single_row">
      <input class="button add" type="button" value="+" onclick="open_media_uploader_image_plus()" title="Add image" />
   </div>
   </div>
<?php
}

function property_gallery_styles_scripts()
{
   global $post;
   if ('products' != $post->post_type)
      return;
?>
   <style type="text/css">
      .gallery_area {
         float: right;
      }

      .image_container {
         float: left !important;
         width: 100px;
         background: url('https://i.hizliresim.com/dOJ6qL.png');
         height: 100px;
         background-repeat: no-repeat;
         background-size: cover;
         border-radius: 3px;
         cursor: pointer;
      }

      .image_container img {
         height: 100px;
         width: 100px;
         border-radius: 3px;
      }

      .clear {
         clear: both;
      }

      #gallery_wrapper {
         width: 100%;
         height: auto;
         position: relative;
         display: inline-block;
      }

      #gallery_wrapper input[type=text] {
         width: 300px;
      }

      #gallery_wrapper .gallery_single_row {
         float: left;
         display: inline-block;
         width: 100px;
         position: relative;
         margin-right: 8px;
         margin-bottom: 20px;
      }

      .dolu {
         display: inline-block !important;
      }

      #gallery_wrapper label {
         padding: 0 6px;
      }

      .button.remove {
         background: none;
         color: #f1f1f1;
         position: absolute;
         border: none;
         top: 4px;
         right: 7px;
         font-size: 1.2em;
         padding: 0px;
         box-shadow: none;
      }

      .button.remove:hover {
         background: none;
         color: #fff;
      }

      .button.add {
         background: #c3c2c2;
         color: #ffffff;
         border: none;
         box-shadow: none;
         width: 100px;
         height: 100px;
         line-height: 100px;
         font-size: 4em;
      }

      .button.add:hover,
      .button.add:focus {
         background: #e2e2e2;
         box-shadow: none;
         color: #0f88c1;
         border: none;
      }
   </style>
   <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/solid.js" integrity="sha384-+Ga2s7YBbhOD6nie0DzrZpJes+b2K1xkpKxTFFcx59QmVPaSA8c7pycsNaFwUK6l" crossorigin="anonymous"></script>
   <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
   <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/fontawesome.js" integrity="sha384-7ox8Q2yzO/uWircfojVuCQOZl+ZZBg2D2J5nkpLqzH1HY0C1dHlTKIbpRz/LG23c" crossorigin="anonymous"></script>
   <script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
   <script type="text/javascript">
      function remove_img(value) {
         var parent = jQuery(value).parent().parent();
         parent.remove();
      }
      var media_uploader = null;

      function open_media_uploader_image(obj) {
         media_uploader = wp.media({
            frame: "post",
            state: "insert",
            multiple: false
         });
         media_uploader.on("insert", function() {
            var json = media_uploader.state().get("selection").first().toJSON();
            var image_url = json.url;
            var html = '<img class="gallery_img_img" src="' + image_url + '" height="55" width="55" onclick="open_media_uploader_image_this(this)"/>';
            console.log(image_url);
            jQuery(obj).append(html);
            jQuery(obj).find('.meta_image_url').val(image_url);
         });
         media_uploader.open();
      }

      function open_media_uploader_image_this(obj) {
         media_uploader = wp.media({
            frame: "post",
            state: "insert",
            multiple: false
         });
         media_uploader.on("insert", function() {
            var json = media_uploader.state().get("selection").first().toJSON();
            var image_url = json.url;
            console.log(image_url);
            jQuery(obj).attr('src', image_url);
            jQuery(obj).siblings('.meta_image_url').val(image_url);
         });
         media_uploader.open();
      }

      function open_media_uploader_image_plus() {
         console.log('ell');
         media_uploader = wp.media({
            frame: "post",
            state: "insert",
            multiple: true
         });
         media_uploader.on("insert", function() {

            var length = media_uploader.state().get("selection").length;
            var images = media_uploader.state().get("selection").models

            for (var i = 0; i < length; i++) {
               var image_url = images[i].changed.url;
               var box = jQuery('#master_box').html();
               jQuery(box).appendTo('#img_box_container');
               var element = jQuery('#img_box_container .gallery_single_row:last-child').find('.image_container');
               var html = '<img class="gallery_img_img" src="' + image_url + '" height="55" width="55" onclick="open_media_uploader_image_this(this)"/>';
               element.append(html);
               element.find('.meta_image_url').val(image_url);
               console.log(image_url);
            }
         });
         media_uploader.open();
      }
      jQuery(function() {
         jQuery("#img_box_container").sortable(); // Activate jQuery UI sortable feature
      });
   </script>
<?php
}
add_action('admin_head-post.php', 'property_gallery_styles_scripts');
add_action('admin_head-post-new.php', 'property_gallery_styles_scripts');



function property_gallery_save($post_id)
{
   if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
      return;
   }
   $is_autosave = wp_is_post_autosave($post_id);
   $is_revision = wp_is_post_revision($post_id);
   $is_valid_nonce = (isset($_POST['sample_nonce']) && wp_verify_nonce($_POST['sample_nonce'], basename(__FILE__))) ? 'true' : 'false';

   if ($is_autosave || $is_revision || !$is_valid_nonce) {
      return;
   }
   if (!current_user_can('edit_post', $post_id)) {
      return;
   }

   // Correct post type
   if ('products' != get_post_type( $post_id)) // here you can set the post type name
      return;

   if ($_POST['gallery']) {

      // Build array for saving post meta
      $gallery_data = array();
      for ($i = 0; $i < count($_POST['gallery']['image_url']); $i++) {
         if ('' != $_POST['gallery']['image_url'][$i]) {
            $gallery_data['image_url'][]  = $_POST['gallery']['image_url'][$i];
         }
      }

      if ($gallery_data)
         update_post_meta($post_id, 'gallery_data', $gallery_data);
      else
         delete_post_meta($post_id, 'gallery_data');
   }else {
      delete_post_meta($post_id, 'gallery_data');
   }

   if($_POST['product_price']){
      update_post_meta($post_id, 'product_price_data', $_POST['product_price']);
   }
   if($_POST['product_url']){
      update_post_meta($post_id, 'product_url_data', $_POST['product_url']);
   }
   if($_POST['product_delivery_cost']){
      update_post_meta($post_id, 'product_delivery_cost_data', $_POST['product_delivery_cost']);
   }
   if($_POST['product_delivery_time']){
      update_post_meta($post_id, 'product_delivery_time_data', $_POST['product_delivery_time']);
   }

   if($_POST['product_currency']){
      update_post_meta($post_id, 'product_currency_data', $_POST['product_currency']);
   }
   if($_POST['product_brand']){
      update_post_meta($post_id, 'product_brand_data', $_POST['product_brand']);
   }
   if($_POST['product_color']){
      update_post_meta($post_id, 'product_color_data', $_POST['product_color']);
   }
   if($_POST['product_stock']){
      update_post_meta($post_id, 'product_stock_data', $_POST['product_stock']);
   }
   if($_POST['product_campaign_id']){
      update_post_meta($post_id, 'product_campaign_id_data', $_POST['product_campaign_id']);
   }
   if($_POST['product_ean']){
      update_post_meta($post_id, 'product_ean_data', $_POST['product_ean']);
   }
   if($_POST['product_from_price']){
      update_post_meta($post_id, 'product_from_price_data', $_POST['product_from_price']);
   }

}
add_action('save_post', 'property_gallery_save');
