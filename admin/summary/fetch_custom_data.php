<?php
// Include database configuration file
include_once '../../dbConfig.php';

// Retrieve the selected start and end dates from the AJAX request
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

// Check if both start date and end date are set
if (isset($startDate) && isset($endDate)) {
    echo "<script>";
    echo "console.log('Start Date: " . $startDate . "');";
    echo "console.log('End Date: " . $endDate . "');";
    echo "</script>";

    // Extract month and year from start date
    $startMonth = date('F', strtotime($startDate));
    $startYear = date('Y', strtotime($startDate));

    // Extract month and year from end date
    $endMonth = date('F', strtotime($endDate));
    $endYear = date('Y', strtotime($endDate));

    // Construct the SQL query with the provided date range
    $query = "SELECT
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
                TotalQty DESC";

    // Execute the query and fetch data
    $result = mysqli_query($conn, $query);

    // Process the fetched data and generate HTML for display
    if ($result) {
        // Start building the HTML data card
        $output = "
                        <div class='data-card' id='card-4'>
                            <h2 id='PQ'>Product Summary - Custom Range ($startMonth $startYear to $endMonth $endYear)</h2>
                            <table>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price Per Unit</th>
                                    <th>Total Unit Sold</th>
                                </tr>";

        // Loop through the query results and append rows to the table
        while ($row = mysqli_fetch_assoc($result)) {
            $output .= "<tr>";
            $output .= "<td>" . $row['ProID'] . "</td>";
            $output .= "<td>" . $row['ProName'] . "</td>";
            $output .= "<td>" . $row['Description'] . "</td>";
            $output .= "<td>" . $row['PricePerUnit'] . "</td>";
            $output .= "<td>" . $row['TotalQty'] . "</td>";
            $output .= "</tr>";
        }

        // Close the table and data card
        $output .= "</table></div>";

        // Output the generated HTML
        echo $output;
    } else {
        // Handle query errors
        echo "<script>";
        echo "console.log('SUCCESS');";
        echo "Error executing query: " . mysqli_error($cx);
        echo "</script>";
    }
} else {
    // If start date or end date is not set, do nothing or handle accordingly
    echo "<script>";
    echo "console.log('No filter applied.');";
    echo "</script>";
}
?>
