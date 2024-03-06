<?php
include('./component/session.php');

include_once '../dbConfig.php'; 

echo $_POST['id_receiver'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_receiver'])) {
        $id_customer = $_POST['id_customer'];
        $id_receiver = $_POST['id_receiver'];
        $query_address = "SELECT * FROM receiver 
        INNER JOIN receiver_detail ON receiver.RecvID = receiver_detail.RecvID  
        WHERE receiver_detail.CusID = '$uid' AND receiver.RecvID = '$id_receiver'";
        $result_address = mysqli_query($conn, $query_address);

        // Check if there are rows returned
        if ($result_address && mysqli_num_rows($result_address) > 0) {
            $user_data = mysqli_fetch_assoc($result_address);
        } else {
            $user_data = null;  
        }

        if (!$result_address) {
            die("Error fetching user data: " . mysqli_error($conn));
        }
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form id="profileForm" method="post" action="./accessAddressProfile.php">
        <input type="hidden" name="id_customer" value="<?php echo $uid ?>">
        <input type="hidden" name="id_receiver" value="<?php echo isset($user_data['RecvID']) ? $user_data['RecvID'] : '' ?>">

        <label for="fname">First name:</label>
        <input type="text" name="fname" value="<?php echo isset($user_data['RecvFName']) ? $user_data['RecvFName'] : '' ?>">

        <label for="lname">Last name:</label>
        <input type="text" name="lname" value="<?php echo isset($user_data['RecvLName']) ? $user_data['RecvLName'] : '' ?>">

        <label for="tel">Tel:<span>*</span></label>
        <input type="tel" name="tel" value="<?php echo isset($user_data['Tel']) ? $user_data['Tel'] : '' ?>">

        <label for="address">Address:<span>*</span></label>
        <input type="address" name="address" value="<?php echo isset($user_data['Address']) ? $user_data['Address'] : '' ?>">
        <button type="submit">กด</button>
    </form>
</body>
</html>