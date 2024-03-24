<?php
include('./component/session.php');

include_once '../dbConfig.php';


$uid = $_SESSION['id_username'];


$query = "SELECT * FROM customer INNER JOIN customer_account ON customer_account.CusID = customer.CusID WHERE  customer.CusID = '$uid'";
$result = mysqli_query($conn, $query);
$user_data = mysqli_fetch_assoc($result);



$query_address = "SELECT * FROM shipping_address WHERE CusID = '$uid'";
$result_address = mysqli_query($conn, $query_address);
$user_address = mysqli_fetch_assoc($result_address); // Fetch only one address



if (!$result) {
    die("Error fetching user data: " . mysqli_error($conn));
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uid = $_POST['id_customer'];
    $new_cusfname = $_POST['cus_fname'];
    $new_cuslname = $_POST['cus_lname'];
    $new_sex = $_POST['sex'];
    $new_tel = $_POST['tel'];

    // Update customer table
    $update_query_customer = "UPDATE customer SET CusFName = ?, CusLName = ?, Sex = ?, Tel = ? WHERE CusID = ?";
    $stmt_customer = mysqli_prepare($conn, $update_query_customer);
    mysqli_stmt_bind_param($stmt_customer, 'ssssi', $new_cusfname, $new_cuslname, $new_sex, $new_tel, $uid);
    $update_result_customer = mysqli_stmt_execute($stmt_customer);

    // Update shipping_address table
    $recipient_name = $_POST['recipient_name'];
    $address_line1 = $_POST['address_line1'];
    $phone_number = $_POST['phone_number'];

    $update_query_address = "UPDATE shipping_address SET recipient_name = ?, address_line1 = ?, phone_number = ? WHERE CusID = ?";
    $stmt_address = mysqli_prepare($conn, $update_query_address);
    mysqli_stmt_bind_param($stmt_address, 'sssi', $recipient_name, $address_line1, $phone_number, $uid);
    $update_result_address = mysqli_stmt_execute($stmt_address);

    // Check if both updates were successful
    if ($update_result_customer && $update_result_address) {
        echo "User data updated successfully!";
    } else {
        echo "Error updating user data: " . mysqli_error($conn);
    }

    // Close prepared statements
    mysqli_stmt_close($stmt_customer);
    mysqli_stmt_close($stmt_address);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Setting</title>

    <style>
        /* Updated styles for the sex container */
        .sex-container {
            position: relative;
            margin-bottom: 16px;
        }

        .sex-container label {
            display: inline-flex;
            align-items: center;
            margin-right: 20px;
            /* Adjust the right margin to control spacing between radio buttons and text */
        }

        .sex-container input[type="radio"] {
            margin-right: 15px;
            position: relative;
            /* Add this line */
            left: 10px;
            top: 8px;
            /* Adjust this value as needed to vertically align the radio button with the text */
        }




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
            background-color: #fff;
            /* Added background color */
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            /* Added box-shadow */
            border-radius: 8px;
            /* Added border-radius for a rounded appearance */
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
            width: 200px;
            /* ปรับขนาดตามต้องการ */
            cursor: pointer;
        }

        .user-card:hover {
            background-color: #f0f0f0;
            /* สีเวลา hover */
        }

        /* สไตล์ของปุ่ม */
        .long-button {
            display: inline-block;
            padding: 5px 50px;
            background-color: #3498db;
            /* สีพื้นหลัง */
            color: #fff;
            /* สีตัวอักษร */
            border: none;
            /* border-radius: 20px;  */
            cursor: pointer;
            position: relative;
        }

        /* สไตล์ของไอคอนกลม */
        .circle-icon {
            width: 24px;
            height: 24px;
            background-color: #fff;
            /* สีพื้นหลัง */
            border-radius: 50%;
            /* รัศมีของวงกลม */
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
            background-color: #3498db;
            /* สีของเส้นสัญลักษณ์ */
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

        .navCon {
            z-index: 100;
            border: 1px solid #333;
        }
    </style>
</head>

<body>
    <div class="navCon">
        <?php include('./component/accessNavbar.php'); ?>
    </div>
    <div class="body-container">
        <div class="container">
            <p id='head-text'>Edit Profile</p>
            <p id='text-1'>Change your basic account here. You may also want to edit your profile</p>

            <form id="profileForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                <input type="hidden" name="id_customer" value="<?php echo $user_data['CusID'] ?>">

                <label for="cus_fname">First Name:</label>
                <input type="text" name="cus_fname" value="<?php echo $user_data['CusFName'] ?>">

                <label for="cus_lname">Last Name:</label>
                <input type="text" name="cus_lname" value="<?php echo $user_data['CusLName'] ?>">

                <div class="sex-container">
                    <label> Male
                        <input type="radio" name="sex" value="Male" <?php echo ($user_data['Sex'] == 'M') ? 'checked' : ''; ?>>

                    </label>
                    <label> Female
                        <input type="radio" name="sex" value="Female" <?php echo ($user_data['Sex'] == 'F') ? 'checked' : ''; ?>>

                    </label>
                </div>

                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo $user_data['Username'] ?>">

                <label for="password">Password:</label>
                <input type="password" name="password" value="<?php echo $user_data['Password'] ?>" required>

                <label for="tel">Tel:<span></span></label>
                <input type="tel" name="tel" value="<?php echo $user_data['Tel'] ?>" required>






                <div class="address-container">
                    <label for="address">Address:<span></span></label>
                    <?php
                    // $user_address = mysqli_fetch_array($result_address);
                    ?>
                    <!-- Add the hidden form outside the loop -->
                    <form id="addressForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <label for="recipient_name">Recipient Name:</label>
                        <input type="text" name="recipient_name" value="<?php echo isset($user_address['recipient_name']) ? $user_address['recipient_name'] : ''; ?>">
                        <label for="address_line1">Address Line 1:</label>
                        <input type="text" name="address_line1" value="<?php echo isset($user_address['address_line1']) ? $user_address['address_line1'] : ''; ?>">
                        <label for="phone_number">Phone Number:</label>
                        <input type="tel" name="phone_number" value="<?php echo isset($user_address['phone_number']) ? $user_address['phone_number'] : ''; ?>">
                        <br />
                        <button type="submit">บันทึกข้อมูล</button>
                    </form>
                </div>


                <!-- Overlay -->
                <div class="overlay" id="overlay">
                    <div class="overlay-content">
                        <p>บันทึกข้อมูลสำเร็จ!</p>
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

                    document.getElementById('profileForm').addEventListener('submit', function(event) {
                        event.preventDefault();

                        var formData = new FormData(this);

                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', this.action, true);
                        xhr.onload = function() {
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
<?php mysqli_close($conn); ?>

</html>