<?php
require_once("../settings/db_class.php");

class UserModel extends db_connection {
    public function add_user($user_firstname, $user_lastname, $user_email, $user_facilityname, $user_country, $user_city, $user_address, $user_phonenumber, $user_password) {
        // Get database connection
        $conn = $this->db_conn();
        
        // Sanitize the inputs
        $user_firstname = $conn->real_escape_string($user_firstname);
        $user_lastname = $conn->real_escape_string($user_lastname);
        $user_email = $conn->real_escape_string($user_email);
        $user_facilityname = $conn->real_escape_string($user_facilityname);
        $user_country = $conn->real_escape_string($user_country);
        $user_city = $conn->real_escape_string($user_city);
        $user_address = $conn->real_escape_string($user_address);
        $user_phonenumber = $conn->real_escape_string($user_phonenumber);
        $user_password = $conn->real_escape_string($user_password);

        // Hash the password using bcrypt
        $hashed_password = password_hash($user_password, PASSWORD_BCRYPT);

        // Begin transaction
        $conn->begin_transaction();

        try {
            // Insert user
            $sql = "INSERT INTO user (user_firstname, user_lastname, user_password, user_phonenumber, user_country, user_city, user_facilityname, user_email, user_address) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            $stmt->bind_param("sssssssss", $user_firstname, $user_lastname, $hashed_password, $user_phonenumber, $user_country, $user_city, $user_facilityname, $user_email, $user_address);
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }

            // Get the inserted user ID
            $user_id = $conn->insert_id;

            // Insert facility
            $sql = "INSERT INTO facility (facility_name, user_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            $stmt->bind_param("si", $user_facilityname, $user_id);
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }

            // Commit transaction
            $conn->commit();
            return true;
        } catch (Exception $e) {
            // Rollback transaction if any operation fails
            $conn->rollback();
            error_log($e->getMessage()); // Log the error message
            throw new Exception("User registration failed: " . $e->getMessage());
        }
    }

    public function check_email_exists($user_email) {
        try {
            $conn = $this->db_conn();
            
            $sql = "SELECT COUNT(*) as count FROM user WHERE user_email = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            $stmt->bind_param("s", $user_email);
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'] > 0;
        } catch (Exception $e) {
            error_log($e->getMessage()); // Log the error message
            throw new Exception("Email check failed: " . $e->getMessage());
        }
    }

    public function login_user($user_email, $user_password) {
        try {
            $conn = $this->db_conn();
            
            $sql = "SELECT u.*, f.facility_name FROM user u
                    LEFT JOIN facility f ON u.user_id = f.user_id
                    WHERE u.user_email = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            $stmt->bind_param("s", $user_email);
            if (!$stmt->execute()) {
                throw new Exception("Execute statement failed: " . $stmt->error);
            }
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user && password_verify($user_password, $user['user_password'])) {
                return $user;
            } else {
                throw new Exception("Invalid email or password");
            }
        } catch (Exception $e) {
            error_log($e->getMessage()); // Log the error message
            throw new Exception("Login failed: " . $e->getMessage());
        }
    }
}
?>