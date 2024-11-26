<?php

@include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
</head>
<body>


    <!-- Main Contentqwq -->
    <div class="main-content">
        <h1>Welcome to Admin Dashboard</h1>
        <p>Here you can manage products, customers, lenders, approvals and track orders.</p>

        
        <div class="quick-stats">
            <div class="card">
                <h3>Products</h3>
                <p>Manage and Update product listings.</p>
                <a href="AdminProductsApproval.php" class="btn">View Products</a>
            </div>
            <div class="card">
                <h3>Customers</h3>
                <p>Manage customer registrations and profiles.</p>
                <a href="ApprovedCus.php" class="btn">View Customers</a>
            </div>
            <div class="card">
                <h3>Lender</h3>
                <p>Manage Lender registrations and profiles.</p>
                <a href="ApprovedLend.php" class="btn">View Lender</a>
            </div>
            <div class="card">
                <h3>Approvals</h3>
                <p>Approve or decline pending applications.</p>
                <a href="AdminCustomerReg.php" class="btn">Manage Approvals</a>
            </div>
            <div class="card">
                <h3>Orders</h3>
                <p>Tracking Orders</p>
                <a href="Delivery.php" class="btn">View Orders</a>
            </div>
        </div>

        
        
    </div>

    <style>
 body {
    font-family: Arial, sans-serif;
    display: flex;
    min-height: 100vh;
    margin: 0;
}

.main-content {
    margin: auto;
    padding: 20px;
}
.main-content h1 {
    font-size: 2.5em;
    color: #000000;
}
.main-content p {
    font-size: 1.2em;
    margin-bottom: 30px;
}

/* Quick Stats Cards */
.quick-stats {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
}
.card {
    background-color: #ecf0f1;
    border: 1px solid #bdc3c7;
    padding: 20px;
    width: 15%;
    margin-bottom: 20px;
    text-align: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}
.card h3 {
    font-size: 1.8em;
    margin-bottom: 10px;
}
.card p {
    font-size: 1.1em;
    margin-bottom: 20px;
}
.card .btn {
    background-color:  #000000;
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 5px;
}
.card .btn:hover {
    background-color: #2980b9;
}

/* Helpful Tips Section */
.help-tips {
    margin-top: 50px;
}
.help-tips ul {
    list-style-type: disc;
    padding-left: 20px;
}
.help-tips ul li {
    font-size: 1.1em;
    margin-bottom: 10px;
}
    </style>
    

</body>
</html>
