<?php
//DB QUERY
include_once '../../../dbConfig.php';
session_start();
$ProductQuery = mysqli_query($conn, "SELECT COUNT(*) AS total_products FROM product");
$ProductDetails = mysqli_fetch_assoc($ProductQuery);

setlocale(LC_TIME, 'th_TH.UTF-8');

$statData = "increase"; // Assume as increase
$statsArrow = ($statData == "increase") ? "increase.png" : "decrease.png";

// Initialize variables
$StartDate = isset($_SESSION['startDate']) ? $_SESSION['startDate'] : date("Y-m-d", strtotime("-1 week")); // Example: One week ago if not provided
$EndDate = isset($_SESSION['endDate']) ?$_SESSION['endDate'] : date("Y-m-d"); // Today's date if not provided

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

// Query to count the number of fulfilled orders within the specified date range
$fulfilledQuery = mysqli_query($conn, "SELECT COUNT(order_id) AS fulfilled_order_count FROM orders 
          WHERE order_date BETWEEN '$StartDate' AND '$EndDate'
          AND fullfill_status = 'Fulfilled'");

// Check if the query was successful
if ($fulfilledQuery) {
  // Fetch the result row
  $fulfilledRow = mysqli_fetch_assoc($fulfilledQuery);
  // Assign the fulfilled order count to the $purchaseTotal variable
  $purchaseTotal = $fulfilledRow['fulfilled_order_count'];
} else {
  // Handle the case where the query failed
  echo "Error: " . mysqli_error($conn);
}

// Query to count the number of unfulfilled orders within the specified date range
$unfulfilledQuery = mysqli_query($conn, "SELECT COUNT(order_id) AS unfulfilled_order_count FROM orders 
          WHERE order_date BETWEEN '$StartDate' AND '$EndDate'
          AND fullfill_status != 'Fulfilled'");

// Check if the query was successful
if ($unfulfilledQuery) {
  // Fetch the result row
  $unfulfilledRow = mysqli_fetch_assoc($unfulfilledQuery);
  // Assign the unfulfilled order count to the $no_purchaseTotal variable
  $no_purchaseTotal = $unfulfilledRow['unfulfilled_order_count'];
} else {
  // Handle the case where the query failed
  echo "Error: " . mysqli_error($conn);
}

// Modify the query to get counts by date within the specified date range for only fulfilled orders
$dateOrdersQuery = mysqli_query($conn, "SELECT DATE(order_date) AS formatted_date, 
                                            COUNT(order_id) AS fulfilled_orders_count
                                        FROM Orders 
                                        WHERE order_date BETWEEN '$StartDate' AND '$EndDate'
                                        AND fullfill_status = 'Fulfilled'
                                        GROUP BY formatted_date");

// Check if the query was successful
if ($dateOrdersQuery) {
  // Initialize arrays to store data points for bar and line charts
  $dataPoints = array();
  $LineDataPoints = array();

  // Loop through the results and add data points for the bar chart
  while ($row = mysqli_fetch_assoc($dateOrdersQuery)) {
    // Format the data point for the bar chart
    $dataPoint = array(
      "label" => $row['formatted_date'], // Using the formatted date
      "y1" => (int)$row['fulfilled_orders_count'], // Count of fulfilled orders
      "y2" => $no_purchaseTotal // Count of unfulfilled orders
    );
    // Add the data point to the array for the bar chart
    array_push($dataPoints, $dataPoint);

    // Format the data point for the line chart
    $lineDataPoint = array(
      "label" => $row['formatted_date'], // Using the formatted date
      "y" => $row['fulfilled_orders_count'] // Count of fulfilled orders
    );
    // Add the data point to the array for the line chart
    array_push($LineDataPoints, $lineDataPoint);
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
          text: "Overall Sales Performance"
        },
        axisY: {
          title: "Number of Sales"
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
  <center>
    <div class="globalContainer">
      <section class="Header">
        <h2>Sales Summary Report</h2>
        <h3><?php echo $_SESSION['startDate']; ?> - <?php echo $_SESSION['endDate']; ?> </h3>
        <button id="convertToPDF" style="display:block;">Convert to PDF</button>
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
    </div>
  </center>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
  document.getElementById('convertToPDF').addEventListener('click', function() {
    // Hide the button first
    document.getElementById("convertToPDF").style.display = "none";

    // Capture the content of globalContainer and convert it to PDF
    const element = document.querySelector('.globalContainer');
    html2canvas(element).then(function(canvas) {
      // Convert canvas to image data
      const imageData = canvas.toDataURL('image/png');

      // Create a new PDF document
      var doc = new jspdf.jsPDF();

      // Add the image data to the PDF document
      doc.addImage(imageData, 'PNG', 0, 0, doc.internal.pageSize.getWidth(), doc.internal.pageSize.getHeight());

      // Save the PDF document
      doc.save('Summary_Report.pdf');

      document.getElementById("convertToPDF").style.display = "block";
    });
  });
</script>

</html>