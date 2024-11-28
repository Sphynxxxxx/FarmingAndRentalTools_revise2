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

// Fetch the most recent order for this customer, including the reference number
$order_query = "SELECT o.id AS order_id, o.reference_number, o.order_date, o.delivery_method, 
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

// Fetch order details including start_date and end_date
$order_details_query = "
    SELECT 
        od.quantity, 
        od.price, 
        od.shippingfee, 
        p.product_name, 
        p.lender_name,
        p.image AS product_image,
        od.start_date, 
        od.end_date
    FROM order_details od
    JOIN products p ON od.product_id = p.id
    WHERE od.order_id = ?";
$stmt = $conn->prepare($order_details_query);
$stmt->bind_param("i", $order['order_id']);
$stmt->execute();
$order_details_result = $stmt->get_result();

// Reset the pointer for later use
mysqli_data_seek($order_details_result, 0);

// Check if the form for generating PDF was submitted
if (isset($_POST['download_pdf'])) {
    // Create PDF object
    $pdf = new TCPDF();
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 12);

    // Add the content to the PDF
    $pdf->Cell(0, 10, 'Order Confirmation', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Reference Number: ' . $order['reference_number'], 0, 1);
    $pdf->Cell(0, 10, 'Customer Information', 0, 1);
    $pdf->Cell(0, 10, 'Name: ' . htmlspecialchars($order['name']), 0, 1);
    $pdf->Cell(0, 10, 'Email: ' . htmlspecialchars($order['email']), 0, 1);
    $pdf->Cell(0, 10, 'Contact Number: ' . htmlspecialchars($order['contact_number']), 0, 1);
    $pdf->Cell(0, 10, 'Address: ' . htmlspecialchars($order['address']), 0, 1);
    $pdf->Cell(0, 10, 'Order Date: ' . htmlspecialchars($order['order_date']), 0, 1);
    $pdf->Cell(0, 10, 'Delivery Method: ' . htmlspecialchars($order['delivery_method']), 0, 1);

    // Add order details with images to the PDF
    $pdf->Cell(0, 10, 'Order Details:', 0, 1);
    $pdf->SetFont('helvetica', '', 10);

    // Calculate totals for shipping and prices
    $total_price = 0;
    $total_shipping = 0;

    // Prepare PDF listing
    while ($detail = $order_details_result->fetch_assoc()) {
        
        if (strtolower($order['delivery_method']) == 'pickup') {
            $shipping_subtotal = 0;
        } else {
            $shipping_subtotal = $detail['quantity'] * $detail['shippingfee'];
        }

        // Calculate subtotal including shipping
        $subtotal = $detail['quantity'] * $detail['price'] + $shipping_subtotal;
        $total_price += $detail['quantity'] * $detail['price'];
        $total_shipping += $shipping_subtotal;

        $pdf->Cell(30, 10, $detail['product_name'], 0, 0);
        $pdf->Cell(30, 10, $detail['lender_name'], 0, 0);
        $pdf->Cell(30, 10, $detail['quantity'], 0, 0);
        $pdf->Cell(30, 10, '₱' . number_format($detail['price'], 2), 0, 0);
        $pdf->Cell(30, 10, '₱' . number_format($detail['shippingfee'], 2), 0, 0);
        $pdf->Cell(30, 10, '₱' . number_format($subtotal, 2), 0, 1);

        // Add rental start and end dates to PDF
        $pdf->Cell(30, 10, 'Start Date: ' . $detail['start_date'], 0, 1);
        $pdf->Cell(30, 10, 'End Date: ' . $detail['end_date'], 0, 1);

        $product_image = 'uploaded_img/' . $detail['product_image'];
        if (file_exists($product_image)) {
            $pdf->Image($product_image, $pdf->GetX(), $pdf->GetY(), 15, 15, 'JPG');
            $pdf->Ln(5);
        }
    }

    // Output the PDF as a download
    $pdf->Output('Order_Receipt.pdf', 'D');
    exit();  
}

// Resetting the result pointer for HTML display
mysqli_data_seek($order_details_result, 0);
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
                <h3>Order Reference Number: <?php echo htmlspecialchars($order['reference_number']); ?></h3>

                <h3>Customer Information</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($order['contact_number']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
                <p><strong>Delivery Method:</strong> <?php echo htmlspecialchars($order['delivery_method']); ?></p>

                <h3>Order Details</h3>
                <form id="updateDatesForm" method="post" action="update_order_dates.php">
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
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_price = 0;
                            $total_shipping = 0;
                            while ($detail = $order_details_result->fetch_assoc()): 

                                if (strtolower($order['delivery_method']) == 'pickup') {
                                    $shipping_subtotal = 0;
                                } else {
                                    $shipping_subtotal = $detail['quantity'] * $detail['shippingfee'];
                                }
                                $subtotal = $detail['quantity'] * $detail['price'] + $shipping_subtotal;
                                $total_price += $detail['quantity'] * $detail['price'];
                                $total_shipping += $shipping_subtotal;
                            ?>
                            <tr>
                                <td><img src="uploaded_img/<?php echo htmlspecialchars($detail['product_image']); ?>" alt="<?php echo htmlspecialchars($detail['product_name']); ?>" style="width: 50px; height: 50px;"></td>
                                <td><?php echo htmlspecialchars($detail['product_name']); ?></td>
                                <td><?php echo htmlspecialchars($detail['lender_name']); ?></td>
                                <td><?php echo htmlspecialchars($detail['quantity']); ?></td>
                                <td>₱<?php echo number_format($detail['price'], 2); ?></td>
                                <td>
                                    <?php 
                                    if (strtolower($order['delivery_method']) == 'pickup') {
                                        echo '₱0.00';
                                    } else {
                                        echo '₱' . number_format($shipping_subtotal, 2);
                                    }
                                    ?>
                                </td>
                                <td>₱<?php echo number_format($subtotal, 2); ?></td>
                                <td>
                                    <input type="date" name="start_date" value="<?php echo $detail['start_date']; ?>">
                                </td>
                                <td>
                                    <input type="date" name="end_date" value="<?php echo $detail['end_date']; ?>">
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                   
                </form>

               
                <button onclick="window.history.back();">Back</button>
                <form method="post" action="">
                    <button type="submit" name="download_pdf">Download PDF</button>
                </form>
            </div>
            <?php else: ?>
                <p>No order found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
