function viewInvoiceDetails(invoiceId) {
    $.ajax({
        url: '../../actions/get_invoice_action.php',
        type: 'GET',
        data: { invoice_id: invoiceId },
        dataType: 'json',
        success: function(response) {
            if (response.success && response.invoice) {
                const invoice = response.invoice;
                const detailsHtml = `
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title fw-bold">Invoice Information</h6>
                                <div class="mb-2">
                                    <strong>Invoice Number:</strong><br>
                                    ${escapeHtml(invoice.invoice_number)}
                                </div>
                                <div class="mb-2">
                                    <strong>Facility:</strong><br>
                                    ${escapeHtml(invoice.facility_name)}
                                </div>
                                <div class="mb-2">
                                    <strong>Status:</strong><br>
                                    <span class="badge ${invoice.status_name === 'PAID' ? 'bg-success' : 'bg-warning'}">
                                        ${escapeHtml(invoice.status_name)}
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <strong>Issue Date:</strong><br>
                                    ${formatDate(invoice.invoice_date_start)}
                                </div>
                                <div class="mb-2">
                                    <strong>Due Date:</strong><br>
                                    ${formatDate(invoice.invoice_date_due)}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6 class="card-title fw-bold">Payment Details</h6>
                                <div class="mb-2">
                                    <strong>Subtotal:</strong><br>
                                    $${formatAmount(invoice.invoice_subtotal || 0)}
                                </div>
                                <div class="mb-2">
                                    <strong>Discount:</strong><br>
                                    $${formatAmount(invoice.invoice_discount || 0)}
                                </div>
                                <div class="mb-2">
                                    <strong>Transaction Fee:</strong><br>
                                    $${formatAmount(invoice.invoice_vat || 0)}
                                </div>
                                <div class="mb-2">
                                    <strong>Total Amount:</strong><br>
                                    <span class="fw-bold">$${formatAmount(invoice.invoice_total || 0)}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title fw-bold">Service Details</h6>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Service</th>
                                                <th>Description</th>
                                                <th>Price</th>
                                                <th>Quantity</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${invoice.products.map(product => `
                                                <tr>
                                                    <td>${escapeHtml(product.invoiceproduct_name)}</td>
                                                    <td>${escapeHtml(product.invoiceproduct_description)}</td>
                                                    <td>$${formatAmount(product.invoiceproduct_price)}</td>
                                                    <td>${product.invoiceproduct_quantity}</td>
                                                    <td>$${formatAmount(product.invoiceproduct_subtotal)}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                $('#invoiceDetails').html(detailsHtml);
                const modal = new bootstrap.Modal(document.getElementById('viewInvoiceModal'));
                modal.show();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Unable to load invoice details'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load invoice details. Please try again.'
            });
        }
    });
}

function formatDate(dateString) {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function formatAmount(amount) {
    if (isNaN(amount) || amount === null) return '0.00';
    return parseFloat(amount).toFixed(2);
}

function escapeHtml(unsafe) {
    if (!unsafe) return '';
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}