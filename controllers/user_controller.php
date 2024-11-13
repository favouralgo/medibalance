<?php
require_once("../classes/user_class.php");

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function add_user($user_firstname, $user_lastname, $user_email, $user_facilityname, $user_country, $user_city, $user_address, $user_phonenumber, $user_password) {
        return $this->userModel->add_user($user_firstname, $user_lastname, $user_email, $user_facilityname, $user_country, $user_city, $user_address, $user_phonenumber, $user_password);
    }

    public function check_email_exists($user_email) {
        return $this->userModel->check_email_exists($user_email);
    }

    public function login_user_ctr($user_email, $user_password) {
        return $this->userModel->login_user($user_email, $user_password);
    }
}
?>