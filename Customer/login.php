<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .font-login {
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 50px;
            color: #333; /* เปลี่ยนสีข้อความใน font-login */
            margin-bottom: 20px; /* เพิ่มขอบล่าง */
        }

        .body-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .container {
            background-color: #f4f4f4; /* เปลี่ยนสีพื้นหลังของ container */
            padding: 50px 150px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            margin-bottom: 8px;
            color: #333; /* เปลี่ยนสีข้อความ label */
            align-self: flex-start;
        }

        input {
            margin-bottom: 16px;
            padding: 8px;
            width: 250px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #3498db; /* เปลี่ยนสีปุ่ม submit */
            color: #fff; /* เปลี่ยนสีข้อความปุ่ม submit */
            cursor: pointer;
            width: 150px;
        }
        .container-register {
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include('./component/backButton.php')?>
    <div class="font-login">
        <p>Log In</p>
    </div>
    <div class="body-container">
        <div class="container">
            <form method="post" action="loginProcess.php">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"  required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password"  required>
                <input type="submit" value="เข้าสู่ระบบ">
            </form>
            <div class='container-register'> 
                <a href="register.php">มีบัญชีเเล้วหรือยัง?</a>
            </div>
        </div>
    </div>
</body>
</html>



