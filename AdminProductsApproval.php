<?php

@include 'config.php';

// Approve product
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $stmt = $conn->prepare("UPDATE products SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: AdminProductsApproval.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

// Decline product
if (isset($_GET['decline'])) {
    $id = intval($_GET['decline']);
    $stmt = $conn->prepare("UPDATE products SET status = 'declined' WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: AdminProductsApproval.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

// Delete product
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Select image path
    $select_image = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $select_image->bind_param("i", $id);
    $select_image->execute();
    $result = $select_image->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = 'uploads/' . $row['image']; 
        if (file_exists($image_path)) {
            unlink($image_path); // Remove the product image from server
        }
    }

    // Delete product from the database
    $delete_stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $delete_stmt->bind_param("i", $id);
    if ($delete_stmt->execute()) {
        header('Location: AdminProductsApproval.php');
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $delete_stmt->close();
}

// Fetch pending products
$pending_result = $conn->query("SELECT * FROM products WHERE status = 'pending'");

// Fetch approved products
$approved_result = $conn->query("SELECT * FROM products WHERE status = 'approved'");

// Fetch declined products
$declined_result = $conn->query("SELECT * FROM products WHERE status = 'declined'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Product Approval</title>
    <link rel="stylesheet" href="css\Adminstyles.css?v=1.0">
</head>
<body>

    <div class="sidebar">
        <a href="AdminProductsApproval.php">Products</a>
        <a href="CustomerAdminApproval.php">Customer Admin Approval</a>
        <a href="AdminLenderReg.php">Lender Admin Approval</a>
    </div>
    
    <h2>Product Admin Approval</h2>
    <div class="container2">

        <!-- Pending Products -->
        <div class="section">
            <h2>Pending Product Registrations</h2>
            <?php if ($pending_result && $pending_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Shipping Fee</th>
                            <th>Lender Name</th>
                            <th>Location</th>
                            <th>Product Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $pending_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td>₱<?php echo htmlspecialchars($row['price']); ?></td>
                                <td>₱<?php echo htmlspecialchars($row['shippingfee']); ?></td>
                                <td><?php echo htmlspecialchars($row['lender_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['location']); ?></td>
                                <td><img src="uploaded_img/<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image" style="width:200px;height:200px;"></td>
                                <td>
                                    <a href="?approve=<?php echo $row['id']; ?>">Approve</a> | 
                                    <a href="?decline=<?php echo $row['id']; ?>">Decline</a> |
                                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No pending product registrations.</p>
            <?php endif; ?>
        </div>

        <!-- Approved Products -->
        <div class="section">
            <h2>Approved Products</h2>
            <?php if ($approved_result && $approved_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Shipping Fee</th>
                            <th>Lender Name</th>
                            <th>Location</th>
                            <th>Product Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $approved_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td>₱<?php echo htmlspecialchars($row['price']); ?></td>
                                <td>₱<?php echo htmlspecialchars($row['shippingfee']); ?></td>
                                <td><?php echo htmlspecialchars($row['lender_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['location']); ?></td>
                                <td><img src="uploaded_img/<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image" style="width:100px;height:100px;"></td>
                                <td>
                                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No approved product registrations.</p>
            <?php endif; ?>
        </div>

        <!-- Declined Products -->
        <div class="section">
            <h2>Declined Products</h2>
            <?php if ($declined_result && $declined_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Shipping Fee</th>
                            <th>Lender Name</th>
                            <th>Location</th>
                            <th>Product Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $declined_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td>₱<?php echo htmlspecialchars($row['price']); ?></td>
                                <td>₱<?php echo htmlspecialchars($row['shippingfee']); ?></td>
                                <td><?php echo htmlspecialchars($row['lender_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['location']); ?></td>
                                <td><img src="uploaded_img/<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image" style="width:100px;height:100px;"></td>
                                <td>
                                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No declined product registrations.</p>
            <?php endif; ?>
        </div>

    </div>

    <?php $conn->close(); ?>

</body>
</html>
