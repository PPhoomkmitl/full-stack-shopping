<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <title>Stock List</title>
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

        .add-user-form {
            display: inline-block;
            margin-right: 5px;
        }

        #addUserButton {
            background-color: #4b93ff;
            color: #fff;
            padding: 5px 10px;
            border: none;
        }

        #addUserButton:hover {
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
            padding: 5px;
            /* border-bottom: 1px solid #ccc; */
        }

        tr{
            background-color: #E3F4F4;
            border: 1px solid #C4DFDF;
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
    <h1>Stock List</h1>
    <div class="container">
            <?php
                if ($_SESSION['admin'] !== 'user_admin') {
                    echo '<div>
                            <input type="checkbox" id="checkAll" onchange="checkAll()">
                            <label class="check-all-label">Check All</label>
                            <form id="deleteForm" class="delete-form" action="stock_delete_confirm.php" method="post">
                                <input type="hidden" name="list_id_stock" id="selectedValues" value="">
                                <input type="hidden" name="total_id_stock" id="selectedTotal" value="">
                                <input type="submit" id="deleteButton" value="Delete Product" disabled>
                            </form>
                        </div>';
                }
            ?>

        <div>
            <!------------- Fillter ------------------->
            <label for="filter">Filter by Name:</label>
            <input type="text" name="filter" id="filter" placeholder="Enter name to filter">
            <!------------------------------------------>
            <?php
                if ($_SESSION['admin'] == 'user_admin') {
                    echo '<form class="add-user-form" action="stock_insert_form.php" method="post">
                            <input type="submit" id="addUserButton" value="Add Product"/>
                        </form>';
                }
            ?>

            <br>
        </div>
    </div>

    <?php
    include_once '../../dbConfig.php'; 
    $cur = "SELECT * FROM product";
    $msresults = mysqli_query($conn, $cur);

    echo "<center>";
    echo "<div>
        <table>
            <tr>  
                <th></th>                   
                <th>ID</th>
                <th>ProductName</th>
                <th>ProductPerUnit</th>
                <th>StockQty</th>
                <th>Available</th>
                <th>Action</th>
            </tr>";

    if (mysqli_num_rows($msresults) > 0) {
        while ($row = mysqli_fetch_array($msresults)) {
            /* class='user-row' */
            $result = $row['StockQty'] - $row['OnHands'];
            echo "<tr class='user-row'>
                    <td><input type='checkbox' name='checkbox[]' value='{$row['ProID']}'></td>
                    <td>{$row['ProID']}</td>
                    <td>{$row['ProName']}</td>
                    <td>{$row['PricePerUnit']}</td>
                    <td>{$row['StockQty']}</td>
                    <td>{$result}</td>
                    <td>
                        <form class='action-button' action='stock_update.php' method='post' style='display: inline-block;'>  
                            <input type='hidden' name='id_stock' value={$row['ProID']}>
                            <input type='image' alt='update' src='../img/pencil.png'/>
                        </form>
               
                    </td>
                </tr>";
        }
    //     <form class='action-button' action='stock_delete_confirm.php' method='post' style='display: inline-block;'>
    //     <input type='hidden' name='id_stock' value={$row['ProID']}>
    //     <input type='image' alt='delete' src='../img/trash.png'/>
    // </form>
      
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
        individualCheckboxes[i].addEventListener('change', function () {
            updateDeleteButtonStatus(); // Update deleteButton's status
        });
    }
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
