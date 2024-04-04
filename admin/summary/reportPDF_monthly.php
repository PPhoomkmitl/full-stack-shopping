<?php
require_once('../../dbConfig.php');
require_once('../../Customer/vendor/autoload.php');

// Create new TCPDF instance
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Summary Report');
$pdf->SetHeaderData('', '', 'Summary Report', '');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetFont('sarabun', '', 11);
$pdf->SetDefaultMonospacedFont('helvetica');
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->AddPage();

// Retrieve data from database and generate HTML table for monthly summary
$currentMonth = date('m');
$currentYear = date('Y');
$html = '
<h1>Monthly Summary Report - ' . date('F Y') . '</h1>
<div class="data-container" id="monthly-summary">
    <div class="data-card" id="card-1">
        <h2 id="PQ">Product Summary - Monthly</h2>
        <table style="border-collapse: collapse; width: 100%;">
            <tr>
                <th style="border: 1px solid #000;">ID</th>
                <th style="border: 1px solid #000;">Name</th>
                <th style="border: 1px solid #000;">Price Per Unit</th>
                <th style="border: 1px solid #000;">Total Unit Sold</th>
                <th style="border: 1px solid #000;">Total Price</th>
                <th style="border: 1px solid #000;">Percentage Sold</th>
            </tr>';

// Calculate the total quantity sold across all products for the month
$totalQuantityMonthly_Query = mysqli_query($conn, "
    SELECT SUM(order_details.quantity) AS TotalQtyMonthly
    FROM order_details
    INNER JOIN orders ON order_details.order_id = orders.order_id
    WHERE MONTH(orders.order_date) = $currentMonth AND YEAR(orders.order_date) = $currentYear
");
$totalQuantityMonthly_row = mysqli_fetch_assoc($totalQuantityMonthly_Query);
$totalQuantityMonthly = $totalQuantityMonthly_row['TotalQtyMonthly'];

$monthlySell_Query = mysqli_query($conn, "
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
        MONTH(orders.order_date) = $currentMonth AND YEAR(orders.order_date) = $currentYear
    GROUP BY
        product.ProID
    ORDER BY
        TotalQty DESC
");

while ($row = mysqli_fetch_assoc($monthlySell_Query)) {
    $totalSum = $row['PricePerUnit'] * $row['TotalQty'];
    $percentageSold = ($row['TotalQty'] / $totalQuantityMonthly) * 100;

    $html .= "<tr>";
    $html .= "<td style='border: 1px solid #000;'>" . $row['ProID'] . "</td>";
    $html .= "<td style='border: 1px solid #000;'>" . $row['ProName'] . "</td>";
    $html .= "<td style='border: 1px solid #000;'>" . $row['PricePerUnit'] . "</td>";
    $html .= "<td style='border: 1px solid #000;'>" . $row['TotalQty'] . "</td>";
    $html .= "<td style='border: 1px solid #000;'>" . $totalSum . "</td>";
    $html .= "<td style='border: 1px solid #000;'>" . number_format($percentageSold, 2) . "%</td>";
    $html .= "</tr>";
}

$html .= '</table>';
// Retrieve data for category-wise sales
$categorySell_Query = mysqli_query($conn, "
    SELECT
        product_type.id,
        product_type.name,
        SUM(order_details.quantity) AS TotalQty,
        SUM(order_details.quantity * product.PricePerUnit) AS TotalPrice,
        (SUM(order_details.quantity) / (SELECT SUM(order_details.quantity) FROM order_details INNER JOIN product ON order_details.ProID = product.ProID WHERE MONTH(orders.order_date) = $currentMonth AND YEAR(orders.order_date) = $currentYear) * 100) AS PercentageSold
    FROM
        product
        INNER JOIN order_details ON product.ProID = order_details.ProID
        INNER JOIN product_type ON product.product_type_id = product_type.id
        INNER JOIN orders ON order_details.order_id = orders.order_id
    WHERE
        MONTH(orders.order_date) = $currentMonth AND YEAR(orders.order_date) = $currentYear
    GROUP BY
        product_type.id
    ORDER BY
        TotalQty DESC
");

// Generate HTML table for category-wise sales
$html .= '
<h2>Category-wise Sales</h2>
<table style="border-collapse: collapse; width: 100%;">
    <tr>
        <th style="border: 1px solid #000;">Category ID</th>
        <th style="border: 1px solid #000;">Category Name</th>
        <th style="border: 1px solid #000;">Total Unit Sold</th>
        <th style="border: 1px solid #000;">Total Price</th>
        <th style="border: 1px solid #000;">Percentage Sold</th>
    </tr>';

while ($row = mysqli_fetch_assoc($categorySell_Query)) {
    $html .= "<tr>";
    $html .= "<td style='border: 1px solid #000;'>" . $row['id'] . "</td>";
    $html .= "<td style='border: 1px solid #000;'>" . $row['name'] . "</td>";
    $html .= "<td style='border: 1px solid #000;'>" . $row['TotalQty'] . "</td>";
    $html .= "<td style='border: 1px solid #000;'>" . $row['TotalPrice'] . "</td>";
    $html .= "<td style='border: 1px solid #000;'>" . number_format($row['PercentageSold'], 2) . "%</td>";
    $html .= "</tr>";
}
$html .= '</table>';
// Query for total monthly income
$monthlyIncome_Query = mysqli_query($conn, "
    SELECT SUM(product.PricePerUnit * order_details.quantity) AS TotalMonthlyIncome
    FROM product 
    INNER JOIN order_details ON product.ProID = order_details.ProID
    INNER JOIN orders ON order_details.order_id = orders.order_id
    WHERE MONTH(orders.order_date) = $currentMonth AND YEAR(orders.order_date) = $currentYear
");
$total_monthly_income_row = mysqli_fetch_assoc($monthlyIncome_Query);
$total_monthly_income = $total_monthly_income_row['TotalMonthlyIncome'];

$html .= "<h2>Total Income: " . number_format($total_monthly_income, 2) . "</h2>";

// Write HTML content for monthly summary to PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Output PDF
$pdf->Output('summary_report.pdf', 'I');
?>
