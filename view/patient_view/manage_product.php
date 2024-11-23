<?php 
include '../pincludes/header.php';
// Ensure session is started and customer is logged in
if (!isset($_SESSION['customer_id'])) {
    header('Location: ../../login/login.php');
    exit;
}

require_once('../../controllers/product_controller.php');

// Get search and entries parameters with proper defaults
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$entries = isset($_GET['entries']) ? (int)$_GET['entries'] : 10;

// Initialize Product Controller
$productController = new ProductController();

// Get customer-specific products
$result = $productController->get_customer_products_ctr($_SESSION['customer_id'], $search, $entries);

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
        <h1>My Services</h1>
    </div>
    
    <!-- Search and Entries Controls -->
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
                   value="<?php echo htmlspecialchars($search); ?>">
            <i class="fas fa-search search-icon" id="searchButton"></i>
        </div>
    </div>

        <!-- Products Table -->
        <div class="product-list-header">
            <p class="text-muted">Services assigned to you by your healthcare provider</p>
        </div>

            <!-- Products Table -->
            <table class="product-table">
                <thead>
                    <tr>
                        <th>Service <i class="fas fa-sort sort-icon"></i></th>
                        <th>Description <i class="fas fa-sort sort-icon"></i></th>
                        <th>Price <i class="fas fa-sort sort-icon"></i></th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result['success'] && !empty($result['data'])): ?>
                        <?php foreach ($result['data'] as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($product['product_description']); ?></td>
                                <td>$<?php echo number_format($product['product_price'], 2); ?></td>
                                <td>
                                    <button onclick="viewProductDetails(<?php echo $product['product_id']; ?>)" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">
                                <div class="no-services-message">
                                    <i class="fas fa-info-circle mb-2"></i>
                                    <p>No services have been assigned to you yet.</p>
                                    <p class="text-muted">Please contact your healthcare provider for more information.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
    </div>

<!-- View Product Details Modal -->
<div class="modal fade" id="viewProductModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Service Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="productDetails">
                    <!-- Product details will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>


<script src="../../js/product.js"></script>

<?php include '../pincludes/footer.php'; ?>