<?php
// Prevent any output before headers
ob_start();

// Set headers
header('Content-Type: application/json');

try {
    require_once(__DIR__ . '/../controllers/customer_controller.php');

    // Log the incoming data
    error_log("POST Data: " . print_r($_POST, true));

    // Validate required fields
    $required_fields = ['customer_id', 'customer_firstname', 'customer_lastname', 
                       'customer_email', 'customer_phonenumber', 'customer_address', 
                       'customer_city', 'customer_country'];
    
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            throw new Exception("Missing required field: {$field}");
        }
    }

    $customerController = new CustomerController();
    
    $result = $customerController->update_customer_ctr(
        $_POST['customer_id'],
        $_POST['customer_firstname'],
        $_POST['customer_lastname'],
        $_POST['customer_email'],
        $_POST['customer_phonenumber'],
        $_POST['customer_address'],
        $_POST['customer_city'],
        $_POST['customer_country']
    );

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Customer updated successfully'
        ]);
    } else {
        throw new Exception('Failed to update customer');
    }

} catch (Exception $e) {
    // Log the error
    error_log("Update Customer Error: " . $e->getMessage());
    
    // Clear any output that might have been sent
    if (ob_get_length()) ob_clean();
    
    // Send error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>