<?php
require_once("../../controllers/invoice_controller.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verify customer is logged in
if (!isset($_SESSION['customer_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$reference = $_POST['reference'] ?? '';
$invoice_id = (int)($_POST['invoice_id'] ?? 0);

if (!$reference || !$invoice_id) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
    exit();
}

// Verify payment with Paystack
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer sk_test_645173d7c168770afa6a5841a8bb46a67ba9be7a",
        "Cache-Control: no-cache",
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    echo json_encode(['status' => 'error', 'message' => 'Verification failed']);
    exit();
}

$result = json_decode($response, true);

if (!$result['status'] || $result['data']['status'] !== 'success') {
    echo json_encode(['status' => 'error', 'message' => 'Payment failed']);
    exit();
}

// Verify payment amount matches invoice amount
$invoiceController = new InvoiceController();
$invoice = $invoiceController->get_invoice_details_ctr($invoice_id);

if (!$invoice || $invoice['customer_id'] != $_SESSION['customer_id']) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid invoice']);
    exit();
}

$paid_amount = $result['data']['amount'] / 100; // Convert from kobo to GHS
if ($paid_amount < $invoice['invoice_total']) {
    echo json_encode(['status' => 'error', 'message' => 'Partial payment not allowed']);
    exit();
}

// Update invoice status to PAID
try {
    $update_result = $invoiceController->update_invoice_status_ctr($invoice_id, 1); // 1 = PAID status
    if ($update_result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update invoice status']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'System error']);
}