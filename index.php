<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: index.php");
    exit();
}

include 'dbconnect.php';

$sql = "
SELECT 
SUM(UnitPrice * Quantity) AS total, 
SUM((UnitPrice * Quantity) * Discount) AS discount, 
SUM((UnitPrice * Quantity) * Discount - (UnitPrice * Quantity)) AS final
FROM order_details
";
$result = $conn->query($sql);

// Fetch all
print_r($result->fetch_all(MYSQLI_ASSOC));

// Free result set
$result->free_result();

$conn->close();


?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <style>
        .fontColor {
            color: #ccccb3;
        }
    </style>
</head>

<body style="background-color: #ebebe0;">
    <div class="container">
        <h5>index.php</h5>
        <hr>
        <div class="row">
            <div class="col">
                <h1>Sales Dashboard</h1>
                <p>May 1995</p>
            </div>
        </div>

        <div class="row">
            <div class="col bg-white border">
                <h5>Total Sales</h5>
                <p>$ 123,123,132.12</p>
            </div>
            <div class="col bg-white border">
                <h5>Total Orders</h5>
                <p>$ 123,123,132.12</p>
            </div>
        </div>
    </div>
</body>

</html>