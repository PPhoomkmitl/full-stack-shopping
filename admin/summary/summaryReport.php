
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #343a40;
        }

        /* .navbar {
            margin-bottom: 20px;
        } */
        .container {
            margin-top: 100px;
        }

        .dashboard-heading {
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
            font-weight: bold;
        }

        .data-container {
            width: 60%;
            margin: auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 20px;
        }

        .data-card {
            flex-grow: 1;
            flex-basis: calc(50% - 20px);
            margin: 0 10px 20px;
            background-color: #E3F4F4;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .data-card:hover {
            transform: scale(1.0);
        }

        .data-card h2,
        .data-card h3,
        .data-card p {
            margin: 0;
            color: #343a40;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 8px;
            text-align: center;
            border-bottom: 1px solid #C4DFDF;
        }

        th {
            background-color: #C4DFDF;
        }

        #card-4 h1 {
            text-align: center;
            padding: auto;
            margin-top: 35px;
            margin-bottom: 35px;
        }
        .navCon {
            z-index: 10;
        }
    </style>
</head>
<body> 
    <div class="navCon">
        <?php include('../navbar/navbarAdmin.php') ?>
    </div>
    <div class="container">
        <h1 class="dashboard-heading">Summary Report</h1>
        <?php
            date_default_timezone_set('Asia/Bangkok');
            $currentDateTime = date("d-m-Y");
        ?>

        <center><h2>Day: <?php echo $currentDateTime; ?></h2></center>
        <div class="data-container">
            <div class="data-card" id='card-1'>
                <h2 id='PQ'>Product Summary</h2>
                <?php 
                    $cx =  mysqli_connect("localhost", "root", "", "shopping");
                ?>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price Per Unit</th>
                        <th>Total Unit Sold</th>
                        <th>Total Price</th>
                        <th>PercentageSold</th>
                    </tr>
                    <?php
                        // Calculate the total quantity sold across all products
                        $totalQuantityAllProducts_Query = mysqli_query($cx, "SELECT SUM(order_details.quantity) AS TotalQtyAllProducts
                        FROM order_details
                        INNER JOIN orders ON order_details.order_id = orders.order_id
                        WHERE DATE(orders.order_date) = CURDATE()");
                        $totalQuantityAllProducts_row = mysqli_fetch_assoc($totalQuantityAllProducts_Query);
                        $totalQuantityAllProducts = $totalQuantityAllProducts_row['TotalQtyAllProducts'];

                        $bestSell_Query = mysqli_query($cx, "
                        SELECT
                            product.ProID,
                            product.ProName,
                            product.Description,
                            product.PricePerUnit,
                            SUM(order_details.quantity) AS TotalQty
                        FROM
                            product
                            INNER JOIN order_details ON product.ProID = order_details.ProID
                            INNER JOIN orders ON order_details.order_id = orders.order_id
                        WHERE
                            DATE(orders.order_date) = CURDATE()
                        GROUP BY
                            product.ProID
                        ORDER BY
                            TotalQty DESC
                    ");

                    while ($row = mysqli_fetch_assoc($bestSell_Query)) {
                        $totalSum = $row['PricePerUnit'] * $row['TotalQty'];
                        $percentageSold = ($row['TotalQty'] / $totalQuantityAllProducts) * 100;
                        
                        echo "<tr>";
                        echo "<td>" . $row['ProID'] . "</td>";
                        echo "<td>" . $row['ProName'] . "</td>";
                        echo "<td>" . $row['PricePerUnit'] . "</td>";
                        echo "<td>" . $row['TotalQty'] . "</td>";
                        echo "<td>" . $totalSum . "</td>";
                        echo "<td>" . number_format($percentageSold, 2) . "%</td>";
                        echo "</tr>";
                    }
                    ?>
                </table>
                <h1 id='Re'></h1>
                    <?php 
                        $income_Query = mysqli_query($cx, "SELECT SUM(product.PricePerUnit * order_details.quantity) AS TotalIncome
                        FROM product 
                        INNER JOIN order_details ON product.ProID = order_details.ProID
                        INNER JOIN orders ON order_details.order_id = orders.order_id
                        WHERE DATE(orders.order_date) = CURDATE()");
                        $total_income_row = mysqli_fetch_assoc($income_Query);
                        $total_income = $total_income_row['TotalIncome'];
                        echo "<h2>Total Income: ฿" . number_format($total_income, 2) . "</h2>";
                    ?>
            </div>
        </div>
    </div>
    <!-- Monthly Summary -->
    <?php
        // Display the month
        $currentMonth = date('F');
        echo "<center><h2>Month: $currentMonth</h2></center>";
    ?>
    <div class="data-container">
        <div class="data-card" id='card-2'>
            <h2 id='PQ'>Product Summary - Monthly</h2>
            <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price Per Unit</th>
                        <th>Total Unit Sold</th>
                        <th>Total Price</th>
                        <th>PercentageSold</th>
                    </tr>
            <?php
            // Calculate the total quantity sold across all products for the month
            $totalQuantityMonthly_Query = mysqli_query($cx, "SELECT SUM(order_details.quantity) AS TotalQtyMonthly
                    FROM order_details
                    INNER JOIN orders ON order_details.order_id = orders.order_id
                    WHERE MONTH(orders.order_date) = MONTH(CURDATE()) AND YEAR(orders.order_date) = YEAR(CURDATE())");
            $totalQuantityMonthly_row = mysqli_fetch_assoc($totalQuantityMonthly_Query);
            $totalQuantityMonthly = $totalQuantityMonthly_row['TotalQtyMonthly'];

            $monthlySell_Query = mysqli_query($cx, "
                    SELECT
                        product.ProID,
                        product.ProName,
                        product.Description,
                        product.PricePerUnit,
                        SUM(order_details.quantity) AS TotalQty
                    FROM
                        product
                        INNER JOIN order_details ON product.ProID = order_details.ProID
                        INNER JOIN orders ON order_details.order_id = orders.order_id
                    WHERE
                        MONTH(orders.order_date) = MONTH(CURDATE()) AND YEAR(orders.order_date) = YEAR(CURDATE())
                    GROUP BY
                        product.ProID
                    ORDER BY
                        TotalQty DESC
                ");

            while ($row = mysqli_fetch_assoc($monthlySell_Query)) {
                $totalSum = $row['PricePerUnit'] * $row['TotalQty'];
                $percentageSold = ($row['TotalQty'] / $totalQuantityMonthly) * 100;

                echo "<tr>";
                echo "<td>" . $row['ProID'] . "</td>";
                echo "<td>" . $row['ProName'] . "</td>";
                echo "<td>" . $row['PricePerUnit'] . "</td>";
                echo "<td>" . $row['TotalQty'] . "</td>";
                echo "<td>" . $totalSum . "</td>";
                echo "<td>" . number_format($percentageSold, 2) . "%</td>";
                echo "</tr>";
            }
            
            ?>
            </table>
            <h1 id='Re'></h1>
            <?php 
                $monthlyIncome_Query = mysqli_query($cx, "
                    SELECT SUM(product.PricePerUnit * order_details.quantity) AS TotalMonthlyIncome
                    FROM product 
                    INNER JOIN order_details ON product.ProID = order_details.ProID
                    INNER JOIN orders ON order_details.order_id = orders.order_id
                    WHERE MONTH(orders.order_date) = MONTH(CURDATE()) AND YEAR(orders.order_date) = YEAR(CURDATE())
                ");
                $total_monthly_income_row = mysqli_fetch_assoc($monthlyIncome_Query);
                $total_monthly_income = $total_monthly_income_row['TotalMonthlyIncome'];
                echo "<h2>Total Income: ฿" . number_format($total_monthly_income, 2) . "</h2>";
            ?>
        </div>
    </div>

    <!-- Yearly Summary -->
    <?php
        // Display the month
        $currentMonth = date('Y');
        echo "<center><h2>Year: $currentMonth</h2></center>";
    ?>
    <div class="data-container">
        <div class="data-card" id='card-3'>
            <h2 id='PQ'>Product Summary - Yearly</h2>
            <table>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price Per Unit</th>
                        <th>Total Unit Sold</th>
                        <th>Total Price</th>
                        <th>PercentageSold</th>
                    </tr>
            <?php
            // Calculate the total quantity sold across all products for the year
            $totalQuantityYearly_Query = mysqli_query($cx, "SELECT SUM(order_details.quantity) AS TotalQtyYearly
                    FROM order_details
                    INNER JOIN orders ON order_details.order_id = orders.order_id
                    WHERE YEAR(orders.order_date) = YEAR(CURDATE())");
            $totalQuantityYearly_row = mysqli_fetch_assoc($totalQuantityYearly_Query);
            $totalQuantityYearly = $totalQuantityYearly_row['TotalQtyYearly'];

            $yearlySell_Query = mysqli_query($cx, "
                    SELECT
                        product.ProID,
                        product.ProName,
                        product.Description,
                        product.PricePerUnit,
                        SUM(order_details.quantity) AS TotalQty
                    FROM
                        product
                        INNER JOIN order_details ON product.ProID = order_details.ProID
                        INNER JOIN orders ON order_details.order_id = orders.order_id
                    WHERE
                        YEAR(orders.order_date) = YEAR(CURDATE())
                    GROUP BY
                        product.ProID
                    ORDER BY
                        TotalQty DESC
                ");

            while ($row = mysqli_fetch_assoc($yearlySell_Query)) {
                $totalSum = $row['PricePerUnit'] * $row['TotalQty'];
                $percentageSold = ($row['TotalQty'] / $totalQuantityYearly) * 100;

                echo "<tr>";
                echo "<td>" . $row['ProID'] . "</td>";
                echo "<td>" . $row['ProName'] . "</td>";
                echo "<td>" . $row['PricePerUnit'] . "</td>";
                echo "<td>" . $row['TotalQty'] . "</td>";
                echo "<td>" . $totalSum . "</td>";
                echo "<td>" . number_format($percentageSold, 2) . "%</td>";
                echo "</tr>";
            }
            ?>
            </table>
            <h1 id='Re'></h1>
            <?php 
                $yearlyIncome_Query = mysqli_query($cx, "
                    SELECT SUM(product.PricePerUnit * order_details.quantity) AS TotalYearlyIncome
                    FROM product 
                    INNER JOIN order_details ON product.ProID = order_details.ProID
                    INNER JOIN orders ON order_details.order_id = orders.order_id
                    WHERE YEAR(orders.order_date) = YEAR(CURDATE())
                ");
                $total_yearly_income_row = mysqli_fetch_assoc($yearlyIncome_Query);
                $total_yearly_income = $total_yearly_income_row['TotalYearlyIncome'];
                echo "<h2>Total Income: ฿" . number_format($total_yearly_income, 2) . "</h2>";
            ?>
        </div>
    </div>
</div>
</body>
</html>