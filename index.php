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
    <div class="container-fluid">
        <div class="row mx-3">
            <h5>index.php</h5>
            <hr>
            <div class="col">
                <h1>Sales Dashboard</h1>
                <p class="f-3" style="font-size: 24px;">May 1995</p>
            </div>
        </div>

        <div class="row ms-3">
            <div class="w-25 me-3 col col-sm-12 col-md-6 p-2 bg-white border">
                <h3>Total Sales</h3>
                <h2>$ <?php echo round($totalSales, 2); ?></h2>
            </div>
            <div class="w-25 col col-sm-12 col-md-6 p-2 bg-white border">
                <h3>Total Orders</h3>
                <h2><?php echo $totalOrders; ?></h2>
            </div>
        </div>

        <?php
        $sql2 = "
        SELECT 
        DATE(orders.OrderDate) AS OrderDate, 
        ROUND(SUM((UnitPrice * Quantity) - (UnitPrice * Quantity) * Discount), 2) AS final, 
        ROUND(AVG((UnitPrice * Quantity) - (UnitPrice * Quantity) * Discount), 2) AS average
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
        // $conn->close();
        ?>
        <div class="row mx-3 mt-3">
            <div class="col bg-white border">
                <h1>Daily Sales</h1>
                <div id="chart_div" style="width: inherit; height: 500px;"></div>
            </div>
        </div>
        
        <div class="row mx-3 mt-3">
            <?php
            $sql4 = "
                SELECT 
                categories.CategoryName,
                ROUND(SUM((order_details.UnitPrice * order_details.Quantity) - (order_details.UnitPrice * order_details.Quantity) * Discount), 2) AS final
                FROM categories JOIN products ON categories.CategoryID = products.CategoryID
                JOIN order_details ON products.ProductID = order_details.ProductID
                JOIN orders ON orders.OrderID = order_details.OrderID
                WHERE EXTRACT(MONTH FROM orders.OrderDate) = 05
                AND EXTRACT(YEAR FROM orders.OrderDate) = 1995
                GROUP BY categories.CategoryID;
                ";

            $result4 = $conn->query($sql4);

            $categoryArr = array();

            // Fetch all
            while ($row = $result4->fetch_assoc()) {
                array_push($categoryArr, [
                    $row["CategoryName"],
                    $row["final"],
                ]);
            }

            // Free result set
            $result4->free_result();

            // close connection
            // $conn->close();
            ?>
            <div class="col bg-white border">
                <h2 class="text-center">Sales by Product Categories</h2>
                <div id="piechart_3d" style="width: inherit; height: 500px;"></div>
            </div>
            <?php
            $sql5 = "
                SELECT             
                customers.ContactName,
                ROUND(SUM((order_details.UnitPrice * order_details.Quantity) - (order_details.UnitPrice * order_details.Quantity) * Discount), 2) AS final
                FROM customers JOIN orders ON customers.CustomerID = orders.CustomerID
                JOIN order_details ON orders.OrderID = order_details.OrderID
                WHERE EXTRACT(MONTH FROM orders.OrderDate) = 05
                AND EXTRACT(YEAR FROM orders.OrderDate) = 1995
                GROUP BY customers.ContactName;
                ";

            $result5 = $conn->query($sql5);

            $customerArr = array();

            // Fetch all
            while ($row = $result5->fetch_assoc()) {
                array_push($customerArr, [
                    $row["ContactName"],
                    $row["final"],
                ]);
            }

            // Free result set
            $result5->free_result();

            // close connection
            // $conn->close();
            ?>
            <div class="col bg-white border">
                <h2 class="text-center">Sales by Customers</h2>
                <div id="top_x_div" style="width: inherit; height: 500px;"></div>
            </div>
            <?php
            $sql6 = "
                SELECT 
                employees.FirstName,
                ROUND(SUM((order_details.UnitPrice * order_details.Quantity) - (order_details.UnitPrice * order_details.Quantity) * Discount), 2) AS final
                FROM employees JOIN orders ON employees.EmployeeID = orders.EmployeeID
                JOIN order_details ON orders.OrderID = order_details.OrderID
                WHERE EXTRACT(MONTH FROM orders.OrderDate) = 05
                AND EXTRACT(YEAR FROM orders.OrderDate) = 1995
                GROUP BY employees.EmployeeID;
            ";

            $result6 = $conn->query($sql6);

            $employeeArr = array();

            // Fetch all
            while ($row = $result6->fetch_assoc()) {
                array_push($employeeArr, [
                    $row["FirstName"],
                    $row["final"],
                ]);
            }

            // Free result set
            $result6->free_result();

            // close connection
            $conn->close();
            ?>
            <div class="col bg-white border">
                <h2 class="text-center">Sales by Employees</h2>
                <div id="top_x_div_2" style="width: inherit; height: 500px;"></div>
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
            value.push(
                [
                    item[0], 
                    parseFloat(item[1]), 
                    parseFloat(item[2])
                ]
            );
        }

        // console.log(date);

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
    google.charts.load("current", {
        packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart);

    var category = <?php echo json_encode($categoryArr, JSON_HEX_TAG); ?>;

    const value2 = [
        ['Task', 'Hours per Day'],
    ];

    category.forEach(myFunction2)

    function myFunction2(item, index) {
        value2.push([item[0], parseFloat(item[1])]);
    }

    function drawChart() {
        var data = google.visualization.arrayToDataTable(value2);

        var options = {

            is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
    }

    // Sales by Customers chart
    google.charts.load('current', {
        'packages': ['bar']
    });
    google.charts.setOnLoadCallback(drawStuff);

    var customer = [
        ['Customers', 'Total Sales'],
    ];

    var custData = <?php echo json_encode($customerArr, JSON_HEX_TAG); ?>;

    custData.forEach(myFunction3)

    function myFunction3(item, index) {
        customer.push([item[0], parseFloat(item[1])]);
    }

    function drawStuff() {
        var data = new google.visualization.arrayToDataTable(customer);

        var options = {
            width: 900,
            legend: {
                position: 'none'
            },          
            bars: 'horizontal', // Required for Material Bar Charts.
            axes: {
                x: {
                    0: {
                        side: 'top',
                        label: 'Total Sales'
                    } // Top x-axis.
                }
            },
            bar: {
                groupWidth: "90%"
            }
        };

        var chart = new google.charts.Bar(document.getElementById('top_x_div'));
        chart.draw(data, options);
    };

    // Sales by Employee chart
    google.charts.load('current', {
        'packages': ['bar']
    });
    google.charts.setOnLoadCallback(drawStuff2);

    var employee = [
        ['Employees', 'Total Sales'],
    ];

    var empData = <?php echo json_encode($employeeArr, JSON_HEX_TAG); ?>;


    empData.forEach(myFunction4)

    function myFunction4(item, index) {
        employee.push([item[0], parseFloat(item[1])]);
    }

    function drawStuff2() {
        var data = new google.visualization.arrayToDataTable(employee);

        var options = {
            width: 900,
            legend: {
                position: 'none'
            },
            bars: 'horizontal', // Required for Material Bar Charts.
            axes: {
                x: {
                    0: {
                        side: 'top',
                        label: 'Total Sales'
                    } // Top x-axis.
                }
            },
            bar: {
                groupWidth: "90%"
            }
        };

        var chart = new google.charts.Bar(document.getElementById('top_x_div_2'));
        chart.draw(data, options);
    };
</script>

</html>