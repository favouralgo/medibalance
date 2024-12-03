<?php
session_start();
require_once("../controllers/user_controller.php");
require_once("../controllers/customer_controller.php");
require_once("../controllers/admin_controller.php");

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['user_email'] ?? '';
    $password = $_POST['user_password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Email and password are required"]);
        exit;
    }

    try {
        $adminController = new AdminController();
        $userController = new UserController();
        $customerController = new CustomerController();
       

        if ($adminController->check_email_exists($email)) {
            // Map to admin field names
            // $_POST['admin_email'] = $email;
            // $_POST['admin_password'] = $password;
            try {
                $admin = $adminController->login_admin_ctr($email, $password);
                if ($admin) {
                    $_SESSION['admin_id'] = $admin['admin_id'];
                    $_SESSION['admin_firstname'] = $admin['admin_firstname'];
                    $_SESSION['admin_lastname'] = $admin['admin_lastname'];
                    $_SESSION['admin_email'] = $admin['admin_email'];
                    $_SESSION['user_type'] = 'admin';
                    echo json_encode([
                        "success" => true, 
                        "message" => "Login successful", 
                        "redirect" => "../view/admin/admindashboard.php",
                        "debug" => "Admin login successful"
                    ]);
                    exit;
                } else {
                    echo json_encode([
                        "success" => false, 
                        "message" => "Invalid admin credentials",
                        "debug" => "Admin login failed - invalid credentials"
                    ]);
                    exit;
                }
            } catch (Exception $e) {
                error_log("Admin login error: " . $e->getMessage());
                echo json_encode([
                    "success" => false, 
                    "message" => "Admin login error",
                    "debug" => $e->getMessage()
                ]);
                exit;
            }
        }

        // Check if it's a hospital user
        if ($userController->check_email_exists($email)) {
            $_POST['user_email'] = $email;
            $_POST['user_password'] = $password;
            
            $user = $userController->login_user_ctr($email, $password);
            if ($user) {
                // Check if user is approved
                if ($user['is_approved'] !== 'APPROVED') {
                    echo json_encode([
                        "success" => false, 
                        "message" => "Your account is pending approval. Please wait for admin confirmation."
                    ]);
                    exit;
                }
                
                // Fetch facility_id for this user
                $facility = $userController->get_facility_by_user_id($user['user_id']);
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_firstname'] = $user['user_firstname'];
                $_SESSION['user_lastname'] = $user['user_lastname'];
                $_SESSION['facility_name'] = $user['facility_name'];
                $_SESSION['facility_id'] = $facility['facility_id'] ?? null;
                $_SESSION['user_type'] = 'user';
                echo json_encode([
                    "success" => true, 
                    "message" => "Login successful", 
                    "redirect" => "../view/hospital_view/dashboard.php"
                ]);
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
                echo json_encode([
                    "success" => true, 
                    "message" => "Login successful", 
                    "redirect" => "../view/patient_view/patient_dashboard.php"
                ]);
                exit;
            }
        }
        
        echo json_encode([
            "success" => false, 
            "message" => "Invalid email or password",
            "debug" => "No matching user found"
        ]);
        
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        echo json_encode([
            "success" => false, 
            "message" => "An error occurred during login",
            "debug" => $e->getMessage()
        ]);
    }
    exit; 
}
?>