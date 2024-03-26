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
            position: absolute;
            cursor: pointer;
            z-index: 100;
            width: 25px;
            /* Adjust size as needed */
            height: 25px;
            margin-left: 70px;
            /* Distance from the left side */
            top: 50px;
            transform: translateY(-50%);
            z-index: 1;
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
            display: box;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .filter-content {
            margin-left: 5%;
            border: 1px solid #000;
            top: 25%;
            position: absolute;
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


        <div id="filter-zone" class="filter-zone">
            <!-- Filter content -->

            <div class="filter-content">
                <h2>Filters</h2>
                <div id="filter-icon" class="filter-icon" onclick="resetFilters()">
                    <img id="filter-icon-img" src="../img/filtered.png" alt="Filter Icon">
                </div>
                <!-- Filter options here -->
                <label for="filter-type">Filter Type:</label>
                <select id="filter-type" onchange="handleFilterTypeChange()">
                    <option value="no-filter">No Filter</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                    <option value="daily">Daily</option>
                    <option value="custom">Custom</option>
                </select>

                <div id="filter-options" style="display: none;" onchange="handleMonthChange()">
                    <div id="monthly-options">
                        <label for="filter-month">Month:</label>
                        <select id="filter-month">
                            <option value="0">No filter</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                        <label for="filter-year">Year:</label>
                        <input type="number" id="filter-month-year" min="2000" max="2100" value="2024">
                    </div>
                    <div id="yearly-options" onchange="handleYearlyChange()">
                        <label for="filter-year">Year:</label>
                        <input type="number" id="filter-year" min="2000" max="2100" value="2024">
                    </div>
                    <div id="daily-options">
                        <label for="filter-day">Day:</label>
                        <input type="date" id="filter-date" onchange="handleDailyChange()">
                    </div>
                </div>
                <div id="custom-filter" style="display: none;">
                    <label for="start-date">Start Date:</label>
                    <input type="date" id="start-date" onchange="handleCustomChange()">
                    <br />
                    <label for="end-date">End Date:</label>
                    <input type="date" id="end-date" onchange="handleCustomChange()">
                </div>
                <button onclick="resetFilters()">Reset</button>
            </div>
        </div>

        <div class="data-container" id="daily-summary">
            <div class="data-card" id='card-1'>
                <h2 id='PQ'>Product Summary</h2>
                <?php
                // Establish database connection
                $cx = mysqli_connect("localhost", "root", "", "shopping");

                // Check if custom filter dates are set
                if (isset($_POST['start-date']) && isset($_POST['end-date'])) {
                    // Retrieve start and end dates from form inputs
                    $startDate = $_POST['start-date'];
                    $endDate = $_POST['end-date'];

                    // Construct SQL queries with custom date range
                    $totalQuantityAllProducts_Query = mysqli_query($cx, "SELECT SUM(order_details.quantity) AS TotalQtyAllProducts
                FROM order_details
                INNER JOIN orders ON order_details.order_id = orders.order_id
                WHERE DATE(orders.order_date) BETWEEN '$startDate' AND '$endDate'");

                    // Execute the query
                    $totalQuantityAllProducts_row = mysqli_fetch_assoc($totalQuantityAllProducts_Query);
                    $totalQuantityAllProducts = $totalQuantityAllProducts_row['TotalQtyAllProducts'];

                    // Query for best selling products within the custom date range
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
                    DATE(orders.order_date) BETWEEN '$startDate' AND '$endDate'
                GROUP BY
                    product.ProID
                ORDER BY
                    TotalQty DESC
            ");

                    // Loop through the results and display them in a table
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

                    // Query for total income within the custom date range
                    $income_Query = mysqli_query($cx, "SELECT SUM(product.PricePerUnit * order_details.quantity) AS TotalIncome
                FROM product 
                INNER JOIN order_details ON product.ProID = order_details.ProID
                INNER JOIN orders ON order_details.order_id = orders.order_id
                WHERE DATE(orders.order_date) BETWEEN '$startDate' AND '$endDate'");

                    // Fetch and display total income
                    $total_income_row = mysqli_fetch_assoc($income_Query);
                    $total_income = $total_income_row['TotalIncome'];
                    echo "<h2>Total Income: ฿" . number_format($total_income, 2) . "</h2>";
                } else {
                    // If custom filter dates are not set, display a message or handle accordingly
                    echo "<p>Please select a custom date range.</p>";
                }
                ?>
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
    <div class="data-container" id="custom-summary" style="display : none">
        <div class="data-card" id='card-1'>
            <h2 id='PQ'>Custom Summary</h2>
            <?php
            // Establish database connection
            $cx = mysqli_connect("localhost", "root", "", "shopping");

            // Check if custom filter dates are set
            if (isset($_POST['start-date']) && isset($_POST['end-date'])) {
                // Retrieve start and end dates from form inputs
                $startDate = $_POST['start-date'];
                $endDate = $_POST['end-date'];

                // Construct SQL queries with custom date range
                $totalQuantityAllProducts_Query = mysqli_query($cx, "SELECT SUM(order_details.quantity) AS TotalQtyAllProducts
                FROM order_details
                INNER JOIN orders ON order_details.order_id = orders.order_id
                WHERE DATE(orders.order_date) BETWEEN '$startDate' AND '$endDate'");

                // Execute the query
                $totalQuantityAllProducts_row = mysqli_fetch_assoc($totalQuantityAllProducts_Query);
                $totalQuantityAllProducts = $totalQuantityAllProducts_row['TotalQtyAllProducts'];

                // Query for best selling products within the custom date range
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
                    DATE(orders.order_date) BETWEEN '$startDate' AND '$endDate'
                GROUP BY
                    product.ProID
                ORDER BY
                    TotalQty DESC
            ");

                // Loop through the results and display them in a table
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

                // Query for total income within the custom date range
                $income_Query = mysqli_query($cx, "SELECT SUM(product.PricePerUnit * order_details.quantity) AS TotalIncome
                FROM product 
                INNER JOIN order_details ON product.ProID = order_details.ProID
                INNER JOIN orders ON order_details.order_id = orders.order_id
                WHERE DATE(orders.order_date) BETWEEN '$startDate' AND '$endDate'");

                // Fetch and display total income
                $total_income_row = mysqli_fetch_assoc($income_Query);
                $total_income = $total_income_row['TotalIncome'];
                echo "<h2>Total Income: ฿" . number_format($total_income, 2) . "</h2>";
            } else {
                // If custom filter dates are not set, display a message or handle accordingly
                echo "<p>Please select a custom date range.</p>";
            }
            ?>
        </div>
    </div>


    <script>
        var filterApplied = false; // Variable to track filter status
        var customFilterDetails = {}; // Object to store custom filter details

        // Function to handle filter type change event
        function handleFilterTypeChange() {
            var filterType = document.getElementById('filter-type').value;
            var filterOptions = document.getElementById('filter-options');
            var monthlyOptions = document.getElementById('monthly-options');
            var yearlyOptions = document.getElementById('yearly-options');
            var dailyOptions = document.getElementById('daily-options');

            // Filter daily, monthly, yearly summary data based on the selected filter type
            var summarySections = document.querySelectorAll('.data-container');
            summarySections.forEach(section => {
                if (section.id === filterType + '-summary') {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });

            // Change filter icon and set filter status
            document.getElementById('filter-icon-img').src = '../img/filter.png';
            filterApplied = true;

            if (filterType === 'no-filter') {
                filterOptions.style.display = 'none';
                resetFilters();
                logFilterDetails(); // Log filter details
            } else if (filterType === 'monthly') {
                handleMonthChange();
                filterOptions.style.display = 'block';
                monthlyOptions.style.display = 'block';
                yearlyOptions.style.display = 'none';
                dailyOptions.style.display = 'none';
                document.getElementById('custom-filter').style.display = 'none';
                logFilterDetails(); // Log filter details
            } else if (filterType === 'yearly') {
                handleYearlyChange();
                filterOptions.style.display = 'block';
                monthlyOptions.style.display = 'none';
                yearlyOptions.style.display = 'block';
                dailyOptions.style.display = 'none';
                document.getElementById('custom-filter').style.display = 'none';
                logFilterDetails(); // Log filter details
            } else if (filterType === 'daily') {
                handleDailyChange()
                filterOptions.style.display = 'block';
                monthlyOptions.style.display = 'none';
                yearlyOptions.style.display = 'none';
                dailyOptions.style.display = 'block';
                document.getElementById('custom-filter').style.display = 'none';
                logFilterDetails(); // Log filter details
            } else if (filterType === 'custom') {
                filterOptions.style.display = 'block';
                monthlyOptions.style.display = 'none';
                yearlyOptions.style.display = 'none';
                dailyOptions.style.display = 'none';
                // Show date format box beside filter icon if 'custom' filter applied
                document.getElementById('custom-filter').style.display = 'block';
                // logFilterDetails(); // Log filter details
            } else {
                // Hide custom filter options when other filters are applied
                document.getElementById('custom-filter').style.display = 'none';
                // logFilterDetails(); // Log filter details
            }
        }

        function logFilterDetails() {
            var filterType = document.getElementById('filter-type').value;

            if (filterType === 'custom') {
                var startDate = document.getElementById('start-date').value;
                var endDate = document.getElementById('end-date').value;
                customFilterDetails.startDate = startDate;
                customFilterDetails.endDate = endDate;
                console.log("Filtering by custom range. Start Date:", startDate, "End Date:", endDate);
                // applyFilter();
            } else {
                console.log("Filtering by:", filterType);
                // applyFilter();
            }
        }

        // Function to reset filters and filter icon
        function resetFilters() {
            // Reset filter icon
            document.getElementById('filter-icon-img').src = '../img/filtered.png';
            filterApplied = false;

            // Reset filter details
            var filterDetailsElement = document.getElementById('filter-details');

            // Reset filter options
            document.getElementById('filter-type').value = 'no-filter';
            document.getElementById('start-date').value = '';
            document.getElementById('end-date').value = '';

            // Reset summary sections
            var summarySections = document.querySelectorAll('.data-container');
            summarySections.forEach(section => {
                section.style.display = 'block';
            });

            // Hide custom filter options
            var customFilter = document.getElementById('custom-filter');
            customFilter.style.display = 'none';

            document.getElementById('monthly-options').style.display = 'none';
            document.getElementById('yearly-options').style.display = 'none';
            document.getElementById('daily-options').style.display = 'none';
        }

        // Add event listener to filter type dropdown to handle changes
        document.getElementById('filter-type').addEventListener('change', handleFilterTypeChange);

        // Function to handle month change event
        function handleMonthChange() {
            var selectedMonth = document.getElementById('filter-month').value;
            var selectedYear = document.getElementById('filter-month-year').value; // Assuming you have a year filter as well
            var startDate, endDate;

            if (selectedMonth == 0) {
                // If the selected month is 0, select all months
                startDate = selectedYear + '-01-01'; // Assuming the start of the year
                endDate = selectedYear + '-12-31'; // Assuming the end of the year
            } else {
                // Calculate start and end dates for the selected month
                startDate = selectedYear + '-' + selectedMonth + '-01';
                endDate = selectedYear + '-' + selectedMonth + '-31'; // Assuming 31 days in a month for simplicity, adjust as necessary
            }

            // Send an AJAX request to fetch data for the selected month(s)
            $.ajax({
                url: 'fetch_monthly_data.php', // Change the URL to your PHP script
                method: 'POST',
                data: {
                    startDate: startDate,
                    endDate: endDate
                },
                success: function(response) {
                    // Update the HTML with the fetched data
                    $('#monthly-summary').html(response);
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });
        }
        // Function to handle daily filter change
        function handleDailyChange() {
            var selectedDate = document.getElementById('filter-date').value;
            console.log(selectedDate)

            // Send an AJAX request to fetch data for the selected date
            $.ajax({
                url: 'fetch_daily_data.php', // Change the URL to your PHP script
                method: 'POST',
                data: {
                    date: selectedDate
                },
                success: function(response) {
                    // Update the HTML with the fetched data
                    $('#daily-summary').html(response);
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });
        }

        // Function to handle yearly filter change
        function handleYearlyChange() {
            var selectedYear = document.getElementById('filter-year').value;


            // Send an AJAX request to fetch data for the selected year
            $.ajax({
                url: 'fetch_yearly_data.php', // Change the URL to your PHP script
                method: 'POST',
                data: {
                    year: selectedYear
                },
                success: function(response) {
                    // Update the HTML with the fetched data
                    $('#yearly-summary').html(response);
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });
        }

        function handleCustomChange() {
            var startDate = document.getElementById('start-date').value;
            var endDate = document.getElementById('end-date').value;
            logFilterDetails();

            // Send an AJAX request to fetch data for the selected date range
            $.ajax({
                url: 'fetch_custom_data.php', // Change the URL to your PHP script
                method: 'POST',
                data: {
                    startDate: startDate,
                    endDate: endDate
                },
                success: function(response) {
                    // Update the HTML with the fetched data
                    $('#custom-summary').html(response);
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });
        }


        // Function to handle no-filter option
        // Function to handle no-filter option
        function handleNoFilter() {
            // Reset filter icon
            document.getElementById('filter-icon-img').src = '../img/filtered.png';

            // Reset filter options
            document.getElementById('filter-type').value = 'no-filter';
            document.getElementById('start-date').value = '';
            document.getElementById('end-date').value = '';
            document.getElementById('filter-month').value = 0;
            document.getElementById('filter-year').value = new Date().getFullYear(); // Set current year

            // Hide custom filter options
            document.getElementById('monthly-options').style.display = 'none';
            document.getElementById('yearly-options').style.display = 'none';
            document.getElementById('daily-options').style.display = 'none';
            document.getElementById('custom-filter').style.display = 'none';

            // Send an AJAX request to fetch data without any filter
            $.ajax({
                url: 'fetch_all_data.php', // Change the URL to your PHP script
                method: 'POST',
                success: function(response) {
                    // Update the HTML with the fetched data
                    $('#daily-summary').html(response);
                    $('#monthly-summary').html(response);
                    $('#yearly-summary').html(response);
                    $('#custom-summary').html(response);
                },
                error: function(xhr, status, error) {
                    // Handle errors
                    console.error(xhr.responseText);
                }
            });
        }
    </script>


</body>

</html>