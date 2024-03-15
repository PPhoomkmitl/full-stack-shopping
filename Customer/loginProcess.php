<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        include_once '../dbConfig.php'; 

        $select_user = "SELECT customer_account.*, customer.* FROM customer_account 
        INNER JOIN customer ON customer.CusID = customer_account.CusID
        WHERE Username = '$username'";

        $run_qry = mysqli_query($conn, $select_user);

        if (mysqli_num_rows($run_qry) > 0) {
            while ($row = mysqli_fetch_assoc($run_qry)) {

                echo 'hello';
                if(password_verify($password, $row['Password'])) {
                    // ตรวจสอบรหัสผ่าน
                    if ($row['role'] === 'member') {
                        // ถ้าเป็นสมาชิก
                        echo "Password match!";
                        $_SESSION['status'] = true;
                        $_SESSION['member'] = 'member';
                        $_SESSION['id_username'] = $row['CusID'];
                        unset($_SESSION['cart']);
                        unset($_SESSION['admin']);
                        header("Location: ./index.php");
                        exit();

                    } elseif($row['role'] === 'super_admin' || $row['role'] === 'user_admin'){
                        // ถ้าเป็น super admin
                        echo "Password match!";
                        $_SESSION['status'] = true;
                        $_SESSION['admin'] = $row['role'];
                        unset($_SESSION['cart']);
                        header("Location: ../admin/dashboard/dashboard.php");
                        exit();

                    } 
                } else {
                    // กรณีอื่น ๆ
                    header("Location: ./login.php");
                    echo "Password Not match!";
                    exit();
                }
            }
        } else {
            header("Location: ./login.php");
        }
    }
    else {
        header("Location: ./login.php");
    }
    mysqli_close($conn);
?>

