<?php
require_once('../../controllers/customer_controller.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json');
    
    try {
        $customerController = new CustomerController();
        
        $customer_id = $_POST['customer_id'];
        $customer_firstname = $_POST['customer_firstname'];
        $customer_lastname = $_POST['customer_lastname'];
        $customer_email = $_POST['customer_email'];
        $customer_phonenumber = $_POST['customer_phonenumber'];
        $customer_address = $_POST['customer_address'];
        $customer_city = $_POST['customer_city'];
        $customer_country = $_POST['customer_country'];

        $result = $customerController->update_customer_ctr(
            $customer_id,
            $customer_firstname,
            $customer_lastname,
            $customer_email,
            $customer_phonenumber,
            $customer_address,
            $customer_city,
            $customer_country
        );

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Customer updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update customer']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>