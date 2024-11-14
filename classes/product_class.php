<?php
require_once(__DIR__ . "/../settings/db_class.php");

class product_class extends db_connection {
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
    public function get_all_products() {
        $sql = "SELECT * FROM `product`";
        $result = $this->db_conn()->query($sql);
        
        if (!$result) {
            throw new Exception("Query failed: " . $this->db_conn()->error);
        }
        
        $products = array();
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
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
}
?>