<?php
session_start();


@include 'config.php';


if (!isset($_SESSION['email'])) {
    header('Location: Login.php'); 
    exit();
}

if (isset($_POST['add_product'])) {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $lender_name = mysqli_real_escape_string($conn, $_POST['lender_name']);  
    $location = mysqli_real_escape_string($conn, $_POST['location']);  
    $description = mysqli_real_escape_string($conn, $_POST['description']);  
    $quantity = intval($_POST['quantity']);  // Get quantity from form
    $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
    $shipping_fee = mysqli_real_escape_string($conn, $_POST['shippingfee']);
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'uploaded_img/' . basename($product_image); 
    $status = 'pending';

    if (empty($product_name) || empty($lender_name) || empty($location) || empty($product_price) || empty($shipping_fee) || empty($product_image)) {
        $message[] = 'Please fill out all fields';
    } else {
        $insert = $conn->prepare("INSERT INTO products (product_name, lender_name, location, description, quantity, price, shippingfee, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param("ssssisdss", $product_name, $lender_name, $location, $description, $quantity, $product_price, $shipping_fee, $product_image, $status);

        if ($insert->execute()) {
            if (move_uploaded_file($product_image_tmp_name, $product_image_folder)) {
                $message[] = 'New product added successfully';
            } else {
                $message[] = 'Failed to upload image';
            }
        } else {
            $message[] = 'Could not add the product';
        }
        $insert->close();

        header('Location: LenderDashboard.php');
        exit();
    }
}



if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']); 
    $select_image = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $select_image->bind_param("i", $id);
    $select_image->execute();
    $result = $select_image->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        
        if (file_exists('uploaded_img/' . $row['image'])) {
            unlink('uploaded_img/' . $row['image']);
        }

        // Delete the product from the database
        $delete_stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $delete_stmt->bind_param("i", $id);
        $delete_stmt->execute();
        $delete_stmt->close();
    }

    header('Location: LenderDashboard.php');
    exit();
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
        <li>
            <button onclick="window.location.reload();" class="refresh-btn">Refresh</button>
        </li>
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

<!-- Product Form -->
<div class="container">
    <div class="admin-product-form-container">
        <form action="LenderDashboard.php" method="post" enctype="multipart/form-data">
            <h3>Add a New Product</h3>
            <input type="text" placeholder="Enter Product Name" name="product_name" class="box" required>
            <input type="text" placeholder="Enter Lender Name" name="lender_name" class="box" required>
            <input type="text" placeholder="Location" name="location" class="box" required>
            <input type="text" placeholder="Description" name="description" class="box" required>
            <input type="number" placeholder="Quantity" name="quantity" class="box" required>
            <input type="number" placeholder="Enter Rent Price" name="product_price" class="box" required>
            <input type="number" placeholder="Enter Shipping Fee" name="shippingfee" class="box" required>
            <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" class="box" required>
            <input type="submit" class="btn" name="add_product" value="Add Product">
            <a href="LenderDashboard2.php" class="btn">View Product List</a>
        </form>
        
    </div>
    
</body>
</html>