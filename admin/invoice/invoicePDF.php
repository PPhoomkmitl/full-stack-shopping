<?php
require_once('../../Customer/vendor/autoload.php');
include_once '../../dbConfig.php'; 


session_start();

// $conn =  mysqli_connect("localhost", "root", "", "shopping");

if (isset($_POST['order_id'])) {
    // $receiptCode = $_SESSION['ReceiptCode'];
    $receiptCode = $_POST['order_id'];
    $sql = "SELECT shipping_address_id FROM orders WHERE order_id = '$receiptCode'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $address_id = $row['shipping_address_id'];
} 
// else {
//     $receiptCode = $_POST["order_id"];
//     $sql = "SELECT shipping_address_id FROM orders WHERE order_id = '$receiptCode'";
//     $result = mysqli_query($conn, $sql);
//     $row = mysqli_fetch_assoc($result);
//     $address_id = $row['shipping_'];
// }

$sql = "SELECT * FROM shipping_address r  
        WHERE  r.address_id = '$address_id'";

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);
$cusID = $row["CusID"];
$cusAddress = $row['address_line1'];
$cusFName = $row['recipient_name'];
$cusphone_number = $row['phone_number'];

$sql = "SELECT order_date FROM orders WHERE order_id = '$receiptCode'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$payTime = $row['order_date'];
$payDate = date('Y-m-d', strtotime($payTime));



// Create a new TCPDF object
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Disable automatic header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set the font to 'sarabun'
$pdf->SetFont('sarabun', '', 12);

// Add a page
$pdf->AddPage();

$pdf->SetMargins(20, 20, 20);
$pdf->SetAutoPageBreak(true, 20);

$pdf->SetXY(20, 10);

$pdf->Cell(60, 10, 'บริษัท เดดสต็อค จำกัด(สำนักงานใหญ่)', 0, 0, 'L');

$pdf->SetXY(130, 10);

$pdf->SetFont('sarabun', '', 15);

$pdf->Cell(60, 10, 'ใบเสร็จรับเงิน/ใบกำกับภาษี', 0, 1, 'R');

$pdf->SetFont('sarabun', '', 9);

$pdf->SetXY(20, 15);

$pdf->Cell(60, 10, '999 หมู่ 999 ถ.ฉลองกรุง 9999 แขวงลาดกระบัง', 0, 0, 'L');

$pdf->SetXY(130, 15);

$pdf->Cell(60, 10, 'Receipt/Tax Invoice', 0, 0, 'C');

$pdf->SetXY(20, 20);

$pdf->Cell(60, 10, 'เขตลาดกระบัง กรุงเทพมหานคร 10500', 0, 0, 'L');

$pdf->SetXY(130, 20);

$pdf->Cell(60, 10, 'ต้นฉบับ', 0, 0, 'C');

$pdf->SetXY(20, 25);

$pdf->Cell(60, 10, 'เลขประจำตัวผู้เสียภาษี 12345678909999', 0, 1, 'L');

$pdf->SetXY(20, 30);

$pdf->Cell(60, 10, 'โทร. 0123456789 อีเมล deadstock@test.com', 0, 0, 'L');

$pdf->SetFont('sarabun', '', 10);

$pdf->SetXY(20, 40);

$pdf->Cell(60, 10, "ลูกค้า: ", 0, 0, 'L');

$pdf->SetFont('sarabun', '', 9);

$pdf->SetXY(30, 40);

$pdf->Cell(60, 10, " . $cusFName . " , 0, 0, 'L');

$pdf->SetFont('sarabun', '', 10);

$pdf->SetXY(130, 40);

$pdf->Cell(60, 10, "เลขที่: ", 0, 0, 'L');

$pdf->SetFont('sarabun', '', 9);

$pdf->SetXY(140, 40);

$pdf->Cell(60, 10, "" . $receiptCode . "", 0, 0, 'L');

$pdf->SetFont('sarabun', '', 10);

$pdf->SetXY(20, 45);

$pdf->Cell(60, 10, "ที่อยู่: ", 0, 0, 'L');

$pdf->SetXY(30, 45);

