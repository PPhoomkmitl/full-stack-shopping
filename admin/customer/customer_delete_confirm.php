<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Customer</title>
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
    <?php 
    /* POST connection */
    include_once '../../dbConfig.php'; 
    echo '123';
    /*SELECT*/
    if (isset($_POST['list_id_customer'])){

        $code = $_POST['list_id_customer'];
        $codesArray = explode(',', $code);
        echo "<center>";
        echo "<form method='POST' action='customer_delete.php'>";
        echo "<h4>จำนวนชุดข้อมูลที่จะลบ</h4><font size='8'>";
        echo count($codesArray);
        echo "</font><br>";
        echo "⚠️โปรดให้เเน่ใจที่จะต้องการลบข้อมูล⚠️<br><br>";
        echo "<input type='hidden' name='list_id_customer' value={$_POST['list_id_customer']}>";
        echo "<a href='customer_index.php'>ยกเลิก</a>"; 
        echo "<input type='submit' value='ยืนยัน'>";
        echo "</form>\n";
        echo "</center>";
    }
    else {
        $code = $_POST['id_customer'];
        $cur = "SELECT * FROM Customer WHERE CusID = '$code'";
      
        $msresults = mysqli_query($conn,$cur);
        if(mysqli_num_rows($msresults) > 0) {

            $cur = "SELECT Customer.CusFName , Customer.CusLName , Customer.Sex , Customer.Tel , receiver.* FROM Customer 
            INNER JOIN receiver_detail ON receiver_detail.CusID = customer.CusID
            INNER JOIN receiver ON receiver_detail.RecvID = receiver.RecvID
            WHERE receiver_detail.CusID = '$code'";

            $msresults_receiver = mysqli_query($conn,$cur);
            $row_recv = mysqli_fetch_array($msresults_receiver);


            $row = mysqli_fetch_array($msresults);
            echo "<center>";
            echo "<form method='POST' action='customer_delete.php'>";
            echo "<h1>Delete Customer Form</h1>";
            echo "<h2>รหัสลูกค้า ". $row['CusID'] ."</h2><br>";
            echo "<input type='hidden' name='id_customer' value='" . $row['CusID'] . "'>";
            echo "<input type='hidden' name='id_receiver' value='" . (isset($row_recv['RecvID']) ? $row_recv['RecvID'] : "") . "'>";
            echo "ชื่อ : {$row['CusFName']} {$row['CusLName']}<br>";
            echo "เพศ : {$row['Sex']}<br>";
            echo "ที่อยู่ : " . (isset($row_recv['Address']) ? $row_recv['Address'] : "-") . "<br>";
            echo "เบอร์โทรศัพท์ : {$row['Tel']}<br><br>";
            echo "⚠️โปรดให้เเน่ใจที่จะต้องการลบข้อมูล⚠️<br><br>";
            echo "<a href='customer_index.php'>ยกเลิก</a>";
            echo "<input type='submit' value='ยืนยัน'>";
            echo "</form>\n"; 
            echo "</center>";
        }
    }

    /* close connection */
    mysqli_close($conn);
    ?>
</body>

</html>


