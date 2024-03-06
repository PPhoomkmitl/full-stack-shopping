<?php
include('./component/session.php');
include_once '../dbConfig.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['productId'];
    $newQuantity = $_POST['newQuantity'];
   

    // Update the cart table with the new quantity
    // $uid_query = mysqli_query($conn, "SELECT CusID FROM customer WHERE Username = '$user'");
    // $uid_row = mysqli_fetch_assoc($uid_query);
    // $uid_results = $uid_row['CusID'];


    if (isset($_SESSION['id_username'])){
        $updateQuery = "UPDATE cart SET Qty = $newQuantity WHERE CusID = $uid  AND ProID = $productId";
        mysqli_query($conn, $updateQuery);
    }
     /* Process the update logic, for example, update $_SESSION['cart'] */
    else if(isset($_SESSION['cart'])) {
        $_SESSION['cart'][$productId] = [
            'quantity' => $newQuantity
        ];
    }

    // You may need to handle errors and send appropriate responses
    echo json_encode(array("success" => true));
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(array("error" => "Method Not Allowed"));
}
?>