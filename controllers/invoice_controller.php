<?php

if (!file_exists(__DIR__ . "/../classes/invoice_class.php")) {
    die(json_encode([
        'success' => false,
        'message' => 'Invoice class file not found'
    ]));
}

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

    public function get_user_statistics_ctr($user_id) {
        try {
            return $this->invoiceModel->get_user_statistics($user_id);
        } catch (Exception $e) {
            error_log("Error in get_user_statistics_ctr: " . $e->getMessage());
            return [
                'total_invoices' => 0,
                'total_revenue' => 0,
                'pending_bills' => 0,
                'due_amount' => 0,
                'total_products' => 0,
                'total_customers' => 0,
                'paid_bills' => 0
            ];
        }
    }
    
    public function get_recent_invoices_ctr($user_id, $limit = 4) {
        try {
            return $this->invoiceModel->get_recent_invoices($user_id, $limit);
        } catch (Exception $e) {
            error_log("Error in get_recent_invoices_ctr: " . $e->getMessage());
            return [];
        }
    }

    public function get_recent_activities_ctr($user_id) {
        try {
            // Get 3 of each type (total 6 activities)
            return $this->invoiceModel->get_recent_activities($user_id, 3);
        } catch (Exception $e) {
            error_log("Error in get_recent_activities_ctr: " . $e->getMessage());
            return [];
        }
    }
    
    public function get_monthly_statistics_ctr($user_id) {
        try {
            return $this->invoiceModel->get_monthly_statistics($user_id);
        } catch (Exception $e) {
            error_log("Error in get_monthly_statistics_ctr: " . $e->getMessage());
            return [
                'sales_percentage' => 0,
                'paid_percentage' => 0
            ];
        }
    }

    public function get_invoice_by_number_ctr($invoice_number) {
        try {
            if (empty($invoice_number)) {
                throw new Exception("Invoice number is required");
            }
            
            $invoice = $this->invoiceModel->get_invoice_by_number($invoice_number);
            
            if (!$invoice) {
                throw new Exception("Invoice not found");
            }
            
            return [
                'success' => true,
                'data' => $invoice
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function update_invoice_status_ctr($invoice_id, $status_id) {
        try {
            if (empty($invoice_id)) {
                throw new Exception("Invoice ID is required");
            }
            
            if (!in_array($status_id, [1, 2])) { // 1 = PAID, 2 = UNPAID
                throw new Exception("Invalid status ID");
            }
            
            return [
                'success' => true,
                'data' => $this->invoiceModel->update_invoice_status($invoice_id, $status_id)
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