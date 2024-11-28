<?php
include '../pincludes/header.php';
require_once("../../controllers/invoice_controller.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verify customer is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: ../../login/login.php");
    exit();
}

$invoiceController = new InvoiceController();

// Get customer's invoices
$result = $invoiceController->get_customer_invoices_ctr($_SESSION['customer_id']);
$invoices = $result['success'] ? $result['data'] : [];

// Calculate dashboard metrics
$total_invoices = count($invoices);
$total_paid = 0;
$total_pending = 0;
$total_amount_paid = 0;
$total_amount_due = 0;
$total_products = [];

foreach ($invoices as $invoice) {
    if ($invoice['status_name'] === 'PAID') {
        $total_paid++;
        $total_amount_paid += $invoice['invoice_total'];
    } else {
        $total_pending++;
        $total_amount_due += $invoice['invoice_total'];
    }
}

function timeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    if ($diff->d == 0) {
        if ($diff->h == 0) {
            if ($diff->i == 0) {
                return "just now";
            }
            return $diff->i . " minutes ago";
        }
        return $diff->h . " hours ago";
    }
    if ($diff->d == 1) {
        return "yesterday";
    }
    return $diff->d . " days ago";
}
?>

<!-- Dashboard Content -->
<div class="container-fluid">
    <div class="row">
        <!-- Sales Amount -->
        <div class="col-xl-3 col-md-6">
            <div class="metric-card sales">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value">GHS <?php echo number_format($total_amount_paid, 2); ?></div>
                        <div class="metric-label">Amount paid</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Invoices -->
        <div class="col-xl-3 col-md-6">
            <div class="metric-card invoices">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value"><?php echo $total_invoices; ?></div>
                        <div class="metric-label">Total Bills</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Bills -->
        <div class="col-xl-3 col-md-6">
            <div class="metric-card pending">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value"><?php echo $total_pending; ?></div>
                        <div class="metric-label">Bills to pay</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Due Amount -->
        <div class="col-xl-3 col-md-6">
            <div class="metric-card due">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value">GHS <?php echo number_format($total_amount_due, 2); ?></div>
                        <div class="metric-label">Amount To Pay</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paid Bills -->
        <div class="col-xl-3 col-md-6">
            <div class="metric-card paid">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value"><?php echo $total_paid; ?></div>
                        <div class="metric-label">Paid Bills</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Invoices Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Recent Invoices</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Invoice #</th>
                                <th>Amount</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($invoices)): ?>
                                <?php foreach (array_slice($invoices, 0, 5) as $invoice): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($invoice['invoice_number']); ?></td>
                                        <td>GHS <?php echo number_format($invoice['invoice_total'], 2); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($invoice['invoice_date_start'])); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($invoice['invoice_date_due'])); ?></td>
                                        <td>
                                            <span class="badge <?php echo $invoice['status_name'] === 'PAID' ? 'bg-success' : 'bg-warning'; ?>">
                                                <?php echo htmlspecialchars($invoice['status_name']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button onclick="viewInvoiceDetails(<?php echo $invoice['invoice_id']; ?>)" 
                                                    class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <?php if ($invoice['status_name'] !== 'PAID'): ?>
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
                                    <td colspan="6" class="text-center">No invoices found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Activity and Stats Section -->
<div class="row mt-4">
    <!-- Recent Activity -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                <div class="activity-list">
                    <?php foreach (array_slice($invoices, 0, 5) as $invoice): ?>
                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon bg-light rounded-circle p-2 me-3">
                                <?php if ($invoice['status_name'] === 'PAID'): ?>
                                    <i class="fas fa-check text-success"></i>
                                <?php else: ?>
                                    <i class="fas fa-file-invoice text-primary"></i>
                                <?php endif; ?>
                            </div>
                            <div class="activity-content">
                                <div class="fw-bold">
                                    <?php echo $invoice['status_name'] === 'PAID' ? 'Payment Made' : 'Invoice Created'; ?>
                                </div>
                                <div class="text-muted small">
                                    <?php echo htmlspecialchars($invoice['invoice_number']); ?> - 
                                    GHS <?php echo number_format($invoice['invoice_total'], 2); ?>
                                </div>
                                <div class="text-muted smaller">
                                    <?php echo timeAgo($invoice['created_at']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Payment Statistics</h5>
            </div>
            <div class="card-body">
                <div class="stats-item mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Paid Invoices</span>
                        <span><?php echo $total_invoices > 0 ? round(($total_paid / $total_invoices) * 100) : 0; ?>%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" 
                             style="width: <?php echo $total_invoices > 0 ? ($total_paid / $total_invoices) * 100 : 0; ?>%">
                        </div>
                    </div>
                </div>
                
                <div class="stats-item mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Amount Paid</span>
                        <span><?php 
                            $total = $total_amount_paid + $total_amount_due;
                            echo $total > 0 ? round(($total_amount_paid / $total) * 100) : 0;
                        ?>%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" 
                             style="width: <?php echo $total > 0 ? ($total_amount_paid / $total) * 100 : 0; ?>%">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Details Modal -->
<div class="modal fade" id="viewInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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