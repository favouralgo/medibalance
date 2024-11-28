<?php include '../pincludes/header.php'; ?>

<div class="trx-container">
    <form class="trx-form" method="POST" action="">
        <div class="trx-section trx-card">
            <div class="trx-card-header">
                <div class="trx-header-title">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Transaction History</span>
                </div>
                <div class="trx-balance">
                    <span class="trx-balance-label">Current Balance</span>
                    <span class="trx-balance-amount">$1,234.56</span>
                </div>
            </div>

            <div class="trx-controls">
                <div class="trx-filter-wrapper">
                    <select name="transaction_type" class="trx-filter-select">
                        <option value="all">All Transactions</option>
                        <option value="deposits">Deposits</option>
                        <option value="withdrawals">Withdrawals</option>
                    </select>
                </div>
                <div class="trx-search-wrapper">
                    <i class="fas fa-search trx-search-icon"></i>
                    <input type="text" name="search" class="trx-search-input" placeholder="Search transactions...">
                </div>
            </div>

            <div class="trx-list">
                <div class="trx-item">
                    <div class="trx-item-details">
                        <div class="trx-item-icon">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <div class="trx-item-info">
                            <div class="trx-item-title">Deposit via Credit Card</div>
                            <div class="trx-item-date">Dec 15, 2023 • 14:30</div>
                        </div>
                    </div>
                    <div class="trx-amount trx-amount-positive">+$500.00</div>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../js/main.js"></script>
<?php include '../pincludes/footer.php'; ?>