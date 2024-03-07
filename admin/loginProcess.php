<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $cx = mysqli_connect("localhost", "root", "", "shopping");

        // $select_user = "SELECT * FROM customer_account WHERE Username = '$username'";
        // $run_qry = mysqli_query($cx, $select_user);
        // if (mysqli_num_rows($run_qry) > 0) {
        //     while ($row = mysqli_fetch_assoc($run_qry)) {
        //         if (password_verify($password, $row['Password'])) {
        //             echo "Password match!";
        //             $user = $row['Username'];
        //             $uid = $row['CusID'];
        //             $_SESSION['status'] = true;
        //             $_SESSION['id_username'] = $uid;
        //             unset($_SESSION['cart']);
        //             header("Location: index.php");
        //             exit(); 

        //         } else {
        //             echo "Password Not match!";
        //         }
        //     }
        // }
        if($username == 'admin' && $password == '123456'){
            $_SESSION['admin'] = true;
            header("Location: ../admin/dashboard/dashboard.php");
        }
        else {
            header("Location: ./login.php");
        }
        mysqli_close($cx);
    }
?>

