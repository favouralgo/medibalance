<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/product.css">
    <title>Product</title>
</head>
<body>
    <h1>Add Product</h1>
    <a href="../login/logout.php" class="btn btn-secondary">Logout</a>
    <!--Add Product Start-->
    <form action="../actions/add_product_action.php" method="post">
        <select name="category" required>
            <option value="" disabled selected>Select Product Category</option>
            <?php
            require_once("../controllers/categories_controller.php");
            $result = view_category_ctr();
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['cat_id'] . "'>" . $row['cat_name'] . "</option>";
            }
            ?>
        </select> 
        <input type="text" name="title" placeholder="Product Title">
        <select name="brand">
            <option value="" disabled selected>Select Brand</option>
            <?php
            require_once("../controllers/brand_controller.php");
            $result = view_brands_ctr();
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['brand_id'] . "'>" . $row['brand_name'] . "</option>";
            }
            ?>
        </select>        
        <input type="number" name="price" placeholder="Product Price"> 
        <input type="text" name="description" placeholder="Product Description">
        <input type="text" name="keywords" placeholder="Product keywords">
        <input type="submit" value="Add Product">
    </form>
<!--Add Product End-->    
    
<!--Delete Product Start-->
<h1>Delete Product</h1>
    <form action="../actions/delete_product_action.php" method="get">
        <select name="delete">
            <option value="" disabled selected>Select Product to Delete</option>
            <?php
            require_once("../controllers/product_controller.php");
            $result = view_product_ctr();
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['product_id'] . "'>" . $row['product_title'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" value="Delete Product">
    </form>

<!--Delete Product End-->


<!--View Product Start-->
<h1>View Product</h1>
    <table>
        <tr>
            <th>Product Title</th>
            <th>Brand Name</th>
            <th>Action</th>
        </tr>

        <?php
        require_once("../controllers/product_controller.php");

        $result = view_product_ctr();
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['product_title'] . "</td>";
                echo "<td>" . $row['brand_name'] . "</td>";
                echo "<td><a href='../actions/delete_product_action.php?delete=" . $row['product_id'] . "'>Delete</a></td>";
                echo "<td>
                        <form action='../actions/add_cart_action.php' method='post'>
                            <input type='hidden' name='product_id' value='" . $row['product_id'] . "'>
                            <input type='hidden' name='quantity' value='1'>
                            <input type='submit' value='Add to Cart'>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No products found</td></tr>";
        }
        
        ?>
    </table>
<!--View Product End-->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>
</html>