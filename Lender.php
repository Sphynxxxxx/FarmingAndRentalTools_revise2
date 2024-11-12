<?php
@include 'config.php';

$id = isset($_GET['edit']) ? intval($_GET['edit']) : null;

if (!$id) {
    header('Location: LenderDashboard2.php');
    exit();
}

if (isset($_POST['update_product'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_quantity = isset($_POST['product_quantity']) ? intval($_POST['product_quantity']) : 0; 
    $product_image = isset($_FILES['product_image']['name']) ? $_FILES['product_image']['name'] : '';
    $product_image_tmp_name = isset($_FILES['product_image']['tmp_name']) ? $_FILES['product_image']['tmp_name'] : '';
    $product_image_folder = 'uploaded_img/' . $product_image;

    
    if (empty($product_name) || empty($product_price) || $product_quantity < 0) {
        $message[] = 'Please fill out all required fields!';
    } else {
        
        if (!empty($product_image)) {
            
            $allowed_types = ['image/png', 'image/jpeg', 'image/jpg'];
            $file_type = mime_content_type($product_image_tmp_name);

            if (!in_array($file_type, $allowed_types)) {
                $message[] = 'Invalid image format. Please upload a PNG or JPEG image.';
            } else {
                // Update with a new image
                $update_data = "UPDATE products SET product_name='$product_name', price='$product_price', quantity='$product_quantity', image='$product_image' WHERE id='$id'";
                if (mysqli_query($conn, $update_data)) {
                    move_uploaded_file($product_image_tmp_name, $product_image_folder);
                    header('Location: LenderDashboard2.php');
                    exit();
                } else {
                    $message[] = 'Failed to update product. Please try again.';
                }
            }
        } else {
            // Update without a new image
            $update_data = "UPDATE products SET product_name='$product_name', price='$product_price', quantity='$product_quantity' WHERE id='$id'";
            if (mysqli_query($conn, $update_data)) {
                header('Location: LenderDashboard2.php');
                exit();
            } else {
                $message[] = 'Failed to update product. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '<span class="message">' . htmlspecialchars($msg) . '</span>';
    }
}
?>

<div class="container">
   <div class="admin-product-form-container centered">
      <?php
      $select = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
      if ($row = mysqli_fetch_assoc($select)) {
      ?>
      <form action="" method="post" enctype="multipart/form-data">
         <h3 class="title">Update the Product</h3>
         <input type="text" class="box" name="product_name" value="<?php echo htmlspecialchars($row['product_name']); ?>" placeholder="Enter the product name" required>
         <input type="number" min="0" class="box" name="product_price" value="<?php echo htmlspecialchars($row['price']); ?>" placeholder="Enter the product price" required>
         <input type="number" min="0" class="box" name="product_quantity" value="<?php echo htmlspecialchars($row['quantity']); ?>" placeholder="Enter the product quantity" required>
         <input type="file" class="box" name="product_image" accept="image/png, image/jpeg, image/jpg">
         <input type="submit" value="Update Product" name="update_product" class="btn">
         <a href="LenderDashboard2.php" class="btn">Go Back!</a>
      </form>
      <?php } else { ?>
         <p>Product not found.</p>
      <?php } ?>
   </div>
</div>

</body>
</html>
