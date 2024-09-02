<?php
$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
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


        $calculateButtonVisible = ($module['payview'] == 1) ? true : false;


        $addButtonVisible = ($module['payadd'] == 1) ? true : false;
        $deleteButtonVisible = ($module['paydelete'] == 1) ? true : false;

        $verifyButtonVisible = ($module['payverify'] == 1) ? true : false;



        $updateButtonVisible = ($module['payedit'] == 1) ? true : false;


        $generateButtonVisible = ($module['paygenerate'] == 1) ? true : false;
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
    $title = 'Payment Filter';
    include $redirect_link . 'partials/title-meta.php'; ?>

    <?php include $redirect_link . 'partials/head-css.php'; ?>
</head>

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
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Filter</h4>
                    </div>
                    <div class="p-6">
                        <form>
                            <div class="flex flex-wrap">
                                <div class="m-2 flex-1">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">From </label>
                                    <input type="date" name="from" class="form-input" value="<?= isset($_GET['from']) ? $_GET['from'] : '' ?>" required>
                                </div>
                                <div class="m-2 flex-1">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">To </label>
                                    <input type="date" name="to" class="form-input" value="<?= isset($_GET['to']) ? $_GET['to'] : '' ?>" required>
                                </div>
                                <div class="m-2 flex-1">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Bank Name </label>

                                    <select name="method" class="selectize" id="selectize" value="<?= isset($_GET['method']) ? $_GET['method'] : '' ?>">
                                        <option value="all">All</option>
                                        <?php
                                        $sql = "SELECT * FROM bank GROUP BY name";
                                        $result = mysqli_query($con, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <option value="<?php echo $row['name']; ?>"> <?php echo $row['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <a href="?" class="btn btn-sm bg-danger text-white rounded-full me-2">
                                    <i class="msr text-base me-2">restart_alt</i>
                                    Reset
                                </a>
                                <button type="submit" class="btn btn-sm bg-success text-white rounded-full">
                                    <i class="msr text-base me-2">filter_list</i>
                                    Filter
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Payment Filter</h4>
                            <div>

                                <?php if ($generateButtonVisible) { ?>
                                    <a href="<?= $redirect_link . 'pages/export.php?type=bank' ?>" class=" btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                        <i class="msr text-base me-2">picture_as_pdf</i>
                                        Export
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <form method="POST" action="export2.php">


                            <div class="overflow-x-auto">
                                <div class="min-w-full inline-block align-middle">
                                    <div class="overflow-hidden">
                                        <table id="zero_config" data-order='[[ 1, "dsc" ]]' class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="py-3 ps-4" data-searchable="false" data-orderable="false">
                                                        <div class="flex items-center h-5">
                                                            <input id="checkAll" type="checkbox" class="form-checkbox rounded">
                                                            <label for="table-checkbox-all" class="sr-only">Checkbox</label>
                                                        </div>
                                                    </th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Client Name</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Bank Name</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Ref</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Job Number</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Tax</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($_GET['from']) && !empty($_GET['to'])) {
                                                    $from_date = $_GET['from'];
                                                    $to_date = $_GET['to'];
                                                }

                                                $method = '';
                                                if (!empty($_GET['method'])) {
                                                    $method = $_GET['method'];
                                                    echo $method;
                                                }

                                                if (empty($method)) {
                                                    if (isset($from_date) && isset($to_date)) {
                                                        $sql = "SELECT * FROM bank WHERE DATE(date) >= '$from_date' AND DATE(date) <= '$to_date' AND verified = '0'";
                                                    } else {
                                                        $sql = "SELECT * FROM bank WHERE verified = '0'";
                                                    }
                                                } else {
                                                    if (isset($from_date) && isset($to_date)) {
                                                        $sql = "SELECT * FROM bank WHERE DATE(date) >= '$from_date' AND DATE(date) <= '$to_date' AND name='$method' AND verified = '0'";
                                                    } else {
                                                        $sql = "SELECT * FROM bank WHERE name='$method' AND verified = '0'";
                                                    }
                                                }
                                                $result = mysqli_query($con, $sql);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                        <td class="py-3 ps-4">
                                                            <div class="flex items-center h-5">
                                                                <input id="table-checkbox-5" name="update[]" type="checkbox" class="form-checkbox rounded" value="<?php echo $row['bank_id'] ?>">
                                                                <label for="table-checkbox-5" class="sr-only">Checkbox</label>
                                                            </div>
                                                        </td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['bank_id']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['client']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['name']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['reference_number']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['amount']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['jobnumber']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo number_format($row['taxwithholding'], 2); ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            <?php
                                                            $verify = $row['verified'];
                                                            if ($verify == 1) {
                                                            ?>
                                                                <span class="text-success">Verified</span>
                                                            <?php } else { ?>
                                                                <span class="text-danger">Unverified</span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['date']; ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-between items-center">

                                <?php if ($verifyButtonVisible) { ?>
                                    <button type="submit" name="verify" class="btn bg-success text-white rounded-full">
                                        <i class="mgc_check_circle_line text-base me-2"></i>
                                        verify
                                    </button>
                                <?php } ?>
                                <?php
                                if (!$method) {
                                    if (isset($from_date, $to_date))
                                        $sql = "SELECT SUM(amount) as amount FROM bank WHERE DATE(date) >= '$from_date' AND DATE(date) <= '$to_date' AND verify = '0'";
                                    else
                                        $sql = "SELECT SUM(amount) as amount FROM bank WHERE verified = '0'";
                                } else {
                                    if (isset($from_date, $to_date))
                                        $sql = "SELECT SUM(amount) as amount FROM bank WHERE DATE(date) >= '$from_date' AND DATE(date) <= '$to_date' AND {$method} AND verify = '0'";
                                    else
                                        $sql = "SELECT SUM(amount) as amount FROM bank {$method} AND verified = '0'";
                                }
                                $result = mysqli_query($con, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $amount =  number_format($row['amount'], 2);
                                }
                                ?>


                                <button type="submit" name="export" class="btn bg-primary text-white rounded-full">
                                    <i class="mgc_download text-base me-2"></i>
                                    Export to Excel
                                </button>
                                <h4 id="total">Total amount: <b> <?= $amount ?> </b></h4>
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




</body>

</html>

<?php
if (isset($_POST['verify'])) {
    if (isset($_POST['update'])) {
        foreach ($_POST['update'] as $update_id) {
            $update_verify = "UPDATE `bank` SET `verified`= '1' WHERE bank_id = $update_id";
            $result_update = mysqli_query($con, $update_verify);
            if ($result_update) {
                echo "<script>window.location = 'action.php?status=success&redirect=payment_filter.php'; </script>";
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=payment_filter.php'; </script>";
            }
        }
    }
}
?>