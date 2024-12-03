<?php
require_once("../controllers/admin_controller.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'], $_POST['product_name'], $_POST['product_description'], 
              $_POST['product_price'], $_POST['product_quantity'])) {
        
        $adminController = new AdminController();
        $result = $adminController->update_product_ctr(
            $_POST['product_id'],
            $_POST['product_name'],
            $_POST['product_description'],
            $_POST['product_price'],
            $_POST['product_quantity']
        );
        
        echo json_encode($result);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Missing required fields'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}