<?php
// Include your database connection config
include 'config.php';

// Query to fetch all products from the products table
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .container {
            margin: 20px;
        }
        img {
            max-width: 100px; /* Restrict image width */
            height: auto; /* Keep image aspect ratio */
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Products Dashboard</h1>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Description</th>
            <th>Location</th>
            <th>Status</th>
            <th>Image</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Check if the result contains rows
        if ($result->num_rows > 0) {
            // Output data for each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['product_name']) . "</td>";
                echo "<td>₱" . htmlspecialchars($row['price']) . "</td>";
                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                echo "<td>" . htmlspecialchars($row['status']) . "</td>";

                // Image display logic
                if (!empty($row['image'])) {
                    // Make sure the image file path is correct based on your server setup
                    echo "<td><img src='uploaded_img/" . htmlspecialchars($row['image']) . "' alt='Product Image'></td>";
                } else {
                    echo "<td>No image available</td>";
                }
                
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No products found</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
