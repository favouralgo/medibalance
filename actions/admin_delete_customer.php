<?php
require_once("../controllers/admin_controller.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customer_id'])) {
    try {
        $adminController = new AdminController();
        $result = $adminController->delete_customer_ctr($_POST['customer_id']);
        echo json_encode($result);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Customer ID is required'
    ]);
}