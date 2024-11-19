<?php include '../includes/header.php'; ?>

<div class="adf-container">
    <form class="adf-form" method="POST" action="">
        <div class="adf-section adf-card">
            <div class="adf-card-header">
                <div class="adf-header-title">
                    <i class="fas fa-wallet"></i>
                    <span>Add Funds</span>
                </div>
                <div class="adf-balance">
                    <span class="adf-balance-label">Current Balance</span>
                    <span class="adf-balance-amount">$1,234.56</span>
                </div>
            </div>

            <div class="adf-fields-grid">
                <div class="adf-form-group">
                    <label class="adf-label">Amount to Add</label>
                    <div class="adf-input-with-icon">
                        <span class="adf-currency-symbol">$</span>
                        <input 
                            type="number" 
                            name="amount" 
                            class="adf-input" 
                            placeholder="0.00" 
                            min="0" 
                            step="0.01" 
                            required
                        >
                    </div>
                </div>

                <div class="adf-form-group adf-full-width">
                    <label class="adf-label">Payment Method</label>
                    <div class="adf-payment-options">
                        <label class="adf-payment-option">
                            <input type="radio" name="payment_method" value="card" required>
                            <div class="adf-option-content">
                                <i class="fas fa-credit-card"></i>
                                <span>Credit Card</span>
                            </div>
                        </label>
                        <label class="adf-payment-option">
                            <input type="radio" name="payment_method" value="paypal">
                            <div class="adf-option-content">
                                <i class="fab fa-paypal"></i>
                                <span>PayPal</span>
                            </div>
                        </label>
                        <label class="adf-payment-option">
                            <input type="radio" name="payment_method" value="bank">
                            <div class="adf-option-content">
                                <i class="fas fa-university"></i>
                                <span>Bank Transfer</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="adf-form-actions">
            <button type="submit" class="adf-submit-btn">Add Funds</button>
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>