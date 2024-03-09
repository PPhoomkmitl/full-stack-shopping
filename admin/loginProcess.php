<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        include_once '../../dbConfig.php'; 

        if($username == 'admin' && $password == '123456'){
            $_SESSION['admin'] = true;
            header("Location: ../admin/dashboard/dashboard.php");
        }
        else {
            header("Location: ./login.php");
        }
        mysqli_close($conn);
    }
?>

