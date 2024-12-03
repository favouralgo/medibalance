<?php
require_once("../controllers/admin_controller.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $adminController = new AdminController();
    $result = $adminController->delete_product_ctr($_POST['product_id']);
    echo json_encode($result);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Product ID is required'
    ]);
}