<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: CustomerDashboard.php");
    exit();
}
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
          <li><a href="profile.php">Profile</a></li>
          <li><a href="settings.php">Settings</a></li>
          <li><a href="delivery.php">Delivery</a></li>
          <li><a href="logout.php">Logout</a></li>
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
      <div class="menu-items" id="menu-items"></div>
    </div>

    <!-- Order Summary -->
    <div class="order-summary">
      <h3>Order Summary</h3>
      <div id="order-list"></div>
      <div class="total">
        <p>Subtotal</p>
        <p id="subtotal">₱0.00</p>
      </div>
      <div class="payment">
        <button id="cash-btn">Cash</button>
        <button id="online-payment-btn">Online Payment</button>
        <button id="qr-code-btn">QR Code</button>
      </div>
      <button class="place-order">Place Order</button>
    </div>
  </div>

  <script src="scripts.js"></script>
</body>
</html>
