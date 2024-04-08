<?php
    /* เชื่อมต่อฐานข้อมูล */
    include_once '../../dbConfig.php'; 

    /* SELECT */
    $code = $_POST['id_category'];
    $cur = "SELECT * FROM product_type WHERE id = '$code'";
    $msresults = mysqli_query($conn, $cur);

    // เลือก
    if (mysqli_num_rows($msresults) > 0) {
        $row = mysqli_fetch_array($msresults);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Update Category</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center" style="color: #3498db;">Update Category</h1>
                <h2 class="text-center">Category ID: <?php echo $row['id']; ?></h2><br>
                <form id="updateForm">
                    <input type="hidden" name="a1" value="<?php echo $row['id']; ?>">
                    <div class="form-group">
                        <label for="category-name">Category Name</label>
                        <input type="text" id="category-name" name="a2" class="form-control" value="<?php echo $row['name']; ?>">
                    </div>
                    <div class="text-center">
                        <p class="text-danger">⚠️ Please make sure you want to update your information. ⚠️</p>
                        <button type="reset" class="btn btn-danger">Reset</button>
                        <button type="submit" class="btn btn-primary">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('updateForm').addEventListener('submit', function(event) {
            event.preventDefault(); // ป้องกันการส่งค่าข้อมูลโดยปกติ
            const formData = new FormData(this); // เก็บข้อมูลฟอร์ม
            const a1 = formData.get('a1');
            const a2 = formData.get('a2');
    
            fetch(`http://localhost:8000/category/updateCategory`, {
                method: 'PUT' ,
                headers: {
                    'Content-Type': 'application/json' // ระบุ Content-Type เป็น application/json
                },
                body: JSON.stringify({ a1 , a2 })
            })
            .then(response => {
                // ประมวลผลการตอบสนอง
                if (response.status == 200) {
                    window.location.href = "./category_index.php"; // Redirect using JavaScript
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>

</body>
</html>

<?php
    }

    /* ปิดการเชื่อมต่อ */
    mysqli_close($conn);
?>
