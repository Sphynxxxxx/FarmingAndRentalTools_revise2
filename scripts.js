// Wait for the DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.getElementById('menu-items');
    const orderList = document.getElementById('order-list');
    const subtotalElement = document.getElementById('subtotal');
    const shippingFeeElement = document.getElementById('shippingfee'); // For displaying the total shipping fee
    let subtotal = 0;
    let totalShippingFee = 0;
    let orderItems = {};

    // Function to format prices with commas and decimal places
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
            const totalItemShipping = details.shippingfee
            subtotal += totalPrice;
            totalShippingFee += totalItemShipping;

            summaryHTML += `<div class="order-item">
                <span>${name} (x${details.quantity})</span>
                <span>₱${formatPrice(totalPrice)}</span>
            </div>`;
        }

        orderList.innerHTML = summaryHTML;
        subtotalElement.textContent = `₱${formatPrice(subtotal)}`;
        shippingFeeElement.textContent = `₱${formatPrice(totalShippingFee)}`;
    }

    // Event delegation for quantity controls and rent button
    menuItems.addEventListener('click', function(event) {
        const item = event.target.closest('.item');
        if (!item) return;

        const quantityElement = item.querySelector('.quantity');
        let quantity = parseInt(quantityElement.textContent);

        if (event.target.classList.contains('minus-btn') && quantity > 1) {
            quantity--;
        } else if (event.target.classList.contains('plus-btn')) {
            quantity++;
        } else if (event.target.classList.contains('rent-btn')) {
            const name = item.dataset.name;
            const price = parseFloat(item.dataset.price.replace(/,/g, '')); 
            const shippingfee = parseFloat(item.dataset.shippingfee.replace(/,/g, '')); // Get shipping fee from data attribute

            // Check if the item is already in the orderItems
            if (orderItems[name]) {
                orderItems[name].quantity += quantity;
            } else {
                orderItems[name] = { price: price, shippingfee: shippingfee, quantity: quantity };
            }

            updateOrderSummary();
            quantity = 1;  // Reset quantity to 1 after adding to order
        }

        quantityElement.textContent = quantity;
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
});
