<?php
require_once(__DIR__ . '/../controllers/customer_controller.php');

header('Content-Type: application/json');

$customerController = new CustomerController();
$customers = $customerController->get_all_customers_ctr();

if ($customers) {
    echo json_encode(['success' => true, 'customers' => $customers]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch customers']);
}
?>