<?php
session_start();
include 'config.php';


if (!isset($_SESSION['email'])) {
    header("Location: CustomerDashboard.php"); 
    exit();
}

// Fetch products
$sql = "SELECT id, product_name, lender_name, location, description, price, shippingfee, image FROM products WHERE status = 'approved'";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Farming Tool and Rental System</title>
  <link rel="stylesheet" href="css/Customercss.css?v=1.0">
</head>
<body>
  <div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="logo">
        <h2>Farming Tool and Rental System</h2>
      </div>
      <nav>
        <ul>
          <li><a href="CusProfile.php">Profile</a></li>
          <li><a href="settings.php">Settings</a></li>
          <li><a href="delivery.php">Delivery</a></li>
          <li><a href="CusLogout.php">Logout</a></li>
        </ul>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
      <header>
        <div class="user-welcome">
          <p>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</p>
        </div>
        <input id="search-box" type="text" placeholder="Search Product here...">
        <div class="table-info"></div>
      </header>

      <!-- Categories Buttons -->
      <div class="menu-categories">
        <button data-category="all">All</button>
        <button data-category="Hand Tools">Hand Tools</button>
        <button data-category="Ploughs">Ploughs</button>
        <button data-category="Seeding Tools">Seeding Tools</button>
        <button data-category="Harvesting Tools">Harvesting Tools</button>
        <button data-category="Tilling Tools">Tilling Tools</button>
        <button data-category="Cutting Tools">Cutting Tools</button>
        <button data-category="Garden Tools">Garden Tools</button>
      </div>

      <!-- Menu Items -->
      <div class="menu-items" id="menu-items">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $productName = htmlspecialchars($row['product_name']);
                $lenderName = htmlspecialchars($row['lender_name']);
                $location = htmlspecialchars($row['location']);
                $description = htmlspecialchars($row['description']);
                $price = number_format($row['price'], 2);
                $shippingFee = number_format($row['shippingfee'], 2);
                $image = htmlspecialchars($row['image']);
                ?>
                <div class="item" data-name="<?php echo $productName; ?>" data-price="<?php echo $price; ?>" data-shippingfee="<?php echo $shippingFee; ?>">
                    <p><strong>Product Name:</strong> <?php echo $productName; ?></p>
                    <p><strong>Lender Name:</strong> <?php echo $lenderName; ?></p>
                    <p><strong>Location:</strong> <?php echo $location; ?></p>
                    <p><strong>Description:</strong> <?php echo $description; ?></p>
                    <img src="uploaded_img/<?php echo $image; ?>" alt="<?php echo $productName; ?>" onerror="this.src='uploaded_img/default_image.jpg';">
                    <span class="item-price">₱<?php echo $price; ?></span>
                    <div class="quantity-control">
                        <button class="minus-btn">-</button>
                        <span class="quantity">1</span>
                        <button class="plus-btn">+</button>
                    </div>
                    <button class="rent-btn">Rent</button>
                </div>
                <?php
            }
        } else {
            echo "<p>No products available</p>";
        }
        $conn->close();
        ?>
      </div>
    </div>

    <!-- Order Summary -->
    <div class="order-summary">
      <h3>Order Summary</h3>
      <div id="order-list"></div>
      <div class="total">
        <p>Subtotal</p>
        <p id="subtotal">₱0.00</p>
      </div>
      <div class="total">
        <p>Shipping Fee</p>
        <p id="shippingfee">₱0.00</p> <!-- Display shipping fee -->
      </div>
      <div class="payment">
        <button id="cash-on-delivery">Cash On Delivery</button>
      </div>
      <button class="place-order">Place Order</button>
    </div>
  </div>

  <!-- Include your JavaScript for interactivity -->
  <script src="scripts.js"></script>
</body>
</html>

