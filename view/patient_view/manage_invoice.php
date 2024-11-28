<?php 
include '../pincludes/header.php';
require_once("../../controllers/invoice_controller.php");

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$invoiceController = new InvoiceController();
$search = isset($_GET['search']) ? $_GET['search'] : '';
$entries = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;

// Check if the user is a customer or power user
$isCustomer = isset($_SESSION['customer_id']);
$customerId = $isCustomer ? $_SESSION['customer_id'] : null;

// Get invoices based on user type
if ($isCustomer) {
    $result = $invoiceController->get_customer_invoices_ctr($customerId, $search, $entries);
} else {
    $result = $invoiceController->get_all_invoices_ctr($search, $entries);
}

if (!$result['success']) {
    echo "<div class='alert alert-danger'>{$result['message']}</div>";
}
?>

<div class="product-list-container">
    <div class="product-list-header">
        <h1><?php echo $isCustomer ? 'My Invoices' : 'All Invoices'; ?></h1>
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
            <input type="text" class="search-input" id="searchInput" 
                   placeholder="<?php echo $isCustomer ? 'Search invoice/status...' : 'number...'; ?>" 
                   value="<?php echo htmlspecialchars($search); ?>">
            <i class="fas fa-search search-icon" id="searchButton"></i>
        </div>
    </div>

    <table class="product-table">
        <thead>
            <tr>
                <th>Invoice Number <i class="fas fa-sort sort-icon"></i></th>
                <?php if (!$isCustomer): ?>
                    <th>Customer <i class="fas fa-sort sort-icon"></i></th>
                <?php endif; ?>
                <th>Issue Date <i class="fas fa-sort sort-icon"></i></th>
                <th>Due Date <i class="fas fa-sort sort-icon"></i></th>
                <th>Status <i class="fas fa-sort sort-icon"></i></th>
                <th>Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result['success'] && !empty($result['data'])): ?>
                <?php foreach ($result['data'] as $invoice): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($invoice['invoice_number']); ?></td>
                        <?php if (!$isCustomer): ?>
                            <td><?php echo htmlspecialchars($invoice['customer_name']); ?></td>
                        <?php endif; ?>
                        <td><?php echo date('d/m/Y', strtotime($invoice['invoice_date_start'])); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($invoice['invoice_date_due'])); ?></td>
                        <td>
                            <?php
                            $statusClass = $invoice['status_name'] === 'PAID' ? 'bg-success' : 'bg-warning';
                            ?>
                            <span class="badge <?php echo $statusClass; ?>">
                                <?php echo htmlspecialchars($invoice['status_name']); ?>
                            </span>
                        </td>
                        <td><?php echo '$' . number_format($invoice['invoice_total'], 2); ?></td>
                        <td>
                            <button onclick="viewInvoiceDetails(<?php echo $invoice['invoice_id']; ?>)" 
                                    class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <?php if ($isCustomer && $invoice['status_name'] !== 'PAID'): ?>
                                <a href="pay_invoice.php?id=<?php echo $invoice['invoice_id']; ?>" 
                                   class="btn btn-sm btn-success">
                                    <i class="fas fa-credit-card"></i> Pay
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?php echo $isCustomer ? '6' : '7'; ?>" class="text-center">No invoices found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- View Invoice Details Modal -->
<div class="modal fade" id="viewInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Changed to modal-lg for wider display -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invoice Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="invoiceDetails" class="row">
                    <!-- Invoice details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>


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
    
    searchButton.addEventListener('click', updateTable);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            updateTable();
        }
    });
    entriesSelect.addEventListener('change', updateTable);
});
</script>
<script src="../../js/invoice.js"></script>
<style>
.invoice-details-container {
    padding: 1rem;
}

.invoice-header {
    border-bottom: 1px solid #eee;
    padding-bottom: 1rem;
}

.products-section {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    margin: 1rem 0;
}

.invoice-summary {
    border-top: 1px solid #eee;
    padding-top: 1rem;
}

#viewInvoiceModal .modal-body {
    padding: 1rem;
}

.badge.fs-6 {
    font-size: 1rem !important;
    padding: 0.5rem 1rem;
}

.table-responsive {
    margin: 0;
    padding: 0;
}

.products-section .table th {
    background-color: #fff;
}

.invoice-summary .table-sm td {
    padding: 0.5rem;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../js/main.js"></script>
<?php include '../pincludes/footer.php'; ?>