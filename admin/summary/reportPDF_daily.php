<?php
// Include TCPDF library and database configuration file
require_once('../../dbConfig.php');
require_once('../../Customer/vendor/autoload.php');

// Create new TCPDF instance
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Summary Report');
$pdf->SetHeaderData('', '', 'Summary Report', '');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set Sarabun font
$pdf->SetFont('sarabun', '', 11);

$pdf->SetDefaultMonospacedFont('helvetica');
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();  

if (isset($startDate) && isset($endDate)) {
    // Extract month and year from start date
    $startMonth = date('F', strtotime($startDate));
    $startYear = date('Y', strtotime($startDate));

    // Extract month and year from end date
    $endMonth = date('F', strtotime($endDate));
    $endYear = date('Y', strtotime($endDate));
}

// Retrieve data from database and generate HTML table for daily summary
$html = '
<h2>Product Summary - Custom Range (' . $startMonth . ' ' . $startYear . ' to ' . $endMonth . ' ' . $endYear . ')</h2>
<div class="data-container" id="daily-summary">
    <div class="data-card" id="card-1">
        <h2 id="PQ">Product Summary - Daily</h2>
        <table style="border-collapse: collapse;">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Price Per Unit</th>
                <th>Total Unit Sold</th>
                <th>Total Price</th>
                <th>Percentage Sold</th>
            </tr>';

// Calculate the total quantity sold across all products for the custom range
$totalQuantityDaily_Query = mysqli_query($conn, "SELECT SUM(order_details.quantity) AS TotalQtyDaily
    FROM order_details
    INNER JOIN orders ON order_details.order_id = orders.order_id
    WHERE DATE(orders.order_date) BETWEEN '$startDate' AND '$endDate'");
$totalQuantityDaily_row = mysqli_fetch_assoc($totalQuantityDaily_Query);
$totalQuantityDaily = $totalQuantityDaily_row['TotalQtyDaily'];


$dailySell_Query = mysqli_query($conn, "
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

while ($row = mysqli_fetch_assoc($dailySell_Query)) {
    $totalSum = $row['PricePerUnit'] * $row['TotalQty'];
    $percentageSold = ($row['TotalQty'] / $totalQuantityDaily) * 100;

    $html .= "<tr>";
    $html .= "<td>" . $row['ProID'] . "</td>";
    $html .= "<td>" . $row['ProName'] . "</td>";
    $html .= "<td>" . $row['PricePerUnit'] . "</td>";
    $html .= "<td>" . $row['TotalQty'] . "</td>";
    $html .= "<td>" . $totalSum . "</td>";
    $html .= "<td>" . number_format($percentageSold, 2) . "%</td>";
    $html .= "</tr>";
}


$html .= '</table>';

// Query for total daily income
$dailyIncome_Query = mysqli_query($conn, "
    SELECT SUM(product.PricePerUnit * order_details.quantity) AS TotalDailyIncome
    FROM product 
    INNER JOIN order_details ON product.ProID = order_details.ProID
    INNER JOIN orders ON order_details.order_id = orders.order_id
    WHERE DATE(orders.order_date) BETWEEN '$startDate' AND '$endDate'
");
$total_daily_income_row = mysqli_fetch_assoc($dailyIncome_Query);
$total_daily_income = $total_daily_income_row['TotalDailyIncome'];

$html .= "<h2>Total Income: " . number_format($total_daily_income, 2) . "</h2>
    </div>
</div>";

// Write HTML content for daily summary to PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
$pdf->Output('summary_report.pdf', 'I');
?>
