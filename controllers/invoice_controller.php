<?php
require_once(__DIR__ . "/../classes/invoice_class.php");

class InvoiceController {
    private $invoiceModel;

    public function __construct() {
        $this->invoiceModel = new InvoiceModel();
    }

    public function create_invoice_ctr($customer_id, $facility_id, $user_id, $invoice_number, 
                                     $start_date, $due_date, $products) {
        try {
            if (empty($customer_id) || empty($facility_id) || empty($user_id) || 
                empty($invoice_number) || empty($start_date) || empty($due_date) || 
                empty($products)) {
                throw new Exception("Missing required fields");
            }

            $inserted_products = $this->invoiceModel->create_invoice_products($products);
            
            if (empty($inserted_products)) {
                throw new Exception("Failed to create invoice products");
            }

            // Calculate totals
            $subtotal = array_sum(array_column($inserted_products, 'subtotal'));
            $discount = isset($products[0]['discount']) ? $products[0]['discount'] : 0;
            $transaction_fee = $subtotal * 0.01; // 1% transaction fee
            $total = $subtotal - ($subtotal * ($discount / 100)) + $transaction_fee;

            $invoice_id = $this->invoiceModel->create_invoice(
                $customer_id,
                $facility_id,
                $user_id,
                $invoice_number,
                $start_date,
                $due_date,
                $discount,
                $transaction_fee,
                $total
            );

            if (empty($invoice_id)) {
                throw new Exception("Failed to create invoice record");
            }

            return [
                'success' => true,
                'invoice_id' => $invoice_id,
                'message' => 'Invoice created successfully'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
?>