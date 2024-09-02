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

        $calculateButtonVisible = ($module['payview'] == 1);
        $addButtonVisible = ($module['payadd'] == 1);
        $deleteButtonVisible = ($module['paydelete'] == 1);
        $verifyButtonVisible = ($module['payverify'] == 1);
        $updateButtonVisible = ($module['payedit'] == 1);
        $generateButtonVisible = ($module['paygenerate'] == 1);
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
                                    <select name="method" class="selectize" id="selectize">
                                        <option value="all">All</option> <!-- Adding the 'All' option -->
                                        <?php
                                        $sql = "SELECT * FROM bank GROUP BY name";
                                        $result = mysqli_query($con, $sql);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                            <option value="<?php echo $row['name']; ?>" <?= (isset($_GET['method']) && $_GET['method'] === $row['name']) ? 'selected' : '' ?>>
                                                <?php echo $row['name']; ?>
                                            </option>
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
                                                $from_date = isset($_GET['from']) ? $_GET['from'] : '';
                                                $to_date = isset($_GET['to']) ? $_GET['to'] : '';
                                                $method = isset($_GET['method']) ? $_GET['method'] : '';

                                                // Start building the SQL query
                                                $sql = "SELECT * FROM bank WHERE verified = '0'";

                                                // Add date filter if both dates are provided
                                                if (!empty($from_date) && !empty($to_date)) {
                                                    $sql .= " AND DATE(date) >= '$from_date' AND DATE(date) <= '$to_date'";
                                                }

                                                // Add bank filter if a specific bank is selected
                                                if ($method !== 'all' && !empty($method)) {
                                                    $sql .= " AND name = '$method'";
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
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['info_amount']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['jobnumber']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['taxwithholding']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['verified']; ?></td>
                                                        <td class="px-2.5 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['date']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php if ($updateButtonVisible) { ?>
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </main>
        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <?php include $redirect_link . 'partials/right-sidebar.php'; ?>

    <?php include $redirect_link . 'partials/vendor-scripts.php'; ?>

</body>

</html>