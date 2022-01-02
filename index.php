<?php

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
} else {
    include 'dbconnect.php';

    $sql = "
    SELECT 
    SUM(UnitPrice * Quantity) AS total, 
    SUM((UnitPrice * Quantity) * Discount) AS discount, 
    SUM((UnitPrice * Quantity) - (UnitPrice * Quantity) * Discount) AS final,
    COUNT(order_details.OrderID) AS orderCount    
    FROM orders join order_details on orders.OrderID = order_details.OrderID
    WHERE  EXTRACT(MONTH FROM orders.OrderDate) = 05
	AND EXTRACT(YEAR FROM orders.OrderDate) = 1995;
    ";
    $result = $conn->query($sql);

    $totalSales;
    $totalOrders;

    // Fetch all
    while ($row = $result->fetch_assoc()) {
        $totalSales = $row["final"];
        $totalOrders = $row["orderCount"];
    }

    // Free result set
    $result->free_result();

    // close connection
    // $conn->close();
}

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
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
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
                <p>$ <?php echo round($totalSales, 2); ?></p>
            </div>
            <div class="col bg-white border">
                <h5>Total Orders</h5>
                <p><?php echo $totalOrders; ?></p>
            </div>
        </div>

        <?php
        $sql2 = "
        SELECT DATE(orders.OrderDate) AS OrderDate, SUM((UnitPrice * Quantity) - (UnitPrice * Quantity) * Discount) AS final, AVG((UnitPrice * Quantity) - (UnitPrice * Quantity) * Discount) AS average
        FROM orders join order_details on orders.OrderID = order_details.OrderID
        WHERE  EXTRACT(MONTH FROM orders.OrderDate) = 05
        AND EXTRACT(YEAR FROM orders.OrderDate) = 1995
        GROUP BY orders.OrderDate;
        ";

        $result2 = $conn->query($sql2);

        $dateArr = array();

        // Fetch all
        while ($row = $result2->fetch_assoc()) {
            array_push($dateArr, [
                $row["OrderDate"],
                $row["final"],
                $row["average"]
            ]);
        }

        // Free result set
        $result2->free_result();

        // close connection
        $conn->close();
        ?>
        <div class="row mt-3">
            <div class="col bg-white border">
                <h1>Daily Sales</h1>
                <div id="chart_div" style="width: 900px; height: 500px;"></div>
            </div>
        </div>

        <?php
        $sql3 = "
        SELECT DATE(orders.OrderDate) AS OrderDate, SUM((UnitPrice * Quantity) - (UnitPrice * Quantity) * Discount) AS final, AVG((UnitPrice * Quantity) - (UnitPrice * Quantity) * Discount) AS average
        FROM orders join order_details on orders.OrderID = order_details.OrderID
        WHERE  EXTRACT(MONTH FROM orders.OrderDate) = 05
        AND EXTRACT(YEAR FROM orders.OrderDate) = 1995
        GROUP BY orders.OrderDate;
        ";

        $result3 = $conn->query($sql3);

        $dateArr = array();

        // Fetch all
        while ($row = $result3->fetch_assoc()) {
            array_push($dateArr, [
                $row["OrderDate"],
                $row["final"],
                $row["average"]
            ]);
        }

        // Free result set
        $result3->free_result();

        // close connection
        $conn->close();
        ?>
        <div class="row mt-3">
            <div class="col bg-white border">
                <h1>Sales by Product Categories</h1>
                <div id="piechart_3d" style="width: auto; height: 500px;"></div>
            </div>
            <div class="col bg-white border">
                <h1>Sales by Customers</h1>
            </div>
            <div class="col bg-white border">
                <h1>Sales by Employees</h1>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">
    // Daily Sales Chart
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawVisualization);

    function drawVisualization() {
        // Some raw data (not necessarily accurate)
        var date = <?php echo json_encode($dateArr, JSON_HEX_TAG); ?>;

        const value = [
            ['Month', 'Sales', 'Average'],
        ];

        date.forEach(myFunction);

        function myFunction(item, index) {
            // console.log(item[0],parseInt(item[1]),parseInt(item[2]));
            value.push([item[0], parseFloat(item[1]), parseFloat(item[2])]);
        }

        // console.log(value);
        var data = google.visualization.arrayToDataTable(value);

        var options = {
            title: 'Monthly Coffee Production by Country',
            vAxis: {
                title: 'Cups'
            },
            hAxis: {
                title: 'Month'
            },
            seriesType: 'bars',
            series: {
                1: {
                    type: 'line'
                }
            }
        };

        var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }

    // Sales by Product Categories chart
    google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Hours per Day'],
          ['Work',     11],
          ['Eat',      2],
          ['Commute',  2],
          ['Watch TV', 2],
          ['Sleep',    7]
        ]);

        var options = {
          title: 'My Daily Activities',
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
    }

    // Sales by Customers chart
    // Sales by Employee chart
</script>

</html>