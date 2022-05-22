<?php
/*
* Plugin Name:       KCW Advertising
* Description:       Provides KCW Advertising for Merch (and maybe more)
* Version:           0.0.1
* Requires at least: 5.2
* Requires PHP:      7.2
* Author:            Zack Jones
*/

function  kcw_advertising_register_dependencies() {
    wp_register_style("kcw-advertising", plugins_url("kcw-advertising.css", __FILE__), null, "0.0.1");
    wp_register_script("kcw-advertising", plugins_url("kcw-advertising.js", __FILE__), array('jquery'), "0.0.1");
}
add_action("wp_enqueue_scripts", "kcw_gallery_register_dependencies");

function kcw_advertising_enqueue_dependencies() {
    wp_enqueue_style("kcw-advertising");
    wp_enqueue_script("kcw-advertising");
}

//Create random & suitable a set of merch images to ensure a fresh advertisment everytime
function kcw_advertising_merch_create_product_set() {
    /*
        Thoughts:
            0. Read in a list of available products
            1. Use a guidline for #of shirts, stickers, and prints per banner
            2. Create a set of products based on the guideline
            3. Shuffle the images 
    */
    return json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "products.json"), true);
}

function kcw_advertising_merch_create_product_card($product) {
    $name = $product["name"];
    $image = $product["image"];
    $link = $product["link"];
    $html = "<li>
        <a href='$link'>
            <div class='kcw-advertising-merch-card-wrapper'>
                <div class='kcw-advertising-merch-card-image'>
                    <img src='$image'>
                </div>
                <div class='kcw-advertising-merch-card-title'>
                    <p>$name</p>
                </div>
            </div>
        </a>
    </li>";
    return $html;
}

//Return essential HTML for the merch banner
function kcw_advertising_merch_banner_html($products) {
    $product_html = "";
    foreach ($products as $product) {
        $product_html .= kcw_advertising_merch_create_product_card($product);
    }
    
    return "<div class='kcw-advertising-merch-wrapper'>
        <div class='kcw-advertising-merch-cards-wrapper'>
            <ul>
                $product_html
            </ul>
        </div>
        <div class='kcw-advertising-merch-subline-wrapper'>
            <div clas='kcw-advertising-merch-subline'>
                <em>Our merch is printed & shipped by Teespring AKA Spring. <a href='https://www.spri.ng/#howitworks'>Learn more</a></em>
            </div>
        </div>
    </div>";
}

function kcw_advertising_js_data($data) {
    $data = json_encode($data);
    return `<script>var kcw_advertising_data = $data</script>`;
}

function kcw_advertising_merch_init() {
    //Enqueue style and script
    kcw_advertising_enqueue_dependencies();

    //Merch product set
    $products = kcw_advertising_merch_create_product_set();
    //Merch advertisment html
    $html = kcw_advertising_merch_banner_html($products);

    echo $html;
}

add_shortcode("kcw-advertising-merch", 'kcw_advertising_merch_init');
