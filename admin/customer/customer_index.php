<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
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
        }
        .user-row{
            border: 1px solid #C4DFDF;
            background-color: #E3F4F4;
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
    <h1>User List</h1>
    <div class="container">
        <?php
            if ($_SESSION['admin'] !== 'user_admin') {
                echo '<div>
                        <input type="checkbox" id="checkAll" onchange="checkAll()">
                        <label class="check-all-label">Check All</label>
                        <form id="deleteForm" class="delete-form" action="customer_delete_confirm.php" method="post">
                            <input type="hidden" name="list_id_customer" id="selectedValues" value="">
                            <input type="hidden" name="total_id_customer" id="selectedTotal" value="">
                            <input type="submit" id="deleteButton" value="Delete User" disabled>
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
                if ($_SESSION['admin'] !== 'user_admin') {
                    echo '<form class="add-user-form" action="customer_insert_form.html" method="post">
                            <input type="submit" id="addUserButton" value="Add User">
                        </form>';
                }
            ?>
            <br>
        </div>
    </div>

    <?php
    $cx =  mysqli_connect("localhost", "root", "", "shopping");
    $cur = "SELECT * FROM Customer";
    $msresults = mysqli_query($cx, $cur);

    echo "<center>";
    echo "<div>
        <table>
            <tr>
                <th><img src='http://localhost/phpmyadmin/themes/pmahomme/img/arrow_ltr.png'></th>
                <th>ID</th>
                <th>Name</th>
                <th>Sex</th>
                <th>Tel</th>
                <th>Action</th>
            </tr>";

    if (mysqli_num_rows($msresults) > 0) {
        while ($row = mysqli_fetch_array($msresults)) {
            /* class='user-row' */
            echo "<tr class='user-row'>
                    <td><input type='checkbox' name='checkbox[]' value='{$row['CusID']}'></td>
                    <td>{$row['CusID']}</td>
                    <td>{$row['CusFName']} {$row['CusLName']}</td>
                    <td>{$row['Sex']}</td>
                    <td>{$row['Tel']}</td>
                    <td class='action-buttons'>
                        <form class='action-button' action='customer_update.php' method='post'>  
                            <input type='hidden' name='id_customer' value={$row['CusID']}>
                            <input type='image' alt='update' src='../img/pencil.png'>
                        </form>:";
                if($_SESSION['admin'] == 'super_admin'){
                    echo"<form class='action-button' action='customer_delete_confirm.php' method='post'>
                                <input type='hidden' name='id_customer' value={$row['CusID']}>
                                <input type='image' alt='delete' src='../img/trash.png'>
                            </form>
                        </td>
                    </tr>";
                }
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

                row.querySelectorAll('td').forEach(function (cell, index) {
                    var cellText = cell.innerText.toLowerCase();

                    if (cellText.includes(filterKeyword.toLowerCase())) {
                        containsKeyword = true;
                        return; 
                    }

                    var cellNumber = parseFloat(cellText);
                    var filterNumber = parseFloat(filterKeyword);

                    if (!isNaN(cellNumber) && !isNaN(filterNumber) && cellNumber === filterNumber) {
                        containsKeyword = true;
                        return; 
                    }
                });

                
                if (containsKeyword) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        }

       
        $('#filter').on('input', function() {    
            var filterKeyword = $(this).val();
            updateTable(filterKeyword);
        });

    </script>
</body>

</html>
