<?php 
require_once(__DIR__ . "/../settings/db_class.php");

class InvoiceModel extends db_connection {
    public function create_invoice_products($products) {
        $conn = null;
        try {
            $conn = $this->db_conn();
            $conn->autocommit(FALSE);
            
            $sql = "INSERT INTO invoice_product (
                product_id, invoice_product_price, invoice_product_quantity, 
                invoice_product_description, invoice_product_name, status_id,
                invoice_product_subtotal
            ) VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare invoice products statement: " . $conn->error);
            }

            $inserted_products = [];
            foreach ($products as $product) {
                $subtotal = ($product['price'] * $product['quantity']) * 
                           (1 - ($product['discount'] / 100));
                
                $status_id = 2; // UNPAID
                
                $stmt->bind_param("iddssdd",
                    $product['product']['product_id'],
                    $product['price'],
                    $product['quantity'],
                    $product['product']['product_description'],
                    $product['product']['product_name'],
                    $status_id,
                    $subtotal
                );
                
                if (!$stmt->execute()) {
                    throw new Exception("Failed to create invoice product: " . $stmt->error);
                }

                $inserted_products[] = [
                    'id' => $conn->insert_id,
                    'subtotal' => $subtotal
                ];
            }
            
            $stmt->close();
            $conn->commit();
            return $inserted_products;
            
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

    public function create_invoice($customer_id, $facility_id, $user_id, $invoice_number, 
                                 $start_date, $due_date, $discount, $transaction_fee, 
                                 $total) {
        $conn = null;                             
        try {
            $conn = $this->db_conn();
            $conn->autocommit(FALSE);
            
            $sql = "INSERT INTO invoice (
                customer_id, facility_id, user_id, status_id, invoice_number,
                invoice_date_start, invoice_date_due, invoice_discount, 
                invoice_vat, invoice_total
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare invoice statement: " . $conn->error);
            }
            
            $status_id = 2; // UNPAID
            
            $stmt->bind_param("iiiisssddd",
                $customer_id,
                $facility_id,
                $user_id,
                $status_id,
                $invoice_number,
                $start_date,
                $due_date,
                $discount,
                $transaction_fee,
                $total
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to create invoice: " . $stmt->error);
            }
            
            $invoice_id = $conn->insert_id;
            $stmt->close();
            
            $conn->commit();
            return $invoice_id;
            
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
}
?>