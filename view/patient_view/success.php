<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link rel="stylesheet" href="../../css/success.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="popup" id="popup">
        <div class="popup-content">
            <h2>Payment Successful!</h2>
            <p>Your payment has been processed successfully.</p>
            <button id="close-popup">Close</button>
        </div>
    </div>

    <div class="container">
        <h1>Thank You for Your Purchase!</h1>
        <p>Your order has been placed successfully. You will receive a confirmation email shortly.</p>
        <a href="product.php" class="btn btn-primary">Continue Shopping</a>
    </div>

    <script>
        $(document).ready(function() {
            $('#popup').show();

            $('#close-popup').on('click', function() {
                $('#popup').hide();
            });
        });
    </script>
</body>
</html>