<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        center {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h1, h2 {
            color: #333;
        }

        h2 {
            margin-bottom: 20px;
        }

        input[type="submit"],
        a {
            display: inline-block;
            padding: 10px 20px;
            text-decoration: none;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 16px;
            margin-right: 5px;
        }

        input[type="submit"] {
            background-color: #ef476f;
        }

        a {
            background-color: #4b93ff;
        }

        input[type="submit"]:hover,
        a:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>
<?php /* get connection */
    include_once '../../dbConfig.php'; 

    /*SELECT*/
    if (isset($_POST['list_id_invoice'])){
        $code = $_POST['list_id_invoice'];
        $codesArray = explode(',', $code);
        echo "<center>";
        echo "<form method='POST' action='invoice_delete.php'>";
        echo "<h4>จำนวนชุดข้อมูลที่จะลบ</h4><font size='8'>";
        echo count($codesArray);
        echo "</font><br>";
        echo "⚠️โปรดให้เเน่ใจที่จะต้องการลบข้อมูล⚠️<br><br>";
        echo "<input type='hidden' name='list_id_invoice' value={$_POST['list_id_invoice']}>";
        echo "<a href='invoice_index.php'>ยกเลิก</a>"; 
        echo "<input type='submit' value='ยืนยัน'>";
        echo "</form>\n";
        echo "</center>";

        echo "<a href='invoice_index.php' 
        style='
        padding: 9px 14px;
        color: #ef476f;             
        text-decoration: none;
        margin-right: 5px;
        '>ยกเลิก</a>";
        echo "</form>\n"; 
        echo "</center>";
    }
    else {
        $code = $_POST['total_id_invoice'];
        echo $code;
        $cur = "SELECT * FROM invoice WHERE InvID = '$code'";
        $msresults = mysqli_query($conn,$cur);
    
        if(mysqli_num_rows($msresults) > 0) {
            $row = mysqli_fetch_array($msresults);
            echo "<center>";
            echo "<form method='POST' action='invoice_delete.php'>";
            echo "<h4>รหัสข้อมูลที่จะลบ</h4><font size='8'>";
            echo $code;
            echo "<br></font><br>";
            echo "⚠️โปรดให้เเน่ใจที่จะต้องการลบข้อมูล⚠️<br><br>";
            echo "<input type='hidden' name='list_id_invoice' value={$code}>";
            echo "<a href='invoice_index.php'>ยกเลิก</a>"; 
            echo "<input type='submit' value='ยืนยัน'>";
            echo "</form>\n";
            echo "</center>";
    
        }
    }

    /* close connection */
    mysqli_close($conn);
?>




