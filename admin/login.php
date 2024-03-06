<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;

        }

        .main-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh; /* Ensure full viewport height */
            background-color: hsl(0, 0%, 96%);
            text-align: center;
            padding: 0 5rem 5rem 5rem;
        }


        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: center;
        }

        .col {
            flex: 1;
            min-width: 0;
        }

        .col h1 {
            font-size: 3rem;
            font-weight: bold;
            letter-spacing: -1px;
            margin-bottom: 1rem;
        }

        .col p {
            color: hsl(217, 10%, 50.8%);
            line-height: 1.6;
        }

        .card {
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        .card-body {
            padding: 5rem;
        }

        .form-outline {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-label {
            position: absolute;
            top: 0.5rem;
            left: 1rem;
            color: #555;
            font-size: 0.9rem;
            pointer-events: none;
            transition: 0.2s ease-out;
        }

        .form-control:focus + .form-label,
        .form-control:not(:placeholder-shown) + .form-label {
            top: -1.2rem;
            font-size: 0.8rem;
            color: #3498db;
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

        .navbar {
          text-align: left;
          margin-bottom: 7rem;
          margin-left: -8rem;
        }

        label {
          color:#555;
          font-size:0.9rem;
        }
    </style>
</head>
<body>
  <!-- Jumbotron -->
  <div class="main-container">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="my-5">Shop Smart<br /><span style="color: #488978;">Look Sharp.</span></h1>
                    <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Eveniet, itaque accusantium odio, soluta, corrupti aliquam
                        quibusdam tempora at cupiditate quis eum maiores libero
                        veritatis? Dicta facilis sint aliquid ipsum atque?
                    </p>
                </div>

                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="./loginProcess.php">                    
                                <div class="form-outline mb-4">                                
                                    <label for="username">Username</label>
                                    <input type="text" id="username" name="username" class="form-control" required>
                                </div>

                                <div class="form-outline mb-4">
                                    <label for="password">Password</label>
                                    <input type="password" id="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-success btn-block mb-4">Sign in</button>
                            </form>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</body>
</html>



