<?php
include_once '../../dbConfig.php';

$filterKeyword = isset($_GET['filterKeyword']) ? mysqli_real_escape_string($conn, $_GET['filterKeyword']) : '';


echo $filterKeyword ;
if (!empty($filterKeyword)) {
    $cur = "SELECT * FROM orders 
        WHERE order_id LIKE '%$filterKeyword%'
       OR CusID LIKE '%$filterKeyword%'
       OR invoice_id LIKE '%$filterKeyword%'
       OR order_date LIKE '%$filterKeyword%'
       OR shipping_status LIKE '%$filterKeyword%'
       OR fullfill_status LIKE '%$filterKeyword%'
       OR total_price LIKE '%$filterKeyword%';
    ";
} else {
    // If $filterKeyword is empty, select all records
    $cur = "SELECT * FROM orders LIMIT 10 OFFSET 0";
}
$msresults = mysqli_query($conn, $cur);

if (mysqli_num_rows($msresults) > 0) {
    echo "<tr>           
        <th>Order ID</th>
        <th>Customer</th>
        <th>Amount</th>
        <th>Order Date</th>
        <th>Delivery Date</th>
        <th>Delivery Status</th>
        <th>Payment Status</th>
        <th>Slip</th>
        <th>Action</th>
        </tr>";

        $index = 1;
        if (mysqli_num_rows($msresults) > 0) {
            while ($row = mysqli_fetch_array($msresults)) {
                echo "<tr class='user-row'>
                        <td>{$row['order_id']}</td>
                        <td>{$row['CusFName']} {$row['CusLName']}</td>
                        <td>{$row['total_price']}</td>
                        <td>{$row['order_date']}</td>";
        
                // Check if fullfill_status is Fullfilled
                // if($row['fullfill_status'] === 'Fullfilled'){
                    echo "<td>{$row['delivery_date']}</td>";
        
                    /* Shipping Status */
                    echo "<td>";
                    echo "<div style='border-radius:10px; padding: 3.920px 7.280px; width:120px; margin: 0 auto; background-color:";
        
                    // Conditions to set background color based on shipping_status value
                    if ($row['shipping_status'] == 'Pickups') {
                        echo '#0176FF;';
                    } elseif ($row['shipping_status'] == 'Pending' || $row['shipping_status'] == 'pending') {
                        echo '#FFA500;';
                    } elseif ($row['shipping_status'] == 'Inprogress') {
                        echo '#7C6BFF;'; 
                    } elseif ($row['shipping_status'] == 'Canceled') {
                        echo '#FF0000;';
                    } else {
                        echo '#06D6B1;'; 
                    }
                    echo "'>";
        
                    // Dropdown select for shipping_status
                    echo "<select id='select_$index' data-order_id='{$row['order_id']}' style='background-color: inherit; color: #ffff;' required>";
        
                    // Options for shipping_status
                    $shipping_statusCompare = ['Pending', 'Inprogress', 'Delivered', 'Canceled'];
                    foreach ($shipping_statusCompare as $value) {
                        $selected = ($value == $row['shipping_status']) ? 'selected' : '';
                        echo "<option value='$value' style='background-color: #ffff; color: black;' $selected>{$value}</option>";
                    }
                    echo "</select>";
        
                    echo "</div></td>";
                // } 
                
        
                /* Payment Status */
                echo "<td>";
                echo "<div style='border-radius:10px; padding: 3.920px 7.280px; width:120px; margin: 0 auto; background-color:";
        
                // Conditions to set background color based on fullfill_status value
                if ($row['fullfill_status'] == 'Unfullfilled') {
                    echo '#FFA500;';
                } elseif ($row['fullfill_status'] == 'Fullfilled') {
                    echo '#06D6B1;';
                } else {
                    echo '#06D6B1;'; 
                }
                echo "'>";
                echo "<select id='select_$index' data-order_id='{$row['order_id']}' style='background-color: inherit; color: #ffff;' required>";
        
                // Options for fullfill_status
                $shipping_statusCompare = ['Unfullfilled' , 'Fullfilled'];
                foreach ($shipping_statusCompare as $value) {
                    $selected = ($value == $row['fullfill_status']) ? 'selected' : '';
                    echo "<option value='$value' style='background-color: #ffff; color: black;' $selected>{$value}</option>";
                }
                echo "</select>";
                echo "</div></td>";
        
                // Check if fullfill_status is Unfullfilled
                // if ($row['fullfill_status'] == 'Unfulfilled') {
                    echo "<td>";
                    // Check if the image_data is not empty
                    if (!empty($row['image_data'])) {
                        // Output the image as a clickable link to open modal
                        echo "<a href='#imageModal' data-toggle='modal' data-image-src='data:image/*;base64," . base64_encode($row['image_data']) . "'><img class='product-image' src='data:image/*;base64," . base64_encode($row['image_data']) . "'></a>";
                    } else {
                        // Output a placeholder or empty cell if image_data is empty
                        echo "-";
                    }
                    echo "</td>";
                // }
        
                echo "<td>
                        <form class='action-button' action='order_update.php' method='post' style='display: inline-block;'>  
                            <input type='hidden' name='id_order' value={$row['order_id']}>
                            <input type='image' alt='update' src='../img/pencil.png'/>
                        </form>
                      </td>
                      </tr>";
                $index++;
            }
        } 
} else {
    echo "<tr><td colspan='5'>No records found</td></tr>";
}

mysqli_close($conn);
?>
