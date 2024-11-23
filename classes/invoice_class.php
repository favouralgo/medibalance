<?php 
require_once(__DIR__ . "/../settings/db_class.php");

class InvoiceModel extends db_connection {
    public function create_invoice_with_products($customer_id, $facility_id, $user_id, 
    $invoice_number, $start_date, $due_date, $products) {
    $conn = null;
    try {
        $conn = $this->db_conn();
        $conn->autocommit(FALSE);
        
        // Calculate totals
        $subtotal = 0;
        $total_discount = 0;
        
        foreach ($products as $product) {
            $item_subtotal = $product['price'] * $product['quantity'];
            $item_discount = $item_subtotal * ($product['discount'] / 100);
            $subtotal += $item_subtotal;
            $total_discount += $item_discount;
        }
        
        $transaction_fee = ($subtotal - $total_discount) * 0.01;
        $total = $subtotal - $total_discount + $transaction_fee;
        
        // Create invoice first
        $status_id = 2; // UNPAID
        
        $sql = "INSERT INTO invoice (
            status_id, invoice_date_start, invoice_date_due, invoice_number,
            invoice_discount, invoice_vat, invoice_total, user_id,
            customer_id, facility_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare invoice statement: " . $conn->error);
        }
        
        $stmt->bind_param("isssdddiii",
            $status_id,
            $start_date,
            $due_date,
            $invoice_number,
            $total_discount,
            $transaction_fee,
            $total,
            $user_id,
            $customer_id,
            $facility_id
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to create invoice: " . $stmt->error);
        }
        
        $invoice_id = $conn->insert_id;
        $stmt->close();
        
        // Create invoice_product entries
        $sql = "INSERT INTO invoice_product (
            invoiceproduct_price, invoiceproduct_quantity, 
            invoiceproduct_description, invoiceproduct_name,
            status_id, invoiceproduct_subtotal, product_id, invoice_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Failed to prepare invoice_product statement: " . $conn->error);
        }

        // Prepare customer_products insert statement
        $customer_products_sql = "INSERT IGNORE INTO customer_products (customer_id, product_id) VALUES (?, ?)";
        $customer_products_stmt = $conn->prepare($customer_products_sql);
        if (!$customer_products_stmt) {
            throw new Exception("Failed to prepare customer_products statement: " . $conn->error);
        }

        foreach ($products as $product) {
            $subtotal = ($product['price'] * $product['quantity']) * 
                       (1 - ($product['discount'] / 100));
            
            // Insert invoice_product
            $stmt->bind_param("ddssdiii",
                $product['price'],
                $product['quantity'],
                $product['product']['product_description'],
                $product['product']['product_name'],
                $status_id,
                $subtotal,
                $product['product']['product_id'],
                $invoice_id
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to create invoice_product: " . $stmt->error);
            }
            
            // Insert into customer_products
            $customer_products_stmt->bind_param("ii", 
                $customer_id, 
                $product['product']['product_id']
            );
            
            if (!$customer_products_stmt->execute()) {
                throw new Exception("Failed to create customer_products entry: " . $customer_products_stmt->error);
            }
        }
        
        $stmt->close();
        $customer_products_stmt->close();
        $conn->commit();
        
        return [
            'success' => true,
            'invoice_id' => $invoice_id
        ];
        
    } catch (Exception $e) {
        if ($conn) {
            $conn->rollback();
        }
        throw new Exception("Database error: " . $e->getMessage());
    } finally {
        if ($conn) {
            $conn->autocommit(TRUE);
        }
    }
}

    public function get_all_invoices($search = '', $limit = 10) {
        try {
            $conn = $this->db_conn();
            
            // Base query
            $sql = "SELECT i.*, 
                          CONCAT(c.customer_firstname, ' ', c.customer_lastname) as customer_name,
                          s.status_name
                   FROM invoice i
                   LEFT JOIN customer c ON i.customer_id = c.customer_id
                   LEFT JOIN status s ON i.status_id = s.status_id";

            // Add search conditions if search term is provided
            if (!empty($search)) {
                $sql .= " WHERE (i.invoice_number LIKE ? 
                        OR CONCAT(c.customer_firstname, ' ', c.customer_lastname) LIKE ?
                        OR s.status_name LIKE ?)";
            }
            
            $sql .= " ORDER BY i.invoice_date_start DESC LIMIT ?";
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
            
            // Bind parameters based on whether search is provided
            if (!empty($search)) {
                $search = "%$search%";
                $stmt->bind_param("sssi", $search, $search, $search, $limit);
            } else {
                $stmt->bind_param("i", $limit);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute query: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            $invoices = [];
            
            while ($row = $result->fetch_assoc()) {
                $invoices[] = $row;
            }
            
            return $invoices;
            
        } catch (Exception $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    public function get_customer_invoices($customer_id, $search = '', $limit = 10) {
        try {
            $conn = $this->db_conn();
            
            $sql = "SELECT i.*, 
                          CONCAT(c.customer_firstname, ' ', c.customer_lastname) as customer_name,
                          s.status_name
                   FROM invoice i
                   LEFT JOIN customer c ON i.customer_id = c.customer_id
                   LEFT JOIN status s ON i.status_id = s.status_id
                   WHERE i.customer_id = ?";

            if (!empty($search)) {
                $sql .= " AND (i.invoice_number LIKE ? 
                        OR s.status_name LIKE ?)";
            }
            
            $sql .= " ORDER BY i.invoice_date_start DESC LIMIT ?";
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
            
            if (!empty($search)) {
                $search = "%$search%";
                $stmt->bind_param("issi", $customer_id, $search, $search, $limit);
            } else {
                $stmt->bind_param("ii", $customer_id, $limit);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute query: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            $invoices = [];
            
            while ($row = $result->fetch_assoc()) {
                $invoices[] = $row;
            }
            
            return $invoices;
            
        } catch (Exception $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }


    public function get_invoice_details($invoice_id) {
        try {
            $conn = $this->db_conn();
            
            // Get invoice details
            $sql = "SELECT i.*, s.status_name, f.facility_name
                    FROM invoice i
                    LEFT JOIN status s ON i.status_id = s.status_id
                    LEFT JOIN facility f ON i.facility_id = f.facility_id
                    WHERE i.invoice_id = ?";
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
            
            $stmt->bind_param("i", $invoice_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute query: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            $invoice = $result->fetch_assoc();
            
            if (!$invoice) {
                throw new Exception("Invoice not found");
            }
    
            // Get invoice products
            $sql = "SELECT ip.*
                    FROM invoice_product ip
                    WHERE ip.invoice_id = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $invoice_id);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $invoice['products'] = [];
            
            while ($product = $result->fetch_assoc()) {
                $invoice['products'][] = $product;
            }
    
            // Calculate subtotal if not present
            if (!isset($invoice['invoice_subtotal'])) {
                $invoice['invoice_subtotal'] = 0;
                foreach ($invoice['products'] as $product) {
                    $invoice['invoice_subtotal'] += $product['invoiceproduct_subtotal'];
                }
            }
            
            return $invoice;
            
        } catch (Exception $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}
?>