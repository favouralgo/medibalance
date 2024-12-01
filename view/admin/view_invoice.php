<?php 
include '../aincludes/header.php';
require_once("../../controllers/invoice_controller.php");

$invoiceController = new InvoiceController();
$search = isset($_GET['search']) ? $_GET['search'] : '';
$entries = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;

$result = $invoiceController->get_all_invoices_ctr($search, $entries);

if (!$result['success']) {
    echo "<div class='alert alert-danger'>{$result['message']}</div>";
}
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

    <table class="product-table">
        <thead>
            <tr>
                <th>Invoice Number <i class="fas fa-sort sort-icon"></i></th>
                <th>Customer <i class="fas fa-sort sort-icon"></i></th>
                <th>Issue Date <i class="fas fa-sort sort-icon"></i></th>
                <th>Due Date <i class="fas fa-sort sort-icon"></i></th>
                <th>Status <i class="fas fa-sort sort-icon"></i></th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result['success'] && !empty($result['data'])): ?>
                <?php foreach ($result['data'] as $invoice): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($invoice['invoice_number']); ?></td>
                        <td><?php echo htmlspecialchars($invoice['customer_name']); ?></td>
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
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No invoices found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

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
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../js/main.js"></script>
<?php include '../aincludes/footer.php'; ?>