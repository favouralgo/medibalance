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

    public function get_user_statistics($user_id) {
        try {
            $conn = $this->db_conn();
            
            $sql = "SELECT 
                    COUNT(*) as total_invoices,
                    SUM(invoice_total) as total_revenue,
                    (SELECT COUNT(*) FROM invoice 
                     WHERE user_id = ? AND status_id = 2) as pending_bills,
                    (SELECT SUM(invoice_total) FROM invoice 
                     WHERE user_id = ? AND status_id = 2) as due_amount,
                    (SELECT COUNT(*) FROM product 
                     WHERE user_id = ?) as total_products,
                    (SELECT COUNT(DISTINCT customer_id) FROM invoice 
                     WHERE user_id = ?) as total_customers,
                    (SELECT COUNT(*) FROM invoice 
                     WHERE user_id = ? AND status_id = 1) as paid_bills
                    FROM invoice 
                    WHERE user_id = ?";
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
            
            $stmt->bind_param("iiiiii", 
                $user_id, $user_id, $user_id, $user_id, $user_id, $user_id
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute query: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            return $result->fetch_assoc();
            
        } catch (Exception $e) {
            error_log("Failed to get user statistics: " . $e->getMessage());
            throw new Exception("Failed to get statistics");
        }
    }
    
    public function get_recent_invoices($user_id, $limit = 4) {
        try {
            $conn = $this->db_conn();
            
            $sql = "SELECT i.*, 
                           CONCAT(c.customer_firstname, ' ', c.customer_lastname) as customer_name,
                           s.status_name
                    FROM invoice i
                    LEFT JOIN customer c ON i.customer_id = c.customer_id
                    LEFT JOIN status s ON i.status_id = s.status_id
                    WHERE i.user_id = ?
                    ORDER BY i.created_at DESC
                    LIMIT ?";
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
            
            $stmt->bind_param("ii", $user_id, $limit);
            
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
            error_log("Failed to get recent invoices: " . $e->getMessage());
            throw new Exception("Failed to fetch recent invoices");
        }
    }

    public function get_recent_activities($user_id, $limit_per_type = 3) {
        try {
            $conn = $this->db_conn();
            
            // Get latest 3 invoice creations and 3 payments separately using UNION ALL
            $sql = "(SELECT 
                    'invoice_created' as activity_type,
                    i.invoice_number,
                    CONCAT(c.customer_firstname, ' ', c.customer_lastname) as customer_name,
                    i.created_at as activity_time,
                    i.invoice_total as amount
                    FROM invoice i
                    JOIN customer c ON i.customer_id = c.customer_id
                    WHERE i.user_id = ?
                    ORDER BY i.created_at DESC
                    LIMIT ?)
                    
                    UNION ALL
                    
                    (SELECT 
                    'payment_received' as activity_type,
                    i.invoice_number,
                    CONCAT(c.customer_firstname, ' ', c.customer_lastname) as customer_name,
                    i.updated_at as activity_time,
                    i.invoice_total as amount
                    FROM invoice i
                    JOIN customer c ON i.customer_id = c.customer_id
                    WHERE i.user_id = ? AND i.status_id = 1
                    ORDER BY i.updated_at DESC
                    LIMIT ?)
                    
                    ORDER BY activity_time DESC";
            
            $stmt = $conn->prepare($sql);
            // Bind both user_id and limit twice (once for each subquery)
            $stmt->bind_param("iiii", $user_id, $limit_per_type, $user_id, $limit_per_type);
            $stmt->execute();
            
            $result = $stmt->get_result();
            $activities = [];
            
            while ($row = $result->fetch_assoc()) {
                $activities[] = $row;
            }
            
            return $activities;
            
        } catch (Exception $e) {
            error_log("Failed to get recent activities: " . $e->getMessage());
            throw new Exception("Failed to fetch recent activities");
        }
    }
    
    public function get_monthly_statistics($user_id) {
        try {
            $conn = $this->db_conn();
            
            // Get current month's statistics
            $sql = "SELECT 
                    (SELECT COUNT(*) 
                     FROM invoice 
                     WHERE user_id = ? 
                     AND MONTH(created_at) = MONTH(CURRENT_DATE())
                     AND YEAR(created_at) = YEAR(CURRENT_DATE())) as total_invoices,
                    
                    (SELECT COUNT(*) 
                     FROM invoice 
                     WHERE user_id = ? 
                     AND status_id = 1
                     AND MONTH(created_at) = MONTH(CURRENT_DATE())
                     AND YEAR(created_at) = YEAR(CURRENT_DATE())) as paid_invoices,
                    
                    (SELECT SUM(invoice_total) 
                     FROM invoice 
                     WHERE user_id = ?
                     AND MONTH(created_at) = MONTH(CURRENT_DATE())
                     AND YEAR(created_at) = YEAR(CURRENT_DATE())) as total_sales";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $user_id, $user_id, $user_id);
            $stmt->execute();
            
            $result = $stmt->get_result()->fetch_assoc();
            
            // Calculate percentages
            $total_invoices = $result['total_invoices'] ?? 0;
            $paid_invoices = $result['paid_invoices'] ?? 0;
            $total_sales = $result['total_sales'] ?? 0;
            
            // Previous month's total sales for comparison
            $sql = "SELECT SUM(invoice_total) as prev_month_sales
                    FROM invoice 
                    WHERE user_id = ?
                    AND MONTH(created_at) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)
                    AND YEAR(created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            
            $prev_month = $stmt->get_result()->fetch_assoc();
            $prev_month_sales = $prev_month['prev_month_sales'] ?? 0;
            
            return [
                'sales_percentage' => $prev_month_sales > 0 ? 
                    min(100, round(($total_sales / $prev_month_sales) * 100)) : 0,
                'paid_percentage' => $total_invoices > 0 ? 
                    round(($paid_invoices / $total_invoices) * 100) : 0
            ];
            
        } catch (Exception $e) {
            error_log("Failed to get monthly statistics: " . $e->getMessage());
            throw new Exception("Failed to fetch monthly statistics");
        }
    }

    public function update_invoice_status($invoice_id, $status_id) {
        try {
            $conn = $this->db_conn();
            
            $sql = "UPDATE invoice SET status_id = ? WHERE invoice_id = ?";
            $stmt = $conn->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
            
            $stmt->bind_param("ii", $status_id, $invoice_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to update invoice: " . $stmt->error);
            }
            
            return $stmt->affected_rows > 0;
            
        } catch (Exception $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}
?>