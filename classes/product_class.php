<?php
require_once("../settings/db_class.php");

class product_class extends db_connection {
    //--INSERT FUNCTION--//
    public function add_product($product_name, $product_description, $product_price, $product_quantity) {
        $product_name = mysqli_real_escape_string($this->db_conn(), $product_name);
        $product_description = mysqli_real_escape_string($this->db_conn(), $product_description);
        $product_price = mysqli_real_escape_string($this->db_conn(), $product_price);
        $product_quantity = mysqli_real_escape_string($this->db_conn(), $product_quantity);
        
        //--INSERT QUERY--//
        $sql = "INSERT INTO `products`(`product_name`, `product_description`, `product_price`, `product_quantity`) 
                VALUES ('$product_name','$product_description','$product_price','$product_quantity')";
        return $this->db_query($sql);
    }

    //--SELECT ALL PRODUCTS FUNCTION--//
    public function get_all_products() {
        $sql = "SELECT * FROM `products`";
        $result = mysqli_query($this->db_conn(), $sql);
        
        if (!$result) {
            return false; // Return false if query fails
        }
        
        $products = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }
        return $products;
    }

    //--SELECT SPECIFIC PRODUCT FUNCTION--//
    public function get_one_product($product_id) {
        $product_id = mysqli_real_escape_string($this->db_conn(), $product_id);
        $sql = "SELECT * FROM `products` WHERE `product_id`='$product_id'";
        $result = mysqli_query($this->db_conn(), $sql);
        
        if (!$result) {
            return false; // Return false if query fails
        }
        
        return mysqli_fetch_assoc($result);
    }

    //--UPDATE FUNCTION--//
    public function update_product($product_id, $product_name, $product_description, $product_price, $product_quantity) {
        $product_id = mysqli_real_escape_string($this->db_conn(), $product_id);
        $product_name = mysqli_real_escape_string($this->db_conn(), $product_name);
        $product_description = mysqli_real_escape_string($this->db_conn(), $product_description);
        $product_price = mysqli_real_escape_string($this->db_conn(), $product_price);
        $product_quantity = mysqli_real_escape_string($this->db_conn(), $product_quantity);
        
        $sql = "UPDATE `products` 
                SET `product_name`='$product_name', 
                    `product_description`='$product_description', 
                    `product_price`='$product_price', 
                    `product_quantity`='$product_quantity' 
                WHERE `product_id`='$product_id'";
        return $this->db_query($sql);
    }

    //--DELETE FUNCTION--//
    public function delete_product($product_id) {
        $product_id = mysqli_real_escape_string($this->db_conn(), $product_id);
        $sql = "DELETE FROM `products` WHERE `product_id`='$product_id'";
        return $this->db_query($sql);
    }
}
?>