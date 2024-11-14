<?php
require_once('../../controllers/customer_controller.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['customer_id'])) {
    header('Content-Type: application/json');
    
    try {
        $customerController = new CustomerController();
        $customer_id = $_GET['customer_id'];
        $customer = $customerController->get_one_customer_ctr($customer_id);

        if ($customer) {
            echo json_encode(['success' => true, 'customer' => $customer]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to fetch customer details']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>