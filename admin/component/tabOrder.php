
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 100px auto auto auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 100px;
        }

        h1 {
            text-align: center;
            color: #3498db;
        }

        .order {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }

        .order p {
            padding: 5px 0;
        }

        .order pf {
            font-size: 1.2em;
            font-weight: bold;
        }

        .order pl {
            font-size: 1.2em;
            color: #ffff;
            border-radius: 10px;
        }

        #Paid {
            padding: 3px 8px;
            background-color: #06D6B1;
        }

        #Unpaid {
            padding: 3px 8px;
            background-color: #F0476F;
        }

        #Pending {
            padding: 3px 8px;
            background-color: #FFA500;
            margin-top: 5px;
        }

        #Inprogress {
            padding: 3px 8px;
            background-color: #7c6bff;
            margin-top: 5px;
        }

        #Delivered {
            padding: 3px 8px;
            background-color: #06D6B1;
            margin-top: 5px;
        }

        #Canceled {
            padding: 3px 8px;
            background-color: #F0476F;
            margin-top: 5px;
        }



        .icon-container {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }


        .tab {
            overflow: hidden;
            border: 1px solid #fff;
            /* Added border bottom */
            margin-bottom: 10px;
        }

        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            border-bottom: 2px solid transparent;
            /* Added transparent border */
        }

        .tab button:hover {
            background-color: #ddd;
        }

        .tab button.active {
            background-color: #ccc;
            border-bottom: 2px solid #3498db;
            /* Underline color */
        }

        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }

        .navCon {
            z-index: 100;
            border: 1px solid #333;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>History</h1>
        <!-- Tab buttons -->
        <div class="tab">
            <button id="UnFullFillTab" class="tablinks" onclick="openTab(event, 'UnFullFill')">UnFullFill</button>
            <button class="tablinks" onclick="openTab(event, 'FullFill')">FullFill</button>
        </div>


        <!-- Tab content -->
        <div id="UnFullFill" class="tabcontent">
            <?php includeUnFullFill("SELECT * FROM orders WHERE fullfill_status = 'UnFullFill'"); ?>
        </div>

        <div id="FullFill" class="tabcontent">
            <?php includeFullFill("SELECT * FROM orders WHERE fullfill_status = 'FullFill'"); ?>
        </div>
    </div>
    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
        document.getElementById("UnFullFillTab").click();
    </script>
</body>

</html>

<?php
function includeUnFullFill($query)
{
    $conn =  mysqli_connect("localhost", "root", "", "shopping");
    $msresults = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($msresults)) {

        if ($row['Status'] == 'Unpaid') {
            echo '<div class="order">';
   
            echo "<pf>Invoice ID: {$row['InvID']}</pf>";
            echo "<p>Order Date: {$row['Period']}</p>";
            echo "<p>Total Amount: {$row['TotalPrice']} ฿</p>";
            if ($row['Status'] == 'Paid') {
                echo "<pl id='Paid'>Status: {$row['Status']}</pl>";
            } else {
                echo "<pl id='Unpaid'>Status: {$row['Status']}</pl>";
            }
            echo '</div>';
        }
    }
}

function includeFullFill($query)
{
    include_once '../dbConfig.php';

    $msresults = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($msresults)) {
        echo '<div class="order">';
    
        echo "<pf>Order ID: {$row['RecID']}</pf>";
        echo "<p>Total Amount: {$row['TotalPrice']} ฿</p>";
        echo "<p>Order Date: {$row['OrderDate']}</p>";
        if ($row['DeliveryDate'] != null) {
            echo "<p>Delivery Date: {$row['DeliveryDate']}</p>";
        }
        if ($row['Status'] == 'Pending') {
            echo "<pl id='Pending'>Status: {$row['Status']}</pl>";
        } else if ($row['Status'] == 'Inprogress') {
            echo "<pl id='Inprogress'>Status: {$row['Status']}</pl>";
        } else if ($row['Status'] == 'Delivered') {
            echo "<pl id='Delivered'>Status: {$row['Status']}</pl>";
        } else if ($row['Status'] == 'Canceled') {
            echo "<pl id='Canceled'>Status: {$row['Status']}</pl>";
        }
        echo '</div>';
    }
}
?>