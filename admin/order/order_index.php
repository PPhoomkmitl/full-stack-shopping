<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Order List</title>
    <!-- Bootstrap CSS -->
    <link href="path/to/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="path/to/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        .container-1 {
            display: flex;
            justify-content: space-between;
            background-color: #C4DFDF;
            padding: 20px;

        }

        .check-all-label {
            display: inline-block;
            margin-right: 5px;
        }

        .delete-form {
            display: inline-block;
            margin-right: 5px;
        }

        #deleteButton {
            background-color: #ef476f;
            color: #fff;
            padding: 5px 10px;
            border: none;
        }

        #deleteButton:hover {
            background-color: #d64161;
        }

        .add-order-form {
            display: inline-block;
            margin-right: 5px;
        }

        #addOrderButton {
            background-color: #4b93ff;
            color: #fff;
            padding: 5px 10px;
            border: none;
        }

        #addOrderButton:hover {
            background-color: #3a75c4;
        }

        table {
            width: 100%;
            margin: auto;
            text-align: center;
            border-collapse: collapse;
            border: 1px solid #ccc;
        }

        th {
            background-color: #D2E9E9;
            padding: 10px;
        }

        td {
            background-color: #E3F4F4;
            padding: 5px;
            /* border-bottom: 1px solid #ccc; */
        }

        .user-row {
            border-bottom: 1px solid #C4DFDF;
        }

        .action-buttons {
            display: flex;
            justify-content: space-around;
        }

        .action-button {
            display: inline-block;
        }

        .action-button input[type='image'] {
            width: 30px;
            height: 30px;
        }

        .navbar {
            margin-top: 100px;
        }

        select {
            border: none;
        }

        .product-image {
            width: 40px;
            height: 40px;
        }
    </style>
</head>

