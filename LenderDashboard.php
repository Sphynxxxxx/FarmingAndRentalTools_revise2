<?php
session_start();
@include 'config.php';

if (!isset($_SESSION['email'])) {
    header('Location: Login.php');
    exit();
}

// Add a product
if (isset($_POST['add_product'])) {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $lender_name = mysqli_real_escape_string($conn, $_POST['lender_name']);  
    $location = mysqli_real_escape_string($conn, $_POST['location']);  
    $description = mysqli_real_escape_string($conn, $_POST['description']);  
    $quantity = intval($_POST['quantity']);
    $rent_days = intval($_POST['rent_days']);
    $categories = mysqli_real_escape_string($conn, $_POST['categories']);
    $product_price = mysqli_real_escape_string($conn, $_POST['product_price']);
    $shipping_fee = mysqli_real_escape_string($conn, $_POST['shippingfee']);
    $product_image = $_FILES['product_image']['name'];
    $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
    $product_image_folder = 'uploaded_img/' . basename($product_image); 
    $status = 'pending';

    // Check if all fields are filled
    if (empty($product_name) || empty($lender_name) || empty($location) || empty($description) || empty($quantity) || empty($categories) || empty($product_price) || empty($shipping_fee) || empty($product_image)) {
        $message[] = 'Please fill out all fields';
    } else {
        // Prepare SQL query
        $insert = $conn->prepare("INSERT INTO products (product_name, lender_name, location, description, quantity, rent_days, categories, price, shippingfee, image, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert->bind_param("ssssisdssss", $product_name, $lender_name, $location, $description, $quantity, $rent_days, $categories, $product_price, $shipping_fee, $product_image, $status);
        
        if ($insert->execute()) {
            // Move the uploaded file to the folder
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

// Delete product functionality
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $select_image = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $select_image->bind_param("i", $id);
    $select_image->execute();
    $result = $select_image->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        // Delete the image file if it exists
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
            
            <select id="register-address" name="location" required>
                    <option value="" disabled selected>Select Barangay</option>
                    <option value="Abangay">Abangay</option>
                    <option value="Amamaros">Amamaros</option>
                    <option value="Bagacay">Bagacay</option>
                    <option value="Barasan">Barasan</option>
                    <option value="Batuan">Batuan</option>
                    <option value="Bongco">Bongco</option>
                    <option value="Cahaguichican">Cahaguichican</option>
                    <option value="Callan">Callan</option>
                    <option value="Cansilayan">Cansilayan</option>
                    <option value="Casalsagan">Casalsagan</option>
                    <option value="Cato-ogan">Cato-ogan</option>
                    <option value="Cau-ayan">Cau-ayan</option>
                    <option value="Culob">Culob</option>
                    <option value="Danao">Danao</option>
                    <option value="Dapitan">Dapitan</option>
                    <option value="Dawis">Dawis</option>
                    <option value="Dongsol">Dongsol</option>
                    <option value="Fernando Parcon Ward">Fernando Parcon Ward</option>
                    <option value="Guibuangan">Guibuangan</option>
                    <option value="Guinacas">Guinacas</option>
                    <option value="Igang">Igang</option>
                    <option value="Intaluan">Intaluan</option>
                    <option value="Iwa Ilaud">Iwa Ilaud</option>
                    <option value="Iwa Ilaya">Iwa Ilaya</option>
                    <option value="Jamabalud">Jamabalud</option>
                    <option value="Jebioc">Jebioc</option>
                    <option value="Lay-ahan">Lay-ahan</option>
                    <option value="Lopez Jaena Ward">Lopez Jaena Ward</option>
                    <option value="Lumbo">Lumbo</option>
                    <option value="Macatol">Macatol</option>
                    <option value="Malusgod">Malusgod</option>
                    <option value="Nabitasan">Nabitasan</option>
                    <option value="Naga">Naga</option>
                    <option value="Nanga">Nanga</option>
                    <option value="Naslo">Naslo</option>
                    <option value="Pajo">Pajo</option>
                    <option value="Palanguia">Palanguia</option>
                    <option value="Pitogo">Pitogo</option>
                    <option value="Primitivo Ledesma Ward">Primitivo Ledesma Ward</option>
                    <option value="Purog">Purog</option>
                    <option value="Rumbang">Rumbang</option>
                    <option value="San Jose Ward">San Jose Ward</option>
                    <option value="Sinuagan">Sinuagan</option>
                    <option value="Tuburan">Tuburan</option>
                    <option value="Tumcon Ilaud">Tumcon Ilaud</option>
                    <option value="Tumcon Ilaya">Tumcon Ilaya</option>
                    <option value="Ubang">Ubang</option>
                    <option value="Zarrague">Zarrague</option>
            </select>
            <input type="text" placeholder="Description" name="description" class="box" required>
            <input type="number" placeholder="Rent Days" name="rent_days" class="box" required>
            <input type="number" placeholder="Quantity" name="quantity" class="box" required>
            <select id="Categories" name="categories" required>
                <option value="" disabled selected>Categories</option>
                <option value="Hand Tools">Hand Tools</option>
                <option value="Ploughs">Ploughs</option>
                <option value="Seeding Tools">Seeding Tools</option>
                <option value="Harvesting Tools">Harvesting Tools</option>
                <option value="Tilling Tools">Tilling Tools</option>
                <option value="Cutting Tools">Cutting Tools</option>
                <option value="Garden Tools">Garden Tools</option>
            </select>
            <input type="number" placeholder="Enter Rent Price" name="product_price" class="box" required>
            <input type="number" placeholder="Enter Shipping Fee" name="shippingfee" class="box" required>
            <input type="file" accept="image/png, image/jpeg, image/jpg" name="product_image" class="box" required>
            <input type="submit" class="btn" name="add_product" value="Add Product">
            <a href="LenderDashboard2.php" class="btn">View Product List</a>
        </form>
    </div>
</div>

</body>
</html>
