<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Form</title>
    <!-- Link Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="text-center mb-4">Send desired product information</h1>
                <form method="post" action="stock_insert.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="a2">Product name:</label>
                        <input type="text" class="form-control" id="a2" name="a2" maxlength="20" required>
                    </div>
                    <div class="form-group">
                        <label for="a5">Detail:</label>
                        <textarea class="form-control" id="a5" name="a5" rows="4" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="a3">Price/unit:</label>
                        <input type="text" class="form-control" id="a3" name="a3" required>
                    </div>
                    <div class="form-group">
                        <label for="a4">Stock:</label>
                        <input type="text" class="form-control" id="a4" name="a4" required>
                    </div>
                    <div class="form-group">
                        <label for="image">Image:</label>
                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                    </div>
                    <div class="form-group">
                        <?php
                            // Include database configuration file
                            require_once('../../dbConfig.php');

                            // Fetch categories from the database
                            $categoryQuery = "SELECT * FROM product_type";
                            $result = mysqli_query($conn, $categoryQuery);

                            // Check if there are any categories
                            if (mysqli_num_rows($result) > 0) {
                                echo '<label for="category">Category:</label>';
                                echo '<select class="form-control" name="category" id="category">';
                                echo '<option value="">Select category</option>';

                                // Loop through each row of the result set
                                while ($row = mysqli_fetch_assoc($result)) {
                                    // Output each category as an option element
                                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                }
                                echo '</select>';
                            } else {
                                echo '<p>No categories found.</p>';
                            }
                        ?>
                    </div>
                    <div class="text-center">
                        <a href="stock_index.php" class="btn btn-danger mr-3">Cancel</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Link Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
