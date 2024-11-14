<?php
session_start();
require_once("../controllers/customer_controller.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $response = array();
    
    $customer_email = $_POST['customer_email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $response['success'] = false;
        $response['message'] = "Passwords do not match.";
        echo json_encode($response);
        exit;
    }

    $customerController = new CustomerController();
    $result = $customerController->update_password_ctr($customer_email, $new_password);

    if ($result) {
        $response['success'] = true;
        $response['message'] = "Password updated successfully.";
    } else {
        $response['success'] = false;
        $response['message'] = "Failed to update password.";
    }
    
    echo json_encode($response);
    exit;
}
?>