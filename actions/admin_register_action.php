<?php
require_once('../controllers/admin_controller.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $adminController = new AdminController();
    
    try {
        // Get and sanitize POST data
        $admin_data = [
            'admin_firstname' => htmlspecialchars($_POST['admin_firstname'] ?? ''),
            'admin_lastname' => htmlspecialchars($_POST['admin_lastname'] ?? ''),
            'admin_email' => htmlspecialchars($_POST['admin_email'] ?? ''),
            'admin_password' => $_POST['admin_password'] ?? '',
            'admin_country' => htmlspecialchars($_POST['admin_country'] ?? ''),
            'admin_city' => htmlspecialchars($_POST['admin_city'] ?? ''),
            'admin_phonenumber' => htmlspecialchars($_POST['admin_phonenumber'] ?? ''),
            'admin_address' => htmlspecialchars($_POST['admin_address'] ?? '')
        ];

        // Register admin
        if ($adminController->register_admin_ctr($admin_data)) {
            echo json_encode([
                'success' => true,
                'message' => 'Admin registered successfully',
                'redirect' => '../login/login.php'
            ]);
        } else {
            throw new Exception("Failed to register admin");
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}
?>