<?php
header('Content-Type: application/json');
require_once(__DIR__ . '/../controllers/product_controller.php');

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $product_id = filter_var($_POST['product_id'], FILTER_VALIDATE_INT);
        
        if (!$product_id) {
            throw new Exception('Invalid product ID');
        }

        $productController = new ProductController();
        $result = $productController->delete_product_ctr($product_id);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } else {
            throw new Exception('Failed to delete product');
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