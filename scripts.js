document.addEventListener('DOMContentLoaded', () => {
    
    fetch('fetch_products.php')
        .then(response => response.json())
        .then(products => renderProducts(products))
        .catch(error => console.error('Error fetching products:', error));

   
    function renderProducts(products) {
        const menuItems = document.getElementById('menu-items');

        products.forEach(product => {
            const item = document.createElement('div');
            item.classList.add('item');
            item.setAttribute('data-name', product.product_name);
            item.setAttribute('data-price', product.price);

            
            item.innerHTML = `
                <img src="uploaded_img/${product.image}" alt="${product.product_name}">
                <p>${product.description}</p>
                <span class="item-price">₱${product.price}</span>
                <div class="quantity-control">
                    <button class="minus-btn">-</button>
                    <span class="quantity">1</span>
                    <button class="plus-btn">+</button>
                </div>
                <button class="rent-btn">Rent</button>
            `;

            menuItems.appendChild(item);
        });

        addQuantityAndRentListeners();
    }

    
    function addQuantityAndRentListeners() {
        const items = document.querySelectorAll('.item');
        
        items.forEach(item => {
            const minusBtn = item.querySelector('.minus-btn');
            const plusBtn = item.querySelector('.plus-btn');
            const quantityElement = item.querySelector('.quantity');
            const priceElement = item.querySelector('.item-price');
            const rentBtn = item.querySelector('.rent-btn');

            let quantity = 1;
            const basePrice = parseFloat(item.getAttribute('data-price'));

            const updatePrice = () => {
                const newPrice = (basePrice * quantity).toFixed(2);
                priceElement.textContent = `₱${newPrice}`;
            };

            plusBtn.addEventListener('click', () => {
                quantity++;
                quantityElement.textContent = quantity;
                updatePrice();
                updateSubtotal();
            });

            minusBtn.addEventListener('click', () => {
                if (quantity > 1) {
                    quantity--;
                    quantityElement.textContent = quantity;
                    updatePrice();
                    updateSubtotal();
                }
            });

            rentBtn.addEventListener('click', () => addToOrderList(item, quantity, basePrice));
        });
    }

    
    function addToOrderList(item, quantity, basePrice) {
        const orderList = document.getElementById('order-list');
        const itemName = item.getAttribute('data-name');
        const totalPrice = (basePrice * quantity).toFixed(2);

        
        let existingOrderItem = Array.from(orderList.children).find(child => 
            child.querySelector('p').textContent.includes(itemName)
        );

        if (existingOrderItem) {
            
            let existingQuantity = parseInt(existingOrderItem.querySelector('p').textContent.match(/\d+/)[0]);
            existingQuantity += quantity;
            existingOrderItem.querySelector('p').textContent = `${itemName} (x${existingQuantity})`;
            existingOrderItem.querySelector('span').textContent = `₱${(basePrice * existingQuantity).toFixed(2)}`;
        } else {
            
            const orderItem = document.createElement('div');
            orderItem.classList.add('order-item');
            orderItem.innerHTML = `<p>${itemName} (x${quantity})</p><span>₱${totalPrice}</span>`;
            orderList.appendChild(orderItem);
        }

        updateSubtotal();
    }

    
    function updateSubtotal() {
        const orderItems = document.querySelectorAll('#order-list .order-item span');
        let subtotal = 0;

        orderItems.forEach(item => {
            subtotal += parseFloat(item.textContent.replace('₱', ''));
        });

        document.getElementById('subtotal').textContent = `₱${subtotal.toFixed(2)}`;
    }

   
    function handlePaymentButtonClick(paymentMethod) {
        console.log(`Payment method selected: ${paymentMethod}`);
        
    }

    
    document.getElementById('cash-btn').addEventListener('click', () => handlePaymentButtonClick('Cash'));
    document.getElementById('online-payment-btn').addEventListener('click', () => handlePaymentButtonClick('Online Payment'));
    document.getElementById('qr-code-btn').addEventListener('click', () => handlePaymentButtonClick('QR Code'));

    document.querySelector('.place-order').addEventListener('click', () => {
        console.log('Order placed!');
        
    });

    
    const searchBox = document.querySelector('header input[type="text"]');
    searchBox.addEventListener('input', () => {
        const query = searchBox.value.toLowerCase();
        const items = document.querySelectorAll('.item');

        items.forEach(item => {
            const itemName = item.getAttribute('data-name').toLowerCase();
            if (itemName.includes(query)) {
                item.style.display = ''; 
            } else {
                item.style.display = 'none'; 
            }
        });
    });
});
