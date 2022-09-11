<?php
/*
* Plugin Name:       KCW Advertising
* Description:       Provides KCW Advertising for Merch (and maybe more)
* Version:           0.2.96
* Requires at least: 5.2
* Requires PHP:      7.2
* Author:            Zack Jones
*/

function  kcw_advertising_register_dependencies() {
    wp_register_style("kcw-advertising", plugins_url("kcw-advertising.css", __FILE__), null, "0.2.95");
    wp_register_script("kcw-advertising", plugins_url("kcw-advertising.js", __FILE__), array('jquery'), "0.2.3");
    wp_register_script("jquery-transit", plugins_url("jquery.transit.min.js", __FILE__), array('jquery'));
}
add_action("wp_enqueue_scripts", "kcw_advertising_register_dependencies");

function kcw_advertising_enqueue_dependencies() {
    wp_enqueue_style("kcw-advertising");
    wp_enqueue_script("kcw-advertising");
    wp_enqueue_script("jquery-transit");
}

//Create random & suitable a set of merch images to ensure a fresh advertisment everytime
function kcw_advertising_merch_create_product_set($count) {
    /*
        Thoughts:
            0. Read in a list of available products
            1. Use a guidline for #of shirts, stickers, and prints per banner
            2. Create a set of products based on the guideline
            3. Shuffle the images 
    */
    
    //Current process is fully random
    $products = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . "products.json"), true);
    shuffle($products);
    $products = array_slice($products, 0, $count);
    return $products;
}

function kcw_advertising_merch_create_product_card($product) {
    $name = $product["name"];
    $image = $product["image"];
    $link = $product["link"];
    $html = "<li>
        <a href='$link'>
            <div class='kcw-advertising-merch-card-wrapper'>
                <div class='kcw-advertising-merch-card-image'>
                    <img src='$image' draggable='false'>
                </div>
                <p class='kcw-advertising-merch-card-title'>$name</p>
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
            <center>
                <ul>
                    $product_html
                </ul>
                <a class='kcw-advertising-merch-heading-link' href='https://merch.kustomcoachwerks.com/'>
                    <h1 class='kcw-advertising-merch-heading fusion-button-text'>
                        Check out our merch store
                        <i class='fa fa-shopping-cart' aria-hidden='true'></i>
                    </h1>
                </a>
            </center>
        </div>
        <div class='kcw-advertising-merch-subline-wrapper'>
            <div class='kcw-advertising-merch-subline'>
                <center><em>Our merch is printed & shipped by Teespring. <a href='https://www.spri.ng/#howitworks'>Learn more</a></em></center>
            </div>
        </div>
    </div>";
}

function kcw_advertising_js_data($data) {
    $data = json_encode($data);
    return `<script>var kcw_advertising_data = $data</script>`;
}

function kcw_advertising_merch_init($args) {
    $count = $args["count"];
    //Enqueue style and script
    kcw_advertising_enqueue_dependencies();

    //Merch product set
    $products = kcw_advertising_merch_create_product_set($count);
    //Merch advertisment html
    $html = kcw_advertising_merch_banner_html($products);

    return $html;
}

add_shortcode("kcw-advertising-merch", 'kcw_advertising_merch_init');
