<?php
require_once("../classes/user_class.php");

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // Parameters in exact database column order
    public function add_user($user_firstname, $user_lastname, $user_password, $user_phonenumber, $user_country, $user_city, $user_facilityname, $user_email, $user_address) {
        try {
            // First check if email exists
            if ($this->check_email_exists($user_email)) {
                throw new Exception("Email already exists");
            }

            // Pass parameters in exact database column order
            return $this->userModel->add_user(
                $user_firstname,    
                $user_lastname,     
                $user_password,     
                $user_phonenumber,  
                $user_country,      
                $user_city,         
                $user_facilityname, 
                $user_email,        
                $user_address       
            );
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function check_email_exists($user_email) {
        return $this->userModel->check_email_exists($user_email);
    }

    public function login_user_ctr($user_email, $user_password) {
        return $this->userModel->login_user($user_email, $user_password);
    }
}
?>