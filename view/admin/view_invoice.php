<?php 
include '../aincludes/header.php';
require_once('../../controllers/admin_controller.php');
$search = isset($_GET['search']) ? $_GET['search'] : '';
$entries = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

$adminController = new AdminController();
$result = $adminController->get_all_invoices_ctr($search, $entries, $page);
?>

<div class="product-list-container">
    <div class="product-list-header">
        <h1>Invoices</h1>
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
            <input type="text" class="search-input" id="searchInput" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
            <i class="fas fa-search search-icon" id="searchButton"></i>
        </div>
    </div>

    <div class="table-responsive">
        <table class="product-table">
            <thead>
                <tr>
                    <th width="15%">Invoice Number <i class="fas fa-sort sort-icon"></i></th>
                    <th width="20%">Customer <i class="fas fa-sort sort-icon"></i></th>
                    <th width="20%">Facility</th>
                    <th width="10%">Amount</th>
                    <th width="12%">Issue Date <i class="fas fa-sort sort-icon"></i></th>
                    <th width="12%">Due Date <i class="fas fa-sort sort-icon"></i></th>
                    <th width="11%">Status <i class="fas fa-sort sort-icon"></i></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result['success'] && !empty($result['data'])): ?>
                    <?php foreach ($result['data'] as $invoice): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($invoice['invoice_number']); ?></td>
                            <td><?php echo htmlspecialchars($invoice['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($invoice['facility_name']); ?></td>
                            <td>GHS <?php echo number_format($invoice['invoice_total'], 2); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($invoice['invoice_date_start'])); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($invoice['invoice_date_due'])); ?></td>
                            <td>
                                <span class="badge <?php echo $invoice['status_name'] === 'PAID' ? 'bg-success' : 'bg-warning'; ?>">
                                    <?php echo htmlspecialchars($invoice['status_name']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No invoices found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($result['success'] && $result['total_pages'] > 1): ?>
        <div class="pagination-container">
            <ul class="pagination">
                <li class="<?php echo $page <= 1 ? 'disabled' : ''; ?>">
                    <a href="javascript:void(0)" onclick="changePage(<?php echo $page - 1; ?>)">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $result['total_pages']; $i++): ?>
                    <li class="<?php echo $i === $page ? 'active' : ''; ?>">
                        <a href="javascript:void(0)" onclick="changePage(<?php echo $i; ?>)"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="<?php echo $page >= $result['total_pages'] ? 'disabled' : ''; ?>">
                    <a href="javascript:void(0)" onclick="changePage(<?php echo $page + 1; ?>)">Next</a>
                </li>
            </ul>
        </div>
    <?php endif; ?>
</div>

<style>
/* Add to your existing styles */
.product-table th, .product-table td {
    padding: 1rem 0.75rem;
    white-space: nowrap;
}

.product-table {
    width: 100%;
    margin-bottom: 1rem;
}

.badge {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    border-radius: 0.25rem;
    display: inline-block;
    min-width: 80px;
    text-align: center;
}

.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 1rem;
}

.pagination {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
}

.pagination li {
    margin: 0 0.25rem;
}

.pagination li a {
    padding: 0.5rem 1rem;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    color: #007bff;
    text-decoration: none;
}

.pagination li.active a {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.pagination li.disabled a {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const entriesSelect = document.getElementById('entriesSelect');
    
    function updateTable(page = 1) {
        const searchValue = searchInput.value;
        const entriesValue = entriesSelect.value;
        window.location.href = `?search=${encodeURIComponent(searchValue)}&entries=${entriesValue}&page=${page}`;
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

function changePage(page) {
    const searchValue = document.getElementById('searchInput').value;
    const entriesValue = document.getElementById('entriesSelect').value;
    window.location.href = `?search=${encodeURIComponent(searchValue)}&entries=${entriesValue}&page=${page}`;
}
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../js/main.js"></script>
<?php include '../aincludes/footer.php'; ?>