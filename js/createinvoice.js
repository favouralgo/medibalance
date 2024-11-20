document.addEventListener('DOMContentLoaded', function() {
    // Initialize formatter for currency
    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // Generate invoice number
    function generateInvoiceNumber() {
        const facilityPrefix = 'MED'; // Fetch facility first three prefix with ID from session
        const year = new Date().getFullYear().toString().slice(-2);
        const randomNum = Math.floor(Math.random() * 1000000000).toString().padStart(9, '0');
        return `${facilityPrefix}/${year}/${randomNum}`;
    }

    // Set initial invoice number
    const invoiceNumberInput = document.getElementById('invoiceNumber');
    if (invoiceNumberInput) {
        invoiceNumberInput.value = generateInvoiceNumber();
    }

    // Set initial dates
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const startDateInput = document.getElementById('startDate');
    const dueDateInput = document.getElementById('dueDate');

    if (startDateInput) {
        startDateInput.valueAsDate = today;
        startDateInput.min = today.toISOString().split('T')[0];
        
        startDateInput.addEventListener('change', function() {
            const startDate = new Date(this.value);
            startDate.setHours(0, 0, 0, 0);
            const dueDate = dueDateInput.valueAsDate;
            
            if (dueDate < startDate) {
                dueDateInput.valueAsDate = startDate;
            }
        });
    }

    if (dueDateInput) {
        const dueDate = new Date(today);
        dueDate.setDate(dueDate.getDate() + 30);
        dueDateInput.valueAsDate = dueDate;
        dueDateInput.min = today.toISOString().split('T')[0];

        dueDateInput.addEventListener('change', function() {
            const startDate = startDateInput.valueAsDate;
            const dueDate = new Date(this.value);
            dueDate.setHours(0, 0, 0, 0);
            
            if (dueDate < startDate) {
                Swal.fire({
                    title: 'Invalid Date',
                    text: 'Due date cannot be before start date',
                    icon: 'error'
                });
                this.valueAsDate = startDate;
            }
        });
    }

    // Customer Details Handler
    window.showCustomerDetails = function(customerId) {
        const detailsDiv = document.getElementById('customerDetails');
        if (!customerId) {
            if (detailsDiv) {
                detailsDiv.style.display = 'none';
            }
            return;
        }

        fetch(`../../actions/get_customer_details.php?customer_id=${customerId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.customer) {
                    const fields = {
                        'customerName': `${data.customer.customer_firstname} ${data.customer.customer_lastname}`,
                        'customerEmail': data.customer.customer_email,
                        'customerAddress': data.customer.customer_address,
                        'customerCity': data.customer.customer_city,
                        'customerCountry': data.customer.customer_country,
                        'customerPhone': data.customer.customer_phonenumber
                    };

                    Object.entries(fields).forEach(([id, value]) => {
                        const element = document.getElementById(id);
                        if (element) {
                            element.value = value || '';
                        }
                    });

                    if (detailsDiv) {
                        detailsDiv.style.display = 'block';
                    }
                } else {
                    throw new Error(data.message || 'Failed to fetch customer details');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to fetch customer details',
                    icon: 'error'
                });
                if (detailsDiv) {
                    detailsDiv.style.display = 'none';
                }
            });
    };

    // Add customer select change event listener
    const customerSelect = document.getElementById('customerSelect');
    if (customerSelect) {
        customerSelect.addEventListener('change', function() {
            showCustomerDetails(this.value);
        });
    }

    // Handle product selection
    window.updateProductDetails = function(select) {
        if (!select) return;
        
        const row = select.closest('tr');
        if (!row) return;

        const priceInput = row.querySelector('.inv-price-input');
        if (!priceInput) return;

        if (select.value) {
            try {
                const productData = JSON.parse(select.value);
                priceInput.value = productData.product_price || '';
                updateRowCalculations(row);
            } catch (error) {
                console.error('Error parsing product data:', error);
                priceInput.value = '';
            }
        } else {
            priceInput.value = '';
        }
        updateTotalCalculations();
    };

    // Handle quantity updates
    window.updateProductQuantity = function(input) {
        if (!input) return;
        const row = input.closest('tr');
        if (row) {
            updateRowCalculations(row);
        }
    };

    // Create new service row
    function createServiceRow() {
        const templateSelect = document.querySelector('.inv-product-select');
        if (!templateSelect) return null;

        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td class="inv-product-cell">
                <select class="inv-form-input inv-product-select" onchange="updateProductDetails(this)">
                    ${templateSelect.innerHTML}
                </select>
            </td>
            <td>
                <input type="number" class="inv-form-input inv-qty-input" value="1" min="1" onchange="updateProductQuantity(this)">
            </td>
            <td>
                <input type="number" class="inv-form-input inv-price-input" placeholder="0.00" step="0.01" readonly>
            </td>
            <td>
                <input type="number" class="inv-form-input inv-discount-input" placeholder="0" min="0" max="100" oninput="updateRowCalculations(this.closest('tr'))">
            </td>
            <td class="inv-subtotal">
                <span>${formatter.format(0)}</span>
            </td>
            <td class="inv-action-cell">
                <button class="inv-remove-row-btn" title="Remove Service">×</button>
            </td>
        `;
        return newRow;
    }

    // Add new service row handler
    const addServiceBtn = document.getElementById('addServiceBtn');
    if (addServiceBtn) {
        addServiceBtn.addEventListener('click', function() {
            const serviceTableBody = document.getElementById('inv-serviceTableBody');
            const newRow = createServiceRow();
            if (serviceTableBody && newRow) {
                serviceTableBody.appendChild(newRow);
            }
        });
    }

    // Remove row handler
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('inv-remove-row-btn')) {
            const row = e.target.closest('tr');
            const tbody = row?.closest('tbody');
            if (tbody && tbody.children.length > 1) {
                row.remove();
                updateTotalCalculations();
            }
        }
    });

    // Calculate row totals
    function updateRowCalculations(row) {
        if (!row) return;

        const qtyInput = row.querySelector('.inv-qty-input');
        const priceInput = row.querySelector('.inv-price-input');
        const discountInput = row.querySelector('.inv-discount-input');
        const subtotalSpan = row.querySelector('.inv-subtotal span');

        if (!qtyInput || !priceInput || !discountInput || !subtotalSpan) return;

        const qty = parseFloat(qtyInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        
        // Calculate subtotal with discount
        const subtotalBeforeDiscount = qty * price;
        const discountAmount = subtotalBeforeDiscount * (discount / 100);
        const finalSubtotal = subtotalBeforeDiscount - discountAmount;
        
        subtotalSpan.textContent = formatter.format(finalSubtotal);
        updateTotalCalculations();
    }

    // Calculate overall totals
    function updateTotalCalculations() {
        let subtotal = 0;
        let totalDiscount = 0;

        document.querySelectorAll('#inv-serviceTableBody tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.inv-qty-input')?.value) || 0;
            const price = parseFloat(row.querySelector('.inv-price-input')?.value) || 0;
            const discount = parseFloat(row.querySelector('.inv-discount-input')?.value) || 0;
            
            const rowSubtotal = qty * price;
            const rowDiscount = rowSubtotal * (discount / 100);
            
            subtotal += rowSubtotal;
            totalDiscount += rowDiscount;
        });

        const transactionFee = (subtotal - totalDiscount) * 0.01; // Apply fee after discount
        const finalTotal = subtotal - totalDiscount + transactionFee;

        // Update displays
        const displayElements = {
            'subtotal-display': subtotal,
            'discount-display': totalDiscount,
            'fee-display': transactionFee,
            'total-display': finalTotal
        };

        Object.entries(displayElements).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = formatter.format(value);
            }
        });
    }

    // Add input event listeners for all input fields
    const serviceTableBody = document.getElementById('inv-serviceTableBody');
    if (serviceTableBody) {
        serviceTableBody.addEventListener('input', function(e) {
            if (e.target.classList.contains('inv-qty-input') || 
                e.target.classList.contains('inv-price-input') || 
                e.target.classList.contains('inv-discount-input')) {
                updateRowCalculations(e.target.closest('tr'));
            }
        });
    }

    // Handle form submission
    const createInvoiceBtn = document.getElementById('createInvoiceBtn');
    if (createInvoiceBtn) {
        createInvoiceBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            
            try {
                // Validate customer selection
                const customerSelect = document.getElementById('customerSelect');
                if (!customerSelect?.value) {
                    throw new Error('Please select a patient');
                }

                // Validate dates
                const startDate = new Date(startDateInput.value);
                const dueDate = new Date(dueDateInput.value);
                startDate.setHours(0, 0, 0, 0);
                dueDate.setHours(0, 0, 0, 0);

                if (startDate < today) {
                    throw new Error('Start date cannot be in the past');
                }

                if (dueDate < startDate) {
                    throw new Error('Due date cannot be before start date');
                }

                // Get all service rows
                const rows = document.querySelectorAll('#inv-serviceTableBody tr');
                if (!rows.length) {
                    throw new Error('Please add at least one service');
                }

                // Format dates for MySQL (YYYY-MM-DD)
                const formatDate = (date) => {
                    return date.toISOString().split('T')[0];
                };

                // Prepare services data
                const services = [];
                for (const row of rows) {
                    const productSelect = row.querySelector('.inv-product-select');
                    if (!productSelect?.value) {
                        throw new Error('Please select a service for all rows');
                    }

                    const product = JSON.parse(productSelect.value);
                    services.push({
                        product: product,
                        quantity: parseFloat(row.querySelector('.inv-qty-input')?.value) || 0,
                        price: parseFloat(row.querySelector('.inv-price-input')?.value) || 0,
                        discount: parseFloat(row.querySelector('.inv-discount-input')?.value) || 0
                    });
                }

                // Prepare invoice data
                const invoiceData = {
                    customer_id: customerSelect.value,
                    invoice_number: invoiceNumberInput.value,
                    start_date: formatDate(startDate),
                    due_date: formatDate(dueDate),
                    subtotal: parseFloat(document.getElementById('subtotal-display')?.textContent.replace(/[^0-9.-]+/g, '')),
                    discount: parseFloat(document.getElementById('discount-display')?.textContent.replace(/[^0-9.-]+/g, '')),
                    transaction_fee: parseFloat(document.getElementById('fee-display')?.textContent.replace(/[^0-9.-]+/g, '')),
                    total: parseFloat(document.getElementById('total-display')?.textContent.replace(/[^0-9.-]+/g, '')),
                    services: services
                };

                // Show loading state
                await Swal.fire({
                    title: 'Creating Invoice...',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Submit the invoice
                const response = await fetch('../actions/invoice_action.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(invoiceData)
                });

                const data = await response.json();

                if (data.success) {
                    await Swal.fire({
                        title: 'Success!',
                        text: 'Invoice created successfully',
                        icon: 'success'
                    });
                    window.location.href = `view_invoice.php?id=${data.invoice_id}`;
                } else {
                    throw new Error(data.message || 'Failed to create invoice');
                }

            } catch (error) {
                console.error('Error:', error);
                await Swal.fire({
                    title: 'Error',
                    text: error.message || 'Failed to create invoice',
                    icon: 'error'
                });
            }
        });
    }

    // Make functions globally available
    window.updateRowCalculations = updateRowCalculations;
    window.updateProductDetails = updateProductDetails;
    window.updateProductQuantity = updateProductQuantity;

    // Initialize calculations
    updateTotalCalculations();
});