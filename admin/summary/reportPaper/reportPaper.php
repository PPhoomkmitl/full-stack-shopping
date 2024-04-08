<?php
//DB QUERY
include_once '../../../dbConfig.php';
$ProductQuery = mysqli_query($conn, "SELECT COUNT(*) AS total_products FROM product");
$ProductDetails = mysqli_fetch_assoc($ProductQuery);

setlocale(LC_TIME, 'th_TH.UTF-8');

// Initialize variables
$StartDate = isset($_POST['StartDate']) ? $_POST['StartDate'] : date("Y-m-d", strtotime("-1 week")); // Example: One week ago if not provided
$EndDate = isset($_POST['EndDate']) ? $_POST['EndDate'] : date("Y-m-d"); // Today's date if not provided

// SQL query to count the number of orders within the specified date range
$query = "SELECT COUNT(order_id) AS order_count FROM orders 
          WHERE order_date BETWEEN '$StartDate' AND '$EndDate'";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if the query was successful
if ($result) {
  // Fetch the result row
  $row = mysqli_fetch_assoc($result);
  // Assign the order count to the $eventCount variable
  $eventCount = $row['order_count'];
} else {
  // Handle the case where the query failed
  echo "Error: " . mysqli_error($conn);
}

// SQL query to count the total of successful orders within the specified date range
$successfulQuery = "SELECT COUNT(order_id) AS successful_count FROM orders 
                    WHERE order_date BETWEEN '$StartDate' AND '$EndDate' AND fullfill_status = 'Fulfilled'";

// Execute the query for successful orders
$successfulResult = mysqli_query($conn, $successfulQuery);

// Check if the query was successful
if ($successfulResult) {
  // Fetch the result row
  $row = mysqli_fetch_assoc($successfulResult);
  // Assign the successful order count to the $purchaseTotal variable
  $purchaseTotal = $row['successful_count'];
} else {
  // Handle the case where the query failed
  echo "Error: " . mysqli_error($conn);
}

// SQL query to count the total number of orders within the specified date range
$totalQuery = "SELECT COUNT(order_id) AS total_count FROM orders 
               WHERE order_date BETWEEN '$StartDate' AND '$EndDate'";

// Execute the query for total orders
$totalResult = mysqli_query($conn, $totalQuery);

// Check if the query was successful
if ($totalResult) {
  // Fetch the result row
  $row = mysqli_fetch_assoc($totalResult);
  // Assign the total order count to the $no_purchaseTotal variable
  $no_purchaseTotal = $row['total_count']; // Subtract successful orders from total orders
} else {
  // Handle the case where the query failed
  echo "Error: " . mysqli_error($conn);
}


$statData = "increase"; // Assume as increase
$statsArrow = ($statData == "increase") ? "increase.png" : "decrease.png";

//BAR CHARTS SET Dynamic
$dataPoints = array();

