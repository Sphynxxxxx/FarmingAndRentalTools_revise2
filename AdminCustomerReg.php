<?php

@include 'config.php';

// Approve customer
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $stmt = $conn->prepare("UPDATE customer SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: AdminCustomerReg.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

// Decline customer
if (isset($_GET['decline'])) {
    $id = intval($_GET['decline']);
    $stmt = $conn->prepare("UPDATE customer SET status = 'declined' WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: AdminCustomerReg.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

// Delete customer
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    
    $select_image = $conn->prepare("SELECT images FROM customer WHERE id = ?");
    $select_image->bind_param("i", $id);
    $select_image->execute();
    $result = $select_image->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = 'Cus_uploads/' . $row['images']; 
        if (file_exists($image_path)) {
            unlink($image_path); 
        }
    }

    // Delete the record from the database
    $delete_stmt = $conn->prepare("DELETE FROM customer WHERE id = ?");
    $delete_stmt->bind_param("i", $id);
    if ($delete_stmt->execute()) {
        header('Location: AdminCustomerReg.php');
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $delete_stmt->close();
}

// Fetch pending customer
$pending_result = $conn->query("SELECT * FROM customer WHERE status = 'pending'");

// Fetch approved customer
$approved_result = $conn->query("SELECT * FROM customer WHERE status = 'approved'");

// Fetch declined customer
$declined_result = $conn->query("SELECT * FROM customer WHERE status = 'declined'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval</title>
    <link rel="stylesheet" href="css\Adminstyles.css?v=1.0">
</head>
<body>

    <div class="sidebar">
        <a href="AdminProductsApproval.php">Products</a>
        <a href="AdminCustomerReg.php">Customer Admin Approval</a>
        <a href="AdminLenderReg.php">Lender Admin Approval</a>
    </div>
    
    <h2>Customer Admin Approval</h2>
    <div class="container">

        <!-- Pending customers -->
        <div class="table">
            <h2>Pending customer Registrations</h2>
            <?php if ($pending_result && $pending_result->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $pending_result->fetch_assoc()): ?>
                        <li>
                            <?php echo htmlspecialchars($row['name']); ?> - 
                            <?php echo htmlspecialchars($row['contact_number']); ?> - 
                            <?php echo htmlspecialchars($row['address']); ?> - 
                            <?php echo htmlspecialchars($row['email']); ?>
                            <img src="<?php echo htmlspecialchars($row['images']); ?>" alt="Image" style="width:200px;height:200px;">

                            <a href="?approve=<?php echo $row['id']; ?>">Approve</a> | 
                            <a href="?decline=<?php echo $row['id']; ?>">Decline</a> |
                            <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No pending customer registrations.</p>
            <?php endif; ?>
        </div>

        <!-- Approved customers -->
        <div class="table">
            <h2>Verified Customer Registrations</h2>
            <?php if ($approved_result && $approved_result->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $approved_result->fetch_assoc()): ?>
                        <li>
                            <?php echo htmlspecialchars($row['name']); ?> - 
                            <?php echo htmlspecialchars($row['contact_number']); ?> - 
                            <?php echo htmlspecialchars($row['address']); ?> - 
                            <?php echo htmlspecialchars($row['email']); ?>
                            <img src="<?php echo htmlspecialchars($row['images']); ?>" alt="Image" style="width:200px;height:200px;">
                            <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No approved customer registrations.</p>
            <?php endif; ?>
        </div>

        <!-- Declined customers -->
        <div class="table">
            <h2>Declined Customer Registrations</h2>
            <?php if ($declined_result && $declined_result->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $declined_result->fetch_assoc()): ?>
                        <li>
                            <?php echo htmlspecialchars($row['name']); ?> - 
                            <?php echo htmlspecialchars($row['contact_number']); ?> - 
                            <?php echo htmlspecialchars($row['address']); ?> - 
                            <?php echo htmlspecialchars($row['email']); ?>
                            <img src="<?php echo htmlspecialchars($row['images']); ?>" alt="Image" style="width:200px;height:200px;">
                            <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this customer?');">Delete</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No declined customer registrations.</p>
            <?php endif; ?>
        </div>

    </div>

    <?php $conn->close(); ?>

    
</body>
</html>
