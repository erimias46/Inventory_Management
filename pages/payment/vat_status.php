
<?php
$redirect_link = "../../"; $side_link = "../../";
include $redirect_link .'partials/main.php';
include_once $redirect_link .'include/db.php';
?>
<?php

// for last 

if(isset($_GET['from']) && isset($_GET['to'])){
    $from_date_get = $_GET['from'];
    $to_date_get = $_GET['to'];

$sql = "SELECT id, from_date, to_date FROM compare WHERE CURDATE() BETWEEN '$from_date_get' AND '$to_date_get'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
$last_id = $row['id'];
$last = $last_id - 1;

$sql0 = "SELECT * FROM compare WHERE id = '$last'";
$result0 = mysqli_query($con, $sql0);
$row0 = mysqli_fetch_assoc($result0);
$last_from_date = $row0['from_date'];
$last_to_date = $row0['to_date'];

$sql1 = "SELECT SUM(purchase) as purchase, SUM(sale) as sale FROM price_data WHERE date >= '$last_from_date' AND date <= '$last_to_date'";
$result1 = mysqli_query($con, $sql1);
$row1 = mysqli_fetch_assoc($result1);
$last_purchase = $row1['purchase'];
$last_sale = $row1['sale'];

}
else{
    $from_date_get = "1000-01-01";
    $to_date_get = "3000-01-01";
}

?>
<script type="text/javascript">
google.charts.load('current', {
    'packages': ['corechart']
});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Task', 'Hours per Day'],
        ['Purchase', <?php echo $last_purchase; ?>],
        ['Sales', <?php echo $last_sale; ?>],
    ]);
    var options = {
        title: 'Data'
    };
    var chart = new google.visualization.PieChart(document.getElementById('last'));
    chart.draw(data, options);
}
</script>

<?php

// for now

