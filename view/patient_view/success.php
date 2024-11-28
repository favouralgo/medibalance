<?php
include '../pincludes/header.php';
require_once("../../controllers/invoice_controller.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verify customer is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

// Get invoice details if ID is provided
$invoice_id = isset($_GET['invoice']) ? (int)$_GET['invoice'] : 0;
$invoice_details = [];

if ($invoice_id) {
    $invoiceController = new InvoiceController();
    $result = $invoiceController->get_invoice_details_ctr($invoice_id);
    if ($result['success']) {
        $invoice_details = $result['data'];
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="card-title text-success mb-4">Payment Successful!</h2>
                    
                    <?php if (!empty($invoice_details)): ?>
                    <div class="payment-details mb-4">
                        <p class="mb-2">Invoice Number: <strong><?php echo htmlspecialchars($invoice_details['invoice_number']); ?></strong></p>
                        <p class="mb-2">Amount Paid: <strong>GHS <?php echo number_format($invoice_details['invoice_total'], 2); ?></strong></p>
                        <p class="mb-2">Date: <strong><?php echo date('d/m/Y H:i:s'); ?></strong></p>
                    </div>
                    <?php endif; ?>
                    
                    <p class="text-muted">You will be redirected back to your invoices in <span id="countdown">3</span> seconds...</p>
                    
                    <div class="mt-4">
                        <a href="manage_invoice.php" class="btn btn-primary">Return to Invoices Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../js/main.js"></script>
<script>
// Countdown timer
let timeLeft = 3;
const countdownElement = document.getElementById('countdown');

const countdownTimer = setInterval(function() {
    timeLeft--;
    countdownElement.textContent = timeLeft;
    
    if (timeLeft <= 0) {
        clearInterval(countdownTimer);
        window.location.href = 'manage_invoice.php';
    }
}, 1000);

// Add confetti animation for celebration effect
function createConfetti() {
    for (let i = 0; i < 150; i++) {
        const confetti = document.createElement('div');
        confetti.classList.add('confetti');
        
        // Random position, color, and animation delay
        confetti.style.left = Math.random() * 100 + 'vw';
        confetti.style.animationDelay = Math.random() * 3 + 's';
        confetti.style.backgroundColor = `hsl(${Math.random() * 360}, 100%, 50%)`;
        
        document.body.appendChild(confetti);
        
        // Remove confetti after animation
        setTimeout(() => confetti.remove(), 3000);
    }
}

// Call createConfetti when page loads
document.addEventListener('DOMContentLoaded', createConfetti);
</script>

<style>
/* Confetti animation */
.confetti {
    position: fixed;
    top: -10px;
    width: 10px;
    height: 10px;
    pointer-events: none;
    opacity: 0;
    transform-origin: 50% 50%;
    animation: confetti-fall 3s ease-in-out forwards;
}

@keyframes confetti-fall {
    0% {
        transform: translateY(0) rotate(0);
        opacity: 1;
    }
    100% {
        transform: translateY(100vh) rotate(360deg);
        opacity: 0;
    }
}

/* Success icon pulse animation */
.fa-check-circle {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

/* Card styling */
.card {
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.payment-details {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
}

.payment-details p {
    margin-bottom: 10px;
}

/* Button hover effect */
.btn-primary:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
}
</style>

<?php include '../pincludes/footer.php'; ?>