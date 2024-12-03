<?php
require_once("../controllers/admin_controller.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    try {
        $adminController = new AdminController();
        $customer = $adminController->get_customer_by_id_ctr($_GET['id']);
        
        if ($customer) {
            echo json_encode([
                'success' => true,
                'data' => $customer
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Customer not found'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}