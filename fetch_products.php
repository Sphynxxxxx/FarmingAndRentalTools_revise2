<?php

include 'config.php'; 

// Update the query to fetch only approved products
$sql = "SELECT * FROM products WHERE status = 'approved'";
$result = $conn->query($sql);

$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Encode the approved products to JSON format
echo json_encode($products);

$conn->close();
?>
