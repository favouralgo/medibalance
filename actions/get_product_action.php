<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../controllers/product_controller.php');

try {
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['product_id'])) {
        $product_id = filter_var($_GET['product_id'], FILTER_VALIDATE_INT);
        
        if ($product_id === false) {
            throw new Exception('Invalid product ID');
        }

        $productController = new ProductController();
        $product = $productController->get_one_product_ctr($product_id);

        if ($product) {
            echo json_encode([
                'success' => true,
                'product' => $product
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Product not found'
            ]);
        }
    } else {
        throw new Exception('Invalid request method or missing product ID');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}