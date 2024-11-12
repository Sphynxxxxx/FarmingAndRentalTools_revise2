document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.getElementById('menu-items');
    const orderList = document.getElementById('order-list');
    const subtotalElement = document.getElementById('subtotal');
    const shippingFeeElement = document.getElementById('shippingfee');
    let subtotal = 0;
    let totalShippingFee = 0;
    let orderItems = {};

    // Function to format price for display
    function formatPrice(price) {
        return parseFloat(price).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // Function to update the order summary
    function updateOrderSummary() {
        let summaryHTML = '';
        subtotal = 0;
        totalShippingFee = 0;

        // Loop through orderItems to calculate total price and shipping fee
        for (const [name, details] of Object.entries(orderItems)) {
            const totalPrice = details.price * details.quantity;
            const totalItemShipping = details.shippingfee * details.quantity; 
            subtotal += totalPrice;
            totalShippingFee += totalItemShipping;

            summaryHTML += `<div class="order-item">
                <span>${name} (x${details.quantity})</span>
                <span>₱${formatPrice(totalPrice)}</span>
            </div>`;
        }

        // Display the updated summary
        orderList.innerHTML = summaryHTML;
        subtotalElement.textContent = `₱${formatPrice(subtotal)}`;
        shippingFeeElement.textContent = `₱${formatPrice(totalShippingFee)}`;
    }

    // Event delegation for quantity controls and rent button
    menuItems.addEventListener('click', function(event) {
        const item = event.target.closest('.item');
        if (!item) return;

        const quantityElement = item.querySelector('.quantity');
        let availableQuantity = parseInt(item.dataset.quantity, 10); 
        let quantity = parseInt(quantityElement.textContent); 

        // Handle minus button click
        if (event.target.classList.contains('minus-btn') && quantity > 0) {
            quantity--;
        } 
        // Handle plus button click
        else if (event.target.classList.contains('plus-btn') && quantity < availableQuantity) {
            quantity++;
        } 
        // Handle rent button click
        else if (event.target.classList.contains('rent-btn')) {
            const name = item.dataset.name;
            const price = parseFloat(item.dataset.price.replace(/,/g, ''));
            const shippingfee = parseFloat(item.dataset.shippingfee.replace(/,/g, '')); 

            // Check if the item is already in the orderItems
            if (orderItems[name]) {
                orderItems[name].quantity += quantity; // Add to the existing quantity in the order
            } else {
                orderItems[name] = { price: price, shippingfee: shippingfee, quantity: quantity };
            }

            // Decrease available stock for this item after renting
            availableQuantity -= quantity;
            item.dataset.quantity = availableQuantity; // Update the dataset with the new quantity

            // If availableQuantity reaches 0, disable the rent button
            if (availableQuantity <= 0) {
                const rentBtn = item.querySelector('.rent-btn');
                rentBtn.disabled = true;  
            }

            
            updateStock(item.dataset.id, quantity);

            // Update the order summary after adding the item to the order
            updateOrderSummary();

            // Reset the displayed quantity to 1 for the next possible interaction
            quantity = 0;  
        }

        // Update the quantity display
        quantityElement.textContent = quantity;

        
        const minusBtn = item.querySelector('.minus-btn');
        const plusBtn = item.querySelector('.plus-btn');
        const rentBtn = item.querySelector('.rent-btn');

       
        minusBtn.disabled = quantity <= 0;
        
        
        plusBtn.disabled = quantity >= availableQuantity;
        
        
        rentBtn.disabled = quantity <= 0 || quantity > availableQuantity;
    });

    // Category filter functionality
    const categoryButtons = document.querySelectorAll('.menu-categories button');
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;
            document.querySelectorAll('.item').forEach(item => {
                if (category === 'all' || item.classList.contains(category)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    // Search functionality
    const searchBox = document.getElementById('search-box');
    searchBox.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        document.querySelectorAll('.item').forEach(item => {
            const productName = item.dataset.name.toLowerCase();
            if (productName.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // AJAX function to update stock in the backend
    function updateStock(productId, quantity) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_stock.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log('Stock updated successfully');
            } else if (xhr.readyState === 4 && xhr.status !== 200) {
                console.error('Error updating stock');
            }
        };
        xhr.send(`productId=${productId}&quantity=${quantity}`);
    }
});
