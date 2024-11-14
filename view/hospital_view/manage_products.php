<?php 
include '../includes/header.php';
require('../../controllers/product_controller.php');

// Get all products
$products = get_all_products_ctr();

// Handle search if submitted
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$entries_per_page = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;

// Initialize filtered products array
$filtered_products = [];

if ($products !== false) {
    foreach ($products as $product) {
        // If there's a search query, filter products
        if ($search_query !== '') {
            if (stripos($product['product_name'], $search_query) !== false || 
                stripos($product['product_description'], $search_query) !== false) {
                $filtered_products[] = $product;
            }
        } else {
            $filtered_products[] = $product;
        }
    }
}

// Pagination logic
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$total_products = count($filtered_products);
$total_pages = ceil($total_products / $entries_per_page);
$offset = ($current_page - 1) * $entries_per_page;

// Get products for current page
$current_products = array_slice($filtered_products, $offset, $entries_per_page);
?>

<div class="product-list-container">
    <!-- Message Display -->
    <div class="message-display">
        <?php
        if (isset($_SESSION['error_msg'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ' . $_SESSION['error_msg'] . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            unset($_SESSION['error_msg']);
        }
        if (isset($_SESSION['success_msg'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    ' . $_SESSION['success_msg'] . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            unset($_SESSION['success_msg']);
        }
        ?>
    </div>

    <div class="product-list-header">
        <h1>Service List</h1>
    </div>
    
    <div class="product-list-controls">
        <div class="entries-control">
            Show 
            <select class="entries-select" onchange="updateEntries(this.value)">
                <option value="10" <?php echo $entries_per_page == 10 ? 'selected' : ''; ?>>10</option>
                <option value="25" <?php echo $entries_per_page == 25 ? 'selected' : ''; ?>>25</option>
                <option value="50" <?php echo $entries_per_page == 50 ? 'selected' : ''; ?>>50</option>
                <option value="100" <?php echo $entries_per_page == 100 ? 'selected' : ''; ?>>100</option>
            </select>
            entries
        </div>
        
        <div class="search-control">
            <input type="text" class="search-input" placeholder="Search..." 
                   value="<?php echo htmlspecialchars($search_query); ?>" 
                   onkeyup="searchProducts(this.value)">
            <i class="fas fa-search search-icon"></i>
        </div>
    </div>

    <table class="product-table">
        <thead>
            <tr>
                <th>Service <i class="fas fa-sort sort-icon"></i></th>
                <th>Description <i class="fas fa-sort sort-icon"></i></th>
                <th>Price <i class="fas fa-sort sort-icon"></i></th>
                <th>Quantity <i class="fas fa-sort sort-icon"></i></th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($current_products): ?>
                <?php foreach ($current_products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['product_description']); ?></td>
                        <td>$<?php echo number_format($product['product_price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($product['product_quantity']); ?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-edit" onclick="openEditModal(<?php echo $product['product_id']; ?>)"><i class="fas fa-edit"></i></button>
                                <button class="btn-delete" onclick="openDeleteModal(<?php echo $product['product_id']; ?>)"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No products found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="product-pagination">
        <?php if ($total_pages > 1): ?>
            <button class="pagination-button" 
                    onclick="changePage(<?php echo max(1, $current_page - 1); ?>)"
                    <?php echo $current_page == 1 ? 'disabled' : ''; ?>>
                Previous
            </button>
            
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <button class="pagination-button <?php echo $current_page == $i ? 'active' : ''; ?>"
                        onclick="changePage(<?php echo $i; ?>)">
                    <?php echo $i; ?>
                </button>
            <?php endfor; ?>
            
            <button class="pagination-button" 
                    onclick="changePage(<?php echo min($total_pages, $current_page + 1); ?>)"
                    <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>>
                Next
            </button>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Product Modal -->
<div class="modal" id="editProductModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    <input type="hidden" id="editProductId" name="product_id">
                    <div class="form-group">
                        <label for="editProductName">Product Name</label>
                        <input type="text" class="form-control" id="editProductName" name="product_name" required>
                    </div>
                    <div class="form-group">
                        <label for="editProductDescription">Description</label>
                        <textarea class="form-control" id="editProductDescription" name="product_description" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editProductPrice">Price</label>
                        <input type="number" class="form-control" id="editProductPrice" name="product_price" required>
                    </div>
                    <div class="form-group">
                        <label for="editProductQuantity">Quantity</label>
                        <input type="number" class="form-control" id="editProductQuantity" name="product_quantity" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Product Modal -->
<div class="modal" id="deleteProductModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this product?</p>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Yes, delete</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script src="../../js/product.js"></script>

<?php include '../includes/footer.php'; ?>