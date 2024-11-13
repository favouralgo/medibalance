<?php
/**
 * OTP Class
 * 
 * This class provides functionality to generate and validate One-Time Passwords (OTPs).
 * It extends the db_connection class to interact with the database.
 * 
 * @package    OTP
 * @subpackage Classes
 * @category   Authentication
 * @version    0.0.1
 * @since      2024-09-30
 */
require_once("../settings/db_class.php");

class OTP extends db_connection {
/**
 * Generates a One-Time Password (OTP) for a given email address.
 * 
 * @param string $email The email address for which the OTP is generated.
 * @return int The generated OTP.
 */
    public function generate_otp($email) {
        $otp = rand(100000, 999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+3 minutes"));
        
        $sql = "INSERT INTO otp (email, otp, expiry) VALUES (?, ?, ?)";
        $stmt = $this->db_conn()->prepare($sql);
        $stmt->bind_param("sss", $email, $otp, $expiry);
        $stmt->execute();
        
        return $otp;
    }
/**
 * Validates a given OTP for a specific email address.
 * 
 * @param string $email The email address to validate the OTP against.
 * @param int $otp The OTP to be validated.
 * @return bool Returns true if the OTP is valid and not expired, false otherwise.
 */
    public function validate_otp($email, $otp) {
        $sql = "SELECT * FROM otp WHERE email = ? AND otp = ? AND expiry > NOW()";
        $stmt = $this->db_conn()->prepare($sql);
        $stmt->bind_param("ss", $email, $otp);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}
?>