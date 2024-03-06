<nav class="navCon">
    <div class="nav-left">
        <ul>
            <h-li><a <?php echo isActive('index.php'); ?> href="index.php">
                    <img src="./image/logo.png" class="logo" />
                </a></h-li>
            <?php $cartIconClass = (basename($_SERVER['PHP_SELF']) == 'cart.php') ? 'class="active"' : ''; ?>
        </ul>
    </div>

    <ur class="nav-right">
        <?php if (isset($_SESSION['id_username']) && isset($_SESSION['status']) === true) : ?>
            <li><a <?php echo isActive('../history.php'); ?> href="./history.php">History</a></li>
        <?php endif; ?>

        <div class='nav-right-container'> <!-- Add a container div -->
            <div class='cart-icon-container'>
                <a <?php echo $cartIconClass; ?> href='cart.php'>
                    <img class='cart-icon' src='./image/cart.webp' alt='Cart'>
                    <?php if (isset($_SESSION['cart'])) : ?>
                        <?php $cartIconCount = (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) ? count($_SESSION['cart']) : 0; ?>

                    <?php elseif (isset($_SESSION['id_username']) && isset($_SESSION['status']) === true) : ?>
                        <?php
                        $conn =  mysqli_connect("localhost", "root", "", "shopping");
                        $uid = (isset($_SESSION['id_username'])) ? $_SESSION['id_username'] : '';
                        $cur = "SELECT * FROM cart WHERE CusID = '$uid'";
                        $msresults = mysqli_query($conn, $cur);

                        $cartIconCount = (mysqli_num_rows($msresults) > 0) ? mysqli_num_rows($msresults) : 0;
                        mysqli_close($conn);
                        ?>
                    <?php endif; ?>
                    <?php if (!empty($cartIconCount)) : ?>
                        <span class='badge badge-warning' id='lblCartCount'><?php echo $cartIconCount; ?></span>
                    <?php endif; ?>
                </a>
            </div>
            <?php if (isset($_SESSION['id_username']) && isset($_SESSION['status']) && $_SESSION['status'] === true) : ?>
                <div class="user-icon-container">
                    <a class="user-icon" <?php echo isActive('profile.php'); ?> href="profile.php"><img src="./image/userTheme.png"></a>
                </div>
                <li><a <?php echo isActive('logoutProcess.php'); ?> href="logoutProcess.php">Logout</a></li>
            <?php else : ?>
                <li><a <?php echo isActive('login.php'); ?> href="login.php">Login</a></li>
            <?php endif; ?>
        </div> <!-- Close the container div -->

    </ur>
</nav>

<!-- navbar.php -->
<style>
    body {
        body {
            margin-top: 50px;
            /* Adjust margin to avoid content being hidden under the fixed navbar */
        }
    }

    navCon {
        padding: 0;
    }

    nav {
        background-color: rgba(25, 135, 84, 0.95);
        /* Light red color with transparency */
        padding: 10px;
        position: fixed;
        width: 100%;
        height: 10%;
        top: 0;
        display: flex;
        justify-content: space-between;
        /* Added to align items to the left and right */

    }

    .badge {
        position: absolute;
        top: 0;
        right: 25%;
        background-color: #d9534f;
        /* Bootstrap's danger color */
        color: white;
        padding: 3px 8px;
        border-radius: 50%;
        font-size: 12px;
    }
</style>