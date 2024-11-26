<?php

@include 'config.php';

// Approve lender
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    $stmt = $conn->prepare("UPDATE lender SET status = 'approved' WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: AdminLenderReg.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

// Decline lender
if (isset($_GET['decline'])) {
    $id = intval($_GET['decline']);
    $stmt = $conn->prepare("UPDATE lender SET status = 'declined' WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header('Location: AdminLenderReg.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
    $stmt->close();
}

// Delete lender
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    
    $select_image = $conn->prepare("SELECT images FROM lender WHERE id = ?");
    $select_image->bind_param("i", $id);
    $select_image->execute();
    $result = $select_image->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = 'Lender_uploads/' . $row['images']; 
        if (file_exists($image_path)) {
            unlink($image_path); 
        }
    }

    // Delete the record from the database
    $delete_stmt = $conn->prepare("DELETE FROM lender WHERE id = ?");
    $delete_stmt->bind_param("i", $id);
    if ($delete_stmt->execute()) {
        header('Location: AdminLenderReg.php');
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $delete_stmt->close();
}

// Fetch pending lenders
$pending_result = $conn->query("SELECT * FROM lender WHERE status = 'pending'");

// Fetch approved lenders
$approved_result = $conn->query("SELECT * FROM lender WHERE status = 'approved'");

// Fetch declined lenders
$declined_result = $conn->query("SELECT * FROM lender WHERE status = 'declined'");
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
        <a href="AdminCustomerReg.php">Customer Admin Approval</a>
        <a href="Admin.php">Back to Dashboard</a>
    </div>
    
    <h2>Lender Admin Approval</h2>
    <div class="container">

        <!-- Pending Lenders -->
        <div class="table">
            <h2>Pending Lender Registrations</h2>
            <?php if ($pending_result && $pending_result->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $pending_result->fetch_assoc()): ?>
                        <li>
                            <?php echo htmlspecialchars($row['lender_name']); ?> - 
                            <?php echo htmlspecialchars($row['contact_number']); ?> - 
                            <?php echo htmlspecialchars($row['address']); ?> - 
                            <?php echo htmlspecialchars($row['email']); ?>
                            <img src="<?php echo htmlspecialchars($row['images']); ?>" alt="Image" style="width:200px;height:200px;">

                            <a href="?approve=<?php echo $row['id']; ?>">Approve</a> | 
                            <a href="?decline=<?php echo $row['id']; ?>">Decline</a> |
                            <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this lender?');">Delete</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No pending lender registrations.</p>
            <?php endif; ?>
        </div>

        <!-- Approved Lenders -->
        <div class="table">
            <h2>Verified Lender Registrations</h2>
            <?php if ($approved_result && $approved_result->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $approved_result->fetch_assoc()): ?>
                        <li>
                            <?php echo htmlspecialchars($row['lender_name']); ?> - 
                            <?php echo htmlspecialchars($row['contact_number']); ?> - 
                            <?php echo htmlspecialchars($row['address']); ?> - 
                            <?php echo htmlspecialchars($row['email']); ?>
                            <img src="<?php echo htmlspecialchars($row['images']); ?>" alt="Image" style="width:200px;height:200px;">
                            <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this lender?');">Delete</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No approved lender registrations.</p>
            <?php endif; ?>
        </div>

        <!-- Declined Lenders -->
        <div class="table">
            <h2>Declined Lender Registrations</h2>
            <?php if ($declined_result && $declined_result->num_rows > 0): ?>
                <ul>
                    <?php while ($row = $declined_result->fetch_assoc()): ?>
                        <li>
                            <?php echo htmlspecialchars($row['lender_name']); ?> - 
                            <?php echo htmlspecialchars($row['contact_number']); ?> - 
                            <?php echo htmlspecialchars($row['address']); ?> - 
                            <?php echo htmlspecialchars($row['email']); ?>
                            <img src="<?php echo htmlspecialchars($row['images']); ?>" alt="Image" style="width:200px;height:200px;">
                            <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this lender?');">Delete</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No declined lender registrations.</p>
            <?php endif; ?>
        </div>

    </div>

    <?php $conn->close(); ?>

    
</body>
</html>
