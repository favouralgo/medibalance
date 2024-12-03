<?php
require_once('../../controllers/invoice_controller.php');
session_start();

$error_message = '';
$invoice = null;
$show_404 = false;


// Clear data if it's a fresh page load (not a form submission)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    unset($_POST['invoice_number']);
    $invoice = null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['invoice_number'])) {
    $invoiceController = new InvoiceController();
    $result = $invoiceController->get_invoice_by_number_ctr($_POST['invoice_number']);
    
    if ($result['success']) {
        $invoice = $result['data'];
    } else {
        $show_404 = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/invoice.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
    <title>MediBalance - Invoice Payment</title>
    <script>
    if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_RELOAD) {
        window.location.href = window.location.pathname;
    }
    </script>


</head>
<body class="bg-light">
    <nav class="sticky-nav bg-white bg-opacity-90 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="../../index.php" class="text-2xl font-bold primary-color">MediBalance</a>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="../../index.php" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900">Home</a>
                        <a href="../../index.php#features" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900">Features</a>
                        <a href="../../index.php#about" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900">About</a>
                        <a href="../../index.php#pricing" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900">Pricing</a>
                        <a href="../../index.php#contact" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900">Contact</a>
                        <a href="check_invoice.php" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900">Pay an invoice</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4 ml-8">
                    <div class="flex-shrink-0">
                    <a href="../../login/login.php" class="nav-button-white relative inline-flex items-center px-6 py-2 border border-green-500 text-sm font-medium rounded-md transition-colors duration-200">
    Sign in
</a>
                    </div>
                    <div class="flex-shrink-0">
                    <a href="../../login/register.php" class="nav-button-green relative inline-flex items-center px-6 py-2 border border-green-500 text-sm font-medium rounded-md transition-colors duration-200">
    Register
</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="payment-container">
        <?php if ($show_404): ?>
            <div class="error-container">
                <div class="error-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h1 class="error-title">Invoice Not Found</h1>
                <p class="error-message">The invoice number you entered doesn't exist in our system.</p>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-primary error-button">
                    <i class="fas fa-arrow-left me-2"></i>Try Again
                </a>
            </div>
        <?php else: ?>
        <div class="logo-container">
            <!-- <img src="../../assets/images/logo.png" alt="MediBalance Logo"> -->
        </div>
        
        <h2 class="text-center mb-4">Invoice Payment Portal</h2>
        
        <form class="search-form" method="POST">
            <div class="input-group">
                <input type="text" name="invoice_number" class="form-control form-control-lg" 
                       placeholder="Enter Invoice Number" required
                       value="<?php echo isset($_POST['invoice_number']) ? htmlspecialchars($_POST['invoice_number']) : ''; ?>">
                <button type="submit" class="btn btn-success">Search Invoice</button>
            </div>
            <?php if ($error_message): ?>
                <div class="alert alert-danger mt-2"><?php echo $error_message; ?></div>
            <?php endif; ?>
        </form>

        <?php if ($invoice): ?>
            <div class="invoice-details">
                <input type="hidden" id="customer_email" value="<?php echo htmlspecialchars($invoice['customer_email']); ?>">
                <h3 class="mb-4">Invoice Details</h3>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Invoice Number:</strong> <?php echo htmlspecialchars($invoice['invoice_number']); ?></p>
                        <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($invoice['customer_firstname'] . ' ' . $invoice['customer_lastname']); ?></p>
                        <p><strong>Issue Date:</strong> <?php echo htmlspecialchars($invoice['invoice_date_start']); ?></p>
                        <p><strong>Due Date:</strong> <?php echo htmlspecialchars($invoice['invoice_date_due']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Total Amount:</strong> GH₵<?php echo htmlspecialchars(number_format($invoice['invoice_total'], 2)); ?></p>
                        <p><strong>Status:</strong> 
                            <span class="<?php echo $invoice['status_id'] == 1 ? 'status-paid' : 'status-unpaid'; ?>">
                                <?php echo $invoice['status_id'] == 1 ? 'Paid' : 'Unpaid'; ?>
                            </span>
                        </p>
                    </div>
                </div>

                <?php if ($invoice['status_id'] == 2): ?>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-success btn-lg" onclick="initializePayment('<?php echo $invoice['invoice_number']; ?>', <?php echo $invoice['invoice_total']; ?>)">
                            Pay Now (GH₵<?php echo number_format($invoice['invoice_total'], 2); ?>)
                        </button>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <footer class="bg-gray-800 mt-auto">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <span class="text-2xl font-bold text-white">MediBalance</span>
                    <p class="mt-4 text-gray-400">
                    Your one-stop solution for cross-border healthcare billing and invoicing.
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">COMPANY</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="../../index.php#about" class="text-base text-gray-300 hover:text-white">About</a></li>
                        <li><a href="#" class="text-base text-gray-300 hover:text-white">Careers</a></li>
                        <li><a href="../../index.php#contact" class="text-base text-gray-300 hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">LEGAL</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="#" class="text-base text-gray-300 hover:text-white">Privacy</a></li>
                        <li><a href="#" class="text-base text-gray-300 hover:text-white">Terms</a></li>
                        <li><a href="#" class="text-base text-gray-300 hover:text-white">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-8">
                <p class="text-base text-gray-400 text-center">
                    © 2024 MediBalance. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--PayStack JavaScript -->
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script src="../../js/public_payment.js"></script>
</body>
</html>