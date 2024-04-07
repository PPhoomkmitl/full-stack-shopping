<?php
    /* เชื่อมต่อฐานข้อมูล */
    include_once '../../dbConfig.php'; 

    /* SELECT */
    $code = $_POST['id_stock'];
    $cur = "SELECT * FROM product WHERE ProID = '$code'";
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
    <title>Update Stock</title>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center" style="color: #3498db;">Update Stock</h1>
                <h2 class="text-center">Product ID: <?php echo $row['ProID']; ?></h2><br>
                <form id="updateForm">
                    <input type="hidden" name="a1" value="<?php echo $row['ProID']; ?>">
                    <div class="form-group">
                        <label for="product-name">Product Name</label>
                        <input type="text" id="product-name" name="a2" class="form-control" value="<?php echo $row['ProName']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="price-per-unit">Price Per Unit</label>
                        <input type="text" id="price-per-unit" name="a3" class="form-control" value="<?php echo $row['PricePerUnit']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="text" id="quantity" name="a4" class="form-control" value="<?php echo $row['StockQty']; ?>">
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
            const a3 = formData.get('a3');
            const a4 = formData.get('a4');
            
            fetch(`http://localhost:8000/product/updateProduct`, {
                method: 'PUT' ,
                headers: {
                    'Content-Type': 'application/json' // ระบุ Content-Type เป็น application/json
                },
                body: JSON.stringify({ a1, a2 ,a3 , a4 })
            })
            .then(response => {
                // ประมวลผลการตอบสนอง
                if (response.status == 200) {
                    window.location.href = "./stock_index.php"; // Redirect using JavaScript
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
