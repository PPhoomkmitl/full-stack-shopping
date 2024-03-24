<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
</head>
<?php include_once '../dbConfig.php'; ?>
<style>
    nav {
        height: 4.5rem;
        width: 100vw;
        background-color: rgba(25, 135, 84, 0.97);
        box-shadow: 0 3px 20px rgba(0, 0, 0, 0.2);
        position: fixed;
        z-index: 100;
    }

    .logo {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%; /* เพิ่มความสูงเท่ากับความสูงของ nav */
    }
</style>
<body>
    <nav>
        <div class="logo">
            <?php $cartIconClass = (basename($_SERVER['PHP_SELF']) == 'cart.php') ? 'class="active"' : ''; ?>
            <a href="index.php" style="text-decoration: none; color: inherit;">
                <span style="color: #FFF; font-size: 26px; font-weight: bold; letter-spacing: 1px;">DEADSTOCK</span>
            </a>
        </div>
    </nav>
</body>


<?php
// Function to check and set 'active' class
function isActive($page)
{
    return (basename($_SERVER['PHP_SELF']) == $page) ? 'class="active"' : '';
}
?>
