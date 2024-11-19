$(document).ready(function() {
    // Initialize tooltips and popovers if using Bootstrap
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    // Handle entries per page change
    $('.entries-select').on('change', function() {
        updateEntries($(this).val());
    });

    // Handle search input with debounce
    let searchTimeout;
    $('.search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val();
        searchTimeout = setTimeout(() => searchProducts(query), 500);
    });

    // Handle pagination button click
    $('.pagination-button').on('click', function() {
        if (!$(this).prop('disabled')) {
            changePage($(this).data('page'));
        }
    });

    // Handle edit product form submission
    $('#editProductForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();

        // Validate form data
        const price = parseFloat($('#editProductPrice').val());
        const quantity = parseInt($('#editProductQuantity').val());
        
        if (isNaN(price) || price < 0) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Price',
                text: 'Please enter a valid non-negative price'
            });
            return;
        }
        
        if (isNaN(quantity) || quantity < 0) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Quantity',
                text: 'Please enter a valid non-negative quantity'
            });
            return;
        }

        $.ajax({
            url: '../../actions/update_product_action.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                Swal.fire({
                    title: 'Updating...',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Product updated successfully'
                    }).then(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editProductModal'));
                        modal.hide();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to update product'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Update error:', error);
                console.error('Response:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update product. Please try again.'
                });
            }
        });
    });

    // Handle delete product confirmation
    $('#confirmDeleteButton').on('click', function() {
        const productId = $(this).data('product-id');
        
        $.ajax({
            url: '../../actions/delete_product_action.php',
            type: 'POST',
            data: { product_id: productId },
            dataType: 'json',
            beforeSend: function() {
                Swal.fire({
                    title: 'Deleting...',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Product deleted successfully'
                    }).then(() => {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteProductModal'));
                        modal.hide();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to delete product'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Delete error:', error);
                console.error('Response:', xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to delete product. Please try again.'
                });
            }
        });
    });

    // Add sorting functionality
    $('.sort-icon').on('click', function() {
        const column = $(this).closest('th').index();
        const table = $('.product-table');
        const tbody = table.find('tbody');
        const rows = tbody.find('tr').toArray();
        const isAscending = !$(this).hasClass('sorted-asc');
        
        // Remove sorting classes from all icons
        $('.sort-icon').removeClass('sorted-asc sorted-desc');
        $(this).addClass(isAscending ? 'sorted-asc' : 'sorted-desc');
        
        rows.sort((a, b) => {
            let aVal = $(a).find('td').eq(column).text().trim();
            let bVal = $(b).find('td').eq(column).text().trim();
            
            // Handle numeric sorting for price and quantity
            if (column === 2 || column === 3) {
                aVal = parseFloat(aVal.replace(/[^0-9.-]+/g, '')) || 0;
                bVal = parseFloat(bVal.replace(/[^0-9.-]+/g, '')) || 0;
            }
            
            if (aVal < bVal) return isAscending ? -1 : 1;
            if (aVal > bVal) return isAscending ? 1 : -1;
            return 0;
        });
        
        tbody.empty().append(rows);
    });

    // Add event listener for the edit modal closing
    $('#editProductModal').on('hidden.bs.modal', function() {
        $('#editProductForm')[0].reset();
    });
});

// Function to open edit modal
function openEditModal(productId) {
    Swal.fire({
        title: 'Loading...',
        showConfirmButton: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: '../../actions/get_product_action.php',
        type: 'GET',
        data: { product_id: productId },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            if (response.success && response.product) {
                const product = response.product;
                $('#editProductId').val(product.product_id);
                $('#editProductName').val(product.product_name);
                $('#editProductDescription').val(product.product_description);
                $('#editProductPrice').val(product.product_price);
                $('#editProductQuantity').val(product.product_quantity);
                
                // Show the modal
                const editModal = new bootstrap.Modal(document.getElementById('editProductModal'));
                editModal.show();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Failed to fetch product details'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Fetch error:', error);
            console.error('Response:', xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to fetch product details. Please try again.'
            });
        }
    });
}

// Function to open delete modal
function openDeleteModal(productId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $('#confirmDeleteButton').data('product-id', productId);
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteProductModal'));
            deleteModal.show();
        }
    });
}

// Function to handle search
function searchProducts(query) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('search', query);
    urlParams.set('page', '1'); // Reset to first page on new search
    window.location.href = window.location.pathname + '?' + urlParams.toString();
}

// Function to update entries per page
function updateEntries(entries) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('entries', entries);
    urlParams.set('page', '1'); // Reset to first page when changing entries
    window.location.href = window.location.pathname + '?' + urlParams.toString();
}

// Function to handle pagination
function changePage(page) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('page', page);
    window.location.href = window.location.pathname + '?' + urlParams.toString();
}