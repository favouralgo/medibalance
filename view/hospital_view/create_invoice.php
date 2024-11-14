<?php include '../includes/header.php'; ?>

<div class="inv-container">
    <div class="inv-header">
        <div class="inv-title">New Invoice</div>
        <div class="inv-header-inputs">
            <div class="inv-input-group">
                <input type="date" class="inv-form-input" placeholder="Start Date">
                <input type="date" class="inv-form-input" placeholder="Due Date">
                <div class="inv-number-input">
                    <input type="text" class="inv-form-input" value="">
                    <label>#INV</label>
                </div>
            </div>
        </div>
    </div>

    <div class="inv-info-section">
        <!-- Customer Information -->
        <div class="inv-card">
            <div class="inv-card-header">
                <h2>Patient Information</h2>
                <a href="#" class="inv-select-customer">Select Existing Patient</a>
            </div>
            <div class="inv-form-grid">
                <input type="text" class="inv-form-input" placeholder="Enter Name">
                <input type="email" class="inv-form-input" placeholder="E-mail Address">
                <input type="text" class="inv-form-input" placeholder="Address 1">
                <input type="text" class="inv-form-input" placeholder="Address 2">
                <input type="text" class="inv-form-input" placeholder="Town">
                <input type="text" class="inv-form-input" placeholder="Country">
                <input type="text" class="inv-form-input" placeholder="Postcode">
                <input type="tel" class="inv-form-input" placeholder="Phone Number">
            </div>
        </div>

        <!-- Patient Information -->
        <div class="inv-card">
            <div class="inv-card-header">
                <h2>Patient Information</h2>
            </div>
            <div class="inv-form-grid">
                <input type="text" class="inv-form-input" placeholder="Enter Name">
                <input type="text" class="inv-form-input" placeholder="Address 1">
                <input type="text" class="inv-form-input" placeholder="Address 2">
                <input type="text" class="inv-form-input" placeholder="Town">
                <input type="text" class="inv-form-input" placeholder="Country">
                <input type="text" class="inv-form-input" placeholder="Postcode">
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="inv-products-section">
        <table class="inv-products-table">
            <thead>
                <tr>
                    <th>Service</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Discount (%)</th>
                    <th>Sub Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="inv-serviceTableBody">
                <tr>
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
                </tr>
            </tbody>
        </table>
        <div class="inv-table-actions">
            <button class="inv-add-row-btn" id="addServiceBtn">+ Add Service</button>
        </div>
    </div>

    <!-- Totals Section -->
    <div class="inv-totals-section">
        <div class="inv-totals-container">
            <div class="inv-total-row">
                <span>Sub Total:</span>
                <span>$0.00</span>
            </div>
            <div class="inv-total-row">
                <span>Discount:</span>
                <span>$0.00</span>
            </div>
            <div class="inv-total-row">
                <span>Shipping:</span>
                <div class="inv-shipping-input">
                    <span>$</span>
                    <input type="number" class="inv-form-input" placeholder="0.00" step="0.01">
                </div>
            </div>
            <!-- <div class="inv-total-row">
                <span>TAX/VAT:</span>
                <span>$0.00</span>
            </div> -->
            <div class="inv-total-row">
                <label class="inv-checkbox-container">
                    <input type="checkbox">
                    <span>Remove TAX/VAT</span>
                </label>
            </div>
            <div class="inv-total-row inv-final-total">
                <span>Total:</span>
                <span>$0.00</span>
            </div>
        </div>
    </div>

    <div class="inv-form-actions">
        <button type="submit" class="inv-create-btn">Create Invoice</button>
    </div>
</div>

<script src="../../js/createinvoice.js"></script>

<?php include '../includes/footer.php'; ?>