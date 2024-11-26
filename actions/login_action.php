<?php
session_start();
require_once("../controllers/user_controller.php");
require_once("../controllers/customer_controller.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['user_email'] ?? '';
    $password = $_POST['user_password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Email and password are required"]);
        exit;
    }

    try {
        $userController = new UserController();
        $customerController = new CustomerController();

        // Check if it's a hospital user
        if ($userController->check_email_exists($email)) {
            $user = $userController->login_user_ctr($email, $password);
            if ($user) {
                // Fetch facility_id for this user
                $facility = $userController->get_facility_by_user_id($user['user_id']);
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_firstname'] = $user['user_firstname'];
                $_SESSION['user_lastname'] = $user['user_lastname'];
                $_SESSION['facility_name'] = $user['facility_name'];
                $_SESSION['facility_id'] = $facility['facility_id'] ?? null;
                $_SESSION['user_type'] = 'user';
                echo json_encode(["success" => true, "message" => "Login successful", "redirect" => "../view/hospital_view/dashboard.php"]);
                exit;
            }
        }
        
        // Check if it's a customer
        if ($customerController->check_email_exists($email)) {
            // Map to customer field names
            $_POST['customer_email'] = $email;
            $_POST['customer_password'] = $password;
            
            $customer = $customerController->login_customer_ctr($email, $password);
            if ($customer) {
                $_SESSION['customer_id'] = $customer['customer_id'];
                $_SESSION['customer_firstname'] = $customer['customer_firstname'];
                $_SESSION['customer_lastname'] = $customer['customer_lastname'];
                $_SESSION['customer_email'] = $customer['customer_email'];
                $_SESSION['user_type'] = 'customer';
                echo json_encode(["success" => true, "message" => "Login successful", "redirect" => "../view/patient_view/patient_dashboard.php"]);
                exit;
            }
        }
        
        echo json_encode(["success" => false, "message" => "Invalid email or password"]);
        
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}
?>