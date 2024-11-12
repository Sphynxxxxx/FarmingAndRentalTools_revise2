<?php
session_start();
@include 'config.php';

if (!isset($_SESSION['email'])) {
    header('Location: Login.php'); 
    exit();
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

        $delete_stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $delete_stmt->bind_param("i", $id);
        $delete_stmt->execute();
        $delete_stmt->close();
    }

    header('Location: LenderDashboard2.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="css/style.css?v=1.0">
</head>
<body>

<nav>
    <ul>
        <li><a href="LenderDashboard.php">Dashboard</a></li>
        <li><a href="Profile.php">Profile</a></li>
        <li><a href="Logout.php">Logout</a></li>
    </ul>
</nav>

<div class="product-display">
    <table class="product-display-table">
        <thead>
        <tr>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Lender Name</th>
            <th>Location</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Rent Price</th>
            <th>Shipping Fee</th>
            <th>Status</th> 
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $select = $conn->query("SELECT * FROM products");
        while ($row = $select->fetch_assoc()) { ?>
            <tr>
                <td><img src="uploaded_img/<?php echo htmlspecialchars($row['image']); ?>" height="100" alt=""></td>
                <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                <td><?php echo htmlspecialchars($row['lender_name']); ?></td>
                <td><?php echo htmlspecialchars($row['location']); ?></td>
                <td class="description"><?php echo htmlspecialchars($row['description']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td>₱<?php echo htmlspecialchars($row['price']); ?></td>
                <td>₱<?php echo htmlspecialchars($row['shippingfee']); ?></td> 
                <td><?php echo htmlspecialchars($row['status']); ?></td> 
                <td>
                    <a href="Lender.php?edit=<?php echo $row['id']; ?>" class="btn"><i class="fas fa-edit"></i> Edit</a>
                    <a href="LenderDashboard2.php?delete=<?php echo $row['id']; ?>" class="btn" onclick="return confirm('Are you sure you want to delete this product?');"><i class="fas fa-trash"></i> Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>
