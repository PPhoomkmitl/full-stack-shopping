<!-- navbar.php -->
<style>
    body {
        margin: 0;
        padding: 0;
    }

    navCon {
        padding: 0;
    }

    nav {
        background-color: rgba(25, 135, 84, 0.9);
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

    .nav-right {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 3%;
        position: relative;
        /* Added relative positioning */
    }

    ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }

    h-li {
        float: left;
        width: 200px;
        height: 50px;
    }

    h-li a {
        font-size: larger;
        display: block;
        color: rgba(40, 40, 40, 1);
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        transition: background-color 0.25s ease;
        /* Smooth transition effect */
        position: relative;
        /* Added for absolute positioning of the underline */
    }

    h-li a:hover {
        color: rgba(0, 0, 0, 1);
        /* Full red color on hover */
        text-decoration: underline;
    }

    /* Style for the clicked link */
    h-li a.active {
        text-decoration: underline;
        font-weight: bold;
        /* Bootstrap's danger color */
    }

    h-li a.active::after {
        content: '';
        /* Create a pseudo-element for the underline */
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 2px;
        /* Underline thickness */
        background-color: white;
        /* Underline color */
    }

    li {
        float: left;
        width: 150px;
        height: 50px;
    }

    li a {
        display: block;
        color: rgba(40, 40, 40, 1);
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        transition: background-color 0.25s ease;
        /* Smooth transition effect */
        position: relative;
        /* Added for absolute positioning of the underline */
    }

    li a:hover {
        color: rgba(0, 0, 0, 1);
        /* Full red color on hover */
        text-decoration: underline;
    }

    /* Style for the clicked link */
    li a.active {
        background-color: #999;
        border-radius: 50%;
        justify-content: center;
        display: flex;
        /* Bootstrap's danger color */
        text-decoration: underline;
        font-weight: bold;
        /* Move text-decoration to this selector */
    }

    li a.active::after {
        content: '';
        /* Create a pseudo-element for the underline */
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 2px;
        /* Underline thickness */
        background-color: white;
        /* Underline color */
    }


    body {
        margin-top: 50px;
        /* Adjust margin to avoid content being hidden under the fixed navbar */
    }

    .nav-left {
        display: flex;
        align-items: center;
        margin-left: 2%;

    }

    .nav-left img {
        width: 50px;
        /* ปรับขนาดของ logo ตามต้องการ */
        height: auto;
    }

    .nav-left h-li {
        width: auto;
        padding: 10%;
        /* ปรับขนาดของพื้นที่สำหรับ logo ให้ข้อความไม่ค้างกัน */
    }


    .nav-right {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-right: 3%;
    }


    .nav-right li a {
        font-size: larger;
        display: block;
        color: rgba(40, 40, 40, 1);
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        transition: background-color 0.25s ease;
        position: relative;
    }

    .nav-right li a:hover {
        color: rgba(0, 0, 0, 1);
        text-decoration: underline;
    }

    .nav-right li a.active {
        text-decoration: underline;
        font-weight: bold;
    }

    .nav-right li a.active::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 2px;
        background-color: white;
    }

    .cart-icon {
        margin-top: -25%;
        width: 100%;
        height: 80%;
        justify-content: center;
        transition: transform 0.3s;
    }

    .cart-icon:hover {
        transform: scale(1.25);
    }

    .user-icon-container,
    .search-icon-container {
        position: relative;
        /* border: 1px solid #000; */
        width: 7%;
    }
    .cart-icon-container{
        width: 10%;
        position: relative;
    }

    .user-icon {
        justify-content: center;
        transition: transform 0.3s;
    }

    .user-icon img {
        width: 50%;
        height: 50%;
    }

    .user-icon:hover {
        transform: scale(1.25);
    }


    .search-icon {
        justify-content: center;
        transition: transform 0.3s;
    }

    .search-icon img {
        width: 50%;
        height: 50%;
    }

    .search-icon:hover {
        transform: scale(1.25);
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

<nav class="navCon">
    <div class="nav-left">
        <ul>
            <h-li><a <?php echo isActive('index.php'); ?> href="index.php">
                    <img src="./image/logo.png" class="logo" />
                </a></h-li>
            <?php $cartIconClass = (basename($_SERVER['PHP_SELF']) == 'cart.php') ? 'class="active"' : ''; ?>
        </ul>
    </div>

    <ul class="nav-right">
        <?php if (isset($_SESSION['id_username']) && isset($_SESSION['status']) === true) : ?>
            <li><a <?php echo isActive('../history.php'); ?> href="./history.php">History</a></li>
        <?php endif; ?>
        <div class='cart-icon-container'>
            <a <?php echo $cartIconClass; ?> href='cart.php'>
                <img class='cart-icon' src='./image/cart.webp' alt='Cart'>
                <?php if (isset($_SESSION['cart'])) : ?>
                    <?php $cartIconCount = (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) ? count($_SESSION['cart']) : 0; ?>

                <?php elseif (isset($_SESSION['id_username']) && isset($_SESSION['status']) === true) : ?>
                    <?php
                    // echo 'TEST123456';
                    $conn=  mysqli_connect("localhost", "root", "", "shopping");
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
            <li class="user-icon-container">
                <a class="user-icon" <?php echo isActive('profile.php'); ?> href="profile.php"><img src="./image/userTheme.png"></a>
            </li>
            <li><a <?php echo isActive('logoutProcess.php'); ?> href="logoutProcess.php">Logout</a></li>

        <?php else : ?>
            <li><a <?php echo isActive('login.php'); ?> href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>
<?php
// Function to check and set 'active' class
function isActive($page)
{
    return (basename($_SERVER['PHP_SELF']) == $page) ? 'class="active"' : '';
}
?>