<?php
/* add_action('admin_init', 'dr_test00');
function dr_test00()
{
    global $wpdb;
    $table_name = $wpdb->base_prefix . 'cpm_product_importify';
    $query = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($table_name));

    if (!$wpdb->get_var($query) == $table_name) {
        // go go

        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE `{$wpdb->base_prefix}cpm_product_importify` (
  public_key varchar(255) NOT NULL,
  private_key varchar(255) NOT NULL,
  created_at datetime NOT NULL,
  expires_at datetime NOT NULL,
) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
} */
add_action('admin_menu', 'tradmin_page_settings');
function tradmin_page_settings()
{
    add_submenu_page('edit.php?post_type=products', 'products',   'Product Setting', 'edit_posts', basename(__FILE__), 'product_setting_fuction');
}

function product_setting_fuction()
{
    global $wpdb;

    $root = dirname(dirname(dirname(dirname(__FILE__)))) . DIRECTORY_SEPARATOR . 'wp-load.php';
    require_once($root);
    $cpm = array(
        "custom-field" => "meta_box_for_product_url_function",
        "custom-post-type" => "products"
    );
    //$row = 1;
    if (isset($_FILES['csv_file'])) {

        if ($_FILES["csv_file"]["size"] > 0) {


            /* dr custom */


            global $wpdb;
            $datafile = $_FILES['csv_file']['tmp_name'];
            $upload_dir = wp_upload_dir();
            // $upload_dir = $uploads['baseurl'];
            $file = $upload_dir['basedir'] . '/' . $_FILES['csv_file']['name'];
            $fileurl = $upload_dir['baseurl'] . '/' . $_FILES['csv_file']['name'];
            if (!move_uploaded_file(
                $_FILES['csv_file']['tmp_name'],
                $file
            )) {
                print_r('Failed to move uploaded file.');
            }


            global $wpdb;
            $table_name = 'cpm_product_importify';
            $sql = "
                LOAD DATA LOCAL INFILE '$fileurl'
                INTO TABLE $table_name
                FIELDS TERMINATED BY ','
                ENCLOSED BY '\"'
                LINES TERMINATED BY '\\n'
                IGNORE 1 ROWS";

            //  $sql = " LOAD DATA LOCAL INFILE '" . $fileurl . "' INTO TABLE " . $table_name . " FIELDS TERMINATED BY ','  LINES TERMINATED BY '\n' (pid,name,currency,price, description,productURL,imageURL,categories,brand,categoryPath,deliveryCosts,deliveryTime,descriptionLong,EAN,fromPrice,imageURL_large,stock,color,campaignID) SET descriptionLong = RTRIM(descriptionLong);";

            /* $sql = " LOAD DATA LOCAL INFILE '" . $fileurl . "' INTO TABLE " . $table_name . " FIELDS TERMINATED BY ';' LINES TERMINATED BY ';;' (@pid,@name,@currency,@price, @description,@productURL,@imageURL,@categories,@brand,@categoryPath,@deliveryCosts,@deliveryTime,@descriptionLong,@EAN,@fromPrice,@imageURL_large,@stock,@color,@campaignID) SET
             pid= @pid,
             name= @name,
             currency= @currency,
             price= @price,
             description= @description,
             productURL= @productURL,
             imageURL= @imageURL,
             categories= @categories,
             brand= @brand,
             categoryPath= @categoryPath,
             deliveryCosts= @deliveryCosts,
             deliveryTime= @deliveryTime,
             descriptionLong= @descriptionLong,
             EAN= @EAN,
             fromPrice= @fromPrice,
             imageURL_large= @imageURL_large,
             stock= @stock,
             color= @color,
             campaignID= @campaignID
            "; */


            $query = $wpdb->query($sql);
            if ($query) {
                echo "success";
            }
            /* dr custom */

            /*    $filename = $_FILES["csv_file"]["tmp_name"];

            //Opening the file.
            $handle = fopen($filename, "r");
            //Fetching Data from CSV file.
            // Open file in read mode
  
            $csvFile = fopen($_FILES['csv_file']['tmp_name'], 'r');
            if ($handle !== FALSE) {
                $ii = 0;
                while (($getData = fgetcsv($csvFile, 10000, ";")) !== FALSE) {
                    if ($ii >= 5) {
                        $csvData = array_map("utf8_encode", $getData);
                        echo "<pre>";
                        print_r($csvData);
                        echo "</pre>";
                    }
                    $ii++;
                }
                //Closing the file. 
                //   fclose($handle);
            } */
        }

        /*        $new = $array[0][0];
        $new = explode(";" , $new);
        $new2 = $array[1][0] ;
        $new2 =   explode (";",$new2);
        print_r($new); 
        echo '<br>'; */
        /*        echo "<pre>";
        print_r($array);
        echo "</pre>";  */
        //   die(); 

        /*     foreach($array as $data){
          
        } */
    }

?>
    <form class="form-horizontal" method="post" enctype="multipart/form-data">

        <div class="form-group">
            <label for="csvfile" class="control-label">Name of the file</label>
            <div class="cvs-input">
                <input type="file" class="form-control" name="csv_file" id="csv_file" accept="text/csv">
            </div>
            <div class="col-xs-3">
                <button type="submit" class="cvs-file-button">Upload</button>
            </div>
        </div>

    </form>
<?php

}
// Simple check to see if the current post exists within the
//  database. This isn't very efficient, but it works.
