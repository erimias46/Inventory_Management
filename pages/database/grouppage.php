<script type="text/javascript">
    $(document).ready(function() {
        $('#zero_config').DataTable({
            "paging": false, // Disable pagination
            "searching": false, // Enable searching
            "order": [], // Disable initial sorting
            "dom": '<"top"lf>rt<"bottom"ip><"clear">' // Customize the layout (search box on the left)
        });
    });
</script>
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
    $title = 'Payment Managment';
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
                <div class="card">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Group Payments</h4>
                            <div>


                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <div class="min-w-full inline-block align-middle">
                                <div class="overflow-hidden">
                                    <table id="zero_config" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Client</th>
                                                <th>View</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM brocher_group  ORDER BY id DESC";

                                            $result = mysqli_query($con, $sql);
                                            $i = 0;
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $i++;
                                            ?>
                                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $i ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['customer'] ?> </td>




                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                        <?php if ($calculateButtonVisible) : ?>
                                                            <a href="detail.php?id=<?php echo $row['id']; ?>&type=<?php echo $row['type']; ?>" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full">
                                                                <i class="mgc_eye_2_line text-base me-2"></i>
                                                                Show Detail
                                                            </a>
                                                        <?php endif; ?>

                                                    </td>

                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <a id="del-btn" href="delete_group.php?id=<?php echo $row['id']; ?>" class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full">
                                                            <i class="mgc_delete_2_line text-base me-2"></i> Delete
                                                        </a>
                                                    </td>


                                                </tr>

                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- pay modal -->

            </main>

            <?php include $redirect_link . 'partials/footer.php'; ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>

    <?php include $redirect_link . 'partials/customizer.php'; ?>

    <?php include $redirect_link . 'partials/footer-scripts.php'; ?>
</body>

</html>