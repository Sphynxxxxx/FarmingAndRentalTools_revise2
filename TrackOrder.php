<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'cart_db2'; 

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to retrieve data from the database, including start_date and end_date
$sql = "
    SELECT 
        p.image AS product_image,
        p.product_name,
        c.name AS customer_name,
        od.price,  
        c.address,
        c.contact_number,
        o.order_date,
        o.delivery_method,
        o.reference_number,
        od.start_date,
        od.end_date,
        o.id AS order_id,
        o.status AS order_status
    FROM 
        order_details od
    JOIN 
        orders o ON od.order_id = o.id
    JOIN 
        customer c ON o.customer_id = c.customer_id
    JOIN 
        products p ON od.product_id = p.id
    ORDER BY 
        o.order_date DESC
";


$result = $conn->query($sql);

// Check if query executed successfully
if (!$result) {
    die("Error executing query: " . $conn->error);
}

echo "<!DOCTYPE html>";
echo "<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css' rel='stylesheet'>
    <title>Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        header {
            margin-bottom: 20px;
        }
        header a {
            text-decoration: none;
        }

        h1 {
            color: #2F5233; 
        }
        
        .back-button {
            text-decoration: none;
            color: #2F5233;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 30px;
            transition: background-color 0.3s;
        }
        .back-button i {
            margin-right: 5px;
        }
        .back-button:hover {
            color: #0056b3;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .order-table th, .order-table td {
            padding: 15px;
            text-align: left;
            font-size: 14px;
            border-bottom: 1px solid #ddd;
        }
        .order-table th {
            background-color: #2F5233;
            color: #fff;
            text-transform: uppercase;
        }
        .order-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .order-table .product-img {
            max-width: 100px;
            height: auto;
            border-radius: 8px;
        }
        .order-table td {
            vertical-align: middle;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .action-buttons button {
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .ready-btn {
            background-color: #28a745;
            color: white;
        }
        .cancel-btn {
            background-color: #dc3545;
            color: white;
        }
        .action-buttons button:hover {
            opacity: 0.8;
        }
        @media (max-width: 768px) {
            .order-table th, .order-table td {
                font-size: 12px;
                padding: 10px;
            }
            .order-table .product-img {
                width: 80px;
                height: auto;
            }
            button {
                font-size: 14px;
                padding: 8px 12px;
            }
        }
        @media (max-width: 480px) {
            .order-table th, .order-table td {
                font-size: 10px;
                padding: 8px;
            }
            button {
                font-size: 12px;
                padding: 6px 10px;
            }
        }
    </style>
</head>
<body>

<div class='container'>
    <div class='main-content'>
        <header>
            <!-- Back button -->
            <a href='Admin.php' class='back-button'><i class='fa-solid fa-house'></i></a>
        </header>
        <h1>Track Orders</h1>";

if ($result->num_rows > 0) {
    // Table header
    // Add a new table column for the status text
echo "
<table class='order-table'>
    <thead>
        <tr>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Customer Name</th>
            <th>Price</th>
            <th>Address</th>
            <th>Contact Number</th>
            <th>Order Date</th>
            <th>Delivery Method</th>
            <th>Reference Number</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Actions</th> <!-- New Actions Column -->
            <th>Status</th> <!-- New Status Column -->
        </tr>
    </thead>
    <tbody>
";

// Add status row for each order
while ($row = $result->fetch_assoc()) {
    echo "
    <tr id='order-{$row['order_id']}'>
        <td><img src='uploaded_img/{$row['product_image']}' alt='{$row['product_name']}' class='product-img' onerror=\"this.src='uploaded_img/default_image.jpg';\"></td>
        <td>{$row['product_name']}</td>
        <td>{$row['customer_name']}</td>
        <td>₱" . number_format($row['price'], 2) . "</td>
        <td>{$row['address']}</td>
        <td>{$row['contact_number']}</td>
        <td>{$row['order_date']}</td>
        <td>" . ucfirst($row['delivery_method']) . "</td>
        <td>{$row['reference_number']}</td>
        <td>{$row['start_date']}</td>
        <td>{$row['end_date']}</td>
        <td>
            <div class='action-buttons'>
                <button class='ready-btn' data-order-id='{$row['order_id']}'>Ready to pick up</button>
                <button class='cancel-btn' data-order-id='{$row['order_id']}'>Cancel</button>
            </div>
        </td>
        <td class='order-status' id='status-{$row['order_id']}'></td> 
    </tr>
    ";
}

echo "</tbody></table>";

} else {
    echo "<p>No orders found.</p>";
}

echo "</div>";  
echo "</div>";  

$conn->close();

echo "
<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>
<script>
$(document).ready(function() {
    // Fetch current status on page load
    fetchOrderStatuses();

    // Handle Ready to pick up button click
    $('.ready-btn').click(function() {
        var orderId = $(this).data('order-id');
        updateOrderStatus(orderId, 'ready_to_pick_up');
        disableOtherButton(orderId, 'ready'); 
    });

    // Handle Cancel button click
    $('.cancel-btn').click(function() {
        var orderId = $(this).data('order-id');
        updateOrderStatus(orderId, 'canceled');
        disableOtherButton(orderId, 'cancel'); 
    });

    // Update order status via AJAX
    function updateOrderStatus(orderId, status) {
        $.ajax({
            url: 'update_order_status.php',
            type: 'POST',
            data: {
                order_id: orderId,
                status: status
            },
            success: function(response) {
                if (response == 'success') {
                    // Display the status in the corresponding status column
                    var displayStatus = (status === 'ready_to_pick_up') ? 'Ready to Pickup' : 'Canceled';
                    $('#status-' + orderId).text(displayStatus);
                    $('#status-' + orderId).css('color', (status === 'ready_to_pick_up') ? 'green' : 'red');
                    alert('Order status updated successfully');
                } else {
                    alert('Failed to update order status');
                }
            },
            error: function() {
                alert('An error occurred while updating the order status');
            }
        });
    }

    // Fetch the current status of orders when the page loads
    function fetchOrderStatuses() {
        $.ajax({
            url: 'fetch_order_status.php', // PHP file to fetch status
            type: 'GET',
            success: function(response) {
                var statuses = JSON.parse(response);
                statuses.forEach(function(order) {
                    // Update the status column for each order
                    var displayStatus = (order.status === 'ready_to_pick_up') ? 'Ready to Pickup' : order.status.charAt(0).toUpperCase() + order.status.slice(1);
                    $('#status-' + order.id).text(displayStatus);
                    $('#status-' + order.id).css('color', order.status === 'ready_to_pick_up' ? 'green' : 'red');
                });
            },
            error: function() {
                alert('An error occurred while fetching the order statuses');
            }
        });
    }

});
</script>

</body>
</html>";
?>
