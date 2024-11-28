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
    <title>MediBalance - Invoice Payment</title>
    <script>
    if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_RELOAD) {
        window.location.href = window.location.pathname;
    }
    </script>
    
    <!-- Space Grotesk Font -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <!-- <link href="../../css/invoice.css" rel="stylesheet"> -->

    <style>
        .payment-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo-container img {
            max-width: 200px;
        }
        .search-form {
            max-width: 500px;
            margin: 0 auto 30px;
        }
        .invoice-details {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            margin-top: 30px;
        }
        .status-paid {
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }
        .status-unpaid {
            background: #dc3545;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
        }
        .invoice-items {
            margin-top: 20px;
        }
        .error-container {
            text-align: center;
            padding: 40px 20px;
        }
        
        .error-icon {
            font-size: 80px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        
        .error-title {
            font-size: 32px;
            color: #333;
            margin-bottom: 15px;
        }
        
        .error-message {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }
        
        .error-button {
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        
        .error-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-light">
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
                <button type="submit" class="btn btn-primary">Search Invoice</button>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--PayStack JavaScript -->
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script src="../../js/public_payment.js"></script>
</body>
</html>