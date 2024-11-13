<?php
session_start();
require_once("../controllers/user_controller.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_email = $_POST['user_email'] ?? '';
    $user_password = $_POST['user_password'] ?? '';

    if (empty($user_email) || empty($user_password)) {
        echo json_encode(["success" => false, "message" => "Email and password are required"]);
        exit;
    }

    $userController = new UserController();

    try {
        // Check if the email exists
        if (!$userController->check_email_exists($user_email)) {
            echo json_encode(["success" => false, "message" => "Email does not exist"]);
            exit;
        }

        // Attempt to log in the user
        $user = $userController->login_user_ctr($user_email, $user_password);

        if ($user) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_firstname'] = $user['user_firstname'];
            $_SESSION['user_lastname'] = $user['user_lastname'];
            $_SESSION['facility_name'] = $user['facility_name'];
            echo json_encode(["success" => true, "message" => "Login successful"]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid email or password"]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
?>