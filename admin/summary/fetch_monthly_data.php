<?php
// Include database configuration file
include_once '../../dbConfig.php';

// Retrieve the start and end dates from the AJAX request
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

// Extract month and year from start date
$startMonth = date('F', strtotime($startDate));
$startYear = date('Y', strtotime($startDate));

// Extract month and year from end date
$endMonth = date('F', strtotime($endDate));
$endYear = date('Y', strtotime($endDate));

// Calculate the number of days in the selected month
$endDay = date('t', strtotime($endDate));

// Construct the SQL query with the provided start and end dates
$query = "SELECT
            product.ProID,
            product.ProName,
            product.Description,
            product.PricePerUnit,
            SUM(order_details.quantity) AS TotalQty,
            SUM(product.PricePerUnit * order_details.quantity) AS TotalPrice
        FROM
            product
            INNER JOIN order_details ON product.ProID = order_details.ProID
            INNER JOIN orders ON order_details.order_id = orders.order_id
        WHERE
            DATE(orders.order_date) BETWEEN '$startDate' AND '$endDate'
        GROUP BY
            product.ProID
        ORDER BY
            TotalQty DESC";

// Execute the query and fetch data
$result = mysqli_query($conn, $query);

// Additional query to calculate total quantity sold across all products for the selected month(s)
$totalQuantityQuery = "SELECT SUM(order_details.quantity) AS TotalQtyMonthly
                        FROM order_details
                        INNER JOIN orders ON order_details.order_id = orders.order_id
                        WHERE DATE(orders.order_date) BETWEEN '$startDate' AND '$endDate'";
$totalQuantityResult = mysqli_query($conn, $totalQuantityQuery);
$totalQuantityRow = mysqli_fetch_assoc($totalQuantityResult);
$totalQuantityMonthly = $totalQuantityRow['TotalQtyMonthly'];

// Process the fetched data and generate HTML for display
if ($result) {
    // Start building the HTML data card
    $output = "
                    <div class='data-card' id='card-2'>
                        <h2 id='PQ'>Product Summary - Monthly ($startMonth $startYear - $endMonth $endYear)</h2>
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price Per Unit</th>
                                <th>Total Unit Sold</th>
                                <th>Total Price</th>
                                <th>PercentageSold</th>
                            </tr>";

    // Loop through the query results and append rows to the table
    while ($row = mysqli_fetch_assoc($result)) {
        $totalPrice = $row['TotalPrice'];
        $percentageSold = ($row['TotalQty'] / $totalQuantityMonthly) * 100;

        $output .= "<tr>";
        $output .= "<td>" . $row['ProID'] . "</td>";
        $output .= "<td>" . $row['ProName'] . "</td>";
        $output .= "<td>" . $row['Description'] . "</td>";
        $output .= "<td>" . $row['PricePerUnit'] . "</td>";
        $output .= "<td>" . $row['TotalQty'] . "</td>";
        $output .= "<td>" . $totalPrice . "</td>";
        $output .= "<td>" . number_format($percentageSold, 2) . "%</td>";
        $output .= "</tr>";
    }

    // Close the table and data card
    $output .= "</table></div>";

    // Output the generated HTML
    echo $output;
} else {
    // Handle query errors
    echo "Error executing query: " . mysqli_error($cx);
}
