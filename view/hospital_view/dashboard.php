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
        <div class="col-xl-3 col-md-6">
            <div class="metric-card sales">
                <div class="metric-content">
                    <div class="metric-info">
                    <div class="metric-value">$<?php echo number_format($stats['total_revenue'] ?? 0, 2); ?></div>
                    <div class="metric-label">Sales Amount</div>
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
        <div class="col-xl-3 col-md-6">
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
        <div class="col-xl-3 col-md-6">
            <div class="metric-card due">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value">$<?php echo number_format($stats['due_amount'] ?? 0, 2); ?></div>
                        <div class="metric-label">Due Amount</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Products -->
        <div class="col-xl-3 col-md-6">
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
        <div class="col-xl-3 col-md-6">
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
        <div class="col-xl-3 col-md-6">
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
                                        <td>$<?php echo number_format($invoice['invoice_total'], 2); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($invoice['invoice_date_start'])); ?></td>
                                        <td>
                                            <span class="badge <?php echo $invoice['status_name'] === 'PAID' ? 'bg-success' : 'bg-warning'; ?>">
                                                <?php echo htmlspecialchars($invoice['status_name']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="view_invoice.php?id=<?php echo $invoice['invoice_id']; ?>" 
                                               class="btn btn-sm btn-primary">View</a>
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
                                        $<?php echo number_format($activity['amount'], 2); ?> 
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

<?php include '../includes/footer.php'; ?>