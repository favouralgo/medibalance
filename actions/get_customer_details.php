<?php
require_once(__DIR__ . '/../controllers/customer_controller.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];
    $customerController = new CustomerController();
    $customer = $customerController->get_one_customer_ctr($customer_id);

    if ($customer) {
        echo json_encode(['success' => true, 'customer' => $customer]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to fetch customer details']);
    }
}
?>