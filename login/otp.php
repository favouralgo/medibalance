<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="../css/register.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/otp.css">
    <title>OTP Verification</title>
</head>
<body class="fade-in">

<div class="otp-container">
    <h2>Verify your OTP</h2>
    <div id="message"></div>
    <form id="validateOtpForm">
        <!-- <input type="hidden" id="email" value="<?php echo $_GET['email']; ?>" required> -->
        <div class="otp-inputs">
            <input type="text" inputmode="numeric" id="otp1" maxlength="1" required>
            <input type="text" inputmode="numeric" id="otp2" maxlength="1" required>
            <input type="text" inputmode="numeric" id="otp3" maxlength="1" required>
            <input type="text" inputmode="numeric" id="otp4" maxlength="1" required>
            <input type="text" inputmode="numeric" id="otp5" maxlength="1" required>
            <input type="text" inputmode="numeric" id="otp6" maxlength="1" required>
        </div>
        <button type="submit">Verify</button>
    </form>
</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/otp.js"></script>
</body>
</html>