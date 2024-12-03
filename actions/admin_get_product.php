<?php
require_once("../controllers/admin_controller.php");

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $adminController = new AdminController();
    $result = $adminController->get_product_ctr($_GET['id']);
    echo json_encode($result);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Product ID is required'
    ]);
}