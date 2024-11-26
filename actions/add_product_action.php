<?php
session_start();
require_once('../controllers/product_controller.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_msg'] = "You must be logged in to add products";
    header("Location: ../view/hospital_view/add_product.php");
    exit();
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data and sanitize inputs
        $product_name = htmlspecialchars(trim($_POST['product_name']));
        $product_description = htmlspecialchars(trim($_POST['product_description']));
        $product_price = floatval($_POST['product_price']);
        $product_quantity = intval($_POST['product_quantity']);
        $user_id = $_SESSION['user_id']; // Get user_id from session

        // Validate inputs
        $errors = array();

        // Validate product name
        if (empty($product_name)) {
            $errors[] = "Product name is required";
        }

        // Validate price
        if ($product_price <= 0) {
            $errors[] = "Price must be greater than zero";
        }

        // Validate quantity
        if ($product_quantity < 0) {
            $errors[] = "Quantity cannot be negative";
        }

        // Validate description
        if (empty($product_description)) {
            $errors[] = "Product description is required";
        }

        // If no errors, proceed with adding the product
        if (empty($errors)) {
            // Create instance of ProductController
            $productController = new ProductController();
            
            // Call the add product method with user_id
            $result = $productController->add_product_ctr(
                $product_name, 
                $product_description, 
                $product_price, 
                $product_quantity,
                $user_id
            );

            if ($result) {
                // Success - Redirect with success message
                $_SESSION['success_msg'] = "Product added successfully!";
                header("Location: ../view/hospital_view/add_product.php");
                exit();
            } else {
                throw new Exception("Failed to add product");
            }
        } else {
            // If there are validation errors, store them in session and redirect back
            $_SESSION['error_msg'] = implode("<br>", $errors);
            header("Location: ../view/hospital_view/add_product.php");
            exit();
        }
    } catch (Exception $e) {
        // Error - Redirect with error message
        $_SESSION['error_msg'] = "Failed to add product: " . $e->getMessage();
        header("Location: ../view/hospital_view/add_product.php");
        exit();
    }
} else {
    // If someone tries to access this file directly without POST data
    $_SESSION['error_msg'] = "Invalid request method";
    header("Location: ../view/hospital_view/add_product.php");
    exit();
}
?>