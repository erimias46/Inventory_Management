<?php
$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

$from_date = $_GET['from'] ?? '';
$to_date = $_GET['to'] ?? '';
?>

<head>
    <?php
    $title = 'Sales';
    include $redirect_link . 'partials/title-meta.php'; ?>

    <?php include $redirect_link . 'partials/head-css.php'; ?>
</head>


<?php
$id = $_SESSION['user_id'];

$result = mysqli_query($con, "SELECT * FROM user WHERE user_id = $id");


if ($result) {
    
    $row = mysqli_fetch_assoc($result);

    
    if ($row) {
        
        $user_id = $row['user_id'];
        $user_name = $row['user_name'];
        $password = $row['password'];
        $privileged = $row['previledge'];
        $module = json_decode($row['module'], true);

        
        $calculateButtonVisible = ($module['saleview'] == 1) ? true : false;

        
        $addButtonVisible = ($module['saleadd'] == 1) ? true : false;

        $deleteButtonVisible = ($module['saledelete'] == 1) ? true : false;

        
        $updateButtonVisible = ($module['saleedit'] == 1) ? true : false;

        
        $generateButtonVisible = ($module['salegenerate'] == 1) ? true : false;
    } else {
        echo "No user found with the specified ID";
    }

    // Free the result set
    mysqli_free_result($result);
} else {
    // Handle the case where the query failed
    echo "Error executing query: " . mysqli_error($con);
}
?>

