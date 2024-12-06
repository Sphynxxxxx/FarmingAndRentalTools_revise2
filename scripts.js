
    // Category filter functionality
    const categoryButtons = document.querySelectorAll('.menu-categories button');
    categoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.dataset.category;  // Get selected category
            document.querySelectorAll('.item').forEach(item => {
                const itemCategory = item.dataset.categories;  // Get the category of each item
                if (category === 'all' || itemCategory === category) {
                    item.style.display = 'block'; // Show the item if it matches the selected category
                } else {
                    item.style.display = 'none'; // Hide the item if it doesn't match
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


    const toggleSidebarBtn = document.getElementById('toggleSidebarBtn');
    const sidebar = document.getElementById('sidebar');

    toggleSidebarBtn.addEventListener('click', () => {
      sidebar.classList.toggle('open');
    });


   // Get the close button and order summary container
    const closeBtn = document.getElementById('closeBtn');
    const orderSummary = document.getElementById('order-summary');

    // Add an event listener to the close button
    closeBtn.addEventListener('click', function() {
        // Hide the order summary when the close button is clicked
        orderSummary.style.display = 'none';
    });

