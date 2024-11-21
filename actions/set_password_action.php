<?php
session_start();
require_once("../controllers/customer_controller.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    if (empty($_POST['customer_email']) || empty($_POST['new_password'])) {
        throw new Exception('Missing required fields');
    }
    
    $customerController = new CustomerController();
    $result = $customerController->update_password_ctr(
        $_POST['customer_email'],
        $_POST['new_password']
    );
    
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update password']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}