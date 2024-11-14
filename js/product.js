$(document).ready(function() {
    // Handle entries per page change
    $('.entries-select').on('change', function() {
        updateEntries($(this).val());
    });

    // Handle search input
    $('.search-input').on('keyup', function() {
        searchProducts($(this).val());
    });

    // Handle pagination button click
    $('.pagination-button').on('click', function() {
        changePage($(this).data('page'));
    });

    // Handle edit product form submission
    $('#editProductForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.ajax({
            url: '../../actions/update_product_action.php',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    $('#editProductModal').modal('hide');
                    location.reload();
                } else {
                    alert('Failed to update product');
                }
            },
            error: function() {
                alert('An error occurred while updating the product');
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
            success: function(response) {
                if (response.success) {
                    $('#deleteProductModal').modal('hide');
                    location.reload();
                } else {
                    alert('Failed to delete product');
                }
            },
            error: function() {
                alert('An error occurred while deleting the product');
            }
        });
    });
});

function openEditModal(productId) {
    $.ajax({
        url: '../../actions/get_product_action.php',
        type: 'GET',
        data: { product_id: productId },
        success: function(response) {
            if (response.success) {
                const product = response.product;
                $('#editProductId').val(product.product_id);
                $('#editProductName').val(product.product_name);
                $('#editProductDescription').val(product.product_description);
                $('#editProductPrice').val(product.product_price);
                $('#editProductQuantity').val(product.product_quantity);
                $('#editProductModal').modal('show');
            } else {
                alert('Failed to fetch product details');
            }
        },
        error: function() {
            alert('An error occurred while fetching product details');
        }
    });
}

function openDeleteModal(productId) {
    $('#confirmDeleteButton').data('product-id', productId);
    $('#deleteProductModal').modal('show');
}

function searchProducts(query) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('search', query);
    urlParams.set('page', '1'); // Reset to first page on new search
    window.location.href = window.location.pathname + '?' + urlParams.toString();
}

function updateEntries(entries) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('entries', entries);
    urlParams.set('page', '1'); // Reset to first page when changing entries
    window.location.href = window.location.pathname + '?' + urlParams.toString();
}

function changePage(page) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('page', page);
    window.location.href = window.location.pathname + '?' + urlParams.toString();
}