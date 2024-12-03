<?php 
include '../aincludes/header.php';
require_once("../../controllers/admin_controller.php");

// Initialize AdminController
$adminController = new AdminController();

// Get search and entries parameters with proper defaults
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$entries = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;

// Get filtered users
$result = $adminController->get_all_users_ctr($search, $entries);
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
        <h1>Users List</h1>
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
                <th>Facility Name <i class="fas fa-sort sort-icon"></i></th>
                <th>Name <i class="fas fa-sort sort-icon"></i></th>
                <th>Email <i class="fas fa-sort sort-icon"></i></th>
                <th>Phone</th>
                <th>Location</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (isset($result['data']) && !empty($result['data'])): 
                foreach ($result['data'] as $user): 
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['user_facilityname']); ?></td>
                    <td><?php echo htmlspecialchars($user['user_firstname'] . ' ' . $user['user_lastname']); ?></td>
                    <td><?php echo htmlspecialchars($user['user_email']); ?></td>
                    <td><?php echo htmlspecialchars($user['user_phonenumber']); ?></td>
                    <td><?php echo htmlspecialchars($user['user_city'] . ', ' . $user['user_country']); ?></td>
                    <td>
                        <span class="badge <?php echo $user['is_approved'] === 'APPROVED' ? 'bg-success' : 'bg-warning'; ?>">
                            <?php echo htmlspecialchars($user['is_approved']); ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-edit" onclick="openEditModal(<?php echo $user['user_id']; ?>)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-delete" onclick="openDeleteModal(<?php echo $user['user_id']; ?>)">
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
                    <td colspan="7" class="text-center">No users found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editUserForm">
                    <input type="hidden" id="editUserId" name="user_id">
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
                    <div class="mb-3">
                        <label for="editFacilityName" class="form-label">Facility Name</label>
                        <input type="text" class="form-control" id="editFacilityName" name="facilityname" required>
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

function openEditModal(userId) {
    // Show loading state in modal
    $('#editUserModal').modal('show');
    
    // Fetch user details
    fetch(`../../actions/get_user_details_action.php?id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate form fields
                document.getElementById('editUserId').value = userId;
                document.getElementById('editFirstName').value = data.data.user_firstname;
                document.getElementById('editLastName').value = data.data.user_lastname;
                document.getElementById('editEmail').value = data.data.user_email;
                document.getElementById('editPhone').value = data.data.user_phonenumber;
                document.getElementById('editFacilityName').value = data.data.user_facilityname;
                document.getElementById('editCity').value = data.data.user_city;
                document.getElementById('editCountry').value = data.data.user_country;
                document.getElementById('editAddress').value = data.data.user_address;
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'Failed to load user details', 'error');
        });
}

function openDeleteModal(userId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this! This will also delete the associated facility.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete!'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteUser(userId);
        }
    });
}

function deleteUser(userId) {
    const formData = new FormData();
    formData.append('user_id', userId);
    
    fetch('../../actions/admin_delete_user.php', {
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
        Swal.fire('Error', 'Failed to delete user', 'error');
    });
}

// Handle edit form submission
document.getElementById('editUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('../../actions/admin_update_user.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#editUserModal').modal('hide');
            Swal.fire('Success', data.message, 'success')
            .then(() => location.reload());
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'Failed to update user', 'error');
    });
});
</script>

<style>
.badge {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    border-radius: 0.25rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.btn-edit, .btn-delete {
    border: none;
    padding: 0.5rem;
    border-radius: 0.25rem;
    cursor: pointer;
    transition: opacity 0.2s;
}

.btn-edit {
    background-color: #ffc107;
    color: #000;
}

.btn-delete {
    background-color: #dc3545;
    color: #fff;
}

.btn-edit:hover, .btn-delete:hover {
    opacity: 0.8;
}

.product-table th {
    white-space: nowrap;
    padding: 1rem;
}

.product-table td {
    padding: 1rem;
    vertical-align: middle;
}

/* Make sure table scrolls horizontally on mobile */
@media (max-width: 992px) {
    .product-table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
    }
}
</style>

<?php include '../aincludes/footer.php'; ?>