<body>
    <div class="navbar"> <?php include('../navbar/navbarAdmin.php') ?></div>
    <h1>Order List</h1>
    <?php
    include_once '../../dbConfig.php';
    $sql = "SELECT 
            SUM(CASE WHEN fullfill_status = 'Unfulfilled' THEN 1 ELSE 0 END) AS Unfulfilled_orders,
            SUM(CASE WHEN shipping_status = 'Pending' THEN 1 ELSE 0 END) AS pending_orders,
            SUM(CASE WHEN shipping_status = 'Inprogress' THEN 1 ELSE 0 END) AS inprogress_orders,
            SUM(CASE WHEN shipping_status = 'Canceled' THEN 1 ELSE 0 END) AS cancel_orders,
            COUNT(*) AS total_orders
        FROM 
            orders";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {

        while ($row = mysqli_fetch_assoc($result)) {
    ?>

            <div class="row py-3 px-3 justify-content-between">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pending Orders</h5>
                            <p class="card-text"><?php echo $row['pending_orders']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Inprogress Orders</h5>
                            <p class="card-text"><?php echo $row['inprogress_orders']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Total Orders</h5>
                            <p class="card-text"><?php echo $row['total_orders']; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Canceled Orders</h5>
                            <p class="card-text"><?php echo $row['cancel_orders']; ?></p>
                        </div>
                    </div>
                </div>
            </div>

    <?php
        }
    }
    ?>





    <div class="container-1">
        <div>
            <!------------- Fillter ------------------->
            <label for="filter">Filter by Name:</label>
            <input type="text" name="filter" id="filter" placeholder="Enter name to filter">
            <!------------------------------------------>
            <?php
            if ($_SESSION['admin'] !== 'user_admin') {
                echo '<form class="add-order-form" action="order_insert.php" method="post">
                        <input type="submit" id="addOrderButton" value="Add Order"/>
                    </form>';
            }
            ?>
            <br>
        </div>

        <div>
            <!------------- Filter by Shipping Status ------------------->
            <label for="filterShipping">Filter by Shipping Status:</label>
            <select name="filter" id="filterDropdown-1" onchange="updateTable()">
                <option value="All">All</option>
                <option value="Unfulfilled">Unfulfilled</option>
                <option value="Inprogress">Inprogress</option>
                <option value="Delivered">Delivered</option>
                <option value="Canceled">Canceled</option>
            </select>
        </div>
    </div>

    <?php



    $cur = "SELECT * 
    FROM orders 
    INNER JOIN customer ON customer.CusID = orders.CusID
    INNER JOIN image_slip ON image_slip.image_slip_id = orders.image_slip_id
    WHERE orders.fullfill_status = 'Fulfilled' 
    UNION
    SELECT * 
    FROM orders 
    INNER JOIN customer ON customer.CusID = orders.CusID
    INNER JOIN image_slip ON image_slip.image_slip_id = orders.image_slip_id
    WHERE orders.shipping_status = 'Canceled'";
    $msresults = mysqli_query($conn, $cur);

    echo "<center>";
    echo "<div>
        <table id='logTable'>
            <tr>               
                <th>Order ID</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Order Date</th>
                <th>Delivery Date</th>
                <th>Delivery Status</th>
                <th>Payment Status</th>
            </tr>";

    $index = 1;
    if (mysqli_num_rows($msresults) > 0) {
        while ($row = mysqli_fetch_array($msresults)) {
            echo "<tr class='user-row'>
                    <td>{$row['order_id']}</td>
                    <td>{$row['CusFName']} {$row['CusLName']}</td>
                    <td>{$row['total_price']}</td>
                    <td>{$row['order_date']}</td>";

            // Check if fullfill_status is Fulfilled
            // if($row['fullfill_status'] === 'Fulfilled'){
            echo "<td>{$row['delivery_date']}</td>";

            /* Shipping Status */
            echo "<td>";
            echo "<div style='border-radius:10px; padding: 3.920px 7.280px; width:120px; margin: 0 auto; background-color:";

            // Conditions to set background color based on shipping_status value
            if ($row['shipping_status'] == 'Pickups') {
                echo '#0176FF;';
            } elseif ($row['shipping_status'] == 'Pending' || $row['shipping_status'] == 'pending') {
                echo '#FFA500;';
            } elseif ($row['shipping_status'] == 'Inprogress') {
                echo '#7C6BFF;';
            } elseif ($row['shipping_status'] == 'Canceled') {
                echo '#FF0000;';
            } else {
                echo '#06D6B1;';
            }
            echo "'>";

            // Dropdown select for shipping_status
            echo "<select id='select_$index' data-order_id='{$row['order_id']}' style='background-color: inherit; color: #ffff;' required>";

            // Options for shipping_status
            if ($_SESSION['admin'] == 'super_admin') {
                $shipping_statusCompare = ['Pending', 'Inprogress', 'Delivered', 'Canceled'];
                foreach ($shipping_statusCompare as $value) {
                    $selected = ($value == $row['shipping_status']) ? 'selected' : '';
                    echo "<option value='$value' style='background-color: #ffff; color: black;' $selected>{$value}</option>";
                }
            } elseif ($_SESSION['admin'] == 'user_admin') {
                // Add your existing code for user_admin here
                $shipping_statusCompare = ['Pending', 'Inprogress', 'Delivered', 'Canceled'];
                foreach ($shipping_statusCompare as $value) {
                    if ($row['shipping_status'] == 'Canceled' && ($value == 'Pending' || $value == 'Inprogress' || $value == 'Delivered')) {
                        echo "<option value='$value' style='background-color: #ffff; color: black;' disabled>{$value}</option>";
                    } else if ($row['shipping_status'] == 'Delivered' && ($value == 'Pending' || $value == 'Inprogress' || $value == 'Canceled')) {
                        echo "<option value='$value' style='background-color: #ffff; color: black;' disabled>{$value}</option>";
                    } else if ($row['shipping_status'] == 'Inprogress' && $value == 'Pending') {
                        echo "<option value='$value' style='background-color: #ffff; color: black;' disabled>{$value}</option>";
                    } else {
                        $selected = ($value == $row['shipping_status']) ? 'selected' : '';
                        echo "<option value='$value' style='background-color: #ffff; color: black;' $selected>{$value}</option>";
                    }
                }
            }

            echo "</select>";

            echo "</div></td>";



            /* Payment Status */
            echo "<td>";
            echo "<div style='border-radius:10px; padding: 3.920px 7.280px; width:120px; margin: 0 auto; background-color:";

            // Conditions to set background color based on fullfill_status value
            if ($row['fullfill_status'] == 'Unfulfilled') {
                echo '#FF6868;';
            } elseif ($row['fullfill_status'] == 'Fulfilled') {
                echo '#06D6B1;';
            } elseif ($row['fullfill_status'] == 'Return' && $row['shipping_status'] == 'Canceled') {
                echo '#FFA500;';
            }
            else {
                echo '#06D6B1;';
            }
            echo "'>";
            echo "<select id='select_payment_$index' data-payment-order_id='{$row['order_id']}' style='background-color: inherit; color: #ffff;' required";


            // Check if the fullfill_status is Fulfilled
            if ($row['fullfill_status'] == 'Fulfilled' && $row['shipping_status'] != 'Canceled') {
                // If it is Fulfilled, add the 'disabled' attribute to disable the select
                echo " disabled";
            }
            elseif ($row['fullfill_status'] == 'Return'){
                echo " disabled";
            }

            echo ">";

            // Options for fullfill_status
            $shipping_statusCompare = ['Unfulfilled', 'Fulfilled', 'Return'];
            foreach ($shipping_statusCompare as $value) {
                $selected = ($value == $row['fullfill_status']) ? 'selected' : '';
                echo "<option value='$value' style='background-color: #ffff; color: black;' $selected>{$value}</option>";
            }
            echo "</select>";
            echo "</div></td>";
            echo "</tr>";
            $index++;
        }
    } else {
        echo "<tr><td colspan='9'>No records found</td></tr>";
    }
    echo "</table></div>";
    echo "</center>";
    mysqli_close($conn);
    ?>

    <!-- Bootstrap JS (Optional, if you need JavaScript components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // AJAX request to fetch filtered data
        function updateTable(filterKeyword) {
            $.ajax({
                type: 'GET',
                url: 'filter_data.php',
                data: {
                    filterKeyword: filterKeyword,
                    index: <?php echo $index; ?>
                },
                success: function(data) {
                    console.log(data);
                    // Replace the table content with the filtered data
                    $('#logTable').html(data); // Ensure that the id matches your table id

                    // Attach event listeners to select elements in the updated table
                    attachEventListenersForShipment();
                    attachEventListenersForPaymentStatus()
                },
                error: function(error) {
                    console.error('Error fetching filtered data:', error);
                }
            });
        }

        $('#filterDropdown-1').on('change', function() {
            // Get the value of the filter input
            var filterKeyword = $(this).val();

            console.log(filterKeyword);

            // Update the table based on the filter keyword
            updateTable(filterKeyword);
        });
    </script>
    <script>
        function attachEventListenersForPaymentStatus() {

            // Loop through all buttons and attach event listeners
            for (var i = 1; i <= <?php echo $index; ?>; i++) {
                var buttonElement = document.getElementById('select_payment_' + i);

                if (buttonElement) {
                    buttonElement.addEventListener('click', function() {
                        var order_id = this.getAttribute('data-payment-order_id');
                        var selectedValue = this.value;

                        console.log(selectedValue);

                        switch (selectedValue) {
                            case 'Unfulfilled':
                                selectDiv.style.backgroundColor = '#FF6868';
                                break;
                            case 'Fulfilled':
                                selectDiv.style.backgroundColor = '#06D6B1';
                                break;
                            case 'Return':
                                selectDiv.style.backgroundColor = '#FFA500';
                                break;
                            default:
                                selectDiv.style.backgroundColor = '#06D6B1';
                        }

                        // Update the fulfillment status using the updateFullfillStatus() function
                        updateFullfillStatus(order_id, selectedValue);

                    });
                }
            }

            // Loop through all buttons and attach event listeners
            for (var i = 1; i <= <?php echo $index; ?>; i++) {
                var buttonElement = document.getElementById('decline_payment_' + i);

                if (buttonElement) {
                    buttonElement.addEventListener('click', function() {
                        var order_id = this.getAttribute('data-payment-order_id');
                        var selectedValue = "Canceled"; // ให้กำหนดค่าตามที่คุณต้องการ

                        // ทำการอัปเดตสถานะการชำระเงินโดยใช้ฟังก์ชัน updateFullfillStatus()
                        updateFullfillStatus(order_id, selectedValue);

                        // สามารถทำอย่างอื่น ๆ ตามที่ต้องการหลังจากอัปเดตสถานะการชำระเงินเสร็จสิ้น
                        // เช่น รีเฟรชหน้าเว็บหรือแสดงข้อความยืนยันการอัปเดตเป็นเรียบร้อย
                    });
                }
            }
        }

        function attachEventListenersForShipment() {
            // Loop through all select elements and attach event listeners
            for (var i = 1; i <= <?php echo $index; ?>; i++) {
                var selectElement = document.getElementById('select_' + i);

                if (selectElement) {
                    selectElement.addEventListener('change', function() {
                        var selectedValue = this.value;
                        var selectDiv = this.parentElement;
                        var order_id = this.getAttribute('data-order_id');

                        switch (selectedValue) {
                            case 'Pending':
                                selectDiv.style.backgroundColor = '#FFA500';
                                break;
                            case 'Inprogress':
                                selectDiv.style.backgroundColor = '#7C6BFF';
                                break;
                            case 'Canceled':
                                selectDiv.style.backgroundColor = '#FF0000';
                                break;
                            default:
                                selectDiv.style.backgroundColor = '#06D6B1';
                        }

                        // Update the shipping_status using AJAX
                        updateshipping_status(order_id, selectedValue);
                    });
                }
            }
        }
    </script>

    <script>
        function updateDeleteButtonshipping_status() {
            var checkboxes = document.getElementsByName('checkbox[]');
            var deleteButton = document.getElementById('deleteButton');
            var selectedValuesInput = document.getElementById('selectedValues');


            var checkedCheckboxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);
            var enableDeleteButton = checkedCheckboxes.length > 0;

            deleteButton.disabled = !enableDeleteButton;

            // Update the hidden input field with the values of checked checkboxes
            var checkboxValues = checkedCheckboxes.map(checkbox => checkbox.value);
            selectedValuesInput.value = checkboxValues.join(',');
        }

        var individualCheckboxes = document.getElementsByName('checkbox[]');
        for (var i = 0; i < individualCheckboxes.length; i++) {
            individualCheckboxes[i].addEventListener('change', function() {
                updateDeleteButtonshipping_status(); // Update deleteButton's shipping_status
            });
        }
    </script>

    <script>
        // Function to update shipping status
        function updateshipping_status(order_id, newshipping_status) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'order_update_status_shipping.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Handle successful response
                        console.log('Shipping status updated successfully');
                    } else {
                        // Handle error
                        console.error('Error updating shipping status');
                    }
                }
            };
            xhr.send('order_id=' + encodeURIComponent(order_id) + '&new_shipping_status=' + encodeURIComponent(newshipping_status));
        }


        document.addEventListener('DOMContentLoaded', function() {
            for (var i = 1; i <= <?php echo $index; ?>; i++) {
                var buttonElement = document.getElementById('select_payment_' + i);
                if (buttonElement) {
                    buttonElement.addEventListener('click', function() {
                    var order_id = this.getAttribute('data-payment-order_id');
                    var selectedValue = this.value;

                    console.log(selectedValue);

                    switch (selectedValue) {
                        case 'Unfulfilled':
                            selectDiv.style.backgroundColor = '#FF6868';
                            break;
                        case 'Fulfilled':
                            selectDiv.style.backgroundColor = '#06D6B1';
                            break;
                        case 'Return':
                            selectDiv.style.backgroundColor = '#FFA500';
                            break;
                        default:
                            selectDiv.style.backgroundColor = '#06D6B1';
                        }

                        // Update the fulfillment status using the updateFullfillStatus() function
                        updateFullfillStatus(order_id, selectedValue);

                    });
                }
            }
        });



        // Function to update fullfill status
        function updateFullfillStatus(order_id, newfullfill_status) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'order_update_status_fulfill.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Handle successful response
                        console.log('Fullfill status updated successfully');
                    } else {
                        // Handle error
                        console.error('Error updating fullfill status');
                    }
                }
            };
            xhr.send('order_id=' + encodeURIComponent(order_id) + '&newfullfill_status=' + encodeURIComponent(newfullfill_status));
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Loop through all select elements and attach event listeners
            for (var i = 1; i <= <?php echo $index; ?>; i++) {
                var selectElement = document.getElementById('select_' + i);

                if (selectElement) {
                    selectElement.addEventListener('change', function() {
                        var selectedValue = this.value;
                        var selectDiv = this.parentElement;
                        var order_id = this.getAttribute('data-order_id');

                        switch (selectedValue) {
                            case 'Pending':
                                selectDiv.style.backgroundColor = '#FFA500';
                                break;
                            case 'Inprogress':
                                selectDiv.style.backgroundColor = '#7C6BFF';
                                break;
                            case 'Canceled':
                                selectDiv.style.backgroundColor = '#FF0000';
                                break;
                            default:
                                selectDiv.style.backgroundColor = '#06D6B1';
                        }

                        // Update the shipping_status using AJAX
                        updateshipping_status(order_id, selectedValue);
                    });
                }
            }

            function updateshipping_status(order_id, newshipping_status) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'order_update_status_shipping.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Handle successful response
                            console.log('Shipping status updated successfully');
                        } else {
                            // Handle error
                            console.error('Error updating shipping status');
                        }
                    }
                };
                xhr.send('order_id=' + encodeURIComponent(order_id) + '&new_shipping_status=' + encodeURIComponent(newshipping_status));
            }
        });
    </script>


</body>

</html>