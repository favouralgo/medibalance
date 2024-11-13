<?php
require_once("../classes/customer_class.php");

class CustomerController {
    private $customerModel;

    public function __construct() {
        $this->customerModel = new CustomerModel();
    }

    public function add_customer_ctr($customer_name, $customer_email, $customer_pass, $customer_country, $customer_city, $customer_contact) {
        return $this->customerModel->add_customer($customer_name, $customer_email, $customer_pass, $customer_country, $customer_city, $customer_contact);
    }

    public function check_email_exists($email) {
        return $this->customerModel->check_email_exists($email);
    }

    public function login_customer_ctr($email, $password) {
        return $this->customerModel->login_customer($email, $password);
    }
}
?>