<?php
include_once '../../dbConfig.php'; 
header("location: ./customer_index.php");

$a1 = $_POST['a1'];
$a2 = $_POST['a2'];
$a3 = $_POST['a3'];
// $a4 = $_POST['a4'];
$a5 = $_POST['a5'];
$a6 = $_POST['a6'];
$a7 = $_POST['a7'];
$role = $_POST['role'];

$result = mysqli_query($conn, "SELECT * FROM customer_account WHERE Username = '$a6'");
$row = mysqli_fetch_assoc($result);

if (mysqli_num_rows($result) > 0) {
    echo 'Username has already been taken';
} 
else {
    $password = password_hash($a7, PASSWORD_DEFAULT);
    
    // Insert into customer table
    $stmt_customer = mysqli_query($conn, "INSERT INTO customer(CusFName, CusLName, sex, Tel ,role)
        VALUES('$a1', '$a2', '$a3', '$a5' , '$role');");

    $cusID = mysqli_insert_id($conn);

    // Insert into customer_account table
    $stmt_account = mysqli_query($conn, "INSERT INTO customer_account(Username, Password, CusID)
        VALUES('$a6', '$password', '$cusID');");

    $RecvID = mysqli_insert_id($conn);

    if (!$stmt_customer || !$stmt_account ) {
        echo "Error";
    } else {
        echo 'Insert data is Successful.';
        header("location: ./customer_index.php");
    }
}

echo "<a href='customer_index.php' 
    style='
    padding: 9px 14px;
    color: #ef476f;             
    text-decoration: none;
    margin-right: 5px;
    '>Back</a>";

mysqli_close($conn);
?>