<body>

    <!-- Begin page -->
    <div class="flex wrapper">

        <?php include $redirect_link . 'partials/menu.php'; ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <?php include $redirect_link . 'partials/topbar.php'; ?>

            <main class="flex-grow p-6">
                <div class="card">
                    <div class="card-header">
                        <p class="text-sm text-gray-500 dark:text-gray-500">
                            Filter Sales
                        </p>
                    </div>
                    <div class="p-6">
                        <form method="POST">
                            <p class="mt-2 text-gray-800 dark:text-gray-400">
                                <div class="flex gap-2">
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> From Date </label>
                                        <input type="date" name="from_date" class="form-input"
                                                required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> To Date </label>
                                        <input type="date" name="to_date" class="form-input"
                                                required>
                                    </div>
                                </div>
                            </p>
                            <div class="flex justify-end">
                                <a href="?" class="btn btn-sm bg-danger text-white rounded-full me-2">
                                    <i class="msr text-base me-2">restart_alt</i>
                                    Reset
                                </a>
                                <button name="filter" type="submit" class="btn btn-sm bg-success text-white rounded-full">
                                    <i class="msr text-base me-2">filter_list</i>
                                    Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Sales</h4>
                            <div>

                            <?php if ($addButtonVisible) : ?>
                                <button type="button" data-fc-type="modal" data-fc-target="addModal"
                                    class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                    <i class="msr text-base me-2">add</i>
                                    Add Sales
                                </button>

                                <?php endif; ?>
                                <?php if ($generateButtonVisible) : ?>
                                <a href="export.php?type=sales_withvat&from_date=<?php echo $from_date; ?>&to_date=<?php echo $to_date; ?>"
                                    class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                    <i class="msr text-base me-2">picture_as_pdf</i>
                                    Export
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <div class="min-w-full inline-block align-middle">
                                <div class="overflow-hidden">
                                    <table id="zero_config"
                                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>
                                            <th>Action</th>
                                                <th>ID</th>
                                                <th>Customer</th>
                                                
                                                <th>Price Before Vat</th>
                                                <th>Vat</th>
                                                <th>Price Include Vat</th>
                                                
                                                <th>Holding Tax</th>
                                                <th>Receipt Number</th>
                                                <th>Date</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (!empty($_GET['from']) && !empty($_GET['to'])) {
                                                $from_date = $_GET['from'];
                                                $to_date = $_GET['to'];
                                            } else {
                                                $from_date = "0000-00-00";
                                                $to_date = "3000-01-01";
                                            }

                                            $customer = '';
                                            if (!empty($_GET['customer'])) {
                                                $get_customer = $_GET['customer'];
                                                $customer = "customer = '$get_customer'";
                                            } else {
                                                $customer = '';
                                            }

                                            if (!$customer) {
                                                $sql = "SELECT * 
                                                FROM sales_withvat
                                                WHERE DATE(sales_date) >= '$from_date' 
                                                    AND DATE(sales_date) <= '$to_date'
                                                ORDER BY sales_date DESC";
                                            } else {
                                                $sql = "SELECT * FROM sales_withvat WHERE DATE(sales_date) >= '$from_date' AND DATE(sales_date) <= '$to_date' AND {$customer} ORDER BY sales_date DESC";
                                            }
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            <tr
                                                class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                    <?php if ($deleteButtonVisible) : ?>
                                                    <a id="del-btn" href="remove.php?id=<?php echo $row['sales_id'];?>&from=sales_withvat"
                                                        class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i
                                                            class="mgc_delete_2_line text-base me-2"></i> Delete</a>
                                                            <?php endif; ?>



                                                            <?php if ($updateButtonVisible) : ?>
                                                    <button type="button" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full"
                                                        data-fc-type="modal" data-fc-target="edit" data-fc-id="<?= $row['sales_id'] ?>">
                                                        <i class="mgc_pencil_line text-base me-2"></i> 
                                                        Edit
                                                    </button>
                                                    <?php endif; ?>
                                                </td>
                                                <td
                                                    class="px-6 90-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <?php echo $row['sales_id']; ?></td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <?php echo $row['customer']; ?></td>
                                                
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <?php echo $row['price_before_vat']; ?></td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php
                                                    $price_before_vat = $row['price_before_vat'];
                                                    $vat = $row['vat'];
                                                    $price_including_vat = $price_before_vat * ($vat * 0.01);
                                                    echo $price_including_vat;
                                                    ?>
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <?php echo $row['price_including_vat']; ?></td>
                                                
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <?php echo $row['with_holding_tax']; ?></td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <?php echo $row['recitnum']; ?></td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <?php echo $row['sales_date']; ?>
                                                </td>
                                            </tr>

                                            <div id="edit"
                    class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div
                        class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div
                            class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Edit  Sales
                            </h3>
                            <button
                                class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200"
                                data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST">
                            <div class="px-4 py-8 overflow-y-auto">
                                <input type="hidden" name="sales_id"
                                    >
                                <input type="hidden" name="customer"
                                    >


                                    <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Receipt Number </label>
                                    <input type="text" name="recitnum" class="form-input"
                                        required>
                                </div>
                                
                                <div class="flex gap-2 justify-between">
                                    

                                    <div class="mb-3">
                                        <label
                                            class="text-gray-800 text-sm font-medium inline-block mb-2">Price
                                            Before Vat</label>
                                        <input type="text" name="price_before_vat"
                                            class="form-input"
                                            
                                            required>
                                    </div>
                                    <div class="mb-3">
                                    <label
                                        class="text-gray-800 text-sm font-medium inline-block mb-2">
                                        Holding Tax </label>
                                    <input type="text" name="with_holding_tax"
                                        class="form-input" 
                                        required>
                                </div>

                                </div>
                            <div class="flex gap-2 justify-between">
                                <div class="mb-3">
                                    <label
                                        class="text-gray-800 text-sm font-medium inline-block mb-2">
                                        Date </label>
                                    <input type="date" name="sales_date" class="form-input"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label
                                        class="text-gray-800 text-sm font-medium inline-block mb-2">
                                        Vat </label>
                                    <input type="text" name="vat"
                                        class="form-input" 
                                        required>
                                </div>
                                </div>

                                <div class="flex gap-2 justify-between">
                                
                                
                                </div>


                            
                            <div
                                class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button
                                    class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                                    data-fc-dismiss type="button">Close
                                </button>
                                <button name="update_sales" type="submit"
                                    class="btn bg-success text-white">Edit Sales</button>
                            </div>
                        </form>
                    </div>
                </div>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Edit modal -->
                

                <div id="addModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div
                        class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Add Sales
                            </h3>
                            <button
                                class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200"
                                data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST">
                            <div class="px-4 py-8 overflow-y-auto">
                                <div class="flex gap-2 justify-between">
                                    <div class="mb-3">
                                        <label
                                            class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Customer Name </label>
                                        <input type="text" name="customer" class="form-input"
                                                required>
                                    </div>
                                    <div class="mb-3">
                                        <label
                                            class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Recipt Number </label>
                                        <input type="text" name="receipt_number"
                                            class="form-input" 
                                            required>
                                    </div>
                                    </div>
        
        
                                    
                                    <div class="flex gap-2 justify-between">
                                    <div class="mb-3">
                                        <label
                                            class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Holding Tax </label>
                                        <input type="text" name="holding_tax"
                                            class="form-input" 
                                            required>
                                    </div>

                                        <div class="mb-3">
                                            <label
                                                class="text-gray-800 text-sm font-medium inline-block mb-2">Price
                                                Before Vat</label>
                                            <input type="text" name="price_before_vat"
                                                class="form-input"
                                                
                                                required>
                                        </div>

                                    </div>
                                <div class="flex gap-2 justify-between">
                                    <div class="mb-3">
                                        <label
                                            class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Date </label>
                                        <input type="date" name="date" class="form-input"
                                                required>
                                    </div>
                                    <div class="mb-3">
                                        <label
                                            class="text-gray-800 text-sm font-medium inline-block mb-2">
                                            Vat </label>
                                        <input type="text" name="vat"
                                            class="form-input" 
                                            required>
                                    </div>
                                    </div>

                                                         
                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button
                                    class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                                    data-fc-dismiss type="button">Close
                                </button>
                                <button name="add_data" type="submit"
                                    class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Add Sales</button>
                            </div>
                        </form>
                    </div>
                </div>
            </main>

            <?php include $redirect_link . 'partials/footer.php'; ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>

    <?php include $redirect_link . 'partials/customizer.php'; ?>

    <?php include $redirect_link . 'partials/footer-scripts.php'; ?>

    <script>
        $(document).ready(function() {
            $('button[data-fc-target=edit]').click(function(event) {
                let id = event.currentTarget.getAttribute('data-fc-id')
                $.get(`api/sales_withvat.php?id=${id}`, data => {
                    for (key in data) {
                        if (key == 'client') {
                            $(`#edit select[name='client']`).val(data[key])
                        } else {
                            $(`#edit input[name='${key}']`).val(data[key]);
                        }
                    }
                }, 'json')
            })
        })
    </script>

