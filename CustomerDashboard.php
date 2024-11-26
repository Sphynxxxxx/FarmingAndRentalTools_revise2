<?php
session_start();
include 'config.php';

if (!isset($_SESSION['email'])) {
    header("Location: CustomerDashboard.php"); 
    exit();
}

// Fetch products
$sql = "SELECT id, categories, product_name, lender_name, location, description, rent_days, price, shippingfee, created_at, image, quantity FROM products WHERE status = 'approved'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Farming Tool and Rental System</title>
  <link rel="stylesheet" href="css/Customercss.css?v=1.0">
  <style>
    
  </style>
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
          <li><a href="#">History</a></li> 
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
              $categories = htmlspecialchars($row['categories']);
              $productName = htmlspecialchars($row['product_name']);
              $lenderName = htmlspecialchars($row['lender_name']);
              $location = htmlspecialchars($row['location']);
              $description = htmlspecialchars($row['description']);
              $rent_days = $row['rent_days'];  
              $price = number_format($row['price'], 2);
              $shippingFee = number_format($row['shippingfee'], 2);
              $image = htmlspecialchars($row['image']);
              $availableQuantity = $row['quantity'];
              $outOfStockClass = $availableQuantity <= 0 ? 'out-of-stock' : '';
              $outOfStockLabel = $availableQuantity <= 0 ? '<div class="out-of-stock-label">Out of Stock</div>' : '';
              ?>
              <div class="item <?php echo $outOfStockClass; ?>" data-id="<?php echo $row['id']; ?>" data-categories ="<?php echo $row['categories']; ?>" data-name="<?php echo $productName; ?>" data-price="<?php echo $price; ?>" data-shippingfee="<?php echo $shippingFee; ?>" data-quantity="<?php echo $availableQuantity; ?>">
                  <?php echo $outOfStockLabel; ?><br>
                  <?php echo $categories; ?>
                  <p><strong>Product Name:</strong> <?php echo $productName; ?></p>
                  <p><strong>Lender Name:</strong> <?php echo $lenderName; ?></p>
                  <p><strong>Location:</strong> <?php echo $location; ?></p>
                  <p><strong>Description:</strong> <?php echo $description; ?></p>
                  <p><strong>Rent Days:</strong> <?php echo $rent_days; ?> </p>
                  <img src="uploaded_img/<?php echo $image; ?>" alt="<?php echo $productName; ?>" onerror="this.src='uploaded_img/default_image.jpg';">
                  <h3 class="item-price" style="color: red;">₱<?php echo $price; ?></h3>

                  <p><strong>Available:</strong> <?php echo $availableQuantity; ?></p>
                  <div class="quantity-control">
                      <button class="minus-btn" <?php echo $availableQuantity <= 0 ? 'disabled' : ''; ?>>-</button>
                      <span class="quantity">0</span>
                      <button class="plus-btn" <?php echo $availableQuantity <= 0 ? 'disabled' : ''; ?>>+</button>
                      <button class="rent-btn" <?php echo $availableQuantity <= 0 ? 'disabled' : ''; ?>>Rent</button>
                  </div>
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

        <!-- Order List Section -->
        <div id="order-list"></div>

        <!-- Rental Period Section -->
        <div class="date-picker-container">
            <h4>Rental Period</h4>
            <div class="date-picker">
            <label for="start-date">Start Date:</label>
            <input type="date" id="start-date" placeholder="Select start date">
            </div>
            <div class="date-picker">
            <label for="end-date">End Date:</label>
            <input type="date" id="end-date" placeholder="Select end date">
            </div>
        </div>

        <!-- Delivery Method Section -->
        <div class="delivery-method">
            <h4>Delivery Method</h4>
            <label>
            <input type="radio" name="delivery-method" value="pickup" checked> 
            Pick Up
            </label>
            <label>
            <input type="radio" name="delivery-method" value="cod"> 
            Cash on Delivery
            </label>
        </div>

        <!-- Total Calculation Section -->
        <div class="total">
            <p>Subtotal</p>
            <p id="subtotal">₱0.00</p>
        </div>
        <div class="total" id="shipping-fee-container">
            <p>Shipping Fee</p>
            <p id="shippingfee">₱0.00</p> 
        </div>
        <div class="total">
            <p><strong>Total</strong></p>
            <p id="total-amount"><strong>₱0.00</strong></p>
        </div>

        <!-- Place Order Button -->
        <button class="place-order">Place Order</button>
        </div>

