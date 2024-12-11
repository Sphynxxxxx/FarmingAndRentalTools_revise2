<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Parse JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['orderDetails'])) {
    echo json_encode(['success' => false, 'message' => 'No order details provided.']);
    exit();
}

$orderDetails = $data['orderDetails'];
$deliveryMethod = $data['deliveryMethod'] ?? 'pickup';
$email = $_SESSION['email'];

// Fetch customer ID based on the email
$sqlCustomer = "SELECT customer_id FROM customer WHERE email = ?";
$stmtCustomer = $conn->prepare($sqlCustomer);
$stmtCustomer->bind_param("s", $email);
$stmtCustomer->execute();
$resultCustomer = $stmtCustomer->get_result();

if ($resultCustomer->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Customer not found!']);
    exit();
}

$customer = $resultCustomer->fetch_assoc();
$customer_id = $customer['customer_id'];

// Generate a unique reference number
$referenceNumber = "REF-" . date("Ymd") . "-" . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));

$conn->begin_transaction();

try {
    // Calculate total price for the order
    $totalPrice = 0;
    foreach ($orderDetails as $item) {
        $totalPrice += $item['quantity'] * $item['price'];
    }

    // Define the initial order status
    $order_status = 'pending';

    // Insert the order into the `orders` table
    $stmtOrder = $conn->prepare("INSERT INTO orders (customer_id, reference_number, delivery_method, total_price, status) VALUES (?, ?, ?, ?, ?)");
    $stmtOrder->bind_param("issds", $customer_id, $referenceNumber, $deliveryMethod, $totalPrice, $order_status);
    if (!$stmtOrder->execute()) {
        throw new Exception("Failed to insert order.");
    }
    $order_id = $conn->insert_id; // Get the inserted order ID

    // Insert order details into `order_details` table
    $stmtOrderDetails = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($orderDetails as $item) {
        // Validate and format dates
        $startDate = isset($item['start_date']) ? date('Y-m-d', strtotime($item['start_date'])) : NULL;
        $endDate = isset($item['end_date']) ? date('Y-m-d', strtotime($item['end_date'])) : NULL;

        // Insert the order details
        $stmtOrderDetails->bind_param("iiidss", $order_id, $item['id'], $item['quantity'], $item['price'], $startDate, $endDate);
        if (!$stmtOrderDetails->execute()) {
            throw new Exception("Failed to insert order details.");
        }

        // Update the product stock
        $stmtUpdateStock = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
        $stmtUpdateStock->bind_param("ii", $item['quantity'], $item['id']);
        if (!$stmtUpdateStock->execute()) {
            throw new Exception("Failed to update product stock.");
        }
    }

    // Create the notification message
    $notification_message = "You have successfully placed your order.";
    $notification_title = "Order Status Update";
    $notification_date = date('Y-m-d H:i:s');

    // Insert the notification into the `notifications` table
    $stmtNotification = $conn->prepare("INSERT INTO notifications (customer_id, title, message, date_created, order_id) VALUES (?, ?, ?, ?, ?)");
    $stmtNotification->bind_param("isssi", $customer_id, $notification_title, $notification_message, $notification_date, $order_id);
    if (!$stmtNotification->execute()) {
        throw new Exception("Failed to insert notification.");
    }

    // Commit the transaction
    $conn->commit();

    // Success response
    echo json_encode([
        'success' => true,
        'message' => 'Order placed successfully!',
        'referenceNumber' => $referenceNumber,
        'deliveryMethod' => $deliveryMethod,
        'totalPrice' => $totalPrice,
        'orderStatus' => $order_status
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
