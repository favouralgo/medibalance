<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Space Grotesk Font -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <!-- <link href="css/styles.css" rel="stylesheet"> -->
    <link href="../../css/dashboard.css" rel="stylesheet">
    <link href="../../css/addproduct.css" rel="stylesheet">
    <link href="../../css/manageproduct.css" rel="stylesheet">
    <link href="../../css/createinvoice.css" rel="stylesheet">
    <link href="../../css/customer.css" rel="stylesheet">
    <link href="../../css/addfunds.css" rel="stylesheet">
    <link href="../../css/managetransactions.css" rel="stylesheet">
    <link href="../../css/managewallet.css" rel="stylesheet">
</head>
<body>
    <!-- Sidebar Toggle Button (Mobile) -->
    <button class="sidebar-toggler">
        <i class="fas fa-bars"></i>
    </button>

    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <nav class="dashboard-sidebar">
            <div class="d-flex flex-column h-100">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                            <i class="fas fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link dropdown-toggle collapsed" data-bs-toggle="collapse" data-bs-target="#invoicesDropdown" aria-expanded="false">
                            <i class="fas fa-file-invoice"></i>
                            <span>Invoices</span>
                            <i class="fas fa-angle-down"></i>
                        </a>
                        <div class="collapse" id="invoicesDropdown">
                            <ul class="nav flex-column ms-3 sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="../hospital_view/manage_invoices.php">
                                        <i class="fas fa-cog fa-sm"></i>
                                        <span>Manage Invoices</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../hospital_view/download_csv.php">
                                        <i class="fas fa-download fa-sm"></i>
                                        <span>Download CSV</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

               
                    <li class="nav-item">
                        <a href="#" class="nav-link dropdown-toggle collapsed" data-bs-toggle="collapse" data-bs-target="#walletDropdown" aria-expanded="false">
                            <i class="fas fa-wallet"></i>
                            <span>Wallet</span>
                            <i class="fas fa-angle-down"></i>
                        </a>
                        <div class="collapse" id="walletDropdown">
                            <ul class="nav flex-column ms-3 sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="../hospital_view/add_funds.php">
                                        <i class="fas fa-plus fa-sm"></i>
                                        <span>Add Funds</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../hospital_view/manage_transactions.php">
                                        <i class="fas fa-exchange-alt fa-sm"></i>
                                        <span>Transactions</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../hospital_view/manage_wallet.php">
                                            <i class="fas fa-cog fa-sm"></i>
                                        <span>Wallet Settings</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link dropdown-toggle collapsed" data-bs-toggle="collapse" data-bs-target="#settingsDropdown" aria-expanded="false">
                            <i class="fas fa-cog"></i>
                            <span>Settings</span>
                            <i class="fas fa-angle-down"></i>
                        </a>
                        <div class="collapse" id="settingsDropdown">
                            <ul class="nav flex-column ms-3 sub-menu">
                                <li class="nav-item">
                                    <a class="nav-link" href="../hospital_view/profile_settings.php">
                                        <i class="fas fa-user-cog fa-sm"></i>
                                        <span>Profile Settings</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="../hospital_view/change_password.php">
                                        <i class="fas fa-key fa-sm"></i>
                                        <span>Change Password</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content Area -->
        <div class="dashboard-main">
            <!-- Top Bar -->
            <div class="dashboard-topbar">
                <h5 class="page-title mb-0">
                    Welcome! 
                    <?php if (isset($_SESSION['user_firstname']) && isset($_SESSION['user_lastname'])) {
                        echo htmlspecialchars($_SESSION['user_firstname']) . ' ' . htmlspecialchars($_SESSION['user_lastname']);}
                    ?>
                </h5>
                <!-- <h5 class="page-title mb-0">Dashboard</h5> -->
                    <div class="d-flex align-items-center gap-3">
                        <div class="notification-icon">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </div>
                        <div class="user-profile dropdown">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span id="userName" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                                <?php
                                    if (isset($_SESSION['user_firstname']) && isset($_SESSION['user_lastname'])) {
                                    echo htmlspecialchars($_SESSION['user_firstname']) . ' ' . htmlspecialchars($_SESSION['user_lastname']);}
                                ?>
                            </span>

                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="../hospital_view/profile_settings.php"><i class="fas fa-user-cog me-2"></i>Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="../../login/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
            </div>

            <!-- Page Content Container -->
            <div class="container-fluid">