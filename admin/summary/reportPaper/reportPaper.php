<?php
setlocale(LC_TIME, 'th_TH.UTF-8');
$date = strftime("%e %B %Y");
$eventCount = 383; // Static Data

$statData = "decrease"; // Assume as increase
$statsArrow = ($statData == "increase") ? "increase.png" : "decrease.png";


//BAR CHARTS SET Dynamic ได้เลย
$dataPoints = array(
  array("label" => "May 2023", "y1" => 124, "y2" => 228),
  array("label" => "June 2023", "y1" => 150, "y2" => 200),
  array("label" => "July 2023", "y1" => 180, "y2" => 250),
  // Add more data points as needed
);

//LINE CHARTS SET Dynamic ได้เลย
$LineDataPoints = array(
  array("y" => 25, "label" => "Sunday"),
  array("y" => 15, "label" => "Monday"),
  array("y" => 25, "label" => "Tuesday"),
  array("y" => 5, "label" => "Wednesday"),
  array("y" => 10, "label" => "Thursday"),
  array("y" => 0, "label" => "Friday"),
  array("y" => 20, "label" => "Saturday")
);

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
    <h3><?php echo $date; ?> - <?php echo $date; ?> </h3>
  </section>

  <section class="Topic">
    <section class="Standard">
      <aside class="left">
        <h3 class="left-gap">กิจกรรม (ครั้ง)</h3>
        <h3 class="left-gap"><?php echo $eventCount; ?><img src=../../img/<?php echo $statsArrow; ?>></h3>
      </aside>
      <aside class="right">
        <h3 class="right-gap">ยอดขาย/WON SO (บาท)</h3>
        <h3 class="right-gap"><?php echo $eventCount; ?><img src=../../img/<?php echo $statsArrow; ?>></h3>
      </aside>
    </section>

    <section class="Standard">
      <aside class="left">
        <h3 class="left-gap">Sales Order (ครั้ง)</h3>
        <h3 class="left-gap"><?php echo $eventCount ?><img src=../../img/<?php echo $statsArrow; ?>></h3>
      </aside>
      <aside class="right">
        <h3 class="right-gap">ยอดขาย/PAID SO (บาท)</h3>
        <h3 class="right-gap"><?php echo $eventCount ?><img src=../../img/<?php echo $statsArrow; ?>></h3>
      </aside>
    </section>
  </section>

  <section class="Graph">
    <div id="chartContainer"></div>
    <div id="LinechartContainer"></div>
  </section>

  <section class="Top10Head">
    <img src="../../img/top-10.png">
    <h3>20 อันดับ ลูกค้าที่มียอดสั่งซื้อสูงสุด</h3>
  </section>

  <section class="Top10Body">
    <table class="leftTable">
      <tr>
        <th class="indexTable">ลำดับ</th>
        <th class="nameTable">ลูกค้า</th>
        <th class="priceTable">ยอดขาย</th>
      </tr>
      <tr>
        <td>1</td>
        <td>Maria Anders</td>
        <td>94859343.284</td>
      </tr>
      <tr>
        <td>2</td>
        <td>Francisco Chang</td>
        <td>12495812945.12</td>
      </tr>
    </table>

    <table class="rightTable">
      <tr>
        <th class="indexTable">ลำดับ</th>
        <th class="nameTable">ลูกค้า</th>
        <th class="priceTable">ยอดขาย</th>
      </tr>
      <tr>
        <td>1</td>
        <td>Maria Anders</td>
        <td>2938392.13</td>
      </tr>
      <tr>
        <td>2</td>
        <td>Francisco Chang</td>
        <td>23492435.53</td>
      </tr>
    </table>
  </section>
  



</body>

</html>