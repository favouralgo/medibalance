document.addEventListener('DOMContentLoaded', function() {
    // Get DOM elements
    const sidebarToggler = document.querySelector('.sidebar-toggler');
    const sidebar = document.querySelector('.dashboard-sidebar');
    const mainContent = document.querySelector('.dashboard-main');
    
    // Handle sidebar toggle for mobile devices
    if (sidebarToggler) {
        sidebarToggler.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('active');
            mainContent.classList.toggle('sidebar-active');
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        if (window.innerWidth < 992) {  // Only on mobile devices
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickInsideToggler = sidebarToggler.contains(event.target);
            
            if (!isClickInsideSidebar && !isClickInsideToggler) {
                sidebar.classList.remove('active');
                mainContent.classList.remove('sidebar-active');
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 992) {
            sidebar.classList.remove('active');
            mainContent.classList.remove('sidebar-active');
        }
    });

    // Prevent sidebar from closing when clicking inside it
    sidebar.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Initialize all Bootstrap dropdowns
    const allDropdownToggles = document.querySelectorAll('[data-bs-toggle="dropdown"]');
    allDropdownToggles.forEach(toggle => {
        new bootstrap.Dropdown(toggle);
    });

    // Determine which dashboard we're in
    const currentPath = window.location.pathname;
    const isPatientDashboard = currentPath.includes('/patient_view/');
    const isHospitalDashboard = currentPath.includes('/hospital_view/');
    const currentPage = currentPath.split('/').pop() || 'dashboard.php';

    // Handle sidebar menu dropdowns
    const sidebarDropdownToggles = document.querySelectorAll('.nav-link.dropdown-toggle');
    let storedStates = JSON.parse(localStorage.getItem('dropdownStates') || '{}');

    sidebarDropdownToggles.forEach(toggle => {
        const targetId = toggle.getAttribute('data-bs-target')?.replace('#', '');
        if (!targetId) return;
        
        // Apply stored state
        if (storedStates[targetId]) {
            toggle.classList.remove('collapsed');
            toggle.setAttribute('aria-expanded', 'true');
            document.getElementById(targetId)?.classList.add('show');
        }

        // Update stored states when dropdown changes
        toggle.addEventListener('click', function() {
            setTimeout(() => {
                const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                storedStates[targetId] = isExpanded;
                localStorage.setItem('dropdownStates', JSON.stringify(storedStates));
            }, 0);
        });

        // Handle arrow rotation
        const arrow = toggle.querySelector('.fa-angle-down');
        if (arrow) {
            // Set initial rotation based on stored state
            if (storedStates[targetId]) {
                arrow.style.transform = 'rotate(180deg)';
            }

            toggle.addEventListener('click', function() {
                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                arrow.style.transform = isExpanded ? 'rotate(180deg)' : 'rotate(0deg)';
            });
        }
    });

    // Handle profile dropdown
    const userDropdown = document.querySelector('#userDropdown');
    if (userDropdown) {
        new bootstrap.Dropdown(userDropdown, {
            boundary: 'window'
        });
    }

    // Update page title
    const pageTitle = document.querySelector('.page-title');
    if (pageTitle) {
        // Define titles for both dashboards
        const hospitalTitles = {
            'dashboard.php': 'Welcome!',
            'create_invoice.php': 'Create Invoice',
            'manage_invoices.php': 'Manage Invoices',
            'download_csv.php': 'Download CSV',
            'add_product.php': 'Add Product',
            'manage_products.php': 'Manage Products',
            'add_customer.php': 'Add Customer',
            'manage_customers.php': 'Manage Customers',
            'add_user.php': 'Add User',
            'manage_users.php': 'Manage Users',
            'profile_settings.php': 'Profile Settings',
            'change_password.php': 'Change Password',
            'product.php': 'Products',
            'view_invoice.php': 'View Invoice',
            'manage_transactions.php': 'Manage Transactions',
            'manage_wallet.php': 'Wallet Settings',
            'customers.php': 'Customers'
        };

        const patientTitles = {
            'patient_dashboard.php': 'Welcome!',
            'manage_invoice.php': 'My Invoices',
            'manage_product.php': 'My Services',
            'add_funds.php': 'Add Funds',
            'manage_transactions.php': 'Transaction History',
            'manage_wallet.php': 'My Wallet'
        };

        // Set title based on dashboard type and current page
        const isDashboardPage = currentPage === 'dashboard.php' || currentPage === 'patient_dashboard.php';
        if (!isDashboardPage || !pageTitle.innerHTML.includes('Welcome!')) {
            if (isHospitalDashboard) {
                pageTitle.textContent = hospitalTitles[currentPage] || 'Dashboard';
            } else if (isPatientDashboard) {
                pageTitle.textContent = patientTitles[currentPage] || 'Dashboard';
            }
        }
    }

    // Handle active states for navigation
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (!href) return;

        const linkPath = href.split('/').pop();
        if (linkPath === currentPage) {
            link.classList.add('active');
            
            // If this link is in a dropdown, expand the dropdown
            const dropdownContent = link.closest('.collapse');
            if (dropdownContent) {
                dropdownContent.classList.add('show');
                const dropdownToggle = document.querySelector(`[data-bs-target="#${dropdownContent.id}"]`);
                if (dropdownToggle) {
                    dropdownToggle.classList.remove('collapsed');
                    dropdownToggle.setAttribute('aria-expanded', 'true');
                    
                    // Rotate the arrow if present
                    const arrow = dropdownToggle.querySelector('.fa-angle-down');
                    if (arrow) {
                        arrow.style.transform = 'rotate(180deg)';
                    }
                }
            }
        }
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const isDropdownButton = event.target.matches('[data-bs-toggle="dropdown"]');
        const isInsideDropdown = event.target.closest('.dropdown-menu');
        
        if (!isDropdownButton && !isInsideDropdown) {
            const dropdowns = document.querySelectorAll('.dropdown-menu.show');
            dropdowns.forEach(dropdown => {
                const toggle = document.querySelector(`[data-bs-toggle="dropdown"][aria-expanded="true"]`);
                if (toggle) {
                    new bootstrap.Dropdown(toggle).hide();
                }
            });
        }
    });
});