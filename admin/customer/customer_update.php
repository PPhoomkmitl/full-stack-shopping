<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    form {
        width: 50%;
        margin: 0 auto;
        padding: 20px;
        /* background-color: #f9f9f9;
        border: 1px solid #ddd; */
        border-radius: 5px;
    }

    h1 {
        text-align: center;
    }

    h2 {
        text-align: center;
    }

    input[type='text'],
    select,
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    input[type='submit'] {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border: none;
        border-radius: 5px;
        background-color: #3498db;
        color: white;
        cursor: pointer;
    }

    input[type='button'] {
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border: none;
        border-radius: 5px;
        background-color: gray;
        color: white;
        background-color: #ef476f;
        cursor: pointer;
    }

    input[type='submit']:hover {
        background-color: #2980b9;
    }

    input[type='button']:hover {
        background-color: #d64161;
    }

    input[type='submit']:active {
        background-color: #3498db;
    }

    input[type='button']:active {
        background-color: #ef476f;
    }
</style>
<body>
    <?php
        /* get connection */
        include_once '../../dbConfig.php';

        /*SELECT*/
        $code = $_POST['id_customer'];
        $cur = "SELECT * FROM Customer WHERE CusID = '$code'";

        $msresults = mysqli_query($conn, $cur);
        $row = mysqli_fetch_array($msresults);
        if (mysqli_num_rows($msresults) > 0) {

            $cur = "SELECT Customer.CusFName , Customer.CusLName , Customer.Sex , Customer.Tel , shipping_address.* FROM Customer 
                INNER JOIN shipping_address ON shipping_address.CusID = customer.CusID
                WHERE shipping_address.CusID = '$code'";

            $msresults_receiver = mysqli_query($conn, $cur);
            $row_recv = mysqli_fetch_array($msresults_receiver);
        }
    ?>
   <form id="updateForm">
    <center>
        <h1>Update Customer Form</h1>
        <h2>No. <?php echo $row['CusID']; ?></h2><br>
        <input type="hidden" name="id_customer" value="<?php echo $row['CusID']; ?>">
        <input type="hidden" name="id_receiver" value="<?php echo isset($row_recv['address_id']) ? $row_recv['address_id'] : ''; ?>">
        Firstname <input type="text" name="a1" value="<?php echo $row['CusFName']; ?>"><br>
        Lastname <input type="text" name="a2" value="<?php echo $row['CusLName']; ?>"><br>
        Sex <select name="a3">
            <option value="F"<?php echo $row['Sex'] == 'F' ? ' selected' : ''; ?>>F</option>
            <option value="M"<?php echo $row['Sex'] == 'M' ? ' selected' : ''; ?>>M</option>
            <option value="N"<?php echo $row['Sex'] == 'N' ? ' selected' : ''; ?>>None</option>
        </select><br>
        Tel <input type="text" name="a4" value="<?php echo $row['Tel']; ?>"><br>
        Role: <select name="a5">
            <option value="member"<?php echo $row['role'] == 'member' ? ' selected' : ''; ?>>Member</option>
            <option value="user_admin"<?php echo $row['role'] == 'user_admin' ? ' selected' : ''; ?>>User Admin</option>
            <option value="permission_admin"<?php echo $row['role'] == 'permission_admin' ? ' selected' : ''; ?>>Permission Admin</option>
            <option value="super_admin"<?php echo $row['role'] == 'super_admin' ? ' selected' : ''; ?>>Super Admin</option>
        </select><br>
        ⚠️ Please make sure you want to update your information. ⚠️<br><br>
        <div style="display:flex;">
            <input type="button" value="Cancel" style="margin-right:1rem;" onclick="history.back();">
            <input type="submit" value="Confirm">
        </div>
    </center>
</form>

<script>
    document.getElementById('updateForm').addEventListener('submit', function(event) {
        event.preventDefault(); // ป้องกันการส่งค่าข้อมูลโดยปกติ
        const formData = new FormData(this); // เก็บข้อมูลฟอร์ม
        const cusID = formData.get('id_customer');

        const a1 = formData.get('a1');
        const a2 = formData.get('a2');
        const a3 = formData.get('a3');
        const a4 = formData.get('a4');
        const a5 = formData.get('a5');
        
        fetch(`http://localhost:8000/user/updateUser`, {
            method: 'PUT' ,
            headers: {
                'Content-Type': 'application/json' // ระบุ Content-Type เป็น application/json
            },
            body: JSON.stringify({ cusID, a1, a2, a3, a4, a5 })
        })
        .then(response => {
            // ประมวลผลการตอบสนอง
            console.log(response);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>
</body>
</html>

