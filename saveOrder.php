<?php
session_start();
include 'config.php';

if (!isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['orderDetails'])) {
    echo json_encode(['success' => false, 'message' => 'No order details provided.']);
    exit();
}

$orderDetails = $data['orderDetails'];

$email = $_SESSION['email'];
$sqlCustomer = "SELECT id FROM customer WHERE email = ?";
$stmtCustomer = $conn->prepare($sqlCustomer);
$stmtCustomer->bind_param("s", $email);
$stmtCustomer->execute();
$resultCustomer = $stmtCustomer->get_result();

if ($resultCustomer->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Customer not found!']);
    exit();
}

$customer = $resultCustomer->fetch_assoc();
$customer_id = $customer['id'];


$conn->begin_transaction();

try {
    // Insert the order into the `orders` table
    $stmtOrder = $conn->prepare("INSERT INTO orders (customer_id) VALUES (?)");
    $stmtOrder->bind_param("i", $customer_id);
    $stmtOrder->execute();
    $order_id = $conn->insert_id;

    // Prepare the statement for inserting order details
    $stmtOrderDetails = $conn->prepare("INSERT INTO order_details (order_id, product_id, quantity, price, shippingfee) VALUES (?, ?, ?, ?, ?)");

    foreach ($orderDetails as $item) {
        // Insert order details into `order_details` table
        $stmtOrderDetails->bind_param("iiidd", $order_id, $item['id'], $item['quantity'], $item['price'], $item['shippingFee']);
        if (!$stmtOrderDetails->execute()) {
            throw new Exception("Failed to insert order details.");
        }

        // Update the stock for the product in `products` table
        $stmtUpdateStock = $conn->prepare("UPDATE products SET quantity = quantity - ? WHERE id = ?");
        $stmtUpdateStock->bind_param("ii", $item['quantity'], $item['id']);
        if (!$stmtUpdateStock->execute()) {
            throw new Exception("Failed to update product stock.");
        }
    }

   
    $conn->commit();

    echo json_encode(['success' => true, 'message' => 'Order placed successfully!']);
} catch (Exception $e) {
    
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