</div>
    <script src="scripts.js"></script>
  <script>
      document.addEventListener('DOMContentLoaded', () => {
            const orderList = document.getElementById('order-list');
            const subtotalElement = document.getElementById('subtotal');
            const shippingFeeElement = document.getElementById('shippingfee');
            const totalAmountElement = document.getElementById('total-amount');
            const placeOrderButton = document.querySelector('.place-order');
            const shippingFeeContainer = document.getElementById('shipping-fee-container');
            const deliveryMethodRadios = document.querySelectorAll('input[name="delivery-method"]');

            let orderItems = []; 
            let deliveryMethod = 'pickup'; 

            // Update the order summary dynamically
            function updateOrderSummary() {
               
                orderList.innerHTML = '';

                let subtotal = 0;
                let totalShippingFee = 0;

                
                orderItems.forEach(item => {
                    const { id, name, price, quantity, shippingFee, image } = item;

                    subtotal += price * quantity;
                    
                    // Calculate shipping fee: if Pick Up, set it to 0
                    if (deliveryMethod === 'pickup') {
                        totalShippingFee += 0; // Shipping is free for pick up
                    } else {
                        totalShippingFee += shippingFee * quantity; // For COD, calculate the actual shipping fee
                    }

                    const orderItem = document.createElement('div');
                    orderItem.className = 'order-item';
                    orderItem.innerHTML = `
                        <div class="order-item-image">
                            <img src="uploaded_img/${image}" alt="${name}" onerror="this.src='uploaded_img/default_image.jpg';">
                        </div>
                        <div class="order-item-details">
                            <p><strong>${name}</strong></p>
                            <p>₱${price.toFixed(2)} x ${quantity} = ₱${(price * quantity).toFixed(2)}</p>
                            ${deliveryMethod === 'cod' ? `<p>Shipping Fee: ₱${(shippingFee * quantity).toFixed(2)}</p>` : ''}
                        </div>
                    `;
                    orderList.appendChild(orderItem);
                });

                // Update totals
                subtotalElement.textContent = `₱${subtotal.toFixed(2)}`;
                
                // Handle shipping fee visibility based on delivery method
                if (deliveryMethod === 'pickup') {
                    shippingFeeContainer.style.display = 'none';
                    shippingFeeElement.textContent = '₱0.00';
                    totalAmountElement.textContent = `₱${subtotal.toFixed(2)}`;
                } else {
                    shippingFeeContainer.style.display = 'flex';
                    shippingFeeElement.textContent = `₱${totalShippingFee.toFixed(2)}`;
                    totalAmountElement.textContent = `₱${(subtotal + totalShippingFee).toFixed(2)}`;
                }
            }

            // Delivery method change event
            deliveryMethodRadios.forEach(radio => {
                radio.addEventListener('change', (event) => {
                    deliveryMethod = event.target.value;
                    updateOrderSummary();
                });
            });

            // Add item to the order summary when Rent button is clicked
            document.querySelectorAll('.rent-btn').forEach(button => {
                button.addEventListener('click', (event) => {
                    const itemElement = event.target.closest('.item');
                    const itemId = itemElement.dataset.id;
                    const itemName = itemElement.dataset.name;
                    const itemPrice = parseFloat(itemElement.dataset.price);
                    const itemShippingFee = parseFloat(itemElement.dataset.shippingfee);
                    const itemImage = itemElement.querySelector('img').src.split('/').pop(); 

                    const quantityElement = itemElement.querySelector('.quantity');
                    const quantity = parseInt(quantityElement.textContent);

                    if (quantity <= 0) {
                        alert('Please select a quantity greater than 0.');
                        return;
                    }

                    // Check if item already exists in the order list
                    const existingItem = orderItems.find(item => item.id === itemId);

                    if (existingItem) {
                        // Update quantity if already in the list
                        existingItem.quantity += quantity;
                    } else {
                        // Add new item to the list
                        orderItems.push({
                            id: itemId,
                            name: itemName,
                            price: itemPrice,
                            shippingFee: itemShippingFee,
                            quantity,
                            image: itemImage,
                        });
                    }

                    // Reset quantity in the product listing
                    quantityElement.textContent = '0';

                    // Update the order summary display
                    updateOrderSummary();
                });
            });

            // Handle quantity adjustment buttons
            document.querySelectorAll('.minus-btn').forEach(button => {
                button.addEventListener('click', (event) => {
                    const quantityElement = event.target.closest('.quantity-control').querySelector('.quantity');
                    const currentQuantity = parseInt(quantityElement.textContent);

                    if (currentQuantity > 0) {
                        quantityElement.textContent = currentQuantity - 1;
                    }
                });
            });

            document.querySelectorAll('.plus-btn').forEach(button => {
                button.addEventListener('click', (event) => {
                    const quantityElement = event.target.closest('.quantity-control').querySelector('.quantity');
                    const currentQuantity = parseInt(quantityElement.textContent);

                    quantityElement.textContent = currentQuantity + 1;
                });
            });

            // Handle order placement
            placeOrderButton.addEventListener('click', () => {
                if (orderItems.length === 0) {
                    alert('Please add items to your order.');
                    return;
                }

                // Send order data to the server
                fetch('saveOrder.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        orderDetails: orderItems,
                        deliveryMethod: deliveryMethod 
                    }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = 'order_details.php'; 
                        } else {
                            alert(data.message);  
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                    });
            });
        });

  </script>
</body>
</html>