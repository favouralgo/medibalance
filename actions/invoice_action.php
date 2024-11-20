<?php
session_start();
header('Content-Type: application/json');
require_once(__DIR__. '/../controllers/invoice_controller.php');

try {
    $jsonData = file_get_contents('php://input');
    if (empty($jsonData)) {
        throw new Exception('No data received');
    }

    $data = json_decode($jsonData, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON data: ' . json_last_error_msg());
    }

    $required_fields = ['customer_id', 'invoice_number', 'start_date', 
                       'due_date', 'services'];
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            throw new Exception("Missing required field: {$field}");
        }
    }

    $facility_id = $_SESSION['facility_id'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null;
    
    if (empty($facility_id) || empty($user_id)) {
        throw new Exception('Session data missing (facility_id or user_id)');
    }

    $products = array_map(function($service) {
        return [
            'product' => $service['product'],
            'quantity' => $service['quantity'],
            'price' => $service['price'],
            'discount' => $service['discount']
        ];
    }, $data['services']);

    $invoiceController = new InvoiceController();
    $result = $invoiceController->create_invoice_ctr(
        $data['customer_id'],
        $facility_id,
        $user_id,
        $data['invoice_number'],
        $data['start_date'],
        $data['due_date'],
        $products
    );

    if ($result['success']) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Invoice created successfully',
            'invoice_id' => $result['invoice_id']
        ]);
    } else {
        throw new Exception($result['message']);
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>