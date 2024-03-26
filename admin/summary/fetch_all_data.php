<?php
include_once '../../dbConfig.php'; 

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
            GROUP BY
                product.ProID
            ORDER BY
                TotalQty DESC";

$result = mysqli_query($conn, $query);

if ($result) {
    // Process and output the data
} else {
    echo "Error executing query: " . mysqli_error($cx);
}
?>
