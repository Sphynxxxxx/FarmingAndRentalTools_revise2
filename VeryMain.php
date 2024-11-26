<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selection</title>
<style>
    body {
        margin: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f4f4f4;
        background-image: url('css/images/Pototan_hall_wide.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .container {
        width: 300px;
        height: 300px;
        background-color: rgba(255, 255, 255, 0);
        backdrop-filter: blur(5px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        border-radius: 15px;
        display: flex; 
        flex-direction: column; 
        justify-content: center; 
        align-items: center; 
    }

    h1 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #fff;
    }

    .btn {
        display: flex; 
        justify-content: center; 
        align-items: center;
        width: 200px;
        padding: 10px 20px;
        margin: 10px;
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        text-decoration: none;
        font-size: 18px;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .btn:hover {
        background-color: rgba(76, 175, 80, 1);
    }


</style>
</head>
<body>

<div class="container">
    <a href="CustomerMain.php" class="btn">Renter</a>
    <a href="LenderMain.php" class="btn">Lender</a>
</div>

</body>
</html>