// Modify the query to get counts by date within the specified date range
$dateOrdersQuery = mysqli_query($conn, "SELECT DATE(order_date) AS formatted_date, 
                                            COUNT(order_id) AS all_orders_count, 
                                            SUM(CASE WHEN fullfill_status = 'fulfilled' THEN 1 ELSE 0 END) AS fulfilled_orders_count
                                        FROM Orders 
                                        WHERE order_date BETWEEN '$StartDate' AND '$EndDate'
                                        GROUP BY formatted_date");

// Check if the query was successful
if ($dateOrdersQuery) {
  // Loop through the results and add data points
  while ($row = mysqli_fetch_assoc($dateOrdersQuery)) {
    // Format the data point
    $dataPoint = array(
      "label" => $row['formatted_date'], // Using the formatted date
      "y1" => (int)$row['all_orders_count'],
      "y2" => (int)$row['fulfilled_orders_count']
    );
    // Add the data point to the array
    array_push($dataPoints, $dataPoint);
  }
} else {
  // Handle the case where the query failed
  echo "Error: " . mysqli_error($conn);
}

//LINE CHARTS SET Dynamic
$LineDataPoints = array();

// Modify the query to get data points for the line chart
$lineOrdersQuery = mysqli_query($conn, "SELECT DATE(order_date) AS formatted_date,
                                            COUNT(order_id) AS orders_count
                                        FROM Orders 
                                        WHERE order_date BETWEEN '$StartDate' AND '$EndDate'
                                        GROUP BY formatted_date");

// Check if the query was successful
if ($lineOrdersQuery) {
    // Loop through the results and add data points
    while ($row = mysqli_fetch_assoc($lineOrdersQuery)) {
        // Format the data point
        $dataPoint = array(
            "label" => $row['formatted_date'], // Using the formatted date
            "y" => (int)$row['orders_count']
        );
        // Add the data point to the array
        array_push($LineDataPoints, $dataPoint);
    }
} else {
    // Handle the case where the query failed
    echo "Error: " . mysqli_error($conn);
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./reportPaper.css">
  <title>ReportPaper</title>

  <script>
    window.onload = function() {
      // BAR CHART
      var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        title: {
          text: "Event (ครั้ง)"
        },
        axisY: {
          title: "Value-Ticks (ครั้ง)"
        },
        toolTip: {
          shared: true
        },
        data: [{
          type: "column",
          name: "กิจกรรม",
          showInLegend: true,
          yValueFormatString: "#,##0.## ครั้ง",
          dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>.map(function(point) {
            return {
              label: point.label,
              y: point.y1
            };
          })
        }, {
          type: "column",
          name: "Sales Order",
          showInLegend: true,
          yValueFormatString: "#,##0.## ครั้ง",
          dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>.map(function(point) {
            return {
              label: point.label,
              y: point.y2
            };
          })
        }]
      });
      chart.render();

      // LINE CHART
      var chart2 = new CanvasJS.Chart("LinechartContainer", {
        title: {
          text: "Push-ups Over a Week"
        },
        axisY: {
          title: "Number of Push-ups"
        },
        data: [{
          type: "line",
          dataPoints: <?php echo json_encode($LineDataPoints, JSON_NUMERIC_CHECK); ?>
        }]
      });
      chart2.render();
    }
  </script>
  <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
  <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</head>

<body>
  <section class="Header">
    <h2>Sales Summary Report</h2>
    <h3><?php echo $StartDate; ?> - <?php echo $EndDate; ?> </h3>
  </section>

  <section class="Topic">
    <section class="Standard">
      <aside class="left">
        <h3 class="left-gap">กิจกรรม (ครั้ง)</h3>
        <h3 class="left-gap"><?php echo $eventCount; ?><img src=../../img/<?php echo $statsArrow; ?>></h3>
      </aside>
      <aside class="right">
        <h3 class="right-gap">ยอดขาย/WON SO (บาท)</h3>
        <h3 class="right-gap"><?php echo $purchaseTotal; ?><img src=../../img/<?php echo $statsArrow; ?>></h3>
      </aside>
    </section>

    <section class="Standard">
      <aside class="left">
        <h3 class="left-gap">Sales Order (ครั้ง)</h3>
        <h3 class="left-gap"><?php echo $no_purchaseTotal ?><img src=../../img/<?php echo $statsArrow; ?>></h3>
      </aside>
      <aside class="right">
        <h3 class="right-gap">ยอดขาย/PAID SO (บาท)</h3>
        <h3 class="right-gap"><?php echo $purchaseTotal ?><img src=../../img/<?php echo $statsArrow; ?>></h3>
      </aside>
    </section>
  </section>

  <section class="Graph">
    <div id="chartContainer"></div>
    <div id="LinechartContainer"></div>
  </section>
  <main class="Top10Container">
    <section class="Top10Head">
      <img src="../../img/top-10.png">
      <h3>10 อันดับ ลูกค้าที่มีกิจกรรมสูงสุด</h3>
    </section>
    <section class="Top10Head">
      <img src="../../img/top-10.png">
      <h3>10 อันดับ สินค้าที่มียอดขายสูงสุด</h3>
    </section>
  </main>
  <main class="Top10Container">
    <section class="Top10Body">
      <table class="leftTable">
        <tr>
          <th class="indexTable">ลำดับ</th>
          <th class="nameTable">ลูกค้า</th>
          <th class="priceTable">Interaction</th>
        </tr>
        <?php
        // Execute the SQL query to get the frequency of orders from each customer within the specified date range
        $ProductQuery = mysqli_query($conn, "SELECT
                CONCAT(c.CusFName, ' ', c.CusLName) AS customer_name,
                COUNT(o.order_id) AS orders_count
                FROM
                Customer c
                LEFT JOIN
                Orders o ON c.CusID = o.CusID
                WHERE
                o.order_date BETWEEN '$StartDate' AND '$EndDate'
                GROUP BY
                c.CusID, c.CusFName, c.CusLName
                ORDER BY
                orders_count DESC
                LIMIT 10
                ");

        // Check if the query was successful
        if ($ProductQuery) {
          $counter = 1; // Initialize the counter variable

          // Loop through the results and display them in a table
          while ($row = mysqli_fetch_assoc($ProductQuery)) {
            echo "<tr>";
            echo "<td>" . $counter . "</td>"; // Output the custom index
            echo "<td>" . $row['customer_name'] . "</td>"; // Output the customer's name
            echo "<td>" . $row['orders_count'] . "</td>"; // Output the count of orders
            echo "</tr>";
            $counter++; // Increment the counter for the next row
          }
        } else {
          // Handle the case where the query failed
          echo "Error: " . mysqli_error($conn);
        }
        ?>
      </table>
    </section>

    <section class="Top10Body">
      <table class="rightTable">
        <tr>
          <th class="indexTable">ลำดับ</th>
          <th class="nameTable">ชื่อสินค้า</th>
          <th class="priceTable">ยอดขาย</th>
        </tr>
        <tr>
          <?php
          // Execute the SQL query to get the top 10 products by sales within the specified date range
          $ProductQuery = mysqli_query($conn, "SELECT * FROM product ORDER BY (PricePerUnit * StockQty) DESC LIMIT 10");
          $index = 1; // Initialize the index variable

          // Loop through the results and display them in a table
          while ($row = mysqli_fetch_assoc($ProductQuery)) {
            $total = (float)$row['PricePerUnit'] * (float)$row['StockQty'];
            echo "<tr>";
            echo "<td>" . $index . "</td>"; // Output the index
            echo "<td>" . $row['ProName'] . "</td>";
            echo "<td>" . $total . "</td>";
            echo "</tr>";
            $index++; // Increment the index for the next row
          }
          ?>

        </tr>
      </table>
    </section>
  </main>
</body>

</html>