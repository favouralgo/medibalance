<?php 
include '../includes/header.php';
require_once('../../controllers/product_controller.php');

// Initialize Product Controller
$productController = new ProductController();

// Get search and entries parameters with proper defaults
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$entries = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;

// Get filtered products
$result = $productController->get_all_products_ctr($search, $entries);
error_log("Products result: " . print_r($result, true));

// Ensure $result has a valid structure
if (!isset($result['success'])) {
    $result = ['success' => false, 'data' => [], 'message' => 'Invalid response from controller'];
}
?>

<div class="product-list-container">
    <!-- Message Display -->
    <div class="message-display">
        <?php
        if (isset($_SESSION['error_msg'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ' . htmlspecialchars($_SESSION['error_msg']) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
            unset($_SESSION['error_msg']);
        }
        if (isset($_SESSION['success_msg'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    ' . htmlspecialchars($_SESSION['success_msg']) . '
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
            <select class="entries-select" id="entriesSelect">
                <option value="10" <?php echo $entries == 10 ? 'selected' : ''; ?>>10</option>
                <option value="25" <?php echo $entries == 25 ? 'selected' : ''; ?>>25</option>
                <option value="50" <?php echo $entries == 50 ? 'selected' : ''; ?>>50</option>
                <option value="100" <?php echo $entries == 100 ? 'selected' : ''; ?>>100</option>
            </select>
            entries
        </div>
        
        <div class="search-control">
                <input type="text" 
                       class="search-input" 
                       id="searchInput" 
                       placeholder="Search..." 
                       value="<?php echo htmlspecialchars($search ?? ''); ?>">
                <i class="fas fa-search search-icon" id="searchButton"></i>
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
                <?php 
                if (isset($result['data']) && !empty($result['data'])): 
                    foreach ($result['data'] as $product): 
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['product_description']); ?></td>
                        <td>$<?php echo number_format((float)$product['product_price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($product['product_quantity']); ?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn-edit" onclick="openEditModal(<?php echo $product['product_id']; ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-delete" onclick="openDeleteModal(<?php echo $product['product_id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php 
                    endforeach; 
                else: 
                ?>
                    <tr>
                        <td colspan="5" class="text-center">No products found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
</div>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm">
                    <input type="hidden" id="editProductId" name="product_id">
                    <div class="mb-3">
                        <label for="editProductName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="editProductName" name="product_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProductDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="editProductDescription" name="product_description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="editProductPrice" class="form-label">Price</label>
                        <input type="number" class="form-control" id="editProductPrice" name="product_price" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="editProductQuantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="editProductQuantity" name="product_quantity" min="0" required>
                    </div>
                    <div class="modal-footer px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
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

<!-- Scripts -->
<script src="../../js/product.js"></script>


<?php include '../includes/footer.php'; ?>