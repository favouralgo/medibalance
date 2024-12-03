<?php
require_once(__DIR__ . "/../classes/admin_class.php");

class AdminController {
    protected $adminModel;
    
    public function __construct() {
        $this->adminModel = new AdminModel();
    }

    // Admin registration with validation
    public function register_admin_ctr($data) {
        try {
            // Validate input data
            $this->validateAdminData($data);

            // Check if email already exists
            if ($this->check_email_exists($data['admin_email'])) {
                throw new Exception("Email already exists");
            }

            // Register the admin
            return $this->adminModel->add_admin(
                $data['admin_firstname'],
                $data['admin_lastname'],
                $data['admin_password'],
                $data['admin_country'],
                $data['admin_city'],
                $data['admin_phonenumber'],
                $data['admin_address'],
                $data['admin_email']
            );
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Validate admin registration data
    private function validateAdminData($data) {
        // Check required fields
        $required_fields = [
            'admin_firstname', 'admin_lastname', 'admin_password',
            'admin_country', 'admin_city', 'admin_phonenumber',
            'admin_address', 'admin_email'
        ];

        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                throw new Exception("All fields are required");
            }
        }

        // Validate email format
        if (!filter_var($data['admin_email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }

        // Validate phone number format (adjust regex as needed)
        if (!preg_match("/^\+?[1-9]\d{1,14}$/", $data['admin_phonenumber'])) {
            throw new Exception("Invalid phone number format");
        }

        // Password strength validation
        if (strlen($data['admin_password']) < 8) {
            throw new Exception("Password must be at least 8 characters long");
        }
    }
    
    // Admin login
    public function login_admin_ctr($email, $password) {
        try {
            $admin = $this->adminModel->login_admin($email, $password);
            if (!$admin) {
                throw new Exception("Invalid email or password");
            }
            return $admin;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    // Check if email exists
    public function check_email_exists($admin_email) {
        return $this->adminModel->check_email_exists($admin_email);
    }
    
    // Add user with facility
    public function add_user_with_facility_ctr($user_data, $facility_data) {
        try {
            return $this->adminModel->add_user_with_facility($user_data, $facility_data);
        } catch (Exception $e) {
            throw new Exception("Failed to add user and facility: " . $e->getMessage());
        }
    }
    
    // Get pending users
    public function get_pending_users_ctr() {
        try {
            return $this->adminModel->get_pending_users();
        } catch (Exception $e) {
            throw new Exception("Failed to get pending users: " . $e->getMessage());
        }
    }



    public function get_dashboard_statistics_ctr() {
        try {
            return $this->adminModel->get_dashboard_statistics();
        } catch (Exception $e) {
            throw new Exception("Failed to get dashboard statistics: " . $e->getMessage());
        }
    }

    public function get_recent_registrations_ctr($limit = 5) {
        try {
            return $this->adminModel->get_recent_registrations($limit);
        } catch (Exception $e) {
            throw new Exception("Failed to get recent registrations: " . $e->getMessage());
        }
    }

    public function get_recent_invoices_ctr($limit = 5) {
        try {
            return $this->adminModel->get_recent_invoices($limit);
        } catch (Exception $e) {
            throw new Exception("Failed to get recent invoices: " . $e->getMessage());
        }
    }

    public function get_recent_activities_ctr($limit = 5) {
        try {
            return $this->adminModel->get_recent_activities($limit);
        } catch (Exception $e) {
            throw new Exception("Failed to get recent activities: " . $e->getMessage());
        }
    }

    public function get_invoice_details_ctr($invoice_id) {
        try {
            return $this->adminModel->get_invoice_details($invoice_id);
        } catch (Exception $e) {
            throw new Exception("Failed to get invoice details: " . $e->getMessage());
        }
    }

    public function get_user_details_ctr($user_id) {
        try {
            return $this->adminModel->get_user_details($user_id);
        } catch (Exception $e) {
            throw new Exception("Failed to get user details: " . $e->getMessage());
        }
    }

    // Approve user
    public function approve_user_ctr($user_id, $admin_id, $comment = null) {
        try {
            return $this->adminModel->approve_user($user_id, $admin_id, $comment);
        } catch (Exception $e) {
            throw new Exception("Failed to approve user: " . $e->getMessage());
        }
    }

    // Reject user
    public function reject_user_ctr($user_id, $admin_id, $comment = null) {
        try {
            return $this->adminModel->reject_user($user_id, $admin_id, $comment);
        } catch (Exception $e) {
            throw new Exception("Failed to reject user: " . $e->getMessage());
        }
    }

    public function get_all_invoices_ctr($search = '', $entries = 10, $page = 1) {
        try {
            $offset = ($page - 1) * $entries;
            $result = $this->adminModel->get_all_invoices($search, $entries, $offset);
            
            return [
                'success' => true,
                'data' => $result['invoices'],
                'total' => $result['total'],
                'page' => $page,
                'total_pages' => ceil($result['total'] / $entries)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function get_all_products_ctr($search = '', $limit = 10) {
        try {
            $products = $this->adminModel->get_all_products($search, $limit);
            return [
                'success' => true,
                'data' => $products
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function get_product_ctr($product_id) {
        try {
            $product = $this->adminModel->get_product_by_id($product_id);
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

    public function update_product_ctr($product_id, $name, $description, $price, $quantity) {
        try {
            $result = $this->adminModel->update_product($product_id, $name, $description, $price, $quantity);
            return [
                'success' => $result,
                'message' => $result ? 'Product updated successfully' : 'Failed to update product'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function delete_product_ctr($product_id) {
        try {
            $result = $this->adminModel->delete_product($product_id);
            return [
                'success' => $result,
                'message' => $result ? 'Product deleted successfully' : 'Failed to delete product'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function get_all_users_ctr($search = '', $limit = 10) {
        try {
            $users = $this->adminModel->get_all_users($search, $limit);
            return [
                'success' => true,
                'data' => $users
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    public function update_user_ctr($user_id, $data) {
        try {
            $result = $this->adminModel->update_user($user_id, $data);
            return [
                'success' => $result,
                'message' => $result ? 'User updated successfully' : 'Failed to update user'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    public function delete_user_ctr($user_id) {
        try {
            $result = $this->adminModel->delete_user($user_id);
            return [
                'success' => $result,
                'message' => $result ? 'User deleted successfully' : 'Failed to delete user'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }



    public function get_all_customers_ctr($search = '', $limit = 10) {
        try {
            $customers = $this->adminModel->get_all_customers($search, $limit);
            return [
                'success' => true,
                'data' => $customers
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function update_customer_ctr($customer_id, $data) {
        try {
            $result = $this->adminModel->update_customer($customer_id, $data);
            return [
                'success' => $result,
                'message' => $result ? 'Customer updated successfully' : 'Failed to update customer'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function delete_customer_ctr($customer_id) {
        try {
            $result = $this->adminModel->delete_customer($customer_id);
            return [
                'success' => $result,
                'message' => $result ? 'Customer deleted successfully' : 'Failed to delete customer'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function get_customer_by_id_ctr($customer_id) {
        try {
            return $this->adminModel->get_customer_by_id($customer_id);
        } catch (Exception $e) {
            throw new Exception("Failed to get customer details: " . $e->getMessage());
        }
    }
}
?>