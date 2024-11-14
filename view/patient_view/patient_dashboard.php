<?php include '../includes/header.php'; ?>

<!-- Dashboard Content -->
<div class="container-fluid">
    <div class="row">
        <!-- Sales Amount -->
        <div class="col-xl-3 col-md-6">
            <div class="metric-card sales">
                <div class="metric-content">
                    <div class="metric-info">
                        <div class="metric-value">$1,650</div>
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
                        <div class="metric-value">12</div>
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
                        <div class="metric-value">8</div>
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
                        <div class="metric-value">$1,708</div>
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
                        <div class="metric-value">8</div>
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
                        <div class="metric-value">10</div>
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
                        <div class="metric-value">4</div>
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
                            <tr>
                                <td>INV-2024-001</td>
                                <td>John Doe</td>
                                <td>$350.00</td>
                                <td>2024-02-15</td>
                                <td><span class="badge bg-success">Paid</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td>INV-2024-002</td>
                                <td>Jane Smith</td>
                                <td>$520.00</td>
                                <td>2024-02-14</td>
                                <td><span class="badge bg-warning">Pending</span></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                            <!-- Add more rows as needed -->
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
                    <div class="activity-item d-flex align-items-center mb-3">
                        <div class="activity-icon bg-light rounded-circle p-2 me-3">
                            <i class="fas fa-plus text-success"></i>
                        </div>
                        <div class="activity-content">
                            <div class="fw-bold">New Invoice Created</div>
                            <div class="text-muted small">Invoice #INV-2024-001 for John Doe</div>
                            <div class="text-muted smaller">2 hours ago</div>
                        </div>
                    </div>
                    <div class="activity-item d-flex align-items-center mb-3">
                        <div class="activity-icon bg-light rounded-circle p-2 me-3">
                            <i class="fas fa-check text-primary"></i>
                        </div>
                        <div class="activity-content">
                            <div class="fw-bold">Payment Received</div>
                            <div class="text-muted small">$520.00 from Jane Smith</div>
                            <div class="text-muted smaller">5 hours ago</div>
                        </div>
                    </div>
                    <!-- Add more activity items as needed -->
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Monthly Statistics</h5>
            </div>
            <div class="card-body">
                <div class="stats-item mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Total Sales</span>
                        <span>70%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: 70%"></div>
                    </div>
                </div>
                <div class="stats-item mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span>Paid Invoices</span>
                        <span>85%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" style="width: 85%"></div>
                    </div>
                </div>
                <!-- More stats as needed -->
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>