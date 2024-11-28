<?php
// Prevent any output before headers
ob_start();

// Set error reporting for development
error_reporting(0);
ini_set('display_errors', 0);

// Check if the file exists before requiring
if (!file_exists(__DIR__ . "/../controllers/invoice_controller.php")) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'System configuration error']);
    exit;
}

require_once(__DIR__ . "/../controllers/invoice_controller.php");

// Function to send JSON response
function sendJsonResponse($success, $data = null, $message = null) {
    ob_clean();
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'data' => $data,
        'message' => $message
    ]);
    exit;
}

// Ensure we're outputting JSON
header('Content-Type: application/json');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


try {
    // Verify customer is logged in
    if (!isset($_SESSION['customer_id'])) {
        sendJsonResponse(false, null, 'Not authenticated');
    }

    // Get and validate invoice ID
    $invoice_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if (!$invoice_id) {
        sendJsonResponse(false, null, 'Invalid invoice ID');
    }

    $debug['invoice_id'] = $invoice_id;
    $debug['customer_id'] = $_SESSION['customer_id'];

    // Get invoice details
    $invoiceController = new InvoiceController();
    $result = $invoiceController->get_invoice_details_ctr($invoice_id);

    $debug['controller_result'] = $result;

    if (!$result['success']) {
        sendJsonResponse(false, null, $result['message'] ?? 'Failed to retrieve invoice');
    }

    $invoice = $result['data'];

    // Verify invoice belongs to customer
    if (!isset($invoice['customer_id']) || $invoice['customer_id'] != $_SESSION['customer_id']) {
        sendJsonResponse(false, null, 'Unauthorized access');
    }

    // Success response
    sendJsonResponse(true, $invoice);

} catch (Exception $e) {
    // Log error for debugging
    error_log("Invoice Action Error: " . $e->getMessage() . "\nDebug Info: " . print_r($debug, true));
    sendJsonResponse(false, null, 'System error: ' . $e->getMessage());
}
?>