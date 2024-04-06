<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>invoice List</title>
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

        .add-invoice-form {
            display: inline-block;
            margin-right: 5px;
        }

        #addInvoiceButton {
            background-color: #4b93ff;
            color: #fff;
            padding: 5px 10px;
            border: none;
        }

        #addInvoiceButton:hover {
            background-color: #3a75c4;
        }

        table {
            width: 100%;
            margin: auto;
            text-align: center;
            border-collapse: collapse;
            border: 1px solid #C4DFDF;
        }

        th {
            background-color: #D2E9E9;
            padding: 10px;
        }

        td {
            padding: 5px;
        }

        .user-row {
            background-color: #E3F4F4;
            border: 1px solid #C4DFDF;
        }

        .navbar {
            margin-top: 100px;
        }

        .action-button {
            display: inline-block;
        }

        .action-button input[type='image'] {
            width: 30px;
            height: 30px;
        }

        select {
            border: none;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;

        }

        .action-buttons h1 {
            margin-top: 0px;
            margin-bottom: 10px;

        }


        .action-button {
            display: inline-block;

        }

        .action-button button[type='submit'] {
            background-color: #364856;
            cursor: pointer;
            border: none;
            width: 73px;
            height: 33px;
        }

        .action-button button[type='submit']:hover {
            background-color: #9BA4AB;
            cursor: pointer;
            border: none;
            width: 73px;
            height: 33px;
        }

        .action-button img {
            width: 20px;
            height: 20px;
        }
    </style>
</head>

