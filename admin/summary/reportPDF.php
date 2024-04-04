<!-- <?php
    include '../session.php';

    function generateRow($startDate, $endDate, $conn){

        echo "<script>";
        echo "console.log('Start Date: " . $startDate . "');";
        echo "console.log('End Date: " . $endDate . "');";
        echo "</script>";

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
                    DATE(orders.order_date) BETWEEN ? AND ?
                GROUP BY
                    product.ProID
                ORDER BY
                    TotalQty DESC";

        // Execute the query and fetch data
        $stmt = $conn->prepare($query);
        $stmt->execute([$startDate, $endDate]);

        if ($stmt) {
            $total = 0;
            $contents = '';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $total += $row['TotalQty'] * $row['PricePerUnit'];
                $contents .= '
                <tr>
                    <td>' . $row['ProID'] . '</td>
                    <td>' . $row['ProName'] . '</td>
                    <td>' . $row['Description'] . '</td>
                    <td>' . $row['PricePerUnit'] . '</td>
                    <td>' . $row['TotalQty']  . '</td>
                </tr>';         
            }
            $contents .= '
            <tr>
                <td colspan="4" align="right"><b>Total</b></td>
                <td align="right"><b>&#36; '.number_format($total, 2).'</b></td>
            </tr>';

            return $contents;
        }
    }

    if(isset($_POST['print'])){
        $from = $_POST['startDate'];
        // $end = date('Y-m-d', strtotime($ex[0]));
        $to = $_POST['endDate'];
        // $from_title = date('M d, Y', strtotime($ex[0]));
        // $to_title = date('M d, Y', strtotime($ex[1]));

        // $conn = $pdo->open();
        $conn = new PDO('mysql:host=localhost;dbname=shopping', 'root', '');


        require_once('../../Customer/tcpdf_6_3_2/tcpdf/tcpdf.php');  
        $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
        $pdf->SetCreator(PDF_CREATOR);  
        $pdf->SetTitle('Product Report');  
        $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
        $pdf->SetDefaultMonospacedFont('helvetica');  
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
        $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
        $pdf->setPrintHeader(false);  
        $pdf->setPrintFooter(false);  
        $pdf->SetAutoPageBreak(TRUE, 10);  
        $pdf->SetFont('helvetica', '', 11);  
        $pdf->AddPage();  
        $content = '';  


        $content .= '
            <h2 align="center">MyShop REPORT</h2>
            <h4 align="center">SALES REPORT</h4>
            <h4 align="center">'.$from." - ".$to.'</h4>
            <table border="1" cellspacing="0" cellpadding="3">  
               <tr>  
                    <th width="15%" align="center"><b>ID</b></th>
                    <th width="30%" align="center"><b>Name</b></th>
                    <th width="40%" align="center"><b>Descirption</b></th>
                    <th width="15%" align="center"><b>Price Per Unit</b></th>  
                    <th width="15%" align="center"><b>Total Unit Sold</b></th>  
               </tr>';  
        $content .= generateRow($from, $to, $conn);  
        $content .= '</table>';  
        $pdf->writeHTML($content);  
        $pdf->Output('product_report.pdf', 'I');

        $pdo->close();

    }
    else{
        $_SESSION['error'] = 'Need date range to provide sales print';
        header('location: summaryReport.php');
    }
?> -->
