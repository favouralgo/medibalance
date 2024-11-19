<?php
require_once(__DIR__ ."/../settings/db_class.php");

class CustomerModel extends db_connection {
    
    public function add_customer($customer_firstname, $customer_lastname, $customer_password, $customer_address, $customer_phonenumber, $customer_city, $customer_country, $customer_email) {
        // Get database connection
        $conn = $this->db_conn();
        
        // Sanitize inputs
        $customer_firstname = mysqli_real_escape_string($conn, $customer_firstname);
        $customer_lastname = mysqli_real_escape_string($conn, $customer_lastname);
        $customer_password = mysqli_real_escape_string($conn, $customer_password);
        $customer_address = mysqli_real_escape_string($conn, $customer_address);
        $customer_phonenumber = mysqli_real_escape_string($conn, $customer_phonenumber);
        $customer_city = mysqli_real_escape_string($conn, $customer_city);
        $customer_country = mysqli_real_escape_string($conn, $customer_country);
        $customer_email = mysqli_real_escape_string($conn, $customer_email);

        // Hash the password
        $hashed_password = password_hash($customer_password, PASSWORD_BCRYPT);
        
        $sql = "INSERT INTO customer (
            customer_firstname, 
            customer_lastname, 
            customer_password, 
            customer_address, 
            customer_phonenumber, 
            customer_city, 
            customer_country, 
            customer_email
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param("ssssssss", 
            $customer_firstname, 
            $customer_lastname, 
            $hashed_password, 
            $customer_address, 
            $customer_phonenumber, 
            $customer_city, 
            $customer_country, 
            $customer_email
        );

        return $stmt->execute();
    }

    public function get_one_customer($customer_id) {
        $conn = $this->db_conn();
        $customer_id = mysqli_real_escape_string($conn, $customer_id);
        
        $sql = "SELECT * FROM customer WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update_customer($customer_id, $customer_firstname, $customer_lastname, $customer_email, $customer_phonenumber, $customer_address, $customer_city, $customer_country) {
        $conn = $this->db_conn();
        
        // Sanitize inputs
        $customer_id = mysqli_real_escape_string($conn, $customer_id);
        $customer_firstname = mysqli_real_escape_string($conn, $customer_firstname);
        $customer_lastname = mysqli_real_escape_string($conn, $customer_lastname);
        $customer_email = mysqli_real_escape_string($conn, $customer_email);
        $customer_phonenumber = mysqli_real_escape_string($conn, $customer_phonenumber);
        $customer_address = mysqli_real_escape_string($conn, $customer_address);
        $customer_city = mysqli_real_escape_string($conn, $customer_city);
        $customer_country = mysqli_real_escape_string($conn, $customer_country);

        $sql = "UPDATE customer SET 
                customer_firstname = ?,
                customer_lastname = ?,
                customer_email = ?,
                customer_phonenumber = ?,
                customer_address = ?,
                customer_city = ?,
                customer_country = ?
                WHERE customer_id = ?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param("sssssssi", 
            $customer_firstname,
            $customer_lastname,
            $customer_email,
            $customer_phonenumber,
            $customer_address,
            $customer_city,
            $customer_country,
            $customer_id
        );

        return $stmt->execute();
    }

    public function delete_customer($customer_id) {
        $conn = $this->db_conn();
        $customer_id = mysqli_real_escape_string($conn, $customer_id);
        
        $sql = "DELETE FROM customer WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        
        $stmt->bind_param("i", $customer_id);
        return $stmt->execute();
    }

    public function all_customers() {
        $sql = "SELECT * FROM customer ORDER BY customer_firstname, customer_lastname";
        $stmt = $this->db_conn()->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function one_customer($customer_id) {
        $sql = "SELECT * FROM customer WHERE customer_id = ?";
        $stmt = $this->db_conn()->prepare($sql);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function get_all_customers($search = '', $limit = 10, $offset = 0) {
        $conn = $this->db_conn();
        $search = mysqli_real_escape_string($conn, $search);
        $limit = (int)$limit;
        $offset = (int)$offset;
        
        $sql = "SELECT * FROM customer 
                WHERE customer_firstname LIKE ? 
                OR customer_lastname LIKE ? 
                OR customer_email LIKE ? 
                LIMIT ? OFFSET ?";
                
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        
        $search_param = "%$search%";
        $stmt->bind_param("sssii", $search_param, $search_param, $search_param, $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function get_customers_count($search = '') {
        $conn = $this->db_conn();
        $search = mysqli_real_escape_string($conn, $search);
        
        $sql = "SELECT COUNT(*) as count FROM customer 
                WHERE customer_firstname LIKE ? 
                OR customer_lastname LIKE ? 
                OR customer_email LIKE ?";
                
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        
        $search_param = "%$search%";
        $stmt->bind_param("sss", $search_param, $search_param, $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    public function check_email_exists($customer_email) {
        $conn = $this->db_conn();
        $customer_email = mysqli_real_escape_string($conn, $customer_email);
        $sql = "SELECT * FROM customer WHERE customer_email = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt->bind_param("s", $customer_email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();


    }

    public function login_customer(){
        $conn = $this->db_conn();
        $email = $_POST['email'];
        $password = $_POST['password'];
        $email = mysqli_real_escape_string($conn, $email);
        $password = mysqli_real_escape_string($conn, $password);
        $sql = "SELECT * FROM customer WHERE customer_email = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            if (password_verify($password, $row['customer_password'])) {
                return $row['customer_id'];
                } else {
                    return false;
                }
        } else {
            return false;
        }

    }

    public function update_password(){
        $conn = $this->db_conn();
        $customer_id = $_POST['customer_id'];
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $customer_id = mysqli_real_escape_string($conn, $customer_id);
        $old_password = mysqli_real_escape_string($conn, $old_password);
        $new_password = mysqli_real_escape_string($conn, $new_password);
        $sql = "SELECT * FROM customer WHERE customer_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement failed: " . $conn->error);
            }
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        if ($row) {
            if (password_verify($old_password, $row['customer_password'])) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE customer SET customer_password = ? WHERE customer_id = ?";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    throw new Exception("Prepare statement failed: " . $conn->error);
                }
                $stmt->bind_param("si", $hashed_password, $customer_id);
                $stmt->execute();
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
?>