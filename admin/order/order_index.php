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
            /* width: 100%; */
            /* margin-right: auto;
            margin-left: auto; */
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
        .user-row{
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
            SUM(CASE WHEN fullfill_status = 'Unfulfilled' THEN 1 ELSE 0 END) AS unfulfilled_orders,
            SUM(CASE WHEN shipping_status = 'Pending' THEN 1 ELSE 0 END) AS pending_orders,
            SUM(CASE WHEN shipping_status = 'Inprogress' THEN 1 ELSE 0 END) AS inprogress_orders,
            COUNT(*) AS total_orders
        FROM 
            orders";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            // วน loop เพื่อดึงข้อมูลจากแต่ละแถว
            while ($row = mysqli_fetch_assoc($result)) {
    ?>

    <div class="row py-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Unfulfilled Orders</h5>
                    <p class="card-text"><?php echo $row['unfulfilled_orders']; ?></p>
                </div>
            </div>
        </div>
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
    </div>

    <?php
            } // ปิดวงเล็บของ while
        } // ปิดวงเล็บของ if
    ?>





    <div class="container-1">
        
        <!-- <div>
            <input type='checkbox' id='checkAll' onchange='checkAll()'>
            <label class="check-all-label">Check All</label>
            <form id='deleteForm' class="delete-form" action='order_delete_confirm.php' method='post'>
                <input type='hidden' name='list_id_order' id='selectedValues' value=''>
                <input type='hidden' name='total_id_order' id='selectedTotal' value=''>
                <input type='submit' id='deleteButton' value='Delete Order' disabled />
            </form>

        </div> -->
        <!-- <div>            -->
            <!-- <div class="tab">
                <form action="order_index.php" method="get">
                    <button id="UnFullFillTab" class="tablinks" name="tab" value="Unfulfilled">UnFullFill</button>
                    <button id="FullFillTab" class="tablinks" name="tab" value="Fullfilled">FullFill</button>
                </form>
            </div> -->
        <!-- </div> -->
        <div>
            <!------------- Fillter ------------------->
            <label for="filter">Filter by Name:</label>
            <input type="text" name="filter" id="filter" placeholder="Enter name to filter">
            <!------------------------------------------>
            <?php
                if($_SESSION['admin'] !== 'user_admin'){
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
                <option value="">All</option>
                <option value="Pending">Pending</option>
                <option value="Inprogress">Inprogress</option>
                <option value="Delivered">Delivered</option>
                <option value="Canceled">Canceled</option>
            </select>
            <!------------------------------------------>
            <!------------- Filter by Payment Status ------------------->
            <label for="filterPayment">Filter by Payment Status:</label>
            <select name="filter" id="filterDropdown-2" onchange="updateTable()">
                <option value="">All</option>
                <option value="Unfullfilled">Unfullfilled</option>
                <option value="Fullfilled">Fullfilled</option>
            </select>
            <!------------------------------------------>
            <br>
        </div>
    </div>

    <?php


    // if (isset($_GET['tab']) && !empty($_GET['tab'])) {
       
    //     // Define the tab variable
    //     $tab = $_GET['tab'];
    //      $tab = $_GET['tab'];
    //     // Check if the tab is 'Fullfilled'
    //     if ($tab === 'Fullfilled') {
    //         // Query to select orders with 'Fullfilled' status
    //         $cur = "SELECT * FROM orders 
    //                 INNER JOIN customer ON customer.CusID = orders.CusID
    //                 INNER JOIN order_details ON order_details.order_id = orders.order_id
    //                 INNER JOIN image_slip ON image_slip.image_slip_id = orders.image_slip_id
    //                 WHERE orders.fullfill_status = 'Fullfilled'";
    //         $msresults = mysqli_query($conn, $cur);
    //         echo "<center>";
    //         echo "<div>
    //         <table>
    //             <tr>               
    //                 <th>Order ID</th>
    //                 <th>Customer</th>
    //                 <th>Amount</th>
    //                 <th>Order Date</th>
    //                 <th>Delivery Date</th>
    //                 <th>Delivery Status</th>
    //                 <th>Payment Status</th>
    //                 <th>Action</th>
    //             </tr>";

    //     } else if($tab === 'Unfulfilled'){
    //         // Query to select orders with 'Unfullfilled' status by default
    //         $cur = "SELECT * FROM orders 
    //                 INNER JOIN customer ON customer.CusID = orders.CusID
    //                 INNER JOIN order_details ON order_details.order_id = orders.order_id
    //                 INNER JOIN image_slip ON image_slip.image_slip_id = orders.image_slip_id
    //                 WHERE orders.fullfill_status = 'Unfulfilled'";
    //         $msresults = mysqli_query($conn, $cur);
    //         echo "<center>";
    //         echo "<div>
    //             <table>
    //                 <tr>               
    //                     <th>Order ID</th>
    //                     <th>Customer</th>
    //                     <th>Amount</th>
    //                     <th>Order Date</th>           
    //                     <th>Payment Status</th>
    //                     <th>Slip</th>
    //                     <th>Action</th>
    //                 </tr>";
            
    //     } else {
    //         $msresults = 0;
    //     }
    // }
    // else {
    //     // Default query to select orders with 'Unfullfilled' status
    //     $cur = "SELECT * FROM orders 
    //             INNER JOIN customer ON customer.CusID = orders.CusID
    //             INNER JOIN order_details ON order_details.order_id = orders.order_id
    //             INNER JOIN image_slip ON image_slip.image_slip_id = orders.image_slip_id
    //             WHERE orders.fullfill_status = 'Unfulfilled'";
    //     $msresults = mysqli_query($conn, $cur);     
    //     echo "<center>";
    //     echo "<div>
    //         <table>
    //             <tr>               
    //                 <th>Order ID</th>
    //                 <th>Customer</th>
    //                 <th>Amount</th>
    //                 <th>Order Date</th>           
    //                 <th>Payment Status</th>
    //                 <th>Slip</th>
    //                 <th>Action</th>
    //             </tr>";   
    // }

    $cur = "SELECT * FROM orders 
    INNER JOIN customer ON customer.CusID = orders.CusID
    INNER JOIN order_details ON order_details.order_id = orders.order_id
    INNER JOIN image_slip ON image_slip.image_slip_id = orders.image_slip_id";
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
                <th>Slip</th>
                <th>Action</th>
            </tr>";


    
    $index = 1;
    if (mysqli_num_rows($msresults) > 0) {
        while ($row = mysqli_fetch_array($msresults)) {
            echo "<tr class='user-row'>
                    <td>{$row['order_id']}</td>
                    <td>{$row['CusFName']} {$row['CusLName']}</td>
                    <td>{$row['total_price']}</td>
                    <td>{$row['order_date']}</td>";
    
            // Check if fullfill_status is Fullfilled
            // if($row['fullfill_status'] === 'Fullfilled'){
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
                $shipping_statusCompare = ['Pending', 'Inprogress', 'Delivered', 'Canceled'];
                foreach ($shipping_statusCompare as $value) {
                    $selected = ($value == $row['shipping_status']) ? 'selected' : '';
                    echo "<option value='$value' style='background-color: #ffff; color: black;' $selected>{$value}</option>";
                }
                echo "</select>";
    
                echo "</div></td>";
            // } 
            
    
            /* Payment Status */
            echo "<td>";
            echo "<div style='border-radius:10px; padding: 3.920px 7.280px; width:120px; margin: 0 auto; background-color:";
    
            // Conditions to set background color based on fullfill_status value
            if ($row['fullfill_status'] == 'Unfullfilled') {
                echo '#FFA500;';
            } elseif ($row['fullfill_status'] == 'Fullfilled') {
                echo '#06D6B1;';
            } else {
                echo '#06D6B1;'; 
            }
            echo "'>";
            echo "<select id='select_$index' data-order_id='{$row['order_id']}' style='background-color: inherit; color: #ffff;' required>";
    
            // Options for fullfill_status
            $shipping_statusCompare = ['Unfullfilled' , 'Fullfilled'];
            foreach ($shipping_statusCompare as $value) {
                $selected = ($value == $row['fullfill_status']) ? 'selected' : '';
                echo "<option value='$value' style='background-color: #ffff; color: black;' $selected>{$value}</option>";
            }
            echo "</select>";
            echo "</div></td>";
    
            // Check if fullfill_status is Unfullfilled
            // if ($row['fullfill_status'] == 'Unfulfilled') {
                echo "<td>";
                // Check if the image_data is not empty
                if (!empty($row['image_data'])) {
                    // Output the image as a clickable link to open modal
                    echo "<a href='#imageModal' data-toggle='modal' data-image-src='data:image/*;base64," . base64_encode($row['image_data']) . "'><img class='product-image' src='data:image/*;base64," . base64_encode($row['image_data']) . "'></a>";
                } else {
                    // Output a placeholder or empty cell if image_data is empty
                    echo "-";
                }
                echo "</td>";
            // }
    
            echo "<td>
                    <form class='action-button' action='order_update.php' method='post' style='display: inline-block;'>  
                        <input type='hidden' name='id_order' value={$row['order_id']}>
                        <input type='image' alt='update' src='../img/pencil.png'/>
                    </form>
                  </td>
                  </tr>";
            $index++;
        }
    } else {
        echo "<h1>Empty</h1>";
    }
    echo "</table></div>";
    echo "</center>";
    mysqli_close($conn);
    ?>

<!-- Bootstrap JS (Optional, if you need JavaScript components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
        individualCheckboxes[i].addEventListener('change', function () {
            updateDeleteButtonshipping_status(); // Update deleteButton's shipping_status
        });
    }
</script>


<script>
   document.addEventListener('DOMContentLoaded', function () {
    // Loop through all select elements and attach event listeners
    for (var i = 1; i <= <?php echo $index; ?>; i++) {
        var selectElement = document.getElementById('select_' + i);

        if (selectElement) {
            selectElement.addEventListener('change', function () {
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

                console.log(order_id , selectedValue )
                // Update the shipping_status using AJAX
                updateshipping_status(order_id, selectedValue);
            });
        }
    }

    function updateshipping_status(order_id, newshipping_status) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'order_update_status.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        console.log(order_id , newshipping_status )

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.shipping_status === 200) {
                    // Handle successful response
                    console.log('shipping_status updated successfully');
                } else {
                    // Handle error
                    console.error('Error updating shipping_status');
                }
            }
        };
        xhr.send('order_id=' + encodeURIComponent(order_id) + '&newshipping_status=' + encodeURIComponent(newshipping_status));

    }
});
</script>

