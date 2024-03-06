<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="<?php echo dirname($_SERVER['PHP_SELF']); ?>/component/CSS/navStyles.css" />
    <title>HomePage</title>
</head>
<body>
    <nav>
        <div class="logo" style="display: flex; align-items: center;">
            <?php $cartIconClass = (basename($_SERVER['PHP_SELF']) == 'cart.php') ? 'class="active"' : ''; ?>
            <span style="color: #FFF; font-size: 26px; font-weight: bold; letter-spacing: 1px; margin-left: 20px;">DEADSTOCK</span>
        </div>

        <ul class="nav-links">
            <?php if (isset($_SESSION['id_username']) && isset($_SESSION['status']) === true) : ?>
                <li><a style="color: #FFF;" <?php echo isActive('../history.php'); ?> href="./history.php">History</a></li>
            <?php endif; ?>

            <li><a <?php echo $cartIconClass; ?> href="cart.php">
                    <img class='cart-icon' src='./image/icons8-cart-96.png' alt='Cart'>
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
                        <span class='badge' id='lblCartCount'><?php echo $cartIconCount; ?></span>
                    <?php endif; ?>
                </a></li>
            <?php if (isset($_SESSION['id_username']) && isset($_SESSION['status']) && $_SESSION['status'] === true) : ?>
                <li><a class="user-icon" <?php echo isActive('profile.php'); ?> href="profile.php"><img src="./image/icons8-customer-90.png"></a></li>
                <li><a style="color: #FFF;" class="login-button" <?php echo isActive('logoutProcess.php'); ?> href="logoutProcess.php">Logout</a></li>
            <?php else : ?>
                <li><a class="login-button" <?php echo isActive('login.php'); ?> href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</body>


<?php
// Function to check and set 'active' class
function isActive($page)
{
    return (basename($_SERVER['PHP_SELF']) == $page) ? 'class="active"' : '';
}
?>