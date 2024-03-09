<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $cx = mysqli_connect("localhost", "root", "", "shopping");

        $select_user = "SELECT * FROM customer_account WHERE Username = '$username'";
        $run_qry = mysqli_query($cx, $select_user);
        if (mysqli_num_rows($run_qry) > 0) {
            while ($row = mysqli_fetch_assoc($run_qry)) {
                if (password_verify($password, $row['Password'])) {
                    if($username == 'admin' && $password == 'Admin123$'){                                              
                        echo "Password match!";
                        $_SESSION['admin'] = true;

                        header("Location: ../admin/dashboard/dashboard.php");
                        exit(); 
                    }
                    else {
                        header("Location: ./login.php");
                        exit;
                    }
                } else {
                    echo "Password Not match!";
                }
            }
        }
        else {
            header("Location: ./login.php");
            exit;
        }
        mysqli_close($cx);
    }
?>

