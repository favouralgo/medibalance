<?php
require_once('../../controllers/customer_controller.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['customer_id'])) {
    header('Content-Type: application/json');
    
    try {
        $customerController = new CustomerController();
        $customer_id = $_POST['customer_id'];
        $result = $customerController->delete_customer_ctr($customer_id);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Customer deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete customer']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>