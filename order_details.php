<?php
session_start();
include 'config.php';

// Include TCPDF
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: CustomerDashboard.php");
    exit();
}

// Get the customer's ID from the session
$customer_email = $_SESSION['email'];
$customer_query = "SELECT id FROM customer WHERE email = ?";
$stmt = $conn->prepare($customer_query);
$stmt->bind_param("s", $customer_email);
$stmt->execute();
$customer_result = $stmt->get_result();
$customer = $customer_result->fetch_assoc();
$customer_id = $customer['id'];

// Fetch the most recent order for this customer
$order_query = "SELECT o.id AS order_id, o.order_date, 
                c.name, c.email, c.contact_number, c.address
                FROM orders o
                JOIN customer c ON o.customer_id = c.id
                WHERE o.customer_id = ? 
                ORDER BY o.order_date DESC 
                LIMIT 1";
$stmt = $conn->prepare($order_query);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

// Generate a random reference number for the order
$reference_no = "REF-" . date("Ymd") . "-" . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));

// Fetch order details
$order_details_query = "SELECT 
                        od.quantity, 
                        od.price, 
                        od.shippingfee, 
                        p.product_name, 
                        p.lender_name,
                        p.image AS product_image
                        FROM order_details od
                        JOIN products p ON od.product_id = p.id
                        WHERE od.order_id = ?";
$stmt = $conn->prepare($order_details_query);
$stmt->bind_param("i", $order['order_id']);
$stmt->execute();
$order_details_result = $stmt->get_result();

// Check if the form for generating PDF was submitted
if (isset($_POST['download_pdf'])) {
    // Create PDF object
    $pdf = new TCPDF();
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 12);

    // Add the content to the PDF
    $pdf->Cell(0, 10, 'Order Confirmation', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Reference Number: ' . $reference_no, 0, 1);
    $pdf->Cell(0, 10, 'Customer Information', 0, 1);
    $pdf->Cell(0, 10, 'Name: ' . htmlspecialchars($order['name']), 0, 1);
    $pdf->Cell(0, 10, 'Email: ' . htmlspecialchars($order['email']), 0, 1);
    $pdf->Cell(0, 10, 'Contact Number: ' . htmlspecialchars($order['contact_number']), 0, 1);
    $pdf->Cell(0, 10, 'Address: ' . htmlspecialchars($order['address']), 0, 1);
    $pdf->Cell(0, 10, 'Order Date: ' . htmlspecialchars($order['order_date']), 0, 1);

    $pdf->Cell(0, 10, 'Order Details:', 0, 1);
    
    // Add the order details with images to the PDF
    $pdf->SetFont('helvetica', '', 10);
    while ($detail = $order_details_result->fetch_assoc()) {
        $pdf->Cell(30, 10, $detail['product_name'], 0, 0);
        $pdf->Cell(30, 10, $detail['lender_name'], 0, 0);
        $pdf->Cell(30, 10, $detail['quantity'], 0, 0);
        $pdf->Cell(30, 10, '₱' . number_format($detail['price'], 2), 0, 0);
        $pdf->Cell(30, 10, '₱' . number_format($detail['shippingfee'], 2), 0, 0);
        $subtotal = $detail['quantity'] * $detail['price'] + $detail['quantity'] * $detail['shippingfee'];
        $pdf->Cell(30, 10, '₱' . number_format($subtotal, 2), 0, 1);
        
        // Optional: Add an image of the product to the PDF
        $product_image = 'uploaded_img/' . $detail['product_image'];
        if (file_exists($product_image)) {
            $pdf->Image($product_image, $pdf->GetX(), $pdf->GetY(), 15, 15, 'JPG');
            $pdf->Ln(5);
        }
    }

    // Output the PDF as a download
    $pdf->Output('order_confirmation.pdf', 'D');
    exit();  // Ensure no further output is sent to the browser
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="css/Customercss.css?v=1.0">
</head>
<body>
    <div class="container">
        <div class="main-content">
            <h2>Order Confirmation</h2>
            
            <?php if ($order): ?>
            <div class="order-confirmation">
                <h3>Order Reference Number: <?php echo $reference_no; ?></h3> <!-- Display the random reference number -->

                <h3>Customer Information</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($order['contact_number']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>

                <h3>Order Details</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Lender Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Shipping Fee</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_price = 0;
                        $total_shipping = 0;
                        while ($detail = $order_details_result->fetch_assoc()): 
                            $subtotal = $detail['quantity'] * $detail['price'];
                            $shipping_subtotal = $detail['quantity'] * $detail['shippingfee'];
                            $total_price += $subtotal;
                            $total_shipping += $shipping_subtotal;
                        ?>
                        <tr>
                            <td><img src="uploaded_img/<?php echo htmlspecialchars($detail['product_image']); ?>" alt="<?php echo htmlspecialchars($detail['product_name']); ?>" style="width: 50px; height: 50px;"></td>
                            <td><?php echo htmlspecialchars($detail['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($detail['lender_name']); ?></td>
                            <td><?php echo htmlspecialchars($detail['quantity']); ?></td>
                            <td>₱<?php echo number_format($detail['price'], 2); ?></td>
                            <td>₱<?php echo number_format($detail['shippingfee'], 2); ?></td>
                            <td>₱<?php echo number_format($subtotal + $shipping_subtotal, 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6"><strong>Total Price</strong></td>
                            <td>₱<?php echo number_format($total_price, 2); ?></td>
                        </tr>
                        <tr>
                            <td colspan="6"><strong>Total Shipping</strong></td>
                            <td>₱<?php echo number_format($total_shipping, 2); ?></td>
                        </tr>
                        <tr>
                            <td colspan="6"><strong>Grand Total</strong></td>
                            <td>₱<?php echo number_format($total_price + $total_shipping, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <?php else: ?>
            <p>No recent order found.</p>
            <?php endif; ?>

            <div class="order-actions">
                <a href="CustomerDashboard.php" class="btn">Back to Dashboard</a>
                <!-- PDF Download Button -->
                <form method="post">
                    <button type="submit" name="download_pdf" class="btn">Download PDF</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
