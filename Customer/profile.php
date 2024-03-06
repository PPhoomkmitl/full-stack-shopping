<?php
include('./component/session.php');

include_once '../dbConfig.php'; 

$query = "SELECT * FROM customer INNER JOIN customer_account ON customer_account.CusID = customer.CusID WHERE  customer.CusID = '$uid'";
$result = mysqli_query($conn, $query);
$user_data = mysqli_fetch_assoc($result);
$uid = $user_data['CusID'];

$query_address = "SELECT * FROM receiver 
    INNER JOIN receiver_detail ON receiver.RecvID = receiver_detail.RecvID  
    WHERE receiver_detail.CusID = '$uid'";
$result_address = mysqli_query($conn, $query_address);



if (!$result) {
    die("Error fetching user data: " . mysqli_error($conn));
}




if ($_SERVER['REQUEST_METHOD'] == 'POST') {;
    $uid = $_POST['id_customer'];
    $new_username = $_POST['username'];
    $new_tel = $_POST['tel'];
    $new_address = $_POST['address'];

    $update_query = "UPDATE customer SET UserName = '$new_username' ,Tel = '$new_tel' WHERE CusID = '$uid'";
    $update_result = mysqli_query($conn, $update_query);

    $update_query = "UPDATE customer_account SET Username = '$new_username' WHERE CusID = '$uid'";
    $update_result = mysqli_query($conn, $update_query);

    if (!$update_result) {
        die("Error updating user data: " . mysqli_error($conn));
    }

    // $_SESSION['username'] = $new_username;
    // $_SESSION['tel'] = $new_tel;

}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Setting</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .body-container {
            /* background-color:blue; */
            display: flex;
            align-items: center;
            justify-content: center;
            height: screen;
            margin-top: 100px;
        }

        .container {
            padding: 20px 60px 70px 60px;
            text-align: left;
            width: 800px;
            background-color: #fff; /* Added background color */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); /* Added box-shadow */
            border-radius: 8px; /* Added border-radius for a rounded appearance */
            margin-top: 2rem;
        }

        #head-text {
            font-size: 40px;
            margin-bottom: 10px;
        }

        #text-1 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input,
        textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        textarea {
            resize: vertical;
        }

        button {
            background-color: #3498db;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #3498db;
        }
         /* Overlay styles */
         .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            text-align: center;
            z-index: 999;
        }

        .overlay-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        span {
            color: red;
        }

        .user-card {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            width: 200px; /* ปรับขนาดตามต้องการ */
            cursor: pointer;
        }

        .user-card:hover {
            background-color: #f0f0f0; /* สีเวลา hover */
        }

         /* สไตล์ของปุ่ม */
        .long-button {
            display: inline-block;
            padding: 5px 50px;
            background-color: #3498db; /* สีพื้นหลัง */
            color: #fff; /* สีตัวอักษร */
            border: none;
            /* border-radius: 20px;  */
            cursor: pointer;
            position: relative;
        }

        /* สไตล์ของไอคอนกลม */
        .circle-icon {
            width: 24px;
            height: 24px;
            background-color: #fff; /* สีพื้นหลัง */
            border-radius: 50%; /* รัศมีของวงกลม */
            display: inline-flex;
            align-items: center;
            justify-content: center;
            /* margin-right: 10px; */
        }

        /* สไตล์ของสัญลักษณ์ '+' */
        .plus-icon::before,
        .plus-icon::after {
            content: '';
            width: 12px;
            height: 2px;
            background-color: #3498db; /* สีของเส้นสัญลักษณ์ */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .plus-icon::after {
            transform: translate(-50%, -50%) rotate(90deg);
        }

        .address-container {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="body-container">
        <?php include('./component/accessNavbar.php')?>
        <div class="container">
            <p id='head-text'>Edit Profile</p>
            <p id='text-1'>Change your basic account here. You may also want to edit your profile</p>

            <form id="profileForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                <input type="hidden" name="id_customer" value="<?php echo $user_data['CusID'] ?>">
                
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo $user_data['Username'] ?>">

                <label for="password">Password:</label>
                <input type="password" name="password" value="<?php echo $user_data['Password'] ?>" readonly>

                <label for="tel">Tel:<span>*</span></label>
                <input type="tel" name="tel" value="<?php echo $user_data['Tel']?>" required>

                <button type="submit" onclick="showOverlay()">บันทึกข้อมูล</button>
            </form>

            <div class="address-container">
                <label for="address" >Address:<span>*</span></label>
                <?php
                    $user_address = mysqli_fetch_array($result_address);
                    while ($user_address) {
                        $recvID = $user_address['RecvID'];
                        echo '<div class="user-card" onclick="submitForm(\'' . $recvID . '\')">
                                <p>' . $user_address['RecvFName'] . '</p>
                                <p>' . $user_address['RecvLName'] . '</p>
                                <p>' . $user_address['Tel'] . '</p>
                                <p>' . $user_address['Address'] . '</p>              
                            </div>';
                        echo   '<form method="post" action="./accessAddressProfile.php">
                                    <input type="hidden" name="delete_id_customer" value="' . $uid . '">
                                    <input type="hidden" name="delete_id_receiver" id="id_receiver" value="'.$recvID.'">
                                    <button type="submit">ลบ</button>
                            </form>'; 

                        $user_address = mysqli_fetch_array($result_address); // Update $user_address
                    }
                ?>
                <!-- Add the hidden form outside the loop -->
                <form id="addressForm" method="post" action="./profileAddress.php">
                    <input type="hidden" name="id_customer" value="<?php echo $uid ?>">
                    <input type="hidden" name="id_receiver" id="id_receiver" value="">
                </form>

                <form id="address" method="post" action="./profileAddress.php">
                    <input type="hidden" name="id_customer" value="<?php echo $uid ?>">
                    <input type="hidden" name="id_receiver" value="<?php echo '' ?>">
                    <button class="long-button">
                        <div class="circle-icon">
                            <div class="plus-icon"></div>
                        </div>
                    </button>
                <form>
            <div>

        </div>
    </div>

    <!-- Overlay -->
    <div class="overlay" id="overlay" >
        <div class="overlay-content">
            <p>บันทึกข้อมูลสำเร็จ!</p>
            <button onclick="hideOverlay()">ตกลง</button>
        </div>
    </div>

    <script>
        // Function to submit the form with the specified receiver ID
        function submitForm(id_receiver) {
            // Set the value of the hidden input in the form
            document.getElementById('id_receiver').value = id_receiver;

            // Submit the form
            document.getElementById('addressForm').submit();
        }
        
    document.getElementById('profileForm').addEventListener('submit', function (event) {
        event.preventDefault();

        var formData = new FormData(this);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', this.action, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                // Form submitted successfully
                showOverlay();
            } else {
                // Handle error
                console.error('Error submitting form');
            }
        };
        xhr.send(formData);
    });

    function showOverlay() {
        document.getElementById('overlay').style.display = 'flex';
        // Delay the hideOverlay function
        setTimeout(hideOverlay, 1000); // Adjust the time (in milliseconds) as needed
    }

    function hideOverlay() {
        document.getElementById('overlay').style.display = 'none';
        // Redirect to profileAddress.php after hiding the overlay
        // window.location.href = './profile.php';
    }
</script>
</body>

</html>
