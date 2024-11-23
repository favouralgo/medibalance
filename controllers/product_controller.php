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

    public function get_all_products_ctr($search = '', $entries = 10) {
        try {
            // Safety check for authentication
            if (!isset($_SESSION['user_id']) && !isset($_SESSION['customer_id'])) {
                return [
                    'success' => false,
                    'message' => 'Authentication required',
                    'data' => []
                ];
            }
            
            $entries = in_array((int)$entries, [10, 25, 50, 100]) ? (int)$entries : 10;
            $user_id = $_SESSION['user_id'] ?? null;
            
            $products = $this->productModel->get_all_products($search, $entries, $user_id);
            
            return [
                'success' => true,
                'data' => $products
            ];
        } catch (Exception $e) {
            error_log("Error in get_all_products_ctr: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    public function get_all_products_for_dropdown_ctr() {
        try {
            // Safety check for authentication
            if (!isset($_SESSION['user_id']) && !isset($_SESSION['customer_id'])) {
                return [];
            }
            
            return $this->productModel->get_all_products_for_dropdown();
        } catch (Exception $e) {
            error_log("Error in get_all_products_for_dropdown_ctr: " . $e->getMessage());
            return [];
        }
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

    public function get_customer_products_ctr($customer_id, $search = '', $entries = 10) {
        try {
            // Ensure entries is valid
            $entries = in_array((int)$entries, [10, 25, 50, 100]) ? (int)$entries : 10;
            
            // Get products from model
            $products = $this->productModel->get_customer_products($customer_id, $search, $entries);
            
            return [
                'success' => true,
                'data' => $products
            ];
        } catch (Exception $e) {
            error_log("Error in get_customer_products_ctr: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    
}
?>