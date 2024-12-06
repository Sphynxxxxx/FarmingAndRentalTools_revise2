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
                <h3>Products & Approvals</h3>
                <p>Manage and Update product listings. Approve or decline pending applications.</p>
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
            background-color: #f4f4f4; 
        }

        .main-content {
            margin: auto;
            padding: 30px;
            max-width: 1000px;
        }

        .main-content h1 {
            font-size: 2.5em;
            color: #2F5233;  
            margin-bottom: 20px;
        }

        .main-content p {
            font-size: 1.1em;
            color: #666;
            margin-bottom: 30px;
        }

        /* Quick Stats Cards */
        .quick-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 50px; 
        }

        .card {
            background-color: #fff; 
            border-radius: 8px;
            padding: 20px;
            width: 30%;  
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px); 
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            font-size: 1.6em;
            color: #2F5233; 
            margin-bottom: 10px;
        }

        .card p {
            font-size: 1em;
            color: #777;
            margin-bottom: 20px;
        }

        .card .btn {
            background-color: #2F5233; 
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 5px;
            font-size: 1.1em;
            transition: background-color 0.3s ease;
        }

        .card .btn:hover {
            background-color: #3b6c4a; 
        }

        /* Responsive Design for Small Screens */
        @media (max-width: 768px) {
            .quick-stats {
                flex-direction: column;
                align-items: center;
            }

            .card {
                width: 80%;  
            }
        }

    </style>
    

</body>
</html>