</body>

</html>

<?php


if (isset($_POST['update_sales'])) {
    $sales_id = $_POST['sales_id'];
    $customer = $_POST['customer'];
    $price_before_vat = $_POST['price_before_vat'];
    $date = $_POST['sales_date'];
    $vat = $_POST['vat'];
    $holding_tax = $_POST['with_holding_tax'];
    $receipt_number = $_POST['recitnum'];
    $price_including_vat = $price_before_vat + ($price_before_vat * ($vat * 0.01));
    
    
    $purchase_update = "UPDATE `sales_withvat` SET `customer`='$customer', `price_before_vat`= '$price_before_vat',
    `vat`='$vat',`sales_date`='$date',`update_date`='$date',
    `price_including_vat`='$price_including_vat', `with_holding_tax`='$holding_tax', `recitnum`='$receipt_number'
    WHERE `sales_id` = '$sales_id'";
    
    
    $result_update = mysqli_query($con, $purchase_update);
    echo $result_update;
    
    if ($result_update) {
    echo "<script>
    window.location = 'action.php?status=success&redirect=sales.php';
    </script>";
    } else {
    echo "<script>
    window.location = 'action.php?status=error&redirect=sales.php';
    </script>";
    }
    }

    if (isset($_POST['add_data'])) {
        $customer = $_POST['customer'];
        $price_before_vat = $_POST['price_before_vat'];
        $date = $_POST['date'];
        $vat = $_POST['vat'];
        $holding_tax = $_POST['holding_tax'];
        $receipt_number = $_POST['receipt_number'];
        $price_including_vat = $price_before_vat + ($price_before_vat * ($vat * 0.01));
    
        $add_data = "INSERT INTO `sales_withvat`(`customer`, `price_before_vat`, `vat`,`price_including_vat`, 
                    `with_holding_tax`,`sales_date`, `update_date`, `recitnum`) 
                    VALUES ('$customer','$price_before_vat','$vat','$price_including_vat',
                    '$holding_tax','$date','$date','$receipt_number')";
        $result_add = mysqli_query($con, $add_data);
    
        if ($result_add) {
            echo "<script>window.location = 'action.php?status=success&redirect=sales.php'; </script>";
        } else {
            echo "<script>window.location = 'action.php?status=error&redirect=sales.php'; </script>";
        }
    }

if (isset($_POST['filter'])) {
    $from = $_POST['from_date'];
    $to = $_POST['to_date'];
    $customer = $_POST['customer'];
    echo "<script>window.location = 'sales.php?from=$from&to=$to&customer=$customer'; </script>";
}




?>