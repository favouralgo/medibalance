<?php
require_once(__DIR__ . "/../controllers/invoice_controller.php");

// Set proper headers
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Get invoice ID
$invoice_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$invoice_id) {
    echo json_encode(['success' => false, 'message' => 'Invalid invoice ID']);
    exit;
}

try {
    $invoiceController = new InvoiceController();
    $result = $invoiceController->get_invoice_details_ctr($invoice_id);
    
    // Add security check to ensure the invoice belongs to the logged-in user
    if ($result['success'] && $result['data']['user_id'] != $_SESSION['user_id']) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
        exit;
    }
    
    echo json_encode($result);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>