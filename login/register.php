<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     <title>Register - MediBalance</title>
</head>

<body>
    <!-- Navbar -->
    <nav class="sticky-nav bg-white bg-opacity-90 shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="../index.php" class="text-2xl font-bold primary-color">MediBalance</a>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="../index.php" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900">Home</a>
                        <a href="../index.php#features" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900">Features</a>
                        <a href="../index.php#about" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900">About</a>
                        <a href="../index.php#pricing" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900">Pricing</a>
                        <a href="../index.php#contact" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900">Contact</a>
                        <a href="../view/public/check_invoice.php" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-900">Pay an invoice</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4 ml-8">
                    <div class="flex-shrink-0">
                        <a href="../login/login.php" class="relative inline-flex items-center px-6 py-2 border border-green-500 text-sm font-medium rounded-md text-green-500 bg-white hover:bg-green-500 hover:text-white transition-colors duration-200">
                            Sign in
                        </a>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="../login/register.php" class="relative inline-flex items-center px-6 py-2 border border-green-500 text-sm font-medium rounded-md text-white bg-green-500 hover:bg-white hover:text-green-500 transition-colors duration-200">
                            Register
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    <!-- <div class="main-content min-h-screen flex items-center justify-center">  -->
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
    <!-- </div> -->

    <!-- Footer -->
    <footer class="bg-gray-800">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <span class="text-2xl font-bold text-white">MediBalance</span>
                    <p class="mt-4 text-gray-400">
                    Your one-stop solution for cross-border healthcare billing and invoicing.
                    </p>
                    <div class="flex space-x-6 mt-6">
                        <!-- Social Media Icons -->
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Facebook</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white">
                            <span class="sr-only">Twitter</span>
                            <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Company</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="#about" class="text-base text-gray-300 hover:text-white">About</a></li>
                        <li><a href="#" class="text-base text-gray-300 hover:text-white">Careers</a></li>
                        <li><a href="#contact" class="text-base text-gray-300 hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Legal</h3>
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
    <!-- End Footer -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/register.js"></script>
</body>

</html>