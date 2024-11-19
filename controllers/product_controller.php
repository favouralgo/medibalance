<?php
require_once(__DIR__ ."/../classes/product_class.php");

class ProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function add_product_ctr($product_name, $product_description, $product_price, $product_quantity) {
        return $this->productModel->add_product($product_name, $product_description, $product_price, $product_quantity);
    }

    public function get_all_products_ctr() {
        return $this->productModel->get_all_products();
    }

    public function get_one_product_ctr($product_id) {
        return $this->productModel->get_one_product($product_id);
    }

    public function update_product_ctr($product_id, $product_name, $product_description, $product_price, $product_quantity) {
        return $this->productModel->update_product($product_id, $product_name, $product_description, $product_price, $product_quantity);
    }

    public function delete_product_ctr($product_id) {
        return $this->productModel->delete_product($product_id);
    }

    public function decrement_product_quantity_ctr($product_id, $quantity) {
        return $this->productModel->decrement_product_quantity($product_id, $quantity);
    }
}
?>