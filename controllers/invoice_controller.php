<?php
require_once(__DIR__ . "/../classes/invoice_class.php");

class InvoiceController {
    private $invoiceModel;

    public function __construct() {
        $this->invoiceModel = new InvoiceModel();
    }

    public function create_invoice_ctr($customer_id, $facility_id, $user_id, 
        $invoice_number, $start_date, $due_date, $services) {
        try {
            if (empty($customer_id) || empty($facility_id) || empty($user_id) || 
                empty($invoice_number) || empty($start_date) || empty($due_date) || 
                empty($services)) {
                throw new Exception("Missing required fields");
            }

            $result = $this->invoiceModel->create_invoice_with_products(
                $customer_id, 
                $facility_id, 
                $user_id,
                $invoice_number, 
                $start_date, 
                $due_date, 
                $services
            );

            return $result;
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    public function get_all_invoices_ctr($search = '', $entries = 10) {
        try {
            // Ensure entries is a valid number
            $entries = in_array((int)$entries, [10, 25, 50, 100]) ? (int)$entries : 10;
            
            return [
                'success' => true,
                'data' => $this->invoiceModel->get_all_invoices($search, $entries)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function get_customer_invoices_ctr($customer_id, $search = '', $entries = 10) {
        try {
            if (empty($customer_id)) {
                throw new Exception("Customer ID is required");
            }
            
            // Ensure entries is a valid number
            $entries = in_array((int)$entries, [10, 25, 50, 100]) ? (int)$entries : 10;
            
            return [
                'success' => true,
                'data' => $this->invoiceModel->get_customer_invoices($customer_id, $search, $entries)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function get_invoice_details_ctr($invoice_id) {
        try {
            if (empty($invoice_id)) {
                throw new Exception("Invoice ID is required");
            }
            
            return [
                'success' => true,
                'data' => $this->invoiceModel->get_invoice_details($invoice_id)
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