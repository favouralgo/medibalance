<?php
require_once(__DIR__ . '/../controllers/invoice_controller.php');

if (!isset($_GET['invoice_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invoice ID is required'
    ]);
    exit;
}

$invoiceController = new InvoiceController();
$invoice_id = $_GET['invoice_id'];

try {
    // Add a new method to your InvoiceModel class to get invoice details
    $result = $invoiceController->get_invoice_details_ctr($invoice_id);
    
    if ($result['success']) {
        echo json_encode([
            'success' => true,
            'invoice' => $result['data']
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => $result['message']
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>