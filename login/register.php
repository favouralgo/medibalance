<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facility Registration</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container">
        <h2>Register your facility</h2>
        <div id="message"></div>
        <form id="registrationForm">
            <div class="form-row">
                <div class="form-group">
                    <input type="text" id="user_firstname" placeholder="Enter your first name" required>
                </div>
                <div class="form-group">
                    <input type="text" id="user_lastname" placeholder="Enter your last name" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <input type="email" id="user_email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <input type="text" id="user_facilityname" placeholder="Enter your facility name" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <input type="text" id="user_country" placeholder="Enter your country" required>
                </div>
                <div class="form-group">
                    <input type="text" id="user_city" placeholder="Enter your city" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <input type="text" id="user_address" placeholder="Enter your address" required>
                </div>
                <div class="form-group">
                    <input type="text" id="user_phonenumber" placeholder="Enter your contact number" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group password-container">
                    <input type="password" id="user_password" placeholder="Enter your password" required>
                    <span toggle="#user_password" class="fa fa-fw fa-eye toggle-password"></span>
                </div>
                <div class="form-group password-container">
                    <input type="password" id="confirm_password" placeholder="Confirm your password" required>
                    <span toggle="#confirm_password" class="fa fa-fw fa-eye toggle-password"></span>
                </div>
            </div>
            <button type="submit">Register</button>
        </form>
        <p class="text-center">
            Already have an account? <a href="login.php">Login</a>
        </p>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/register.js"></script>
</body>

</html>