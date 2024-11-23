<?php include '../pincludes/header.php'; ?>

<div class="wst-container">
    <form class="wst-form" method="POST" action="">
        <div class="wst-section wst-card">
            <div class="wst-card-header">
                <div class="wst-header-title">
                    <i class="fas fa-cog"></i>
                    <span>Wallet Settings</span>
                </div>
                <div class="wst-balance">
                    <span class="wst-balance-label">Current Balance</span>
                    <span class="wst-balance-amount">$1,234.56</span>
                </div>
            </div>

            <div class="wst-content">
                <div class="wst-group wst-full">
                    <h3 class="wst-section-title">Notification Preferences</h3>
                    <div class="wst-notification-item">
                        <div class="wst-notification-info">
                            <div class="wst-notification-title">Transaction Alerts</div>
                            <div class="wst-notification-desc">Get notified for all wallet transactions</div>
                        </div>
                        <label class="wst-toggle">
                            <input type="checkbox" name="transaction_alerts" checked>
                            <span class="wst-toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="wst-group wst-full">
                    <div class="wst-notification-item">
                        <div class="wst-notification-info">
                            <div class="wst-notification-title">Low Balance Alert</div>
                            <div class="wst-notification-desc">Notify when balance falls below $100</div>
                        </div>
                        <label class="wst-toggle">
                            <input type="checkbox" name="low_balance_alert">
                            <span class="wst-toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <div class="wst-payment-card">
                    <div class="wst-payment-info">
                        <i class="fas fa-credit-card"></i>
                        <div class="wst-payment-details">
                            <span class="wst-card-number">Visa ending in 4242</span>
                            <span class="wst-card-expiry">Expires 12/24</span>
                        </div>
                    </div>
                    <div class="wst-payment-actions">
                        <button type="button" class="wst-btn wst-btn-edit">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button type="button" class="wst-btn wst-btn-delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="wst-actions">
            <button type="submit" class="wst-submit-btn">Save Changes</button>
        </div>
    </form>
</div>

<?php include '../pincludes/footer.php'; ?>