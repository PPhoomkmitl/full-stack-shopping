<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Order List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        .container {
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

    </style>
</head>

<body>
    <div class="navbar"> <?php include('../navbar/navbarAdmin.php') ?></div>
    <h1>Order List</h1>
    <div class="container">
        <div>
            <input type='checkbox' id='checkAll' onchange='checkAll()'>
            <label class="check-all-label">Check All</label>
            <form id='deleteForm' class="delete-form" action='order_delete_confirm.php' method='post'>
                <input type='hidden' name='list_id_order' id='selectedValues' value=''>
                <input type='hidden' name='total_id_order' id='selectedTotal' value=''>
                <input type='submit' id='deleteButton' value='Delete Order' disabled />
            </form>

        </div>
        <div>
            <!------------- Fillter ------------------->
            <label for="filter">Filter by Name:</label>
            <input type="text" name="filter" id="filter" placeholder="Enter name to filter">
            <!------------------------------------------>
            <form class="add-order-form" action='order_insert.php' method='post'>
                <input type='submit' id="addOrderButton" value='Add Order'/>
            </form>
            <br>
        </div>
    </div>

    <?php
    $cx =  mysqli_connect("localhost", "root", "", "shopping");
    $cur = "SELECT * FROM receive 
    INNER JOIN customer ON customer.CusID = receive.CusID
    INNER JOIN receive_detail ON receive_detail.RecID = receive.RecID";
    $msresults = mysqli_query($cx, $cur);

    echo "<center>";
    echo "<div>
        <table>
            <tr>  
                <th></th>                   
                <th>Order ID</th>
                <th>Customer</th>
                <th>Amount</th>
                <th>Order Date</th>
                <th>Delivery Date</th>
                <th>Delivery Status</th>
                <th>Action</th>
            </tr>";

    $index = 1;
    if (mysqli_num_rows($msresults) > 0) {
        while ($row = mysqli_fetch_array($msresults)) {

            echo "<tr class='user-row'>
                    <td><input type='checkbox' name='checkbox[]' value='{$row['RecID']}'></td>
                    <td>{$row['RecID']}</td>
                    <td>{$row['CusFName']} {$row['CusLName']}</td>
                    <td>{$row['TotalPrice']}</td>
                    <td>{$row['OrderDate']}</td>
                    <td>{$row['DeliveryDate']}</td>";


                    echo "<td>";
                    echo "<div style='border-radius:10px; padding: 3.920px 7.280px; width:90px; margin: 0 auto; background-color:";

                    // เงื่อนไขตรวจสอบค่า Status และกำหนดสีให้กับ background-color
                    if ($row['Status'] == 'Pickups') {
                        echo '#0176FF;';
                    } elseif ($row['Status'] == 'Pending' || $row['Status'] == 'pending') {
                        echo '#FFA500;';
                    } elseif ($row['Status'] == 'Inprogress') {
                        echo '#7C6BFF;'; 
                    } elseif ($row['Status'] == 'Canceled') {
                        echo '#FF0000;';
                    } else {
                        echo '#06D6B1;'; 
                    }

                    echo "'>";
                    echo "<select id='select_$index' data-recid='{$row['RecID']}' style='background-color: inherit; color: #ffff;' required>";

                    $statusCompare = ['Pending', 'Inprogress', 'Delivered', 'Canceled'];

                    foreach ($statusCompare as $value) {
                        $selected = ($value == $row['Status']) ? 'selected' : '';
 

                        echo "<option value='$value' style='background-color: #ffff; color: black;' $selected>{$value}</option>";
                    }

                    echo "</select>";
                    echo "</div></td>";


                  
            echo    "<td>
                        <form class='action-button' action='order_update.php' method='post' style='display: inline-block;'>  
                            <input type='hidden' name='id_order' value={$row['RecID']}>
                            <input type='image' alt='update' src='../img/pencil.png'/>
                        </form>
                        <form class='action-button' action='order_delete_confirm.php' method='post' style='display: inline-block;'>
                            <input type='hidden' name='total_id_order' value={$row['RecID']}>
                            <input type='image' alt='delete' src='../img/trash.png'/>
                        </form>
                    </td>
                </tr>";
            $index++;
        }
    }

    echo "</table></div>";
    echo "</center>";
    mysqli_close($cx);
    ?>
<script>
    function updateDeleteButtonStatus() {
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
            updateDeleteButtonStatus(); // Update deleteButton's status
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
                var recID = this.getAttribute('data-recid');

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

                console.log(recID , selectedValue )
                // Update the status using AJAX
                updateStatus(recID, selectedValue);
            });
        }
    }

    function updateStatus(recID, newStatus) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'order_update_status.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        console.log(recID , newStatus )

        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Handle successful response
                    console.log('Status updated successfully');
                } else {
                    // Handle error
                    console.error('Error updating status');
                }
            }
        };
        xhr.send('recID=' + encodeURIComponent(recID) + '&newStatus=' + encodeURIComponent(newStatus));

    }
});
</script>
<script>
    function checkAll() {
        var checkboxes = document.getElementsByName('checkbox[]');
        var checkAllCheckbox = document.getElementById('checkAll');
        var deleteButton = document.getElementById('deleteButton');
        var checkboxValues = [];

        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = checkAllCheckbox.checked;
            if (checkboxes[i].checked) {
                checkboxValues.push(checkboxes[i].value);
            }
        }

        // Join values into a comma-separated string
        document.getElementById('deleteForm').elements['selectedValues'].value = checkboxValues.join(',');
        var checkedCheckboxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);
        var enableDeleteButton = checkedCheckboxes.length > 0;

        deleteButton.disabled = !enableDeleteButton;
    }

    /* Fillter */
    function updateTable(filterKeyword) {
            var tableRows = document.querySelectorAll('.user-row');

            tableRows.forEach(function (row) {
                var containsKeyword = false;

                // Loop through all columns (td elements) in the current row
                row.querySelectorAll('td').forEach(function (cell, index) {
                    var cellText = cell.innerText.toLowerCase();

                    // Check if the cell contains the filter keyword (string comparison)
                    if (cellText.includes(filterKeyword.toLowerCase())) {
                        containsKeyword = true;
                        return; // Break out of the loop if the keyword is found in any cell
                    }

                    // Check if the cell contains the filter keyword as a number
                    var cellNumber = parseFloat(cellText);
                    var filterNumber = parseFloat(filterKeyword);

                    if (!isNaN(cellNumber) && !isNaN(filterNumber) && cellNumber === filterNumber) {
                        containsKeyword = true;
                        return; // Break out of the loop if the numeric values match
                    }
                });

                // Display or hide the row based on the keyword presence
                if (containsKeyword) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
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
</script>
</body>
</html>
