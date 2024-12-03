<?php
require_once("../controllers/admin_controller.php");

header('Content-Type: application/json');

try {
    if (!isset($_GET['id'])) {
        throw new Exception("Invoice ID is required");
    }

    $adminController = new AdminController();
    $invoice = $adminController->get_invoice_details_ctr($_GET['id']);
    
    if (!$invoice) {
        throw new Exception("Invoice not found");
    }
    
    echo json_encode([
        'success' => true,
        'data' => $invoice
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}