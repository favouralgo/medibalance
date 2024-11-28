<?php
require_once(__DIR__ . '/../controllers/customer_controller.php');

header('Content-Type: application/json');

if (!isset($_GET['customer_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Customer ID is required'
    ]);
    exit;
}

try {
    $customer_id = $_GET['customer_id'];
    $customerController = new CustomerController();
    $customer = $customerController->get_one_customer_ctr($customer_id);
    
    if ($customer) {
        echo json_encode([
            'success' => true,
            'customer' => $customer
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
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
}
?>