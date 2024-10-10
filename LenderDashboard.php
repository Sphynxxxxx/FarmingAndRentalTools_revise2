<?php
session_start();

@include 'config.php';

if (!isset($_SESSION['email'])) {
    header('Location: LenderDashboard.php');
    exit();
}

if (isset($_POST['add_product'])) {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $lender_name = mysqli_real_escape_string($conn, $_POST['lender_name']);  
    $location = mysqli_real_escape_string($conn, $_POST['location']);  
    $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'uploaded_img/' . basename($product_image); 

    $status = 'pending';  //status for new products
    
    if (empty($product_name) || empty($lender_name) || empty($location) || empty($product_price) || empty($product_image)) {
        $message[] = 'Please fill out all fields';
    } else {
        $insert = "INSERT INTO products (product_name, lender_name, location, price, image, status) VALUES ('$product_name', '$lender_name', '$location', '$product_price', '$product_image', '$status')";
        $upload = mysqli_query($conn, $insert);
        if ($upload) {
            if (move_uploaded_file($product_image_tmp_name, $product_image_folder)) {
                $message[] = 'New product added successfully';
            } else {
                $message[] = 'Failed to upload image';
            }
        } else {
            $message[] = 'Could not add the product';
        }
    }
}

// Handle product deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); 
    $select_image = mysqli_query($conn, "SELECT image FROM products WHERE id = $id");
    $row = mysqli_fetch_assoc($select_image);
    unlink('uploaded_img/' . $row['image']); 

    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    header('location:LenderDashboard.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lender Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/style.css?v=1.0">
</head>
<body>

<nav>
    <ul>
        <li><a href="Profile.php">Profile</a></li>
        <li><a href="Logout.php">Logout</a></li>
    </ul>
</nav>

<?php
if (isset($message)) {
    foreach ($message as $msg) {
        echo '<span class="message">' . htmlspecialchars($msg) . '</span>';
    }
}
?>

<div class="container">
    <div class="admin-product-form-container">
        <form action="LenderDashboard.php" method="post" enctype="multipart/form-data">
            <h3>Add a New Product</h3>
            <input type="text" placeholder="Enter Product Name" name="product_name" class="box" required>
            <input type="text" placeholder="Enter Lender Name" name="lender_name" class="box" required>
            <input type="text" placeholder="Location" name="location" class="box" required>
            <input type="number" placeholder="Enter Rent Price" name="product_price" class="box" required>
            <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" class="box" required>
            <input type="submit" class="btn" name="add_product" value="Add Product">
        </form>
    </div>

    <!-- Display products -->
    <?php
    $select = mysqli_query($conn, "SELECT * FROM products");
    ?>
    <div class="product-display">
        <table class="product-display-table">
            <thead>
            <tr>
                <th>Product Image</th>
                <th>Product Name</th>
                <th>Lender Name</th>
                <th>Location</th>
                <th>Rent Price</th>
                <th>Status</th> 
                <th>Action</th>
            </tr>
            </thead>
            <?php while ($row = mysqli_fetch_assoc($select)) { ?>
                <tr>
                    <td><img src="uploaded_img/<?php echo htmlspecialchars($row['image']); ?>" height="100" alt=""></td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['lender_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td>₱<?php echo htmlspecialchars($row['price']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td> 
                    <td>
                        <a href="Lender.php?edit=<?php echo $row['id']; ?>" class="btn"><i class="fas fa-edit"></i> Edit</a>
                        <a href="LenderDashboard.php?delete=<?php echo $row['id']; ?>" class="btn"><i class="fas fa-trash"></i> Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>

</body>
</html>
