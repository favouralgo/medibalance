<?php 
include '../includes/header.php'; 
require_once('../../controllers/customer_controller.php');

// Fetch all customers
$customerController = new CustomerController();

$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$entries_per_page = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $entries_per_page;

$customers = $customerController->get_all_customers_ctr($search_query, $entries_per_page, $offset);
$total_customers = $customerController->get_customers_count_ctr($search_query);
$total_pages = ceil($total_customers / $entries_per_page);
?>

<div class="managecustomers-list-container">
    <div class="managecustomers-list-header">
        <h1>Patient List</h1>
    </div>
    
    <div class="managecustomers-list-controls">
        <div class="managecustomers-entries-control">
            Show 
            <select class="managecustomers-entries-select" onchange="updateEntries(this.value)">
                <option value="10" <?php echo $entries_per_page == 10 ? 'selected' : ''; ?>>10</option>
                <option value="25" <?php echo $entries_per_page == 25 ? 'selected' : ''; ?>>25</option>
                <option value="50" <?php echo $entries_per_page == 50 ? 'selected' : ''; ?>>50</option>
                <option value="100" <?php echo $entries_per_page == 100 ? 'selected' : ''; ?>>100</option>
            </select>
            entries
        </div>
        
        <div class="managecustomers-search-control">
            <input type="text" class="managecustomers-search-input" placeholder="Search..." value="<?php echo htmlspecialchars($search_query); ?>" onkeyup="searchCustomers(this.value)">
            <i class="fas fa-search managecustomers-search-icon"></i>
        </div>
    </div>

    <table class="managecustomers-table">
        <thead>
            <tr>
                <th>Patient Name <i class="fas fa-sort sort-icon"></i></th>
                <th>Email <i class="fas fa-sort sort-icon"></i></th>
                <th>Phone Number <i class="fas fa-sort sort-icon"></i></th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($customers): ?>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($customer['customer_firstname'] . ' ' . $customer['customer_lastname']); ?></td>
                        <td><?php echo htmlspecialchars($customer['customer_email']); ?></td>
                        <td><?php echo htmlspecialchars($customer['customer_phonenumber']); ?></td>
                        <td>
                            <div class="managecustomers-action-buttons">
                                <button class="managecustomers-btn-edit" onclick="openEditModal(<?php echo $customer['customer_id']; ?>)"><i class="fas fa-edit"></i></button>
                                <button class="managecustomers-btn-delete" onclick="openDeleteModal(<?php echo $customer['customer_id']; ?>)"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No patients found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="managecustomers-pagination">
        <?php if ($total_pages > 1): ?>
            <button class="managecustomers-pagination-button" onclick="changePage(<?php echo max(1, $current_page - 1); ?>)" <?php echo $current_page == 1 ? 'disabled' : ''; ?>>Previous</button>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <button class="managecustomers-pagination-button <?php echo $current_page == $i ? 'active' : ''; ?>" onclick="changePage(<?php echo $i; ?>)"><?php echo $i; ?></button>
            <?php endfor; ?>
            <button class="managecustomers-pagination-button" onclick="changePage(<?php echo min($total_pages, $current_page + 1); ?>)" <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>>Next</button>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Customer Modal -->
<div class="modal" id="editCustomerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCustomerForm">
                    <input type="hidden" id="editCustomerId" name="customer_id">
                    <div class="form-group">
                        <label for="editCustomerFirstname">First Name</label>
                        <input type="text" class="form-control" id="editCustomerFirstname" name="customer_firstname" required>
                    </div>
                    <div class="form-group">
                        <label for="editCustomerLastname">Last Name</label>
                        <input type="text" class="form-control" id="editCustomerLastname" name="customer_lastname" required>
                    </div>
                    <div class="form-group">
                        <label for="editCustomerEmail">Email</label>
                        <input type="email" class="form-control" id="editCustomerEmail" name="customer_email" required>
                    </div>
                    <div class="form-group">
                        <label for="editCustomerPhone">Phone Number</label>
                        <input type="tel" class="form-control" id="editCustomerPhone" name="customer_phonenumber" required>
                    </div>
                    <div class="form-group">
                        <label for="editCustomerAddress">Address</label>
                        <input type="text" class="form-control" id="editCustomerAddress" name="customer_address" required>
                    </div>
                    <div class="form-group">
                        <label for="editCustomerCity">City</label>
                        <input type="text" class="form-control" id="editCustomerCity" name="customer_city" required>
                    </div>
                    <div class="form-group">
                        <label for="editCustomerCountry">Country</label>
                        <input type="text" class="form-control" id="editCustomerCountry" name="customer_country" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Customer Modal -->
<div class="modal" id="deleteCustomerModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this customer?</p>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Yes, delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script src="../../js/customer.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php include '../includes/footer.php'; ?>