// main.js
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

    // Store and restore dropdown states
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    let storedStates = JSON.parse(localStorage.getItem('dropdownStates') || '{}');

    dropdownToggles.forEach(toggle => {
        const targetId = toggle.getAttribute('data-bs-target').replace('#', '');
        
        // Apply stored state
        if (storedStates[targetId]) {
            toggle.classList.remove('collapsed');
            toggle.setAttribute('aria-expanded', 'true');
            document.getElementById(targetId).classList.add('show');
        }

        // Update stored states when dropdown changes
        toggle.addEventListener('click', function() {
            setTimeout(() => {
                const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                storedStates[targetId] = isExpanded;
                localStorage.setItem('dropdownStates', JSON.stringify(storedStates));
            }, 0);
        });
    });

    // Handle arrow rotation
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const arrow = this.querySelector('.fa-angle-down');
            if (arrow) {
                if (this.getAttribute('aria-expanded') === 'true') {
                    arrow.style.transform = 'rotate(180deg)';
                } else {
                    arrow.style.transform = 'rotate(0deg)';
                }
            }
        });
    });

    // Update page title
    const pageTitle = document.querySelector('.page-title');
    if (pageTitle) {
        const currentPage = window.location.pathname.split('/').pop() || 'dashboard.php';
        const titles = {
            'dashboard.php': 'Dashboard',
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
            'change_password.php': 'Change Password'
        };
        pageTitle.textContent = titles[currentPage] || 'Dashboard';
    }
});