<?php
require_once(__DIR__ . "/../settings/db_class.php");

class AdminModel extends db_connection {
    // Add new admin
    public function add_admin($admin_firstname, $admin_lastname, $admin_password, $admin_country, $admin_city, $admin_phonenumber, $admin_address, $admin_email) {
        $conn = $this->db_conn();
        
        // Sanitize inputs
        $admin_firstname = $conn->real_escape_string($admin_firstname);
        $admin_lastname = $conn->real_escape_string($admin_lastname);
        $admin_password = password_hash($conn->real_escape_string($admin_password), PASSWORD_BCRYPT);
        $admin_country = $conn->real_escape_string($admin_country);
        $admin_city = $conn->real_escape_string($admin_city);
        $admin_phonenumber = $conn->real_escape_string($admin_phonenumber);
        $admin_address = $conn->real_escape_string($admin_address);
        $admin_email = $conn->real_escape_string($admin_email);

        $sql = "INSERT INTO admin (
            admin_firstname, admin_lastname, admin_password, admin_country,
            admin_city, admin_phonenumber, admin_address, admin_email
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", 
            $admin_firstname, $admin_lastname, $admin_password, $admin_country,
            $admin_city, $admin_phonenumber, $admin_address, $admin_email
        );
        
        return $stmt->execute();
    }

    // Login admin
    public function login_admin($admin_email, $admin_password) {
        $conn = $this->db_conn();
        $admin_email = $conn->real_escape_string($admin_email);
        
        $sql = "SELECT * FROM admin WHERE admin_email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $admin_email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            if (password_verify($admin_password, $admin['admin_password'])) {
                unset($admin['admin_password']); // Remove password from array
                return $admin;
            }
        }
        return false;
    }

    // Check if email exists
    public function check_email_exists($admin_email) {
        try {
            $conn = $this->db_conn();
            
            $sql = "SELECT COUNT(*) as count FROM admin WHERE admin_email = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                throw new Exception("Prepare statement failed: " . $conn->error);
            }
            $stmt->bind_param("s", $admin_email);
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

    // Add user with facility (Transaction)
    public function add_user_with_facility($user_data, $facility_data) {
        $conn = $this->db_conn();
        $conn->begin_transaction();

        try {
            // Insert user with approved status
            $sql = "INSERT INTO user (
                user_firstname, user_lastname, user_password, user_phonenumber,
                user_country, user_city, user_facilityname, user_email, 
                user_address, is_approved
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'APPROVED')";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssss",
                $user_data['firstname'], $user_data['lastname'], 
                password_hash($user_data['password'], PASSWORD_BCRYPT),
                $user_data['phonenumber'], $user_data['country'], 
                $user_data['city'], $user_data['facilityname'],
                $user_data['email'], $user_data['address']
            );
            
            $stmt->execute();
            $user_id = $conn->insert_id;

            // Insert facility
            $sql = "INSERT INTO facility (facility_name, user_id) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $facility_data['name'], $user_id);
            $stmt->execute();

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }

    // Get pending users
    public function get_pending_users() {
        $conn = $this->db_conn();
        $sql = "SELECT * FROM user WHERE is_approved = 'NOT_APPROVED'";
        $result = $conn->query($sql);
        
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    // Get user by ID
    public function get_user_by_id($user_id) {
        $conn = $this->db_conn();
        $sql = "SELECT * FROM user WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }

    // Approve user
    public function approve_user($user_id, $admin_id, $comment = null) {
        $conn = $this->db_conn();
        $conn->begin_transaction();

        try {
            // Update user approval status
            $sql = "UPDATE user SET 
                    is_approved = 'APPROVED',
                    approved_by = ?,
                    approved_at = CURRENT_TIMESTAMP
                    WHERE user_id = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $admin_id, $user_id);
            $stmt->execute();

            // Record in approval history
            $sql = "INSERT INTO approval_history (
                user_id, admin_id, status, comment
            ) VALUES (?, ?, 'APPROVED', ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $user_id, $admin_id, $comment);
            $stmt->execute();

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }

    // Reject user
    public function reject_user($user_id, $admin_id, $comment = null) {
        $conn = $this->db_conn();
        $conn->begin_transaction();

        try {
            // Insert rejection record
            $sql = "INSERT INTO approval_history (
                user_id, admin_id, status, comment
            ) VALUES (?, ?, 'REJECTED', ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $user_id, $admin_id, $comment);
            $stmt->execute();

            // Delete user account
            $sql = "DELETE FROM user WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            return false;
        }
    }

    // Get all admins (optional, for super admin features)
    public function get_all_admins($search = '', $limit = 10, $offset = 0) {
        $conn = $this->db_conn();
        
        $search = $conn->real_escape_string($search);
        $limit = (int)$limit;
        $offset = (int)$offset;

        $sql = "SELECT admin_id, admin_firstname, admin_lastname, 
                admin_email, admin_country, admin_city, 
                admin_phonenumber, created_at 
                FROM admin 
                WHERE admin_firstname LIKE ? OR admin_lastname LIKE ? 
                OR admin_email LIKE ? 
                LIMIT ? OFFSET ?";
        
        $search = "%$search%";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssii", $search, $search, $search, $limit, $offset);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    

    public function get_dashboard_statistics() {
        $conn = $this->db_conn();
        $stats = [];

        try {
            // Sales Amount (total of invoiceproduct_price)
            $sql = "SELECT COALESCE(SUM(invoiceproduct_price), 0) as total_revenue 
                    FROM invoice_product";
            $result = $conn->query($sql);
            $stats['total_revenue'] = $result->fetch_assoc()['total_revenue'];

            // Total Invoices
            $sql = "SELECT COUNT(*) as total_invoices FROM invoice";
            $result = $conn->query($sql);
            $stats['total_invoices'] = $result->fetch_assoc()['total_invoices'];

            // Total Pending Bills (status_id = 2)
            $sql = "SELECT COUNT(*) as pending_bills 
                    FROM invoice 
                    WHERE status_id = 2";
            $result = $conn->query($sql);
            $stats['pending_bills'] = $result->fetch_assoc()['pending_bills'];

            // Total Due Amount (sum of invoices with status_id = 2)
            $sql = "SELECT COALESCE(SUM(invoice_total), 0) as due_amount 
                    FROM invoice 
                    WHERE status_id = 2";
            $result = $conn->query($sql);
            $stats['due_amount'] = $result->fetch_assoc()['due_amount'];

            // Total Products
            $sql = "SELECT COUNT(*) as total_products FROM product";
            $result = $conn->query($sql);
            $stats['total_products'] = $result->fetch_assoc()['total_products'];

            // Total Patients (Customers)
            $sql = "SELECT COUNT(*) as total_customers FROM customer";
            $result = $conn->query($sql);
            $stats['total_customers'] = $result->fetch_assoc()['total_customers'];

            // Total Paid Bills (status_id = 1)
            $sql = "SELECT COUNT(*) as paid_bills 
                    FROM invoice 
                    WHERE status_id = 1";
            $result = $conn->query($sql);
            $stats['paid_bills'] = $result->fetch_assoc()['paid_bills'];

            // Total Facilities
            $sql = "SELECT COUNT(*) as total_facilities FROM facility";
            $result = $conn->query($sql);
            $stats['total_facilities'] = $result->fetch_assoc()['total_facilities'];

            // User Statistics
            // Approved Users
            $sql = "SELECT COUNT(*) as approved_users 
                    FROM user 
                    WHERE is_approved = 'APPROVED'";
            $result = $conn->query($sql);
            $stats['approved_users'] = $result->fetch_assoc()['approved_users'];

            // Pending Users
            $sql = "SELECT COUNT(*) as pending_users 
                    FROM user 
                    WHERE is_approved = 'NOT_APPROVED'";
            $result = $conn->query($sql);
            $stats['pending_users'] = $result->fetch_assoc()['pending_users'];

            // Rejected Users
            $sql = "SELECT COUNT(*) as rejected_users 
                    FROM approval_history 
                    WHERE status = 'REJECTED'";
            $result = $conn->query($sql);
            $stats['rejected_users'] = $result->fetch_assoc()['rejected_users'];

            return $stats;
        } catch (Exception $e) {
            error_log("Error fetching dashboard statistics: " . $e->getMessage());
            throw new Exception("Failed to fetch dashboard statistics");
        }
    }

    // Add method for recent registrations
    public function get_recent_registrations($limit = 5) {
        $conn = $this->db_conn();

        $sql = "SELECT * FROM user 
                ORDER BY created_at DESC 
                LIMIT ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Add method for recent invoices
    public function get_recent_invoices($limit = 5) {
        $conn = $this->db_conn();

        $sql = "SELECT i.*, c.customer_firstname, c.customer_lastname, 
                s.status_name, f.facility_name
                FROM invoice i 
                LEFT JOIN customer c ON i.customer_id = c.customer_id 
                LEFT JOIN status s ON i.status_id = s.status_id
                LEFT JOIN facility f ON i.facility_id = f.facility_id
                ORDER BY i.created_at DESC 
                LIMIT ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        $invoices = [];
        while ($row = $result->fetch_assoc()) {
            $row['customer_name'] = $row['customer_firstname'] . ' ' . $row['customer_lastname'];
            $invoices[] = $row;
        }

        return $invoices;
    }

    // Add method for recent activities
    public function get_recent_activities($limit = 5) {
        $conn = $this->db_conn();

        // Get recent invoices (3)
        $sql_invoices = "SELECT 
                'invoice_created' as activity_type,
                i.invoice_number,
                CONCAT(c.customer_firstname, ' ', c.customer_lastname) as customer_name,
                i.invoice_total as amount,
                i.created_at as activity_time
                FROM invoice i
                LEFT JOIN customer c ON i.customer_id = c.customer_id
                ORDER BY i.created_at DESC
                LIMIT 3";

        // Get paid invoices as payments (2)
        $sql_payments = "SELECT 
                'payment_received' as activity_type,
                i.invoice_number,
                CONCAT(c.customer_firstname, ' ', c.customer_lastname) as customer_name,
                i.invoice_total as amount,
                i.updated_at as activity_time
                FROM invoice i
                LEFT JOIN customer c ON i.customer_id = c.customer_id
                WHERE i.status_id = 1
                ORDER BY i.updated_at DESC
                LIMIT 2";

        $result_invoices = $conn->query($sql_invoices);
        $result_payments = $conn->query($sql_payments);

        $activities = array_merge(
            $result_invoices->fetch_all(MYSQLI_ASSOC),
            $result_payments->fetch_all(MYSQLI_ASSOC)
        );

        // Sort by activity_time
        usort($activities, function($a, $b) {
            return strtotime($b['activity_time']) - strtotime($a['activity_time']);
        });

        return array_slice($activities, 0, $limit);
    }

    public function get_invoice_details($invoice_id) {
        $conn = $this->db_conn();
        
        try {
            // Get invoice details
            $sql = "SELECT i.*, c.customer_firstname, c.customer_lastname, 
                    f.facility_name, s.status_name
                    FROM invoice i 
                    LEFT JOIN customer c ON i.customer_id = c.customer_id
                    LEFT JOIN facility f ON i.facility_id = f.facility_id
                    LEFT JOIN status s ON i.status_id = s.status_id
                    WHERE i.invoice_id = ?";
                    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $invoice_id);
            $stmt->execute();
            $invoice = $stmt->get_result()->fetch_assoc();
            
            if (!$invoice) {
                return null;
            }
            
            // Get invoice products
            $sql = "SELECT * FROM invoice_product 
                    WHERE invoice_id = ?";
                    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $invoice_id);
            $stmt->execute();
            $products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            
            // Add products to invoice array
            $invoice['products'] = $products;
            $invoice['customer_name'] = $invoice['customer_firstname'] . ' ' . $invoice['customer_lastname'];
            
            return $invoice;
        } catch (Exception $e) {
            error_log("Error fetching invoice details: " . $e->getMessage());
            throw new Exception("Failed to fetch invoice details");
        }
    }

    public function get_user_details($user_id) {
        $conn = $this->db_conn();
        
        try {
            $sql = "SELECT u.*, f.facility_name 
                    FROM user u 
                    LEFT JOIN facility f ON u.user_id = f.user_id 
                    WHERE u.user_id = ?";
                    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error fetching user details: " . $e->getMessage());
            throw new Exception("Failed to fetch user details");
        }
    }


    public function get_all_invoices($search = '', $limit = 10, $offset = 0) {
        $conn = $this->db_conn();
        $search = $conn->real_escape_string($search);
        
        try {
            // Get total count for pagination
            $count_sql = "SELECT COUNT(*) as total FROM invoice i 
                         LEFT JOIN customer c ON i.customer_id = c.customer_id
                         WHERE i.invoice_number LIKE ? OR 
                         CONCAT(c.customer_firstname, ' ', c.customer_lastname) LIKE ?";
                         
            $stmt = $conn->prepare($count_sql);
            $search_param = "%$search%";
            $stmt->bind_param("ss", $search_param, $search_param);
            $stmt->execute();
            $total = $stmt->get_result()->fetch_assoc()['total'];
    
            // Get filtered invoices
            $sql = "SELECT i.*, 
                    CONCAT(c.customer_firstname, ' ', c.customer_lastname) as customer_name,
                    s.status_name, f.facility_name
                    FROM invoice i
                    LEFT JOIN customer c ON i.customer_id = c.customer_id
                    LEFT JOIN status s ON i.status_id = s.status_id
                    LEFT JOIN facility f ON i.facility_id = f.facility_id
                    WHERE i.invoice_number LIKE ? OR 
                    CONCAT(c.customer_firstname, ' ', c.customer_lastname) LIKE ?
                    ORDER BY i.created_at DESC
                    LIMIT ? OFFSET ?";
    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssii", $search_param, $search_param, $limit, $offset);
            $stmt->execute();
            $invoices = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
            return [
                'total' => $total,
                'invoices' => $invoices
            ];
        } catch (Exception $e) {
            error_log("Error fetching invoices: " . $e->getMessage());
            throw new Exception("Failed to fetch invoices");
        }
    }


    public function get_all_products($search = '', $limit = 10) {
        $conn = $this->db_conn();
        
        try {
            $sql = "SELECT * FROM product WHERE 
                    product_name LIKE ? OR 
                    product_description LIKE ? 
                    ORDER BY created_at DESC";
                    
            if ($limit) {
                $sql .= " LIMIT ?";
            }
            
            $stmt = $conn->prepare($sql);
            
            $search_param = "%$search%";
            if ($limit) {
                $stmt->bind_param("ssi", $search_param, $search_param, $limit);
            } else {
                $stmt->bind_param("ss", $search_param, $search_param);
            }
            
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error fetching products: " . $e->getMessage());
            throw new Exception("Failed to fetch products");
        }
    }
    
    public function get_product_by_id($product_id) {
        $conn = $this->db_conn();
        
        try {
            $sql = "SELECT * FROM product WHERE product_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error fetching product: " . $e->getMessage());
            throw new Exception("Failed to fetch product");
        }
    }
    
    public function update_product($product_id, $name, $description, $price, $quantity) {
        $conn = $this->db_conn();
        
        try {
            $sql = "UPDATE product SET 
                    product_name = ?,
                    product_description = ?,
                    product_price = ?,
                    product_quantity = ?,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE product_id = ?";
                    
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdii", $name, $description, $price, $quantity, $product_id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updating product: " . $e->getMessage());
            throw new Exception("Failed to update product");
        }
    }
    
    public function delete_product($product_id) {
        $conn = $this->db_conn();
        
        try {
            $sql = "DELETE FROM product WHERE product_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $product_id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error deleting product: " . $e->getMessage());
            throw new Exception("Failed to delete product");
        }
    }


    public function get_all_users($search = '', $limit = 10) {
        $conn = $this->db_conn();

        try {
            $sql = "SELECT u.*, f.facility_name 
                    FROM user u
                    LEFT JOIN facility f ON u.user_id = f.user_id
                    WHERE u.user_firstname LIKE ? OR 
                          u.user_lastname LIKE ? OR 
                          u.user_email LIKE ? OR
                          u.user_facilityname LIKE ?
                    ORDER BY u.created_at DESC";

            if ($limit) {
                $sql .= " LIMIT ?";
            }

            $stmt = $conn->prepare($sql);

            $search_param = "%$search%";
            if ($limit) {
                $stmt->bind_param("ssssi", $search_param, $search_param, $search_param, $search_param, $limit);
            } else {
                $stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
            }

            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error fetching users: " . $e->getMessage());
            throw new Exception("Failed to fetch users");
        }
    }

    public function update_user($user_id, $data) {
        $conn = $this->db_conn();
        $conn->begin_transaction();

        try {
            // Update user
            $sql = "UPDATE user SET 
                    user_firstname = ?,
                    user_lastname = ?,
                    user_phonenumber = ?,
                    user_country = ?,
                    user_city = ?,
                    user_facilityname = ?,
                    user_email = ?,
                    user_address = ?,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE user_id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssssi", 
                $data['firstname'],
                $data['lastname'],
                $data['phonenumber'],
                $data['country'],
                $data['city'],
                $data['facilityname'],
                $data['email'],
                $data['address'],
                $user_id
            );
            $stmt->execute();

            // Update facility name if it exists
            if (isset($data['facilityname'])) {
                $sql = "UPDATE facility SET 
                        facility_name = ? 
                        WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $data['facilityname'], $user_id);
                $stmt->execute();
            }

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Error updating user: " . $e->getMessage());
            throw new Exception("Failed to update user");
        }
    }

    public function delete_user($user_id) {
        $conn = $this->db_conn();
        $conn->begin_transaction();

        try {
            // Delete from facility first (due to foreign key constraint)
            $sql = "DELETE FROM facility WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            // Delete the user
            $sql = "DELETE FROM user WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Error deleting user: " . $e->getMessage());
            throw new Exception("Failed to delete user");
        }
    }


    public function get_all_customers($search = '', $limit = 10) {
        $conn = $this->db_conn();

        try {
            $sql = "SELECT * FROM customer 
                    WHERE customer_firstname LIKE ? OR 
                          customer_lastname LIKE ? OR 
                          customer_email LIKE ? OR
                          customer_phonenumber LIKE ?
                    ORDER BY created_at DESC";

            if ($limit) {
                $sql .= " LIMIT ?";
            }

            $stmt = $conn->prepare($sql);

            $search_param = "%$search%";
            if ($limit) {
                $stmt->bind_param("ssssi", $search_param, $search_param, $search_param, $search_param, $limit);
            } else {
                $stmt->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
            }

            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("Error fetching customers: " . $e->getMessage());
            throw new Exception("Failed to fetch customers");
        }
    }

    public function update_customer($customer_id, $data) {
        $conn = $this->db_conn();

        try {
            $sql = "UPDATE customer SET 
                    customer_firstname = ?,
                    customer_lastname = ?,
                    customer_address = ?,
                    customer_phonenumber = ?,
                    customer_city = ?,
                    customer_country = ?,
                    customer_email = ?,
                    updated_at = CURRENT_TIMESTAMP
                    WHERE customer_id = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssi", 
                $data['firstname'],
                $data['lastname'],
                $data['address'],
                $data['phonenumber'],
                $data['city'],
                $data['country'],
                $data['email'],
                $customer_id
            );

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error updating customer: " . $e->getMessage());
            throw new Exception("Failed to update customer");
        }
    }

    public function delete_customer($customer_id) {
        $conn = $this->db_conn();
        $conn->begin_transaction();

        try {
            $sql = "DELETE FROM customer_products WHERE customer_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();

            // Then delete the customer
            $sql = "DELETE FROM customer WHERE customer_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();

            $conn->commit();
            return true;
        } catch (Exception $e) {
            $conn->rollback();
            error_log("Error deleting customer: " . $e->getMessage());
            throw new Exception("Failed to delete customer");
        }
    }

    public function get_customer_by_id($customer_id) {
        $conn = $this->db_conn();
        
        try {
            $sql = "SELECT * FROM customer WHERE customer_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error fetching customer: " . $e->getMessage());
            throw new Exception("Failed to fetch customer");
        }
    }

    


}
?>