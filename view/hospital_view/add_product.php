<?php include '../includes/header.php'; ?>

<div class="add-product-page">
    <!-- Page Header -->
    <div class="product-header">
        <p class="text-muted">Create a new product or service by filling out the information below</p>
    </div>
    <!--Error messages display-->
        <div class="error-message-display">
            <?php
            if (isset($_SESSION['error_msg'])) {
                echo '<div class="error-alert error-alert-danger alert-dismissible fade show" role="alert">
                        ' . $_SESSION['error_msg'] . '
                        <button type="button" class="error-btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                unset($_SESSION['error_msg']);
            }
            if (isset($_SESSION['success_msg'])) {
                echo '<div class="error-alert error-alert-success alert-dismissible fade show" role="alert">
                        ' . $_SESSION['success_msg'] . '
                        <button type="button" class="error-btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                unset($_SESSION['success_msg']);
            }
            ?>
        </div>
    <!-- Product Form Card -->
    <div class="product-form-card card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-box-open me-2 text-primary"></i>
                Product or Service Information
            </h5>
        </div>
        <div class="card-body p-4">
            <form id="addProductForm" action="../../actions/add_product_action.php" method="POST">
                <div class="row g-4">
                    <!-- Product Name -->
                    <div class="col-md-4">
                        <div class="product-form-group">
                            <label class="form-label">Product or Service Name</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-tag"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       name="product_name" 
                                       placeholder="Enter product name"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Product Price -->
                    <div class="col-md-4">
                        <div class="product-form-group">
                            <label class="form-label">Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" 
                                       class="form-control" 
                                       name="product_price" 
                                       placeholder="0.00"
                                       step="0.01"
                                       min="0"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Product Quantity -->
                    <div class="col-md-4">
                        <div class="product-form-group">
                            <label class="form-label">Quantity</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-cubes"></i>
                                </span>
                                <input type="number" 
                                       class="form-control" 
                                       name="product_quantity" 
                                       placeholder="Enter quantity"
                                       min="0"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Product Description -->
                    <div class="col-12">
                        <div class="product-form-group">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" 
                                      name="product_description" 
                                      rows="4" 
                                      placeholder="Enter product description"
                                      required></textarea>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="submit" class="btn-add">
                        Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>