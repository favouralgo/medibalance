<?php
require_once('../controllers/product_controller.php');


// Delete brand
if (isset($_GET['delete'])) {
    $productId = $_GET['delete'];
    $delete_product = delete_product_ctr($productId);
    if ($delete_product !== false) {
        header("Location: ../view/product.php");
    } else {
        echo "Failed to delete brand";
    }
}


?>