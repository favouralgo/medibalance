function viewInvoiceDetails(invoiceId) {
    // Show loading state
    const modalBody = document.getElementById('invoiceDetails');
    modalBody.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('viewInvoiceModal'));
    modal.show();

    // Fetch invoice details
    fetch(`../../actions/patient_invoice_action.php?id=${invoiceId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.data) {
                const invoice = data.data;
                let products = '';
                
                // Build products table
                if (invoice.products && invoice.products.length > 0) {
                    products = `
                        <div class="table-responsive">
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
                                            <td>${escapeHtml(product.invoiceproduct_name)}</td>
                                            <td>${escapeHtml(product.invoiceproduct_description || '-')}</td>
                                            <td>${product.invoiceproduct_quantity}</td>
                                            <td>GHS ${formatAmount(product.invoiceproduct_price)}</td>
                                            <td>GHS ${formatAmount(product.invoiceproduct_subtotal)}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>
                    `;
                }

                // Update modal content with formatting
                modalBody.innerHTML = `
                    <div class="invoice-details-container">
                        <div class="invoice-header d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h4 class="mb-1">Invoice #${escapeHtml(invoice.invoice_number)}</h4>
                                <p class="text-muted mb-0">Facility: ${escapeHtml(invoice.facility_name)}</p>
                            </div>
                            <span class="badge ${invoice.status_name === 'PAID' ? 'bg-success' : 'bg-warning'} fs-6">
                                ${escapeHtml(invoice.status_name)}
                            </span>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>Issue Date</h6>
                                <p>${formatDate(invoice.invoice_date_start)}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Due Date</h6>
                                <p>${formatDate(invoice.invoice_date_due)}</p>
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
                                            <td class="text-end">GHS ${formatAmount(invoice.invoice_subtotal)}</td>
                                        </tr>
                                        ${parseFloat(invoice.invoice_discount) > 0 ? `
                                            <tr>
                                                <td>Discount</td>
                                                <td class="text-end">GHS ${formatAmount(invoice.invoice_discount)}</td>
                                            </tr>
                                        ` : ''}
                                        ${parseFloat(invoice.invoice_vat) > 0 ? `
                                            <tr>
                                                <td>VAT</td>
                                                <td class="text-end">GHS ${formatAmount(invoice.invoice_vat)}</td>
                                            </tr>
                                        ` : ''}
                                        <tr class="fw-bold">
                                            <td>Total</td>
                                            <td class="text-end">GHS ${formatAmount(invoice.invoice_total)}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        ${invoice.status_name !== 'PAID' ? `
                            <div class="text-end mt-3">
                                <a href="pay_invoice.php?id=${invoice.invoice_id}" 
                                   class="btn btn-success">
                                    <i class="fas fa-credit-card me-2"></i>Pay Now
                                </a>
                            </div>
                        ` : ''}
                    </div>
                `;
            } else {
                modalBody.innerHTML = `
                    <div class="alert alert-danger">
                        ${escapeHtml(data.message || 'Failed to load invoice details')}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalBody.innerHTML = '<div class="alert alert-danger">Error loading invoice details. Please try again.</div>';
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