$(document).ready(function() {
    // Initialize any tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // Handle entries per page change
    $('.managecustomers-entries-select').on('change', function() {
        updateEntries($(this).val());
    });

    // Handle search input with debounce
    let searchTimeout;
    $('.managecustomers-search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        const searchValue = $(this).val();
        searchTimeout = setTimeout(() => {
            searchCustomers(searchValue);
        }, 500); // Debounce for 500ms
    });

    // Handle pagination button clicks
    $('.managecustomers-pagination-button').on('click', function() {
        const page = $(this).data('page');
        if (page) {
            changePage(page);
        }
    });

    // Handle edit form submission
    $('#editCustomerForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!validateEditForm()) {
            return;
        }

        const formData = $(this).serialize();
        $.ajax({
            url: '../../actions/update_customer_action.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Success',
                        text: response.message || 'Customer updated successfully',
                        icon: 'success'
                    }).then(() => {
                        $('#editCustomerModal').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', response.message || 'Failed to update customer', 'error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Update Error:', error);
                Swal.fire('Error', 'An error occurred while updating the customer', 'error');
            }
        });
    });

    // Close modal handlers
    $('.modal .close, .modal .btn-secondary').on('click', function() {
        $(this).closest('.modal').modal('hide');
    });
});

// Function to open edit modal
function openEditModal(customerId) {
    $.ajax({
        url: '../../actions/get_customer_action.php',
        type: 'GET',
        data: { customer_id: customerId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const customer = response.customer;
                // Populate the edit form with customer data
                $('#editCustomerId').val(customer.customer_id);
                $('#editCustomerFirstname').val(customer.customer_firstname);
                $('#editCustomerLastname').val(customer.customer_lastname);
                $('#editCustomerEmail').val(customer.customer_email);
                $('#editCustomerPhone').val(customer.customer_phonenumber);
                $('#editCustomerAddress').val(customer.customer_address);
                $('#editCustomerCity').val(customer.customer_city);
                $('#editCustomerCountry').val(customer.customer_country);
                
                // Show the modal
                $('#editCustomerModal').modal('show');
            } else {
                Swal.fire('Error', response.message || 'Failed to fetch customer details', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Fetch Error:', error);
            Swal.fire('Error', 'An error occurred while fetching customer details', 'error');
        }
    });
}

// Function to open delete confirmation
function openDeleteModal(customerId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteCustomer(customerId);
        }
    });
}

// Function to handle customer deletion
function deleteCustomer(customerId) {
    $.ajax({
        url: '../../actions/delete_customer_action.php',
        type: 'POST',
        data: { customer_id: customerId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Deleted!',
                    text: response.message || 'Customer has been deleted successfully',
                    icon: 'success'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', response.message || 'Failed to delete customer', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error('Delete Error:', error);
            Swal.fire('Error', 'An error occurred while deleting the customer', 'error');
        }
    });
}

// Function to validate edit form
function validateEditForm() {
    const email = $('#editCustomerEmail').val().trim();
    const phone = $('#editCustomerPhone').val().trim();
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        Swal.fire('Error', 'Please enter a valid email address', 'error');
        return false;
    }
    
    // Phone validation (basic)
    const phoneRegex = /^\+?[\d\s-]{8,}$/;
    if (!phoneRegex.test(phone)) {
        Swal.fire('Error', 'Please enter a valid phone number', 'error');
        return false;
    }
    
    // Check if required fields are filled
    if (!$('#editCustomerFirstname').val().trim() || 
        !$('#editCustomerLastname').val().trim() || 
        !$('#editCustomerAddress').val().trim() || 
        !$('#editCustomerCity').val().trim() || 
        !$('#editCustomerCountry').val().trim()) {
        Swal.fire('Error', 'Please fill in all required fields', 'error');
        return false;
    }
    
    return true;
}

// Function to handle entries per page change
function updateEntries(entries) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('entries', entries);
    urlParams.set('page', '1'); // Reset to first page when changing entries
    window.location.href = window.location.pathname + '?' + urlParams.toString();
}

// Function to handle search
function searchCustomers(query) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('search', query);
    urlParams.set('page', '1'); // Reset to first page for new search
    window.location.href = window.location.pathname + '?' + urlParams.toString();
}

// Function to handle pagination
function changePage(page) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('page', page);
    window.location.href = window.location.pathname + '?' + urlParams.toString();
}