<style>
    form {
        width: 50%;
        margin: 0 auto;
        padding: 20px;
        /* background-color: #f9f9f9;
        border: 1px solid #ddd; */
        border-radius: 5px;
    }

    h1 {
        text-align: center;
    }

    h2 {
        text-align: center;
    }

    input[type='text'],
    select,
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    input[type='submit'] {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border: none;
        border-radius: 5px;
        background-color: #3498db;
        color: white;
        cursor: pointer;
    }

    input[type='button'] {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border: none;
        border-radius: 5px;
        background-color: gray;
        color: white;
        background-color: #ef476f;
        cursor: pointer;
    }

    input[type='submit']:hover {
        background-color: #2980b9;
    }

    input[type='button']:hover {
        background-color: #d64161;
    }

    input[type='submit']:active {
        background-color: #3498db;
    }

    input[type='button']:active {
        background-color: #ef476f;
    }
</style>
<?php /* get connection */
include_once '../../dbConfig.php';

/*SELECT*/
$code = $_POST['id_customer'];
$cur = "SELECT * FROM Customer WHERE CusID = '$code'";

$msresults = mysqli_query($conn, $cur);
$row = mysqli_fetch_array($msresults);
if (mysqli_num_rows($msresults) > 0) {

    $cur = "SELECT Customer.CusFName , Customer.CusLName , Customer.Sex , Customer.Tel , receiver.* FROM Customer 
        INNER JOIN receiver_detail ON receiver_detail.CusID = customer.CusID
        INNER JOIN receiver ON receiver_detail.RecvID = receiver.RecvID
        WHERE receiver_detail.CusID = '$code'";

    $msresults_receiver = mysqli_query($conn, $cur);
    $row_recv = mysqli_fetch_array($msresults_receiver);

    echo "<form method='post' action='customer_save_update.php'>";
    echo "<center>";

    echo "<h1> Update Customer Form </h1>";
    echo "<h2>รหัสลูกค้า " . $row['CusID'] . "</h2><br>";
    echo "<input type='hidden' name='id_customer' value='" . $row['CusID'] . "'>";
    echo "<input type='hidden' name='id_receiver' value='" . (isset($row_recv['RecvID']) ? $row_recv['RecvID'] : "") . "'>";
    echo "ชื่อ <input type='text' name='a1' value='" . $row['CusFName'] . "'><br>";
    echo "นามสกุล <input type='text' name='a2' value='" . $row['CusLName'] . "'><br>";
    // echo "เพศ <input type='text' name='a3' value='" . $row['Sex'] . "'><br>";
    echo "เพศ <select name='a3'>";
    echo "<option value='F'" . ($row['Sex'] == 'F' ? " selected" : "") . ">F</option>";
    echo "<option value='M'" . ($row['Sex'] == 'M' ? " selected" : "") . ">M</option>";
    echo "<option value='N'" . ($row['Sex'] == 'N' ? " selected" : "") . ">None</option>";
    echo "</select><br>";
    echo "เบอร์โทรศัพท์ <input type='text' name='a4' value='" . $row['Tel'] . "'><br>";
    echo "ที่อยู่ <textarea name='a5'>" . (isset($row_recv['Address']) ? $row_recv['Address'] : "") . "</textarea><br>";
    echo "⚠️โปรดให้เเน่ใจที่จะต้องการอัปเดตข้อมูล⚠️<br><br>";
    echo "<div style='display:flex;'>";
    echo "<input type='button' value='กลับ' style='margin-right:1rem;' onclick='history.back();'>";
    echo "<input type='submit' value='ยืนยัน''>";
    echo "</div>";

    echo "</form>\n";
    echo "</center>";
}

/* close connection */

?>