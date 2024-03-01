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
            color: #333;
            margin-bottom: -10px;
        }

        .body-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .container {
            background-color: #f4f4f4;
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
            color: #333;
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
            background-color: #3498db;
            color: #fff;
            cursor: pointer;
            width: 150px;
        }
        
        .radio-label {
            display: flex;
            align-items: center;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .radio-input {
            margin-right: 10px;
            appearance: none;
            width: 16px;
            height: 16px;
            border: 2px solid #333;
            border-radius: 50%;
            outline: none;
            cursor: pointer;
        }

        .radio-input:checked {
            background-color: #06D6B1;
            border-color: #06D6B1;
        }

        .main-container {
            margin-top:-50px;
        }
        
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php include('./component/backLogin.php')?>
    <div class="main-container">
        <div class="font-login">
            <p>Register</p>
        </div>
        <div class="body-container">
            <div class="container">
                <form method="post" action="registerProcess.php">
                    <label for="fname">First name</label>
                    <input type="text" id="fname" name="fname" required>
                    <label for="lname">Last name</label>
                    <input type="text" id="lname" name="lname" required>
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    <p id ="password-error" class="error-message"></p>
                    <label for="tel">Tel</label>
                    <input type="tel" id="tel" name="tel" required>
                    <label class="radio-label">
                        <input type="radio" class="radio-input" name="sex" value="M" required> Male
                    </label>   
                    <label class="radio-label">
                        <input type="radio" class="radio-input" name="sex" value="F"> Female
                    </label>
                    <input type="submit" value="ลงทะเบียน">
                </form> 
            </div>
        </div>
    </div>

    <script>
        document.getElementById('password').addEventListener('input', function() {
            var password = this.value;
            var passwordError = document.getElementById('password-error');
            var specialChars = /[!@#$%^&*()\-_=+{};:,<.>]/;
            var numericChars = /[0-9]/;
            var alphabeticChars = /[a-z]/;
            var alphabeticCharsUp = /[A-Z]/;
            if (!(specialChars.test(password))) {
                passwordError.textContent = "รหัสผ่านต้องประกอบด้วยอักษรพิเศษอย่างน้อย 1 ตัวอักษร";
            } else if (!(alphabeticCharsUp.test(password))) {
                passwordError.textContent = "รหัสผ่านต้องประกอบด้วยตัวอักษรตัวใหญ่อย่างน้อย 1 ตัวอักษร";
            } else if (!(alphabeticChars.test(password))) {
                passwordError.textContent = "รหัสผ่านต้องประกอบด้วยตัวอักษรตัวเล็กอย่างน้อย 1 ตัวอักษร";
            } else if (!(numericChars.test(password))) {
                passwordError.textContent = "รหัสผ่านต้องประกอบด้วยตัวเลขอย่างน้อย 1 ตัว";
            } else if(password.length < 8){
                passwordError.textContent = "รหัสผ่านต้องมีความยาวมากกว่า 8 ตัวอักษร";
            } else if (password.length > 24) {
                passwordError.textContent = "รหัสผ่านต้องมีความยาวไม่เกิน 24 ตัวอักษร";
            } else {
                passwordError.textContent = "";
            }
        });
    </script>
</body>
</html>
