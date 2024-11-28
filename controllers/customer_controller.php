<?php
require_once(__DIR__ ."/../classes/customer_class.php");

class CustomerController {
    private $customerModel;

    public function __construct() {
        $this->customerModel = new CustomerModel();
    }

    public function add_customer_ctr($customer_firstname ,$customer_lastname,$customer_password,$customer_address,$customer_phonenumber,$customer_city,$customer_country,$customer_email) {
        return $this->customerModel->add_customer($customer_firstname ,$customer_lastname,$customer_password,$customer_address,$customer_phonenumber,$customer_city,$customer_country,$customer_email);
    }

    public function check_email_exists($customer_email) {
        return $this->customerModel->check_email_exists($customer_email);
    }

    public function login_customer_ctr($customer_email, $customer_password) {
        return $this->customerModel->login_customer($customer_email, $customer_password);
    }

    public function update_password_ctr($customer_email, $new_password) {
        return $this->customerModel->update_password($customer_email, $new_password);
    }

    public function get_all_customers_ctr($search = '', $limit = 10, $offset = 0) {
        return $this->customerModel->get_all_customers($search, $limit, $offset);
    }

    public function get_customers_count_ctr($search = '') {
        return $this->customerModel->get_customers_count($search);
    }

    public function get_one_customer_ctr($customer_id) {
        return $this->customerModel->get_one_customer($customer_id);
    }

    public function update_customer_ctr($customer_id, $customer_firstname, $customer_lastname, $customer_email, $customer_phonenumber, $customer_address, $customer_city, $customer_country) {
        return $this->customerModel->update_customer($customer_id, $customer_firstname, $customer_lastname, $customer_email, $customer_phonenumber, $customer_address, $customer_city, $customer_country);
    }

    public function delete_customer_ctr($customer_id) {
        return $this->customerModel->delete_customer($customer_id);
    }
}
?>
    
    