<script>
    function openTab(event, tabName) {
        // Prevent the default action of the button
        event.preventDefault();
        // Set the active tab to the clicked tab
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
        event.currentTarget.className += " active";

        // Send the tab value via GET method to the URL
        window.location.href = window.location.pathname + "?tab=" + tabName;
    }
    // function checkAll() {
    //     var checkboxes = document.getElementsByName('checkbox[]');
    //     var checkAllCheckbox = document.getElementById('checkAll');
    //     var deleteButton = document.getElementById('deleteButton');
    //     var checkboxValues = [];

    //     for (var i = 0; i < checkboxes.length; i++) {
    //         checkboxes[i].checked = checkAllCheckbox.checked;
    //         if (checkboxes[i].checked) {
    //             checkboxValues.push(checkboxes[i].value);
    //         }
    //     }

    //     // Join values into a comma-separated string
    //     document.getElementById('deleteForm').elements['selectedValues'].value = checkboxValues.join(',');
    //     var checkedCheckboxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);
    //     var enableDeleteButton = checkedCheckboxes.length > 0;

    //     deleteButton.disabled = !enableDeleteButton;
    // }

    /* Fillter */
    /* Fillter */
    function updateTable(filterKeyword) {
    // AJAX request to fetch filtered data
        $.ajax({
            type: 'GET',
            url: 'filter_data.php',
            data: { filterKeyword: filterKeyword },
            success: function(data) {
                // Replace the table content with the filtered data
                $('#logTable').html(data); // Ensure that the id matches your table id
            },
            error: function(error) {
                console.error('Error fetching filtered data:', error);
            }
        });
    }

    // Listen for input event on the filter input
    $('#filter').on('input', function() {
        // Get the value of the filter input
        var filterKeyword = $(this).val();

        // Update the table based on the filter keyword
        updateTable(filterKeyword);
    });

    $('#filterDropdown-1').on('change', function() {
        // Get the value of the filter input
        var filterKeyword = $(this).val();

        console.log(filterKeyword);

        // Update the table based on the filter keyword
        updateTable(filterKeyword);
    }); 

    $('#filterDropdown-2').on('change', function() {
        // Get the value of the filter input
        var filterKeyword = $(this).val();

        console.log(filterKeyword);

        // Update the table based on the filter keyword
        updateTable(filterKeyword);
    }); 
//     function filterTable() {
//     var filterShipping = document.getElementById("filterShipping").value;
//     var filterPayment = document.getElementById("filterPayment").value;

//     console.log(filterPayment);
//     console.log(filterShipping);

//     var tableRows = document.querySelectorAll('.user-row');

//     console.log(tableRows);

//     tableRows.forEach(function (row) {
//         var shippingStatusCell = row.querySelectorAll('td')[5].getAttribute('data-order_status');
//         var paymentStatusCell = row.querySelectorAll('td')[6].getAttribute('data-order_status');

//         console.log(shippingStatusCell);

//         var matchShipping = filterShipping === '' || shippingStatusCell === filterShipping;
//         var matchPayment = filterPayment === '' || paymentStatusCell === filterPayment;

//         if (matchShipping && matchPayment) {
//             row.style.display = 'table-row';
//         } else {
//             row.style.display = 'none';
//         }
//     });
// }
</script>


</body>
</html>