<body>
    <div class="navbar"> <?php include('../navbar/navbarAdmin.php') ?></div>
    <h1>Invoice List</h1>
    <div class="container">
        <!-- <div>
            <input type="checkbox" id="checkAll" onchange="checkAll()">
            <label class="check-all-label">Check All</label>
            <form id='deleteForm' class="delete-form" action="invoice_delete_confirm.php" method="post">
                <input type="hidden" name="list_id_invoice" id="selectedValues" value="">
                <input type="hidden" name="total_id_invoice" id="selectedTotal" value="">
                <input type="submit" id="deleteButton" value="Delete Invoice" disabled>
            </form>
        </div> -->
        <div>
            <!------------- Fillter ------------------->
            <label for="filter">Filter by Name:</label>
            <input type="text" name="filter" id="filter" placeholder="Enter name to filter">
            <!------------------------------------------>
            <!-- <form class="add-invoice-form" action="invoice_insert.php" method="post">
                <input type="submit" id="addInvoiceButton" value="Add Invoice">
            </form> -->
            <br>
        </div>
    </div>

    <?php
    include_once '../../dbConfig.php'; 
    $cur = "SELECT 
    invoice.*,
    customer.CusFName,
    customer.CusLName,
    orders.order_id
    FROM
        invoice
    INNER JOIN
        customer ON invoice.CusID = customer.CusID
    INNER JOIN
        invoice_detail ON invoice.invoice_id = invoice_detail.invoice_id
    INNER JOIN
        orders ON orders.invoice_id = invoice.invoice_id
    ";

    $msresults = mysqli_query($conn, $cur);
    echo "<center>";
    echo "<div>
        <table>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Inspect</th>
            </tr>";

    // $index = 1;
    if (mysqli_num_rows($msresults) > 0) {
        while ($row = mysqli_fetch_array($msresults)) {
            echo "<tr class='user-row'>
                <td>{$row['invoice_id']}</td>
                <td>{$row['CusFName']} {$row['CusLName']}</td>
                <td>{$row['invoice_date']}</td>
                <td>{$row['total_amount']}</td>
                <td>
                    <form class='action-button' action='invoicePDF.php' method='post' target='_blank' style='display: inline-block;'>
                        <input type='hidden' name='order_id' value='{$row['order_id']}'>
                        <input type='hidden' name='id_customer' value='{$row['CusID']}'>
                        <button type='submit'>
                            <img src='../../Customer/image/print.png' alt='print'>
                        </button>
                    </form>
                </td>
            </tr>";


            // echo "<td><div style='border-radius:10px; padding: 3.920px 7.280px; width:90px; margin: 0 auto; background-color:";                
            // if ($row['Status'] == 'Unpaid') {
            //     echo '#FFA500;';
            // } elseif ($row['Status'] == 'Paid') {
            //     echo '#06D6B1;';
            // } else if ($row['Status'] == 'Canceled'){
            //     echo '#FF0000;';
            // } else {
            //     echo '#06D6B1;'; 
            // }                 
            // echo "'><span style='color: #ffff;'>{$row['Status']}</span></div></td>";





            // echo "<td>";
            // echo "<div style='border-radius:10px; padding: 3.920px 7.280px; width:90px; margin: 0 auto; background-color:";

            // เงื่อนไขตรวจสอบค่า Status และกำหนดสีให้กับ background-color
            // if ($row['Status'] == 'Unpaid') {
            //     echo '#FFA500;';
            // } elseif ($row['Status'] == 'Paid') {
            //     echo '#06D6B1;';
            // } else if ($row['Status'] == 'Canceled') {
            //     echo '#FF0000;';
            // } else {
            //     echo '#06D6B1;';
            // }

            // echo "'>";
            // echo "<select id='select_$index' data-recid='{$row['invoice_id']}' style='background-color: inherit; color: #ffff;' required>";

            // $statusCompare = ['Unpaid', 'Paid', 'Canceled'];

            // foreach ($statusCompare as $value) {
            //     $selected = ($value == $row['Status']) ? 'selected' : '';


            //     echo "<option value='$value' style='background-color: #ffff; color: black;' $selected>{$value}</option>";
            // }

            // echo "</select>";
            // echo "</div></td>";






            // echo "<td class='action-buttons'>
            //             <form class='action-button' action='invoice_update.php' method='post'>  
            //                 <input type='hidden' name='id_invoice' value={$row['invoice_id']}>
            //                 <input type='image' alt='update' src='../img/pencil.png'>
            //             </form>
            //             <form class='action-button' action='invoice_delete_confirm.php' method='post'>
            //                 <input type='hidden' name='total_id_invoice' value={$row['invoice_id']}>
            //                 <input type='image' alt='delete' src='../img/trash.png'>
            //             </form>
            //         </td>
            echo "</tr>";
            // $index++;
        }
    }
    echo "</table></div>";
    echo "</center>";
    mysqli_close($conn);
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
            individualCheckboxes[i].addEventListener('change', function() {
                updateDeleteButtonStatus(); // Update deleteButton's status
            });
        }

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

            tableRows.forEach(function(row) {
                var containsKeyword = false;

                // Loop through all columns (td elements) in the current row
                row.querySelectorAll('td').forEach(function(cell, index) {
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Loop through all select elements and attach event listeners
            for (var i = 1; i <= <?php echo $index; ?>; i++) {
                var selectElement = document.getElementById('select_' + i);

                if (selectElement) {
                    selectElement.addEventListener('change', function() {
                        var selectedValue = this.value;
                        var selectDiv = this.parentElement;
                        var recID = this.getAttribute('data-recid');

                        switch (selectedValue) {
                            case 'Unpaid':
                                selectDiv.style.backgroundColor = '#FFA500';
                                break;
                            case 'Paid':
                                selectDiv.style.backgroundColor = '#06D6B1';
                                break;
                            case 'Canceled':
                                selectDiv.style.backgroundColor = '#FF0000';
                                break;
                            default:
                                selectDiv.style.backgroundColor = '#06D6B1';
                        }

                        console.log(recID, selectedValue);
                        // Update the status using AJAX
                        updateStatus(recID, selectedValue);
                    });
                }
            }

            function updateStatus(invoice_id, newStatus) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'invoice_update_status.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

                console.log(invoice_id, newStatus)

                xhr.onreadystatechange = function() {
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
                xhr.send('invoice_id=' + encodeURIComponent(invoice_id) + '&newStatus=' + encodeURIComponent(newStatus));

            }
        });
    </script>
</body>

</html>