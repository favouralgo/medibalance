<?php
require_once(__DIR__ . "/../settings/db_class.php");

class ProductModel extends db_connection {
    //--INSERT FUNCTION--//
    public function add_product($product_name, $product_description, $product_price, $product_quantity) {
        $product_name = mysqli_real_escape_string($this->db_conn(), $product_name);
        $product_description = mysqli_real_escape_string($this->db_conn(), $product_description);
        $product_price = mysqli_real_escape_string($this->db_conn(), $product_price);
        $product_quantity = mysqli_real_escape_string($this->db_conn(), $product_quantity);
        
        //--INSERT QUERY--//
        $sql = "INSERT INTO `product`(`product_name`, `product_description`, `product_price`, `product_quantity`) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db_conn()->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $this->db_conn()->error);
        }
        $stmt->bind_param("ssdi", $product_name, $product_description, $product_price, $product_quantity);
        if (!$stmt->execute()) {
            throw new Exception("Execute statement failed: " . $stmt->error);
        }
        return true;
    }

    //--SELECT ALL PRODUCTS FUNCTION--//
    public function get_all_products($search = '', $limit = 10) {
        try {
            $conn = $this->db_conn();
            
            // Base query
            $sql = "SELECT * FROM product";
            
            // Add search condition if search term exists
            if (!empty($search)) {
                $sql .= " WHERE (product_name LIKE CONCAT('%', ?, '%') 
                         OR product_description LIKE CONCAT('%', ?, '%'))";
            }
            
            // Add ordering and limit
            $sql .= " ORDER BY product_id DESC";
            if ($limit > 0) {
                $sql .= " LIMIT ?";
            }
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
            
            // Bind parameters
            if (!empty($search)) {
                if ($limit > 0) {
                    $stmt->bind_param("ssi", $search, $search, $limit);
                } else {
                    $stmt->bind_param("ss", $search, $search);
                }
            } elseif ($limit > 0) {
                $stmt->bind_param("i", $limit);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute query: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            $products = [];
            
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            
            return $products;
            
        } catch (Exception $e) {
            error_log("Database error in get_all_products: " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
        }
    }

    public function get_all_products_for_dropdown() {
        try {
            $conn = $this->db_conn();
            
            // Simple query to get all active products
            $sql = "SELECT product_id, product_name, product_price, product_description FROM product ORDER BY product_name ASC";
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute query: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            $products = [];
            
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            
            return $products;
            
        } catch (Exception $e) {
            error_log("Database error in get_all_products_for_dropdown: " . $e->getMessage());
            throw new Exception("Failed to fetch products");
        }
    }

    //--SELECT ONE FUNCTION--//
    public function get_one_product($product_id) {
        $sql = "SELECT * FROM `product` WHERE `product_id` = ?";
        $stmt = $this->db_conn()->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $this->db_conn()->error);
        }
        $stmt->bind_param("i", $product_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute statement failed: " . $stmt->error);
        }
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    //--UPDATE FUNCTION--//
    public function update_product($product_id, $product_name, $product_description, $product_price, $product_quantity) {
        $product_name = mysqli_real_escape_string($this->db_conn(), $product_name);
        $product_description = mysqli_real_escape_string($this->db_conn(), $product_description);
        $product_price = mysqli_real_escape_string($this->db_conn(), $product_price);
        $product_quantity = mysqli_real_escape_string($this->db_conn(), $product_quantity);
        
        $sql = "UPDATE `product` SET `product_name` = ?, `product_description` = ?, `product_price` = ?, `product_quantity` = ? WHERE `product_id` = ?";
        $stmt = $this->db_conn()->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $this->db_conn()->error);
        }
        $stmt->bind_param("ssdii", $product_name, $product_description, $product_price, $product_quantity, $product_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute statement failed: " . $stmt->error);
        }
        return true;
    }

    //--DELETE FUNCTION--//
    public function delete_product($product_id) {
        $sql = "DELETE FROM `product` WHERE `product_id` = ?";
        $stmt = $this->db_conn()->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $this->db_conn()->error);
        }
        $stmt->bind_param("i", $product_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute statement failed: " . $stmt->error);
        }
        return true;
    }

    public function decrement_product_quantity($product_id, $quantity) {
        $sql = "UPDATE product SET product_quantity = product_quantity - ? WHERE product_id = ?";
        $stmt = $this->db_conn()->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $this->db_conn()->error);
        }
        $stmt->bind_param("ii", $quantity, $product_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute statement failed: " . $stmt->error);
        }
        return true;
    }

    public function get_customer_products($customer_id, $search = '', $limit = 10) {
        try {
            $conn = $this->db_conn();
            
            // Query to get products assigned to the customer
            $sql = "SELECT DISTINCT p.* 
                   FROM product p 
                   INNER JOIN customer_products cp ON p.product_id = cp.product_id 
                   WHERE cp.customer_id = ?";
            
            // Add search condition if search term exists
            if (!empty($search)) {
                $sql .= " AND (p.product_name LIKE CONCAT('%', ?, '%') 
                         OR p.product_description LIKE CONCAT('%', ?, '%'))";
            }
            
            // Add ordering and limit
            $sql .= " ORDER BY p.product_name ASC";
            if ($limit > 0) {
                $sql .= " LIMIT ?";
            }
            
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }
            
            // Bind parameters
            if (!empty($search)) {
                if ($limit > 0) {
                    $stmt->bind_param("issi", $customer_id, $search, $search, $limit);
                } else {
                    $stmt->bind_param("iss", $customer_id, $search, $search);
                }
            } else {
                if ($limit > 0) {
                    $stmt->bind_param("ii", $customer_id, $limit);
                } else {
                    $stmt->bind_param("i", $customer_id);
                }
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute query: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            $products = [];
            
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            
            return $products;
            
        } catch (Exception $e) {
            error_log("Database error: " . $e->getMessage());
            throw new Exception("Failed to fetch services");
        }
    }
    
    public function addCustomerProduct($customer_id, $product_id) {
        try {
            $sql = "INSERT INTO customer_products (customer_id, product_id) VALUES (?, ?)";
            $stmt = $this->db_conn()->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare statement failed");
            }
            
            $stmt->bind_param("ii", $customer_id, $product_id);
            return $stmt->execute();
            
        } catch (Exception $e) {
            error_log("Error adding customer product: " . $e->getMessage());
            throw new Exception("Failed to assign product to customer");
        }
    }

    public function get_product_details_ctr($product_id, $customer_id) {
        try {
            if (empty($product_id)) {
                throw new Exception("Product ID is required");
            }
            
            // Get product with the latest invoice's quantity
            $sql = "SELECT p.*, ip.invoiceproduct_quantity, ip.invoiceproduct_description
                    FROM product p 
                    JOIN invoice_product ip ON p.product_id = ip.product_id 
                    JOIN invoice i ON ip.invoice_id = i.invoice_id 
                    WHERE p.product_id = ? 
                    AND i.customer_id = ? 
                    ORDER BY i.invoice_date_start DESC 
                    LIMIT 1";
            
            $stmt = $this->db_conn()->prepare($sql);
            if (!$stmt) {
                throw new Exception("Failed to prepare statement");
            }
            
            $stmt->bind_param("ii", $product_id, $customer_id);
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute query");
            }
            
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();
            
            if (!$product) {
                // If no invoice found, get just the product details
                $sql = "SELECT p.* FROM product p WHERE p.product_id = ?";
                $stmt = $this->db_conn()->prepare($sql);
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $product = $result->fetch_assoc();
            }
            
            return [
                'success' => true,
                'data' => $product
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