<?php
require_once("../controllers/admin_controller.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $adminController = new AdminController();
    $result = $adminController->delete_user_ctr($_POST['user_id']);
    echo json_encode($result);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'User ID is required'
    ]);
}