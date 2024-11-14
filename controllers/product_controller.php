<?php
//connect to the product class
require_once(__DIR__ ."/../classes/product_class.php");

//--INSERT--//
function add_product_ctr($product_name, $product_description, $product_price, $product_quantity) {
    $product = new product_class();
    return $product->add_product($product_name, $product_description, $product_price, $product_quantity);
}

//--SELECT ALL--//
function get_all_products_ctr() {
    $product = new product_class();
    return $product->get_all_products();
}

//--SELECT ONE--//
function get_one_product_ctr($product_id) {
    $product = new product_class();
    return $product->get_one_product($product_id);
}

//--UPDATE--//
function update_product_ctr($product_id, $product_name, $product_description, $product_price, $product_quantity) {
    $product = new product_class();
    return $product->update_product($product_id, $product_name, $product_description, $product_price, $product_quantity);
}

//--DELETE--//
function delete_product_ctr($product_id) {
    $product = new product_class(); 
    return $product->delete_product($product_id);
}

?>