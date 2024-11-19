<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../controllers/product_controller.php'); 

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Validate inputs
        $product_id = filter_var($_POST['product_id'], FILTER_VALIDATE_INT);
        $product_name = trim($_POST['product_name']);
        $product_description = trim($_POST['product_description']);
        $product_price = filter_var($_POST['product_price'], FILTER_VALIDATE_FLOAT);
        $product_quantity = filter_var($_POST['product_quantity'], FILTER_VALIDATE_INT);

        if (!$product_id || !$product_name || $product_price === false || $product_quantity === false) {
            throw new Exception('Invalid input data');
        }

        $productController = new ProductController();
        $result = $productController->update_product_ctr(
            $product_id,
            $product_name,
            $product_description,
            $product_price,
            $product_quantity
        );

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Product updated successfully'
            ]);
        } else {
            throw new Exception('Failed to update product');
        }
    } else {
        throw new Exception('Invalid request method');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>