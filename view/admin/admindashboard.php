<?php 
include '../aincludes/header.php';
require_once("../../controllers/admin_controller.php");

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../login/login.php');
    exit;
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

$adminController = new AdminController();

try {
    // Get dashboard statistics
    $stats = $adminController->get_dashboard_statistics_ctr();
    
    // Get recent registrations
    $recent_registrations = $adminController->get_recent_registrations_ctr();
    
    // Get recent invoices
    $recent_invoices = $adminController->get_recent_invoices_ctr();
    
    // Get recent activities
    $recent_activities = $adminController->get_recent_activities_ctr();
} catch (Exception $e) {
    error_log($e->getMessage());
    $stats = [];
    $recent_registrations = [];
    $recent_invoices = [];
    $recent_activities = [];
}
?>

<!-- Dashboard Content -->
<div class="container-fluid">
    <div class="row">
        <!-- Sales Amount -->
        <div class="col-xl-4 col-md-8">
            <div class="metric-card revenue">
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
                        <div class="metric-label">Total Pending Bills</div>
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
                        <div class="metric-label">Total Due Amount</div>
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
                        <div class="metric-label">Total Paid Bills</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Facilities -->
        <div class="col-xl-4 col-md-8">
            <div class="metric-card facilities">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value"><?php echo $stats['total_facilities'] ?? 0; ?></div>
                        <div class="metric-label">Total Facilities</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-hospital"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approved users -->
        <div class="col-xl-4 col-md-8">
            <div class="metric-card approved">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value"><?php echo $stats['approved_users'] ?? 0; ?></div>
                        <div class="metric-label">Approved Users</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending users -->
        <div class="col-xl-4 col-md-8">
            <div class="metric-card pending-users">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value"><?php echo $stats['pending_users'] ?? 0; ?></div>
                        <div class="metric-label">Pending Users</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-user-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected users -->
        <div class="col-xl-4 col-md-8">
            <div class="metric-card rejected">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value"><?php echo $stats['rejected_users'] ?? 0; ?></div>
                        <div class="metric-label">Rejected Users</div>
                    </div>
                    <div class="metric-icon">
                        <i class="fas fa-user-times"></i>
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

<!-- Recent Registrations -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Registrations</h5>
                <a href="manage_users.php" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Facility Name</th>
                                <th>User Name</th>
                                <th>Email</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th> 
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_registrations)): ?>
                                <?php foreach ($recent_registrations as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['user_facilityname']); ?></td>
                                        <td><?php echo htmlspecialchars($user['user_firstname'] . ' ' . $user['user_lastname']); ?></td>
                                        <td><?php echo htmlspecialchars($user['user_email']); ?></td>
                                        <td><?php echo timeAgo($user['created_at']); ?></td>
                                        <td>
                                            <span class="badge <?php 
                                                echo $user['is_approved'] === 'APPROVED' ? 'bg-success' : 
                                                    ($user['is_approved'] === 'REJECTED' ? 'bg-danger' : 'bg-warning'); ?>">
                                                <?php echo htmlspecialchars($user['is_approved']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($user['is_approved'] === 'NOT_APPROVED'): ?>
                                                <button onclick="approveUser(<?php echo $user['user_id']; ?>)" 
                                                        class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                                <button onclick="rejectUser(<?php echo $user['user_id']; ?>)" 
                                                        class="btn btn-sm btn-danger">
                                                    <i class="fas fa-times"></i> Reject
                                                </button>
                                            <?php endif; ?>
                                            <button onclick="viewUserDetails(<?php echo $user['user_id']; ?>)" 
                                                    class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No recent registrations</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                <div class="activity-list">
                    <?php if (!empty($recent_activities)): ?>
                        <?php foreach ($recent_activities as $activity): ?>
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
                    <?php else: ?>
                        <div class="text-center text-muted">No recent activities</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for User Details -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="userDetails">
                    <!-- User details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to load invoice details in the modal
function viewInvoiceDetails(invoiceId) {
    // Show loading state
    const modalBody = document.getElementById('invoiceDetails');
    modalBody.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('viewInvoiceModal'));
    modal.show();

    // Fetch invoice details
    fetch(`../../actions/admin_invoice_action.php?id=${invoiceId}`)
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

// Function to load user details in the modal
function viewUserDetails(userId) {
    // Show loading state
    const modalBody = document.getElementById('userDetails');
    modalBody.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('viewUserModal'));
    modal.show();

    // Fetch user details
    fetch(`../../actions/get_user_details_action.php?id=${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.data;
                
                // Update modal content
                modalBody.innerHTML = `
                    <div class="user-details-content">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0">${user.user_firstname} ${user.user_lastname}</h4>
                                <span class="badge ${user.is_approved === 'APPROVED' ? 'bg-success' : 'bg-warning'}">
                                    ${user.is_approved}
                                </span>
                            </div>
                            <p class="text-muted mb-0">Facility: ${user.user_facilityname}</p>
                        </div>

                        <div class="info-group mb-3">
                            <h6 class="fw-bold">Contact Information</h6>
                            <div class="ms-3">
                                <p class="mb-2">
                                    <i class="fas fa-envelope me-2"></i>
                                    ${user.user_email}
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-phone me-2"></i>
                                    ${user.user_phonenumber}
                                </p>
                            </div>
                        </div>

                        <div class="info-group mb-3">
                            <h6 class="fw-bold">Location</h6>
                            <div class="ms-3">
                                <p class="mb-2">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    ${user.user_address}
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-city me-2"></i>
                                    ${user.user_city}, ${user.user_country}
                                </p>
                            </div>
                        </div>

                        <div class="info-group">
                            <h6 class="fw-bold">Account Information</h6>
                            <div class="ms-3">
                                <p class="mb-2">
                                    <i class="fas fa-calendar me-2"></i>
                                    Registered: ${new Date(user.created_at).toLocaleDateString()}
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-clock me-2"></i>
                                    Last Updated: ${new Date(user.updated_at).toLocaleDateString()}
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                modalBody.innerHTML = '<div class="alert alert-danger">Failed to load user details</div>';
            }
        })
        .catch(error => {
            modalBody.innerHTML = '<div class="alert alert-danger">Error loading user details</div>';
            console.error('Error:', error);
        });
}



