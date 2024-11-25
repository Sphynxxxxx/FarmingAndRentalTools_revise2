<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: CustomerDashboard.php");
    exit();
}

// Check if customer ID exists in session
if (!isset($_SESSION['customer_id'])) {
    echo "Customer ID is not set in session.";
    exit();
}

$customerId = $_SESSION['customer_id'];  // Ensure you are storing the user ID in the session

// Check if order data is received
if (isset($_POST['order_data'])) {
    $orderItems = json_decode($_POST['order_data'], true);
    
    // Validate the order data
    if (empty($orderItems)) {
        echo "No items in the order.";
        exit();
    }

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Insert the order into the `orders` table
        $stmt = $conn->prepare("INSERT INTO orders (customer_id, order_date) VALUES (?, NOW())");
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $orderId = $stmt->insert_id;  // Get the ID of the inserted order
        $stmt->close();

        // Now insert the order items into the `order_items` table
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, shipping_fee) VALUES (?, ?, ?, ?, ?)");

        foreach ($orderItems as $item) {
            if (isset($item['id'], $item['quantity'], $item['price'], $item['shippingFee'])) {
                $stmt->bind_param("iiidd", $orderId, $item['id'], $item['quantity'], $item['price'], $item['shippingFee']);
                $stmt->execute();
            } else {
                echo "Missing item details.";
                exit();
            }
        }

        // Commit the transaction
        $conn->commit();

        // Order placed successfully
        header("Location: order_confirmation.php"); // Redirect to confirmation page
        exit();

    } catch (Exception $e) {
        // Rollback in case of error
        $conn->rollback();
        echo "Error placing order: " . $e->getMessage();
    }
} else {
    echo "Invalid order data.";
}
?>
