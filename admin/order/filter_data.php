<?php
include_once '../../dbConfig.php';

$filterKeyword = isset($_GET['filterKeyword']) ? mysqli_real_escape_string($conn, $_GET['filterKeyword']) : '';
$index = isset($_GET['index']) ? $_GET['index'] : ''; // เพิ่มบรรทัดนี้เพื่อรับค่า index จาก AJAX

// echo "<script>console.log('Row data: " . json_encode($filterKeyword) . "');</script>";

echo $filterKeyword;

$index = 1;

?>
<?php
if (!empty($filterKeyword) && $filterKeyword != 'All') {
    $cur = "SELECT * FROM orders
                INNER JOIN customer ON customer.CusID = orders.CusID
                INNER JOIN image_slip ON image_slip.image_slip_id = orders.image_slip_id
                INNER JOIN billing_address ON orders.billing_address_id = billing_address.address_id
                WHERE (orders.order_id LIKE '%$filterKeyword%'
                OR customer.CusID LIKE '%$filterKeyword%'
                OR orders.invoice_id LIKE '%$filterKeyword%'
                OR orders.order_date LIKE '%$filterKeyword%'
                OR orders.shipping_status LIKE '%$filterKeyword%'
                OR orders.fullfill_status LIKE '%$filterKeyword%'
                OR orders.total_price LIKE '%$filterKeyword%')";

    $msresults = mysqli_query($conn, $cur);
    // Check for errors in query execution
    if (!$msresults) {
        die("Query failed: " . mysqli_error($conn));
    }
} else if ($filterKeyword == 'All') {
    // If $filterKeyword is empty, select all records
    $cur = "SELECT * 
    FROM orders 
    INNER JOIN customer ON customer.CusID = orders.CusID
    INNER JOIN image_slip ON image_slip.image_slip_id = orders.image_slip_id
    WHERE orders.fullfill_status = 'Fulfilled' 
    UNION
    SELECT * 
    FROM orders 
    INNER JOIN customer ON customer.CusID = orders.CusID
    INNER JOIN image_slip ON image_slip.image_slip_id = orders.image_slip_id
    WHERE orders.fullfill_status = 'Unfulfilled' AND orders.shipping_status = 'Canceled'";
    $msresults = mysqli_query($conn, $cur);
    // Check for errors in query execution
    if (!$msresults) {
        die("Query failed: " . mysqli_error($conn));
    }
} else {

    $msresults = null;
}
if ($msresults !== null && mysqli_num_rows($msresults) > 0) {
    echo "<tr>";
    echo "<th>Order ID</th>";
    echo "<th>Customer</th>";
    echo "<th>Amount</th>";
    echo "<th>Order Date</th>";

    // Check if fullfill_status is Fulfilled
    while ($row = mysqli_fetch_array($msresults)) {
        if ($row['fullfill_status'] == 'Unfulfilled' && $index == 1) {
            echo "<th>Approve</th>";
            echo "<th>Action</th>";
            echo "</tr>";
        } else if (($row['fullfill_status'] == 'Fulfilled' && $index == 1)) {
            echo "<th>Delivery Date</th>";
            echo "<th>Delivery Status</th>";
            echo "<th>Payment Status</th>";
            echo "<th>Action</th>";
            echo "</tr>";
        }

        echo "<tr class='user-row'>
        <td>{$row['order_id']}</td>
        <td>{$row['CusFName']} {$row['CusLName']}</td>
        <td>{$row['total_price']}</td>
        <td>{$row['order_date']}</td>";

        // Check if fullfill_status is Fulfilled
        if ((($row['fullfill_status'] == 'Unfulfilled') || ($row['fullfill_status'] == 'Fulfilled')) && ($filterKeyword != 'Unfulfilled')) {
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
                if ($row['shipping_status'] == 'Canceled') {
                    $selected = ($value == $row['shipping_status']) ? 'selected' : '';
                    echo "<option value='$value' style='background-color: #ffff; color: black;' $selected>{$value}</option>";
                } else {
                    if ($value == 'Canceled') {
                        echo "<option value='$value' style='background-color: #ffff; color: black;' disabled>{$value}</option>";
                    } else {
                        if ($row['shipping_status'] == 'Delivered' && ($value == 'Pending' || $value == 'Inprogress')) {
                            echo "<option value='$value' style='background-color: #ffff; color: black;' disabled>{$value}</option>";
                        } else if ($row['shipping_status'] == 'Inprogress' && $value == 'Pending') {
                            echo "<option value='$value' style='background-color: #ffff; color: black;' disabled>{$value}</option>";
                        } else {
                            $selected = ($value == $row['shipping_status']) ? 'selected' : '';
                            echo "<option value='$value' style='background-color: #ffff; color: black;' $selected>{$value}</option>";
                        }
                    }
                }
            }
            



            echo "</select>";

            echo "</div></td>";

            /* Payment Status */
            echo "<td>";
            echo "<div style='border-radius:10px; padding: 3.920px 7.280px; width:120px; margin: 0 auto; background-color:";

            // Conditions to set background color based on fullfill_status value
            if ($row['fullfill_status'] == 'Unfulfilled') {
                echo '#FF6868;';
            } elseif ($row['fullfill_status'] == 'Fulfilled') {
                echo '#06D6B1;';
            } else {
                echo '#06D6B1;';
            }
            echo "'>";
            echo "<select id='select_payment_$index' data-payment-order_id='{$row['order_id']}' style='background-color: inherit; color: #ffff;' required";


            // Check if the fullfill_status is Fulfilled
            if ($row['fullfill_status'] == 'Fulfilled') {
                // If it is Fulfilled, add the 'disabled' attribute to disable the select
                echo " disabled";
            }

            echo ">";

            // Options for fullfill_status
            $shipping_statusCompare = ['Unfulfilled', 'Fulfilled'];
            foreach ($shipping_statusCompare as $value) {
                $selected = ($value == $row['fullfill_status']) ? 'selected' : '';
                echo "<option value='$value' style='background-color: #ffff; color: black;' $selected>{$value}</option>";
            }
            echo "</select>";
            echo "</div></td>";
        }


        // Check if fullfill_status is Unfulfilled
        if ($row['fullfill_status'] == 'Unfulfilled' && $filterKeyword != 'All' ) {

            echo "
            <td>
            <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#exampleModal'>
              Launch demo modal
            </button>
            
            <div class='modal fade' id='exampleModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>
              <div class='modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl'>
                <div class='modal-content'>
                  <div class='modal-header'>
                    <h5 class='modal-title' id='exampleModalLabel'>Approval</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                  </div>
                  <div class='modal-body'>";

            echo "<div>";

            if (!empty($row['image_data'])) {
                echo "<img style='width:550px; height:450px; cursor:pointer;' src='data:image/*;base64," . base64_encode($row['image_data']) . "' data-bs-toggle='modal' data-bs-target='#imageModal'>";
            } else {
                echo "-";
            }
            echo "</div>";

            echo "<p>Billing name :{$row['recipient_name']}</p>";
            echo "<p>Order date :{$row['order_date']}</p>";



            echo "</div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-bs-dismiss='modal' id='decline_payment_$index' data-payment-order_id='{$row['order_id']}'>Canceled</button>
                    <button type='button' class='btn btn-success' style='margin-left:5px;' data-bs-dismiss='modal' id='select_payment_$index' data-payment-order_id='{$row['order_id']}'>Approve</button>
                </div>
            </div>
            </div>
            </div>
            </td>";
        }

        echo "<td>
                <form class='action-button' action='order_update.php' method='post' style='display: inline-block;'>  
                    <input type='hidden' name='id_order' value={$row['order_id']}>
                    <input type='image' alt='update' src='../img/pencil.png'/>
                </form>
            </td>
            </tr>";
        $index++;
    }
} else {
    echo "<tr><td colspan='5'>No records found</td></tr>";
}
?>

<?php
mysqli_close($conn);
exit();
?>
