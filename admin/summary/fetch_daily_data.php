<?php
// Include database configuration file
include_once '../../dbConfig.php';

// Retrieve the selected date from the AJAX request
$date = $_POST['date'];

// Construct the SQL query with the provided date
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
            DATE(orders.order_date) = '$date'
        GROUP BY
            product.ProID
        ORDER BY
            TotalQty DESC";

// Execute the query and fetch data
$result = mysqli_query($conn, $query);

// Process the fetched data and generate HTML for display
if ($result) {
    // Check if any rows were returned
    if (mysqli_num_rows($result) > 0) {
        // Start building the HTML data card
        $output = "<div class='data-container' id='daily-summary'>
                        <div class='data-card' id='card-1'>
                            <h2 id='PQ'>Product Summary - Daily</h2>
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
        $output .= "</table></div></div>";

        // Output the generated HTML
        echo $output;
    } else {
        // If no data is found, display a message
        echo "<div class='data-container' id='daily-summary'>
                    <div class='data-card' id='card-1'>
                        <h2 id='PQ'>Product Summary - Daily</h2>
                        <p>No data available for the selected date.</p>
                    </div>
                </div>";
    }
} else {
    // Handle query errors
    echo "Error executing query: " . mysqli_error($cx);
}
?>