$pdf->Cell(60, 10, "" . $cusAddress . "", 0, 0, 'L');

$pdf->SetFont('sarabun', '', 10);

$pdf->SetXY(130, 45);

$pdf->Cell(60, 10, "วันที่: ", 0, 0, 'L');

$pdf->SetFont('sarabun', '', 9);

$pdf->SetXY(140, 45);

$pdf->Cell(60, 10, "" . $payDate . "", 0, 0, 'L');

$pdf->SetFont('sarabun', '', 10);

$pdf->SetXY(20, 50);

$pdf->Cell(60, 10, "เลขประจำตัวผู้เสียภาษี: ", 0, 0, 'L');

$pdf->SetXY(55, 50);

$pdf->Cell(60, 10, "123456789123", 0, 0, 'L');

$pdf->SetFont('sarabun', '', 10);

$pdf->SetXY(20, 55);

$pdf->Cell(60, 10, "โทร: ", 0, 0, 'L');

$pdf->SetXY(30, 55);

$pdf->Cell(60, 10, "" . $cusphone_number  . "", 0, 0, 'L');

$pdf->SetFont('sarabun', '', 10);

$pdf->SetXY(55, 55);

$pdf->Cell(60, 10, "อีเมล: ", 0, 0, 'L');

$pdf->SetXY(65, 55);

$pdf->Cell(60, 10, "testuser@test.com", 0, 1, 'L');

$pdf->Cell(170, 10, "", 'T', 1, 'L');

$sql = "SELECT *
FROM order_details
JOIN product ON order_details.ProID = product.ProID 
WHERE order_details.order_id = '$receiptCode'";
$result = mysqli_query($conn, $sql);

$orderProducts = array();
while ($orderProductRow = mysqli_fetch_array($result)) {
    $orderProducts[] = $orderProductRow;
}

$totalPrice = 0;
$id = 1;

$html = "
<table style='width: 100%;'>
<tr>
    <th style='width: 5%'>ลำดับ</th>
    <th style='width: 40%'>รายการสินค้า</th>
    <th style='width: 10%'>จำนวน</th>
    <th style='width: 15%'>ราคาต่อหน่วย</th>
    <th style='width: 15%'>จำนวนเงิน</th>
</tr>";

foreach ($orderProducts as $orderProductRow) {
    $productName = $orderProductRow['ProName'];
    $qty = $orderProductRow['quantity'];
    $pricePerUnit = $orderProductRow['PricePerUnit'];
    $total = $orderProductRow['PricePerUnit'] * $orderProductRow['quantity'];

    $html .= "<tr>
    <td style='padding-top: 50px'>" . $id . "</td>
    <td style='padding-top: 50px'>" . $productName . "</td>
    <td style='padding-top: 50px'>" . $qty . "</td>
    <td style='padding-top: 50px'>฿" . $pricePerUnit . "</td>
    <td style='padding-top: 50px'>" . $total . " บาท</td>
    </tr>";

    $totalPrice += $orderProductRow['PricePerUnit'] * $orderProductRow['quantity'];
    $id++;
}

$html .= "</table>";

$vat = $totalPrice * 0.07;
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Cell(170, 10, "", 'T', 1, 'L');

$pdf->Cell(0, 10, 'หมายเหตุ', 0, 0, 'L');
$pdf->Cell(0, 10, 'ส่วนลด 0 บาท', 0, 1, 'R');
$pdf->Cell(0, 10, 'รวมเป็นเงิน ' . $totalPrice . ' บาท', 0, 1, 'R');
$pdf->Cell(0, 10, 'ภาษีมูลค่าเพิ่ม 7% ' . $vat . ' บาท', 0, 1, 'R');
$pdf->Cell(0, 10, 'ผู้รับเงิน บริษัท เดดสต็อค จำกัด(สำนักงานใหญ่)', 0, 0, 'L');
$pdf->Cell(0, 10, 'จำนวนเงินทั้งสิ้น ' . ($totalPrice + $vat) . ' บาท', 0, 1, 'R');


// Output the PDF
$pdf->Output('receipt.pdf', 'I');