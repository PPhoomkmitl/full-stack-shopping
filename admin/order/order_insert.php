<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>

    <!-- Include necessary libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #cce0ff;
            /* Ocean-sky blue background color */
        }

        .invoice-form {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            /* White background for the form */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Box shadow for a subtle lift */
        }

        .form-block {
            border: 1px solid #b3ccff;
            /* Light ocean-sky blue border color */
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: #edf5ff;
            /* Light ocean-sky blue background color */
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #3366cc;
            /* Dark ocean-sky blue text color for labels */
        }

        input,
        select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #99c2ff;
            /* Light ocean-sky blue border color for inputs and selects */
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            /* Darker ocean-sky blue button color */
            color: #fff;
            /* White text color for the button */
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
            /* Slightly darker color on hover */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body>
    <?php include_once '../../dbConfig.php'; ?>
    <div class="invoice-form">
        <form action="order_save_insert.php" method="post">
            <h2 style="color: #007bff;">Create Order</h2>
    
            <!-- Invoice Information Section -->
            <div class="form-block">
                <h3 style="color: #007bff;">Order Information</h3>
                <div class="form-group">
                    <label for="order_id">order_id:</label>
                <?php     
                    // Generate new RECEIVE ID
                    $result = mysqli_query($conn, "SELECT MAX(order_id) AS order_id FROM orders");
                    $row = mysqli_fetch_assoc($result);
                    $lastID = $row['order_id'];
                    $newNumericPart = $lastID + 1;
                    $order_id = $newNumericPart;
                    echo "<input type='text' id='order_id' name='order_id' value='$order_id' readonly>
                    </div>";
                ?>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select id="status" name="status" required>
                        <option value="Pending">Pending</option>
                        <option value="Inprogress">Inprogress</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Canceled">Delivered</option>
                    </select>
                </div>
            </div>
            
            <h3 style="color: #007bff;">Customer Information</h3>
            <div class="form-block">
                <div class="form-group">
                    <label for="customerName">Customer Name:</label>
                    <select id="customerName" name="customerName" required>
                        <?php
                            // Your PHP code to fetch products from the database
                            $result = mysqli_query($conn, "SELECT * FROM Customer");
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='{$row['CusID']}'>{$row['CusFName']} {$row['CusLName']}</option>";
                            }
                        ?>
                    </select>
                             
                </div>
                <div class="form-group" style="color: #007bff">
                    <label style="color: #007bff" for="customerName">Customer Address:</label>
                        <!-- <input type='text' name='id_recevier' value=''> -->
                        FirstName: <input type='text' name='recv_fname' value=''>
                        LastName: <input type='text' name='recv_lname' value=''>
                        Tel: <input type='text' name='recv_tel' value=''>
                        Address: <input type='text' name='recv_address' value=''>
                </div>
            </div>

            <div class="form-block">
                <h3 style="color: #007bff;">Payer Form</h3>
                <div class="form-group" style="color: #007bff">
                    <label style="color: #007bff" for="customerName">Payer info:</label>
                        <!-- <input type='text' name='id_recevier' value=''> -->
                        FirstName: <input type='text' name='payer_fname' value=''>
                        LastName: <input type='text' name='payer_lname' value=''>
                        Tel: <input type='text' name='payer_tel' value=''>
                </div>
            </div>

            <!-- Add Products Section -->
            <div class="form-block">
                <h3 style="color: #007bff;">Add Products</h3>
                <div class="form-group">
                    <label for="productName">Product Name:</label>
                    <select id="productName" name="productName[]" >
                        <?php
                            // Your PHP code to fetch products from the database
                            $result = mysqli_query($conn, "SELECT * FROM Product");
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option data-product-id='{$row['ProID']}' data-price='{$row['PricePerUnit']}' value='{$row['ProID']}'>{$row['ProName']}</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="text" id="quantity" name="quantity[]" >
                </div>
                <button type="button" id="addProductBtn">Add Product</button>
            </div>
    

            <!-- Product Table -->
            <h3 style="color: #007bff;">Product Information</h3>
            <table id="productTable">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price Per Unit</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>VAT 7%:</strong></td>
                        <td id="totalVatPriceInput">0</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: right;"><strong>Total Price:</strong></td>
                        <td id="totalProductPrice">0</td>
                    </tr>
                </tfoot>
            </table>

            
            <input type="hidden" id="selectedProductsInput" name="selectedProducts" value="">
            <input type="hidden" id="totalProductPriceInput" name="totalProductPrice" value="0">
            <input type="submit" value="Submit">
        </form>
    </div>

    <script>
    // Initialize an array to store selected product names
    var selectedProducts = [];   

    $(document).ready(function() {

    // Add Product Button Click Event using the 'on' method
    $(document).on('click', '#addProductBtn', function() {
        addProduct();
    });

    function addProduct() {
        // Get values from inputs
        var productId = $('#productName option:selected').data('product-id');
        var productName = $('#productName option:selected').text();
        var quantity = $('#quantity').val();
        var pricePerUnit = $('#productName option:selected').data('price');

        // Validate quantity
        if (!quantity || isNaN(quantity) || quantity <= 0) {
            alert("Please enter a valid quantity.");
            return;
        }

        // Calculate total price
        var totalPrice = quantity * pricePerUnit;

        // Create table row
        var row = '<tr>' +
            '<td>' + productName + '</td>' +
            '<td>' + quantity + '</td>' +
            '<td>' + pricePerUnit + '</td>' +
            '<td>' + totalPrice + '</td>' +
            '</tr>';

        // Append row to the table
        $('#productTable tbody').append(row);

        // Update total price
        updateTotalPrice();

        // Store selected product details in array
        var selectedProduct = {
            productId: productId,
            productName: productName,
            quantity: quantity
        };

        selectedProducts.push(selectedProduct);
        $('#selectedProductsInput').val(JSON.stringify(selectedProducts));


        // Clear input fields
        $('#productName').val('');
        $('#quantity').val('');

        console.log(selectedProducts);
    }

    function updateTotalPrice() {
        // Calculate total product price
        var totalProductPrice = 0;
        var vatPrice = 0;
        $('#productTable tbody tr').each(function() {
            var totalPriceCell = $(this).find('td:last-child').text();
            totalProductPrice += parseFloat(totalPriceCell);
            vatPrice = totalProductPrice * 0.07;
        });

        
        $('#totalVatPriceInput').text(vatPrice.toFixed(2));
        // Update total price in the footer
        $('#totalProductPrice').text((totalProductPrice + vatPrice).toFixed(2));
        // Update hidden input value
        $('#totalProductPriceInput').val((totalProductPrice + vatPrice).toFixed(2));
        
    }
    });
       
    </script>

</body>

</html>