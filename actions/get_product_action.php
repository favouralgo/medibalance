<?php
require_once('../../controllers/product_controller.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $product = get_one_product_ctr($product_id);

    if ($product) {
        echo json_encode(['success' => true, 'product' => $product]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch product details']);
    }
}
?>