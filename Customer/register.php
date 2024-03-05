<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Login</title>
    <style>
        .bg-image {
            background-color: #488978;
            height: 280px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .container-main {
            background-color: hsl(0, 0%, 100%);
            height: 100vh;
        }

        label {
            text-align: left;
        }

        .navbar {
            text-align: left;
            position: relative;
            top: -50px;
            margin-left: -3rem;
        }
        .btn-success {
            background-color: #488978;
            color: #fff;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .btn-link {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }

        .btn-floating {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 50%;
            padding: 0.5rem;
            margin: 0 0.5rem;
            cursor: pointer;
        }

        .text-head {
            font-size: 2.5rem;
            font-weight: bold;
            letter-spacing: -1px;
        }

        #password-error {
            color: red;
        }
    </style>
</head>
<body>


        <!-- Background image -->
    <div class="container-main">
        <div class="p-5 bg-image">
            <div class="navbar">
                <?php include('./component/backLogin.php')?>
            </div>
        </div>
        <!-- Background image -->

        <div class="card mx-4 mx-md-5" style="
                margin-top: -130px;
                background: hsla(0, 0%, 100%, 0.9);
                backdrop-filter: blur(20px);
                ">
            <div class="card-body py-5 px-md-5">

            <div class="row d-flex justify-content-center">
                <div class="col-lg-5">
                <h2 class="fw-bold mb-5 text-center text-head" >Sign up</h2>
                <form method="post" action="registerProcess.php">
                    <!-- 2 column grid layout with text inputs for the first and last names -->
                    <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="form-outline">         
                            <label for="fname">First name</label>
                            <input type="text" id="fname" name="fname"  class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="form-outline">                  
                            <label for="lname">Last name</label>
                            <input type="text" id="lname" name="lname"  class="form-control" required>
                        </div>
                    </div>
                    </div>

                    <!-- Username input -->
                    <div class="form-outline mb-4">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                        <p id ="password-error" class="error-message"></p>
                    </div>

                    <!-- Tel input -->
                    <div class="form-outline mb-4">
                        <label for="tel">Tel</label>
                        <input type="tel" id="tel" name="tel" class="form-control" required>
                    </div>
                    <div class="form-outline mb-4">
                        <label class="radio-label">
                            <input type="radio" class="radio-input" name="sex" value="M" required> Male
                        </label>                 
                        
                        <label class="radio-label">
                            <input type="radio" class="radio-input" name="sex" value="F"> Female
                        </label>
                    </div>
             

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-success btn-block mb-4" id="button-submit">
                    Sign up
                    </button>
                </form>
                </div>
            </div>
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
            var buttonSubmit = document.getElementById('button-submit')
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
                buttonSubmit.click();
            }
        });
    </script>
</body>
</html>
