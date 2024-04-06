<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        .main {
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

        <div class="row justify-content-end mb-2">
            <div class="col-auto">
                <button id="activeMember" class="btn btn-primary">Active Member</button>
            </div>
            <div class="col-auto">
                <button id="activeUserAdmin" class="btn btn-primary">Active User Admin</button>
            </div>
            <div class="col-auto">
                <button id="activePermissionAdmin" class="btn btn-primary">Active Permission Admin</button>
            </div>
        </div>

    <div class="main">
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
        include_once '../../dbConfig.php'; 

        // Check if the 'active' parameter is set in the URL
        if(isset($_GET['activeMember'])) {
            $cur = "SELECT * FROM Customer WHERE role = 'member';";
            $msresults = mysqli_query($conn, $cur);
        } elseif(isset($_GET['activeUserAdmin'])) {
            $cur = "SELECT * FROM Customer WHERE role = 'user_admin';";
            $msresults = mysqli_query($conn, $cur);
        } elseif(isset($_GET['activePermissionAdmin'])) {
            $cur = "SELECT * FROM Customer WHERE role = 'permission_admin';";
            $msresults = mysqli_query($conn, $cur);
        } else {
            $cur = "SELECT * FROM Customer WHERE role != 'guest';";
            $msresults = mysqli_query($conn, $cur);
        }

    
        echo "<center>";
        echo "<div>
            <table>
                <tr>
                    <th><img src='http://localhost/phpmyadmin/themes/pmahomme/img/arrow_ltr.png'></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Sex</th>
                    <th>Tel</th>
                    <th>Permission</th>
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
                        <td>{$row['role']}</td>
                        <td class='action-buttons'>
                            <form class='action-button' action='customer_update.php' method='post'>  
                                <input type='hidden' name='id_customer' value={$row['CusID']}>
                                <input type='image' alt='update' src='../img/pencil.png'>
                            </form>";
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
        mysqli_close($conn);


    ?>

    <script>
        document.getElementById("activeMember").addEventListener("click", function() {
            window.location.href = "http://127.0.0.1/shoppingCart/admin/customer/customer_index.php?activeMember=true";
        });

        document.getElementById("activeUserAdmin").addEventListener("click", function() {
            window.location.href = "http://127.0.0.1/shoppingCart/admin/customer/customer_index.php?activeUserAdmin=true";
        });

        document.getElementById("activePermissionAdmin").addEventListener("click", function() {
            window.location.href = "http://127.0.0.1/shoppingCart/admin/customer/customer_index.php?activePermissionAdmin=true";
        });
    </script>
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
    <script>
        document.getElementById("activeButton").addEventListener("click", function() {
            window.location.href = "http://127.0.0.1/shoppingCart/admin/customer/customer_index.php.?active=true";
        });
    </script>
</body>

</html>
