<?php 
include '../aincludes/header.php';
require_once("../../controllers/admin_controller.php");

// Initialize AdminController
$adminController = new AdminController();

// Get search and entries parameters with proper defaults
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$entries = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;

// Get filtered customers
$result = $adminController->get_all_customers_ctr($search, $entries);
?>

<div class="product-list-container">
    <!-- Message Display -->
    <div class="message-display">
        <?php
        if (isset($_SESSION['error_msg'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ' . htmlspecialchars($_SESSION['error_msg']) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            unset($_SESSION['error_msg']);
        }
        if (isset($_SESSION['success_msg'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    ' . htmlspecialchars($_SESSION['success_msg']) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            unset($_SESSION['success_msg']);
        }
        ?>
    </div>

    <div class="product-list-header">
        <h1>Patients List</h1>
    </div>
    
    <div class="product-list-controls">
        <div class="entries-control">
            Show 
            <select class="entries-select" id="entriesSelect">
                <option value="10" <?php echo $entries == 10 ? 'selected' : ''; ?>>10</option>
                <option value="25" <?php echo $entries == 25 ? 'selected' : ''; ?>>25</option>
                <option value="50" <?php echo $entries == 50 ? 'selected' : ''; ?>>50</option>
                <option value="100" <?php echo $entries == 100 ? 'selected' : ''; ?>>100</option>
            </select>
            entries
        </div>
        
        <div class="search-control">
            <input type="text" 
                   class="search-input" 
                   id="searchInput" 
                   placeholder="Search..." 
                   value="<?php echo htmlspecialchars($search ?? ''); ?>">
            <i class="fas fa-search search-icon" id="searchButton"></i>
        </div>
    </div>

    <table class="product-table">
        <thead>
            <tr>
                <th>Name <i class="fas fa-sort sort-icon"></i></th>
                <th>Email <i class="fas fa-sort sort-icon"></i></th>
                <th>Phone</th>
                <th>City</th>
                <th>Country</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (isset($result['data']) && !empty($result['data'])): 
                foreach ($result['data'] as $customer): 
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($customer['customer_firstname'] . ' ' . $customer['customer_lastname']); ?></td>
                    <td><?php echo htmlspecialchars($customer['customer_email']); ?></td>
                    <td><?php echo htmlspecialchars($customer['customer_phonenumber']); ?></td>
                    <td><?php echo htmlspecialchars($customer['customer_city']); ?></td>
                    <td><?php echo htmlspecialchars($customer['customer_country']); ?></td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-edit" onclick="openEditModal(<?php echo $customer['customer_id']; ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete" onclick="openDeleteModal(<?php echo $customer['customer_id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            <?php 
                endforeach; 
            else: 
            ?>
                <tr>
                    <td colspan="6" class="text-center">No customers found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Edit Customer Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">Edit Patient</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCustomerForm">
                    <input type="hidden" id="editCustomerId" name="customer_id">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editFirstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="editFirstName" name="firstname" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editLastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="editLastName" name="lastname" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editPhone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="editPhone" name="phonenumber" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editCity" class="form-label">City</label>
                            <input type="text" class="form-control" id="editCity" name="city" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editCountry" class="form-label">Country</label>
                            <input type="text" class="form-control" id="editCountry" name="country" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editAddress" class="form-label">Address</label>
                        <textarea class="form-control" id="editAddress" name="address" required></textarea>
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../js/main.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const entriesSelect = document.getElementById('entriesSelect');
    
    function updateTable() {
        const searchValue = searchInput.value;
        const entriesValue = entriesSelect.value;
        window.location.href = `?search=${encodeURIComponent(searchValue)}&entries=${entriesValue}`;
    }
    
    // Search button click
    searchButton.addEventListener('click', function() {
        updateTable();
    });
    
    // Search on enter key
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            updateTable();
        }
    });
    
    // Entries select change
    entriesSelect.addEventListener('change', function() {
        updateTable();
    });
});

function openEditModal(customerId) {
    // Show loading state in modal
    $('#editCustomerModal').modal('show');
    
    // Fetch customer details
    fetch(`../../actions/admin_get_customer.php?id=${customerId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate form fields
                document.getElementById('editCustomerId').value = customerId;
                document.getElementById('editFirstName').value = data.data.customer_firstname;
                document.getElementById('editLastName').value = data.data.customer_lastname;
                document.getElementById('editEmail').value = data.data.customer_email;
                document.getElementById('editPhone').value = data.data.customer_phonenumber;
                document.getElementById('editCity').value = data.data.customer_city;
                document.getElementById('editCountry').value = data.data.customer_country;
                document.getElementById('editAddress').value = data.data.customer_address;
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Failed to load customer details', 'error');
        });
}

function openDeleteModal(customerId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete!'
    }).then((result) => {
        if (result.isConfirmed){
            deleteCustomer(customerId);
        }
    });
}

function deleteCustomer(customerId) {
    const formData = new FormData();
    formData.append('customer_id', customerId);
    
    fetch('../../actions/admin_delete_customer.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Deleted!', data.message, 'success')
            .then(() => location.reload());
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Failed to delete customer', 'error');
    });
}

// Handle edit form submission
document.getElementById('editCustomerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('../../actions/admin_update_customer.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#editCustomerModal').modal('hide');
            Swal.fire('Success', data.message, 'success')
            .then(() => location.reload());
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Failed to update customer', 'error');
    });
});
</script>