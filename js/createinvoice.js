// createinvoice.js
document.addEventListener('DOMContentLoaded', function() {
    // Handle date inputs
    const dateInputs = document.querySelectorAll('.inv-date-input');
    
    dateInputs.forEach(input => {
        const placeholder = input.getAttribute('data-placeholder');
        
        // Set initial placeholder text
        if (!input.value) {
            input.type = 'text';
            input.placeholder = placeholder;
        }

        // Handle focus event
        input.addEventListener('focus', function() {
            input.type = 'date';
            input.placeholder = '';
        });

        // Handle blur event
        input.addEventListener('blur', function() {
            if (!input.value) {
                input.type = 'text';
                input.placeholder = placeholder;
            }
        });
    });

    // Handle service table
    const serviceTableBody = document.getElementById('inv-serviceTableBody');
    const addServiceBtn = document.getElementById('addServiceBtn');

    if (!serviceTableBody || !addServiceBtn) {
        console.error('Required elements not found');
        return;
    }

    // Function to create new row
    function createServiceRow() {
        const newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td class="inv-product-cell">
                <input type="text" class="inv-form-input inv-product-input" placeholder="Enter Service Name">
                <a href="#" class="inv-select-product">select a service</a>
            </td>
            <td>
                <input type="number" class="inv-form-input inv-qty-input" value="1">
            </td>
            <td>
                <input type="number" class="inv-form-input inv-price-input" placeholder="0.00" step="0.01">
            </td>
            <td>
                <input type="text" class="inv-form-input inv-discount-input" placeholder="Discount %">
            </td>
            <td class="inv-subtotal">
                <span>$0.00</span>  
            </td>
            <td class="inv-action-cell">
                <button class="inv-remove-row-btn" title="Remove Service">×</button>
            </td>
        `;
        return newRow;
    }

    // Function to handle row removal
    function handleRowRemoval(row) {
        if (serviceTableBody.children.length > 1) {
            row.remove();
        }
    }

    // Add new service row
    addServiceBtn.addEventListener('click', function() {
        const newRow = createServiceRow();
        serviceTableBody.appendChild(newRow);

        // Add event listener to the new remove button
        const removeBtn = newRow.querySelector('.inv-remove-row-btn');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                handleRowRemoval(newRow);
            });
        }
    });

    // Handle existing remove buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('inv-remove-row-btn')) {
            const row = e.target.closest('tr');
            if (row) {
                handleRowRemoval(row);
            }
        }
    });

    // Calculate subtotal when inputs change
    serviceTableBody.addEventListener('input', function(e) {
        if (e.target.classList.contains('inv-qty-input') || 
            e.target.classList.contains('inv-price-input') || 
            e.target.classList.contains('inv-discount-input')) {
            
            const row = e.target.closest('tr');
            if (row) {
                updateRowCalculations(row);
            }
        }
    });

    // Function to update row calculations
    function updateRowCalculations(row) {
        const qty = parseFloat(row.querySelector('.inv-qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.inv-price-input').value) || 0;
        const discount = parseFloat(row.querySelector('.inv-discount-input').value) || 0;
        
        const subtotal = qty * price * (1 - discount/100);
        row.querySelector('.inv-subtotal span').textContent = `$${subtotal.toFixed(2)}`;
        
        updateTotalCalculations();
    }

    // Function to update total calculations
    function updateTotalCalculations() {
        let subtotal = 0;
        let totalDiscount = 0;

        // Calculate subtotal and total discount
        document.querySelectorAll('.inv-subtotal span').forEach(span => {
            const value = parseFloat(span.textContent.replace('$', '')) || 0;
            subtotal += value;
        });

        // Update displayed totals
        document.querySelector('.inv-total-row.subtotal span:last-child').textContent = `$${subtotal.toFixed(2)}`;
        document.querySelector('.inv-total-row.discount span:last-child').textContent = `$${totalDiscount.toFixed(2)}`;
        
        // Calculate and update final total
        const shipping = parseFloat(document.querySelector('.inv-shipping-input input').value) || 0;
        const tax = document.querySelector('.inv-checkbox-container input').checked ? 0 : (subtotal * 0.15); // 15% tax example
        const finalTotal = subtotal - totalDiscount + shipping + tax;
        
        document.querySelector('.inv-final-total span:last-child').textContent = `$${finalTotal.toFixed(2)}`;
    }
});