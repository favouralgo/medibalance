<?php
require_once("../controllers/admin_controller.php");

header('Content-Type: application/json');

try {
    if (!isset($_GET['id'])) {
        throw new Exception("User ID is required");
    }

    $adminController = new AdminController();
    $user = $adminController->get_user_details_ctr($_GET['id']);
    
    if (!$user) {
        throw new Exception("User not found");
    }
    
    echo json_encode([
        'success' => true,
        'data' => $user
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}