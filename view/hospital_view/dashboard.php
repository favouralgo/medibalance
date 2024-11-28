<?php 

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



include '../includes/header.php'; 

require_once("../../controllers/invoice_controller.php");

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login/login.php');
    exit;
}

$invoiceController = new InvoiceController();
$user_id = $_SESSION['user_id'];

try {
    // Get statistics
    $stats = $invoiceController->get_user_statistics_ctr($user_id);
    
    // Get recent invoices
    $recent_invoices = $invoiceController->get_recent_invoices_ctr($user_id, 4);
} catch (Exception $e) {
    // Handle error appropriately
    $error_message = $e->getMessage();
}
?>


<!-- Dashboard Content -->
<div class="container-fluid">
    <div class="row">
        <!-- Sales Amount -->
        <div class="col-xl-4 col-md-8">
            <div class="metric-card sales">
                <div class="metric-content">
                    <div class="metric-info">
                    <div class="metric-value">GHS <?php echo number_format($stats['total_revenue'] ?? 0, 2); ?></div>
                    <div class="metric-label">Sales Amount</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Invoices -->
        <div class="col-xl-4 col-md-8">
            <div class="metric-card invoices">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value"><?php echo $stats['total_invoices'] ?? 0; ?></div>
                        <div class="metric-label">Total Invoices</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Bills -->
        <div class="col-xl-4 col-md-8">
            <div class="metric-card pending">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value"><?php echo $stats['pending_bills'] ?? 0; ?></div>
                    <div class="metric-label">Pending Bills</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Due Amount -->
        <div class="col-xl-4 col-md-8">
            <div class="metric-card due">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value">GHS <?php echo number_format($stats['due_amount'] ?? 0, 2); ?></div>
                        <div class="metric-label">Due Amount</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="col-xl-4 col-md-8">
            <div class="metric-card products">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value"><?php echo $stats['total_products'] ?? 0; ?></div>
                        <div class="metric-label">Total Products</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-box"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Customers -->
        <div class="col-xl-4 col-md-8">
            <div class="metric-card customers">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value"><?php echo $stats['total_customers'] ?? 0; ?></div>
                        <div class="metric-label">Total Patients</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Paid Bills -->
        <div class="col-xl-4 col-md-8">
            <div class="metric-card paid">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value"><?php echo $stats['paid_bills'] ?? 0; ?></div>
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
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_invoices)): ?>
                                <?php foreach ($recent_invoices as $invoice): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($invoice['invoice_number']); ?></td>
                                        <td><?php echo htmlspecialchars($invoice['customer_name']); ?></td>
                                        <td>GHS <?php echo number_format($invoice['invoice_total'], 2); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($invoice['invoice_date_start'])); ?></td>
                                        <td>
                                            <span class="badge <?php echo $invoice['status_name'] === 'PAID' ? 'bg-success' : 'bg-warning'; ?>">
                                                <?php echo htmlspecialchars($invoice['status_name']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button onclick="viewInvoiceDetails(<?php echo $invoice['invoice_id']; ?>)" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No recent invoices found</td>
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
                    <?php 
                    $activities = $invoiceController->get_recent_activities_ctr($user_id);
                    foreach ($activities as $activity): 
                    ?>
                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon bg-light rounded-circle p-2 me-3">
                                <?php if ($activity['activity_type'] === 'invoice_created'): ?>
                                    <i class="fas fa-plus text-success"></i>
                                <?php else: ?>
                                    <i class="fas fa-check text-primary"></i>
                                <?php endif; ?>
                            </div>
                            <div class="activity-content">
                                <div class="fw-bold">
                                    <?php echo $activity['activity_type'] === 'invoice_created' ? 
                                          'New Invoice Created' : 'Payment Received'; ?>
                                </div>
                                <div class="text-muted small">
                                    <?php if ($activity['activity_type'] === 'invoice_created'): ?>
                                        Invoice #<?php echo htmlspecialchars($activity['invoice_number']); ?> 
                                        for <?php echo htmlspecialchars($activity['customer_name']); ?>
                                    <?php else: ?>
                                        GHS <?php echo number_format($activity['amount'], 2); ?> 
                                        from <?php echo htmlspecialchars($activity['customer_name']); ?>
                                    <?php endif; ?>
                                </div>
                                <div class="text-muted smaller">
                                    <?php echo timeAgo($activity['activity_time']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Statistics -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Monthly Statistics</h5>
            </div>
            <div class="card-body">
                <?php $monthly_stats = $invoiceController->get_monthly_statistics_ctr($user_id); ?>
                <div class="stats-item mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Total Sales</span>
                        <span><?php echo $monthly_stats['sales_percentage']; ?>%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" 
                             style="width: <?php echo $monthly_stats['sales_percentage']; ?>%"></div>
                    </div>
                </div>
                <div class="stats-item mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Paid Invoices</span>
                        <span><?php echo $monthly_stats['paid_percentage']; ?>%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" 
                             style="width: <?php echo $monthly_stats['paid_percentage']; ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for invoice -->
<div class="modal fade" id="viewInvoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Invoice Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="invoiceDetails">
                    <!-- Invoice details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewInvoiceDetails(invoiceId) {
    // Show loading state
    const modalBody = document.getElementById('invoiceDetails');
    modalBody.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('viewInvoiceModal'));
    modal.show();

    // Fetch invoice details
    fetch(`../../actions/get_invoice_action.php?id=${invoiceId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const invoice = data.data;
                let products = '';
                
                // Build products table
                if (invoice.products && invoice.products.length > 0) {
                    products = `
                        <table class="table table-sm mt-3">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Description</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${invoice.products.map(product => `
                                    <tr>
                                        <td>${product.invoiceproduct_name}</td>
                                        <td>${product.invoiceproduct_description || '-'}</td>
                                        <td>${product.invoiceproduct_quantity}</td>
                                        <td>GHS ${Number(product.invoiceproduct_price).toFixed(2)}</td>
                                        <td>GHS ${Number(product.invoiceproduct_subtotal).toFixed(2)}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    `;
                }

                // Update modal content
                modalBody.innerHTML = `
                    <div class="invoice-header d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <h4 class="mb-1">Invoice #${invoice.invoice_number}</h4>
                            <p class="text-muted mb-0">Facility: ${invoice.facility_name}</p>
                        </div>
                        <span class="badge ${invoice.status_name === 'PAID' ? 'bg-success' : 'bg-warning'} fs-6">
                            ${invoice.status_name}
                        </span>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Issue Date</h6>
                            <p>${new Date(invoice.invoice_date_start).toLocaleDateString()}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Due Date</h6>
                            <p>${new Date(invoice.invoice_date_due).toLocaleDateString()}</p>
                        </div>
                    </div>

                    <div class="products-section">
                        <h6>Products/Services</h6>
                        ${products}
                    </div>

                    <div class="invoice-summary mt-4">
                        <div class="row justify-content-end">
                            <div class="col-md-5">
                                <table class="table table-sm">
                                    <tr>
                                        <td>Subtotal</td>
                                        <td class="text-end">GHS ${Number(invoice.invoice_subtotal).toFixed(2)}</td>
                                    </tr>
                                    ${Number(invoice.invoice_discount) > 0 ? `
                                        <tr>
                                            <td>Discount</td>
                                            <td class="text-end">GHS ${Number(invoice.invoice_discount).toFixed(2)}</td>
                                        </tr>
                                    ` : ''}
                                    ${Number(invoice.invoice_vat) > 0 ? `
                                        <tr>
                                            <td>VAT</td>
                                            <td class="text-end">GHS ${Number(invoice.invoice_vat).toFixed(2)}</td>
                                        </tr>
                                    ` : ''}
                                    <tr class="fw-bold">
                                        <td>Total</td>
                                        <td class="text-end">GHS ${Number(invoice.invoice_total).toFixed(2)}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                modalBody.innerHTML = '<div class="alert alert-danger">Failed to load invoice details</div>';
            }
        })
        .catch(error => {
            modalBody.innerHTML = '<div class="alert alert-danger">Error loading invoice details</div>';
            console.error('Error:', error);
        });
}
</script>


<style>
.invoice-header {
    border-bottom: 1px solid #eee;
    padding-bottom: 1rem;
}

.products-section {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
}

.invoice-summary {
    border-top: 1px solid #eee;
    padding-top: 1rem;
}

#viewInvoiceModal .modal-body {
    padding: 2rem;
}

.badge.fs-6 {
    font-size: 1rem !important;
    padding: 0.5rem 1rem;
}
</style>

<?php include '../includes/footer.php'; ?>