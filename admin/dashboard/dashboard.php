<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">


    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: grey;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
            box-sizing: border-box;
            height: 100vh;
        }

        .container {
            max-width: 100%;
            height: 100vh;
            margin: 0 auto;
            padding: 20px;
            background-color: #F2F4F1;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            /* margin-top: 20px; */
        }

        .navbar {
            /* Add your styles for the navbar here */
            margin-top: 20px;
        }

        .dashboard-heading {
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
            font-weight: bold;
        }

        /* .data-container {
            display: flex;
            flex-wrap: wrap;
            /
        } */

        .data-card {
            flex-grow: 1;
            flex-basis: calc(33.33% - 20px); /* 33.33% for each card with a margin of 20px */
            margin: 0 10px 20px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .data-card:hover {
            transform: scale(1.0);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #f8f9fa;
        }
 

    </style>
</head>
<body> 
    <div class="navbar"> <?php include('../navbar/navbarAdmin.php') ?></div>  
    <!-- <div class="container">  -->
        <!-- <h1 class="dashboard-heading">CEO Dashboard</h1> -->
        <?php 
            include_once '../../dbConfig.php';
            // $conn =  mysqli_connect("localhost", "root", "", "shopping");
            $ProductQuery = mysqli_query($conn, "SELECT COUNT(*) AS total_products FROM product");  
            $ProductDetails = mysqli_fetch_assoc($ProductQuery);
        ?>
     
         
        <?php 
            if($_SESSION['admin'] == 'super_admin') {
        ?>
        <div class="container">
            <div class="row"> 
                <div class="data-card text-center">
                    <h3>CEO Dashboard</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="data-card" id='card-1'>
                        <h2 id='Re'>Revenue</h2>
                        <?php                       
                            $income_Query = mysqli_query($conn, "SELECT * FROM product INNER JOIN order_details ON product.ProID = order_details.ProID");
                            (double)$total_income = 0;
                            while($row = mysqli_fetch_assoc($income_Query)) {
                                $total_income += (double)$row['PricePerUnit'] * (double)$row['quantity'];
                            }
                            echo "<h1>Total Income: ฿" . number_format($total_income, 2) . "</h1>";
                        ?>        

                    </div>
                    <div class="data-card" id="card-2">
                        <canvas id="donutChart" width="500" height="300"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="data-card" id="card-3">
                        <h2 id='PR'>Monthly Sales Products</h2>
                        <canvas id="myChart" width="500" height="252"></canvas>
                    </div>
                </div>
            </div>
            <div class="row"> 
                <div class="data-card" id='card-4'>
                    <h2 id='PQ'>Product Quantity</h2>               
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price Per Unit</th>
                            <th>Total Price</th>
                            <th>Remaining Quantity</th>
                        </tr>
                        <?php
                            $ProductQuery = mysqli_query($conn, "SELECT * FROM product LIMIT 4");  
                            while($row = mysqli_fetch_assoc($ProductQuery)) {
                            $total = (double)$row['PricePerUnit'] * (double)$row['StockQty'];
                                echo "<tr>";
                                echo "<td>" . $row['ProID'] . "</td>";
                                echo "<td>" . $row['ProName'] . "</td>";
                                echo "<td>" . $row['PricePerUnit'] . "</td>";
                                echo "<td>" . $total . "</td>";
                                echo "<td>" . $row['StockQty'] . "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                </div> 
            </div>
        </div>
        <?php
        }
        ?>


        <?php 
            if($_SESSION['admin'] == 'user_admin') {
        ?>
            <?php
                $sql = "SELECT 
                        SUM(CASE WHEN fullfill_status = 'Unfulfilled' THEN 1 ELSE 0 END) AS Unfulfilled_orders,
                        SUM(CASE WHEN shipping_status = 'Pending' THEN 1 ELSE 0 END) AS pending_orders,
                        SUM(CASE WHEN shipping_status = 'Inprogress' THEN 1 ELSE 0 END) AS inprogress_orders,
                        SUM(CASE WHEN shipping_status = 'Canceled' THEN 1 ELSE 0 END) AS cancel_orders,
                        COUNT(*) AS total_orders
                    FROM 
                        orders";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <div class="container">
                    <div class="row py-3 px-3 justify-content-between">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Pending Orders</h5>
                                    <p class="card-text"><?php echo $row['pending_orders']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Inprogress Orders</h5>
                                    <p class="card-text"><?php echo $row['inprogress_orders']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Total Orders</h5>
                                    <p class="card-text"><?php echo $row['total_orders']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Canceled Orders</h5>
                                    <p class="card-text"><?php echo $row['cancel_orders']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                    <h2 id='PQ'>Total Sales Today</h2>  
                        <div class="data-card">          
                            <canvas id="barChart" width="500" height="250"></canvas>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="data-card">  
                                <canvas id="donutChart" width="500" height="300"></canvas>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="data-card" id='card-4'>
                                <h2 id='PQ'>Product Quantity</h2>                   
                                <table>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Price Per Unit</th>
                                        <th>Total Price</th>
                                        <th>Remaining Quantity</th>
                                    </tr>
                                    <?php
                                        $ProductQuery = mysqli_query($conn, "SELECT * FROM product LIMIT 5 ");  
                                        while($row = mysqli_fetch_assoc($ProductQuery)) {
                                        $total = (double)$row['PricePerUnit'] * (double)$row['StockQty'];
                                            echo "<tr>";
                                            echo "<td>" . $row['ProID'] . "</td>";
                                            echo "<td>" . $row['ProName'] . "</td>";
                                            echo "<td>" . $row['PricePerUnit'] . "</td>";
                                            echo "<td>" . $total . "</td>";
                                            echo "<td>" . $row['StockQty'] . "</td>";
                                            echo "</tr>";
                                        }
                                    ?>
                                </table>
                            </div> 
                        </div>
                    </div> 
                </div>
        <?php
                    }
                }           
        ?>

        <?php
        }
        ?>




        <?php 
            if($_SESSION['admin'] == 'permission_admin') {
        ?>
            <?php
                $sql = "SELECT 
                        SUM(CASE WHEN role = 'member' THEN 1 ELSE 0 END) AS member,
                        SUM(CASE WHEN role = 'super_admin' THEN 1 ELSE 0 END) AS super_admin,
                        SUM(CASE WHEN role = 'permission_admin' THEN 1 ELSE 0 END) AS permission_admin,
                        SUM(CASE WHEN role = 'user_admin' THEN 1 ELSE 0 END) AS user_admin,
                        COUNT(CASE WHEN role != 'guest' THEN 1 ELSE NULL END) AS total_user
                    FROM 
                        customer";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <div class="container">
            <div class="row py-4">
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Member</h5>
                            <p class="card-text"><?php echo $row['member']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Super Admin</h5>
                            <p class="card-text"><?php echo $row['super_admin']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Permission Admin</h5>
                            <p class="card-text"><?php echo $row['permission_admin']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">User Admin</h5>
                            <p class="card-text"><?php echo $row['user_admin']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total User</h5>
                            <p class="card-text"><?php echo $row['total_user']; ?></p>
                        </div>
                    </div>
                </div>
            </div>

                    <div class="row"> 
                        <div class="data-card">
                            <canvas id="barChartLog" width="500" height="250"></canvas>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="data-card">  
                                <canvas id="donutChartRole" width="500" height="300"></canvas>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="data-card" id='card-4'>
                                <h2 id='PQ'>Access Log</h2>                   
                                <table>
                                    <tr>
                                        <th>Time</th>
                                        <th>User ID</th>
                                        <th>Action</th>
                                        <th>File</th>
   
                                    </tr>
                                    <?php
                                        $logtQuery = mysqli_query($conn, "SELECT * FROM log ORDER BY TimeStamp DESC LIMIT 5;");  
                                        while($row = mysqli_fetch_assoc($logtQuery)) {
                                    
                                            echo "<tr>";
                                            echo "<td>" . $row['TimeStamp'] . "</td>";
                                            echo "<td>" . $row['UserID'] . "</td>";
                                            echo "<td>" . $row['Action'] . "</td>";
                                            echo "<td>" . $row['CallingFile'] . "</td>";
                                            echo "</tr>";
                                        }
                                    ?>
                                </table>
                            </div> 
                        </div>
                    </div> 
                </div>
        <?php
                    }
                }           
        ?>

        <?php
        }
        ?>

          
    </div>
    

    <!--------------------------------------------- Dashboard CEO --------------------------------------------->
    <script>
    <?php
            // ปรับคิวรีเพื่อหายอดขายต่อเดือนในช่วง January - July
            $bestSell_Query = mysqli_query($conn, "SELECT 
            product.ProID, 
            product.ProName, 
            DATE_FORMAT(`orders`.order_date, '%Y-%m') AS YearMonth, 
            SUM(orders.total_price) AS TotalQty,
            DATE_FORMAT(`orders`.order_date, '%M') AS MonthName
            FROM 
                product 
            INNER JOIN 
                order_details ON product.ProID = order_details.ProID 
            INNER JOIN 
                `orders` ON order_details.order_id = `orders`.order_id 
            WHERE 
                DATE_FORMAT(`orders`.order_date, '%Y-%m') BETWEEN '2024-01' AND '2024-12' 
            GROUP BY 
                product.ProID, 
                YearMonth 
            ORDER BY 
            YearMonth DESC, 
            TotalQty DESC;");


            // เรียกข้อมูลจากคิวรีและกำหนดให้มีค่าเป็น 0 สำหรับเดือนที่ไม่มีข้อมูล
            $data = ["0", "0", "0", "0", "0", "0", "0","0", "0", "0", "0", "0"];
            $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            
            while ($row = mysqli_fetch_assoc($bestSell_Query)) {
                $index = array_search($row['MonthName'], $months);
                if ($index !== false) {
                    $data[$index] = $row['TotalQty'];
                }
            }
            
    
            // $json_data = json_encode($data);

        ?>
        // ตั้งค่าของกราฟ
        var config_line = {
            type: 'line',
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                datasets: [{
                        label: "Revenu (฿)",
                        data: <?php echo json_encode($data); ?>,
                        fill: false,
                        borderColor: "#0866ff",
                        tension: 0.25
                        }]
            }, 
            options: {
                scales: {
                    y: {
                        beginAtZero: true 
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: ''
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        };
        
    var myChart = new Chart(
        document.getElementById('myChart'),
        config_line
    );
    </script>
    <script>
        <?php
            $ProductQuery = mysqli_query($conn, "SELECT product_type.name AS product_type, SUM(order_details.quantity) AS total_sales 
            FROM order_details JOIN product ON order_details.ProID = product.ProID 
            JOIN product_type ON product.product_type_id = product_type.id 
            GROUP BY product_type.name;");  

            $total_sales = ["0","0","0"];
            $product_type = ["Type A", "Type B", "Type C"];
            while ($row = mysqli_fetch_assoc($ProductQuery)) {
                $index = array_search($row['product_type'], $product_type);
                if ($index !== false) {
                    $total_sales[$index] = $row['total_sales'];
                }
            }
            
            $ProductQuery = mysqli_query($conn, "SELECT product_type.name AS product_type FROM product_type;");
            $data_type = []; 
            while ($row = mysqli_fetch_assoc($ProductQuery)) {
                $data_type[] = $row['product_type']; 
            }
        ?>

  
        const salesData = {
            labels: <?php echo json_encode($data_type); ?>,
            datasets: [{
                label: "Sales",
                data: <?php echo json_encode($total_sales); ?>, 
                backgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"], 
                hoverBackgroundColor: ["#FF6384", "#36A2EB", "#FFCE56"] 
            }]
        };

        // ตั้งค่า Donut Chart
        const config = {
            type: 'doughnut',
            data: salesData,
            options: {
                responsive: true,
                maintainAspectRatio: false, 
                plugins: {
                    legend: {
                        position: 'bottom', 
                    },
                    title: {
                        display: true,
                        text: 'Sales by Product Type'
                    }
                }
            },
        };

        // สร้าง Donut Chart
        const myDonutChart = new Chart(
            document.getElementById('donutChart'),
            config
        );
    </script>


    <!--------------------------------------------- Dashboard user admin --------------------------------------------->
    <?php
    
    $product_name_query = mysqli_query($conn, "SELECT product.ProName FROM product;");
    $product_data = [];
    $i = 0;
    while ($row = mysqli_fetch_assoc($product_name_query)) {
        $product_data[$i] = $row['ProName'];
        $i++;
    }
    
    
    $start_date = date('Y-m-d'); // วันที่เริ่มต้นคือวันนี้
    $end_date = date('Y-m-d'); // วันที่สิ้นสุดคือวันนี้
    
    // คำสั่ง SQL เพื่อดึงข้อมูลยอดขายสินค้าตามวันนี้
    $bestSell_query = mysqli_query($conn, "SELECT 
    product.ProName, 
    DATE_FORMAT(MAX(`orders`.order_date), '%Y-%m-%d') AS OrderDate, 
    SUM(order_details.quantity) AS TotalQty
    FROM 
        product 
    INNER JOIN 
        order_details ON product.ProID = order_details.ProID 
    INNER JOIN 
        `orders` ON order_details.order_id = `orders`.order_id 
    GROUP BY 
        product.ProID
    ORDER BY 
    TotalQty DESC;");

    
    $data = [];  
    while ($row = mysqli_fetch_assoc($bestSell_query)) {
        $index = array_search($row['ProName'], $product_data );
        if ($index !== false) {
            $data[$row['ProName']] = $row['TotalQty'];
        } else {
            $data[$row['ProName']] = 0;
        }
    }
    ?>
    <script>
        // ข้อมูลสำหรับกราฟแท่ง
        var barData = {
            labels: <?php echo json_encode($product_data); ?>, 
            datasets: [{
                label: 'ยอดขาย',
                backgroundColor: 'rgba(54, 162, 235, 0.5)', 
                borderColor: 'rgba(54, 162, 235, 1)', 
                data: <?php echo json_encode($data); ?>,
            }]
        };
    
        // ตั้งค่าของกราฟแท่ง
        var barOptions = {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }              
                }],
                xAxes: [{
                    ticks: {
                        beginAtZero: true
                    }   
                }]
            },
            barThickness: 80,
            maintainAspectRatio: false
        };
    
        // สร้างกราฟแท่ง
        var barChart = new Chart(document.getElementById('barChart'), {
            type: 'bar',
            data: barData,
            options: barOptions
        });
    </script>

    <!--------------------------------------------- Dashboard permission admin --------------------------------------------->
    <?php
    
    $start_date = date('Y-m-d', strtotime('-1 week')); // วันที่เริ่มต้นเป็นวันก่อนหนึ่งสัปดาห์
    $end_date = date('Y-m-d'); // วันที่สิ้นสุดเป็นวันปัจจุบัน

    $log_query = mysqli_query($conn, "SELECT 
    DATE(TimeStamp) AS Date,
    COUNT(*) AS TotalUsers
    FROM log
    WHERE DATE(TimeStamp) BETWEEN '$start_date' AND '$end_date'
    GROUP BY DATE(TimeStamp)
    ORDER BY Date ASC");
    
    $log_data = [];
    $labels = [];
    
    while ($row = mysqli_fetch_assoc($log_query)) {
        $labels[] = $row['Date'];
        $log_data[] = $row['TotalUsers'];
    }
     
    ?>
    <script>
        // ข้อมูลสำหรับกราฟแท่ง
        var barData = {
            labels: <?php echo json_encode($labels); ?>, // วันที่
            datasets: [{
                label: 'Number of users',
                backgroundColor: 'rgba(54, 162, 235, 0.5)', 
                borderColor: 'rgba(54, 162, 235, 1)', 
                data: <?php echo json_encode($log_data); ?>, // จำนวนผู้ใช้งาน
            }]
        };

        // ตั้งค่าของกราฟแท่ง
        var barOptions = {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }              
                }],
                xAxes: [{
                    ticks: {
                        beginAtZero: true
                    }   
                }]
            },
            barThickness: 80,
            maintainAspectRatio: false
        };

        // สร้างกราฟแท่ง
        var barChart = new Chart(document.getElementById('barChartLog'), {
            type: 'bar',
            data: barData,
            options: barOptions
        });
    </script>

    <?php
        $role_data_query = mysqli_query($conn, "SELECT 
                SUM(CASE WHEN role = 'member' THEN 1 ELSE 0 END) AS member,
                SUM(CASE WHEN role = 'super_admin' THEN 1 ELSE 0 END) AS super_admin,
                SUM(CASE WHEN role = 'permission_admin' THEN 1 ELSE 0 END) AS permission_admin,
                SUM(CASE WHEN role = 'user_admin' THEN 1 ELSE 0 END) AS user_admin
            FROM 
                customer");

        $role_data = mysqli_fetch_assoc($role_data_query);
    ?>

    <script>
        // ข้อมูลสำหรับ Donut Chart
        var donutData = {
            labels: ['Member', 'Super Admin', 'Permission Admin', 'User Admin'],
            datasets: [{
                label: 'Users by Role',
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
                data: [
                    <?php echo $role_data['member']; ?>,
                    <?php echo $role_data['super_admin']; ?>,
                    <?php echo $role_data['permission_admin']; ?>,
                    <?php echo $role_data['user_admin']; ?>
                ]
            }]
        };

        // ตั้งค่า Donut Chart
        var donutOptions = {
            maintainAspectRatio: false
        };

        // สร้าง Donut Chart
        var donutChart = new Chart(document.getElementById('donutChartRole'), {
            type: 'doughnut',
            data: donutData,
            options: donutOptions
        });
    </script>

</body>
</html>