if(isset($_GET['from']) && isset($_GET['to'])){
    $from_date_get = $_GET['from'];
    $to_date_get = $_GET['to'];
$sql2 = "SELECT id, from_date, to_date FROM compare WHERE CURDATE() BETWEEN from_date AND to_date";
$result2 = mysqli_query($con, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$id = $row2['id'];
$from_date = $row2['from_date'];
$to_date = $row2['to_date'];



$sql3 = "SELECT SUM(purchase) as purchase, SUM(sale) as sale FROM price_data WHERE date >= '$from_date' AND date <= '$to_date'";
$result3 = mysqli_query($con, $sql3);
$row3 = mysqli_fetch_assoc($result3);
$purchase = $row3['purchase'];
$sale = $row3['sale'];


}
else{
    $from_date_get = "1000-01-01";
    $to_date_get = "3000-01-01";
}

?>




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

        
        $calculateButtonVisible = ($module['vatview'] == 1) ? true : false;

        
        $addButtonVisible = ($module['vatadd'] == 1) ? true : false;
        $deleteButtonVisible = ($module['vatdelete'] == 1) ? true : false;

    


        
        $updateButtonVisible = ($module['vatedit'] == 1) ? true : false;

        
        $generateButtonVisible = ($module['vatgenerate'] == 1) ? true : false;
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
<head>
    <?php
    $title = 'Vat Status';
    include $redirect_link .'partials/title-meta.php'; ?>

    <?php include $redirect_link .'partials/head-css.php'; ?>
</head>

<body>

    <!-- Begin page -->
    <div class="flex wrapper">

        <?php include $redirect_link .'partials/menu.php'; ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <?php include $redirect_link .'partials/topbar.php'; ?>

            <main class="flex-grow p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Vat Compare</h4>
                        </div>
                        <div class="p-6">
                            <form method="POST">
                                <div class="flex flex-wrap">
                                    <div class="m-2 flex-1">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">From </label>
                                        <input type="date" name="from_date" class="form-input" value="<?php if(isset($_GET['from'])) echo $_GET['from'] ?>" required>
                                    </div>
                                    <div class="m-2 flex-1">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">To </label>
                                        <input type="date" name="to_date" class="form-input" value="<?php if(isset($_GET['to'])) echo $_GET['to'] ?>" required>
                                    </div>
                                </div>
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
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Vat Compare Interval</h4>
                        </div>
                        <div class="p-6">
                            <form method="POST">
                                <div class="flex flex-wrap">
                                    <div class="m-2 flex-1">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">From </label>
                                        <input type="date" name="from_date_interval" class="form-input" value="<?php if(isset($_GET['from']))  $_GET['from'] ?>" required>
                                    </div>
                                    <div class="m-2 flex-1">
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">To </label>
                                        <input type="date" name="to_date_interval" class="form-input" value="<?php if(isset($_GET['to']))$_GET['to'] ?>" required>
                                    </div>
                                </div>
                                <div class="flex justify-end">

                                <?php if ($addButtonVisible) : ?>
                                    <button name="add_interval" type="submit" class="btn btn-sm bg-success text-white rounded-full">
                                        <i class="mgc_add_fill text-base me-2"></i>
                                        Add
                                    </button>
                                <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Vat Status</h4>
                            <div>

                                <?php if ($generateButtonVisible) : ?>
                                <a href="<?= $redirect_link .'pages/export.php?type=price_data' ?>" class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                    <i class="msr text-base me-2">picture_as_pdf</i>
                                    Export
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <table id="zero_config" data-order='[[ 0, "dsc" ]]'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Purchase</th>
                                    <th>Sales</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($_GET['from']) && !empty($_GET['to'])) {
                                    $from_date_get = $_GET['from'];
                                    $to_date_get = $_GET['to'];
                                } else {
                                    $from_date_get = "1000-01-01";
                                    $to_date_get = "3000-01-01";
                                }

                                $sql = "SELECT * FROM price_data WHERE DATE(date) >= '$from_date_get' AND DATE(date) <= '$to_date_get'";

                                $result = mysqli_query($con, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['purchase']; ?></td>
                                    <td><?php echo $row['sale']; ?></td>
                                    <td><?php echo $row['date']; ?></td>
                                </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                        <div class="card mt-4">
                            <div class="card-header">
                                <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">
                                    Summary
                                </h4>
                            </div>
                            <div class="p-4">
                                <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6 mb-6">
                                    <?php
                                    $sql = "SELECT SUM(purchase) as purchase, SUM(sale) as sale FROM price_data WHERE DATE(date) >= '$from_date_get' AND DATE(date) <= '$to_date_get'";
                                    $result = mysqli_query($con, $sql);
                                    $row = mysqli_fetch_assoc($result);
                                    $purchase = $row['purchase'];
                                    $sale = $row['sale'];
                                    if ($purchase > $sale) {
                                        $nets = $purchase - $sale;
                                        $net = number_format($nets, 2);
                                        $status = 'Sale The Products';
                                    } else if ($purchase < $sale) {
                                        $nets = $sale - $purchase;
                                        $net = number_format($nets, 2);
                                        $status = 'Purchase The Products';
                                    } else {
                                        $net = 0;
                                        $status = "Best position ✅";
                                    }
                                    ?>
                                    <div class="col-span-1">
                                        <div class="card">
                                            <div class="p-6">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="w-12 h-12 flex justify-center items-center rounded text-primary bg-primary/25">
                                                            <i class="mgc_shopping_cart_1_line text-xl"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow">
                                                        <h5 class="mb-1">Purchase</h5>
                                                        <p><?= number_format($purchase, 2) ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-1">
                                        <div class="card">
                                            <div class="p-2">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="w-12 h-12 flex justify-center items-center rounded text-primary bg-primary/25">
                                                            <i class="mgc_currency_dollar_line text-xl"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow">
                                                        <h5 class="mb-1">Sales</h5>
                                                        <p><?= number_format($sale, 2) ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-1">
                                        <div class="card">
                                            <div class="p-2">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="w-12 h-12 flex justify-center items-center rounded text-primary bg-primary/25">
                                                            <i class="mgc_minus_circle_line text-xl"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow">
                                                        <h5 class="mb-1">NET Amount</h5>
                                                        <p><?= $net ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-span-1">
                                        <div class="card">
                                            <div class="p-6">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="w-12 h-12 flex justify-center items-center rounded text-primary bg-primary/25">
                                                            <i class="mgc_battery_1_line text-xl"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow">
                                                        <h5 class="mb-1">Status</h5>
                                                        <p><?= $status ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">
                            VAT Dates
                        </h4>
                    </div>
                    <div class="p-4">
                        <div class="overflow-x-auto">
                            <div class="min-w-full inline-block align-middle">
                                <div class="overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">From Date</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">To Date</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $sql="Select * from compare;";

                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            <tr class="odd:bg-white even:bg-gray-100 hover:bg-gray-100 dark:odd:bg-gray-800 dark:even:bg-gray-700 dark:hover:bg-gray-700">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><?php echo $row['id']; ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><?php echo $row['from_date']; ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200"><?php echo $row['to_date']; ?></td>

                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                                                    <?php if ($deleteButtonVisible) : ?>
                                                    <a href="remove.php?id=<?php echo $row['id'];?>&from=vat_dates"
                                                        class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i
                                                            class="mgc_delete_2_line text-base me-2"></i> Delete</a>
                                                    <?php endif; ?>

                                                    <?php if ($updateButtonVisible) : ?>
                                                    <button type="button" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full"
                                                        data-fc-type="modal" data-fc-target="edit<?= $row['id'] ?>">
                                                        <i class="mgc_pencil_line text-base me-2"></i> 
                                                        Edit
                                                    </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <div id="edit<?= $row['id'] ?>" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                                                <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                                                    <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                                                        <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                                            edit vat date
                                                        </h3>
                                                        <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200"
                                                                data-fc-dismiss type="button">
                                                            <span class="material-symbols-rounded">close</span>
                                                        </button>
                                                    </div>
                                                    <form method="POST">
                                                        <div class="px-4 py-8 overflow-y-auto">
                                                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                                            <div class="mb-3">
                                                                <label class="text-gray-800 text-sm font-medium inline-block mb-2"> From Date </label>
                                                                <input type="date" name="from_date" class="form-input"
                                                                    value="<?= $row['from_date'] ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="text-gray-800 text-sm font-medium inline-block mb-2"> To Date </label>
                                                                <input type="date" name="to_date" class="form-input"
                                                                    value="<?= $row['to_date'] ?>" required>
                                                            </div>
                                                        </div>
                                                        <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                                            <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                                                                    data-fc-dismiss type="button">Close
                                                            </button>
                                                            <button name="update_purchase" type="submit" class="btn bg-success text-white">Update</button>
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
                <!-- Add Modal -->
            </main>

            <?php include $redirect_link .'partials/footer.php'; ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>

    <?php include $redirect_link .'partials/customizer.php'; ?>

    <?php include $redirect_link .'partials/footer-scripts.php'; ?>
</body>

</html>

<?php 
if (isset($_POST['update_purchase'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    
    $id = $_POST['id'];
    
    $purchase_update = "UPDATE `compare` SET `from_date`='$from_date',`to_date`='$to_date' WHERE `id` = '$id'";
    
    $result_add = mysqli_query($con, $purchase_update);

    if ($result_add) {
        echo "<script>window.location = 'action.php?status=success&redirect=vat_status.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=vat_status.php'; </script>";
    }
}
?>

<script>
$(document).ready(function() {
    $('#checkAll').change(function() {
        if ($(this).is(':checked')) {
            $('input[name="update[]"]').prop('checked', true);
        } else {
            $('input[name="update[]"]').each(function() {
                $(this).prop('checked', false);
            });
        }
    });

    $('input[name="update[]"]').click(function() {
        var total_checkboxes = $('input[name="update[]"]').length;
        var total_checkboxes_checked = $('input[name="update[]"]:checked').length;

        if (total_checkboxes_checked == total_checkboxes) {
            $('#checkAll').prop('checked', true);
        } else {
            $('#checkAll').prop('checked', false);
        }
    });
});
</script>

<?php
if (isset($_POST['verify'])) {
    if (isset($_POST['update'])) {
        foreach ($_POST['update'] as $update_id) {
            $update_verify = "UPDATE `bank_payment` SET `verify`= '1' WHERE id = $update_id";
            $result_update = mysqli_query($con, $update_verify);
            if ($result_update) {
                echo "<script>window.location = 'action.php?status=success&redirect=vat_status.php'; </script>";
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=vat_status.php'; </script>";
            }
        }
    }
}

if (isset($_POST['filter'])) {
    $from = $_POST['from_date'];
    $to = $_POST['to_date'];
    echo "<script>window.location = 'vat_status.php?from=$from&to=$to'; </script>";
}

if (isset($_POST['add_interval'])) {
    $from = $_POST['from_date_interval'];
    $to = $_POST['to_date_interval'];

    $insert_interval = "INSERT INTO `compare`(`from_date`, `to_date`) VALUES('$from','$to')";
    $result = mysqli_query($con, $insert_interval);
    if ($result) {
        echo "<script>window.location = 'action.php?status=success&redirect=vat_status.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=vat_status.php'; </script>";
    }
}

?>