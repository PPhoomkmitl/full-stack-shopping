<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <!-- Your existing meta tags and styles -->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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

        th,
        td {
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

        /* Filter Icon */
        .filter-icon {
            position: fixed;
            cursor: pointer;
            z-index: 100;
            width: 25px;
            /* Adjust size as needed */
            height: 25px;
            left: 10px;
            /* Distance from the left side */
            top: 50%;
            transform: translateY(-50%);
        }

        .filter-icon img {
            width: 150%;
            height: 150%;
        }

        @media screen and (max-width: 768px) {

            /* Adjust icon size for smaller screens */
            .filter-icon {
                width: 25px;
                /* 50% smaller for smaller screens */
            }
        }


        /* Filter Zone */
        .filter-zone {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 99;
        }

        .filter-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 5px;
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

        <div id="filter-icon" class="filter-icon" onclick="toggleFilterZone()">
            <img id="filter-icon-img" src="../img/filtered.png" alt="Filter Icon">
        </div>


        <!-- Filter Zone -->
        <div id="filter-zone" class="filter-zone">
            <div class="filter-content">
                <h2>Filters</h2>
                <!-- Filter options here -->
                <label for="filter-type">Filter Type:</label>
                <select id="filter-type">
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                    <option value="weekly">Weekly</option>
                    <option value="custom">Custom</option>
                </select>
                <div id="month-year-filter" style="display: none;">
                    <label for="filter-month">Month:</label>
                    <select id="filter-month">
                        <!-- Options for months -->
                    </select>
                    <label for="filter-year">Year:</label>
                    <input type="number" id="filter-year" min="2000" max="2100" value="2024">
                </div>
                <div id="custom-filter" style="display: none;">
                    <label for="start-date">Start Date:</label>
                    <input type="date" id="start-date">

                    <label for="end-date">End Date:</label>
                    <input type="date" id="end-date">
                </div>
                <button onclick="toggleFilterZone()" required>Reset</button>
                <button onclick="applyFilter()" required>Apply</button>
            </div>
        </div>
        <center>
            <h2>Day: <?php echo $currentDateTime; ?></h2>
        </center>
        <div class="data-container" id="daily-summary">
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
    <div class="data-container" id="monthly-summary">
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
    <div class="data-container" id="yearly-summary">
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
    <script>
        var filterApplied = false; // Variable to track filter status
        var customFilterDetails = {}; // Object to store custom filter details

        async function toggleFilterZone() {
            var filterZone = document.getElementById('filter-zone');
            if (filterZone.style.display === 'none' || filterZone.style.display === '') {
                // Set placeholder text for date inputs when custom filter option is selected
                var filterType = document.getElementById('filter-type').value;
                if (filterType === 'custom') {
                    document.getElementById('start-date').setAttribute("placeholder", "yyyy-mm-dd");
                    document.getElementById('end-date').setAttribute("placeholder", "yyyy-mm-dd");
                }
                filterZone.style.display = 'block';

                // Wait for the filter type to be selected before logging filter details
                await new Promise(resolve => {
                    document.getElementById('filter-type').addEventListener('change', resolve, {
                        once: true
                    });
                    filterZone.style.display === 'none'
                });
                logFilterDetails();
            } else {
                resetFilters();
                filterZone.style.display = 'none';
            }
        }

        function logFilterDetails() {
            var filterType = document.getElementById('filter-type').value;

            if (filterType === 'weekly') {
                var filterWeek = document.getElementById('filter-week').value;
                var filterWeekYear = document.getElementById('filter-week-year').value;
                console.log("Filtering by week:", filterWeek, "Year:", filterWeekYear);
            } else if (filterType === 'monthly') {
                var filterMonth = document.getElementById('filter-month').value;
                var filterYear = document.getElementById('filter-year').value;
                console.log("Filtering by month:", filterMonth, "Year:", filterYear);
            } else if (filterType === 'yearly') {
                var filterYear = document.getElementById('filter-year').value;
                console.log("Filtering by year:", filterYear);
            } else if (filterType === 'custom') {
                var startDate = document.getElementById('start-date').value;
                var endDate = document.getElementById('end-date').value;
                customFilterDetails.startDate = startDate;
                customFilterDetails.endDate = endDate;
                console.log("Filtering by custom range. Start Date:", startDate, "End Date:", endDate);

                // Show date format box beside filter icon if 'custom' filter applied
                document.getElementById('custom-filter').style.display = 'block';
            }
        }

        function applyFilter() {
            toggleFilterZone();

            // Filter daily summary data
            // Example: If you want to hide the daily summary, you can use:
            document.getElementById('daily-summary').style.display = 'none';

            // Filter monthly summary data
            // Example: If you want to show the monthly summary, you can use:
            document.getElementById('monthly-summary').style.display = 'block';

            // Filter yearly summary data
            // Example: If you want to hide the yearly summary, you can use:
            document.getElementById('yearly-summary').style.display = 'none';

            // Change filter icon and set filter status
            document.getElementById('filter-icon-img').src = '../img/filter.png';
            filterApplied = true;
        }

        // Function to reset filters and filter icon
        function resetFilters() {
            function resetFilters() {
                // Reset filter icon
                document.getElementById('filter-icon-img').src = '../img/filtered.png';

                // Reset filter status
                filterApplied = false;

                // Hide all summary sections
                document.getElementById('daily-summary').style.display = 'block';
                document.getElementById('monthly-summary').style.display = 'block';
                document.getElementById('yearly-summary').style.display = 'block';
            }
        }
    </script>





</body>

</html>