function approveUser(userId) {
    Swal.fire({
        title: 'Approve User?',
        text: "Are you sure you want to approve this user?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, approve!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../../actions/approve_user_action.php',
                type: 'POST',
                data: {
                    user_id: userId,
                    admin_id: <?php echo $_SESSION['admin_id']; ?>
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Approved!', 'User has been approved.', 'success')
                        .then(() => location.reload());
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to approve user', 'error');
                }
            });
        }
    });
}

function rejectUser(userId) {
    Swal.fire({
        title: 'Reject User?',
        text: "Are you sure you want to reject this user?",
        icon: 'warning',
        input: 'textarea',
        inputLabel: 'Reason for rejection',
        inputPlaceholder: 'Enter reason for rejection...',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, reject!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../../actions/reject_user_action.php',
                type: 'POST',
                data: {
                    user_id: userId,
                    admin_id: <?php echo $_SESSION['admin_id']; ?>,
                    comment: result.value
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Rejected!', 'User has been rejected.', 'success')
                        .then(() => location.reload());
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Failed to reject user', 'error');
                }
            });
        }
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

.user-details-content {
    padding: 1rem;
}

.info-group {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.info-group:last-child {
    margin-bottom: 0;
}

.info-group h6 {
    color: #495057;
    margin-bottom: 0.5rem;
}

.info-group p {
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.info-group i {
    width: 20px;
    color: #495057;
}

.metric-card {
    padding: 1.5rem;
    border-radius: 0.75rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.metric-card.revenue { background: linear-gradient(45deg, #4CAF50, #45a049); }
.metric-card.invoices { background: linear-gradient(45deg, #2196F3, #1976D2); }
.metric-card.pending { background: linear-gradient(45deg, #FFC107, #FFA000); }
.metric-card.due { background: linear-gradient(45deg, #F44336, #D32F2F); }
.metric-card.products { background: linear-gradient(45deg, #9C27B0, #7B1FA2); }
.metric-card.customers { background: linear-gradient(45deg, #3F51B5, #303F9F); }
.metric-card.facilities { background: linear-gradient(45deg, #009688, #00796B); }
.metric-card.approved { background: linear-gradient(45deg, #8BC34A, #689F38); }
.metric-card.pending-users { background: linear-gradient(45deg, #FF9800, #F57C00); }
.metric-card.rejected { background: linear-gradient(45deg, #E91E63, #C2185B); }

.metric-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
}

.metric-value {
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.metric-label {
    font-size: 1rem;
    opacity: 0.9;
}

.metric-icon {
    font-size: 2rem;
    opacity: 0.8;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../js/main.js"></script>

<?php include '../aincludes/footer.php'; ?>