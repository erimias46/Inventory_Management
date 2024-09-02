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
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Payments</h4>
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
                                                <th>Action</th>
                                                <th style="display: none;">Job numbers</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT client, GROUP_CONCAT(job_number ORDER BY job_number SEPARATOR ', ') AS job_numbers, MAX(payment_id) AS recent_id FROM payment GROUP BY client ORDER BY recent_id DESC";

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
                                                        <?php echo $row['client'] ?> </td>




                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                        <?php if ($calculateButtonVisible) : ?>
                                                            <a href="record.php?id=<?php echo $row['client']; ?>" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full">
                                                                <i class="mgc_eye_2_line text-base me-2"></i>
                                                                Show Detail
                                                            </a>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php if ($addButtonVisible) : ?>

                                                            <!-- <button type="button"
                                                        class="btn bg-success/25 text-success hover:bg-success hover:text-white btn-sm rounded-full"
                                                        data-fc-type="modal" data-fc-target="payModal"
                                                        data-fc-client="<?= $row['client'] ?>">
                                                        <i class="mgc_check_circle_line text-base me-2"></i>
                                                        Pay
                                                    </button> -->

                                                            <a href="details.php?client=<?= urlencode($row['client']) ?>" class="btn bg-success/25 text-success hover:bg-success hover:text-white btn-sm rounded-full">
                                                                <i class="mgc_check_circle_line text-base me-2"></i>
                                                                Pay
                                                            </a>

                                                        <?php endif; ?>
                                                    </td>

                                                    <td style="display: none;" class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['job_numbers']; ?>
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
                <div id="payModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Pay
                            </h3>
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST">
                            <div class="px-4 py-8 overflow-y-auto">
                                <input type="hidden" name="client">
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Paid amount
                                    </label>
                                    <input type="text" name="paid_amount" class="form-input" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Amount </label>
                                    <input type="number" min="0" step=".01" name="amount_paid" class="form-input" required>
                                </div>
                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                </button>
                                <button name="allpaid_payment" type="submit" class="btn bg-success text-white">Pay</button>
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
</body>

</html>
<?php
if (isset($_POST['allpaid_payment'])) {
    $status = 'success=true';
    if (isset($_POST['amount_paid']) && is_numeric($_POST['amount_paid'])) {
        if ($_POST['amount_paid'] > $_POST['paid_amount']) {
            $status = "error='Amount to be paid cannot be greater than paid amount(the client does not have enough verified amount).'";
        } else {
            $payment_clinet = $_POST['client'];
            $amount = $_POST['amount_paid'];
            $res = mysqli_query($con, "SELECT * FROM bank WHERE amount > 0 AND verified = 1 AND client = '{$_POST['client']}'");
            $bankPaidData = mysqli_fetch_assoc($res);
            $bank_id = $bankPaidData['bank_id'];
            $not_remided_list = [];
            $amount += $bankPaidData['taxwithholding'];
            // print_r($bankPaidData['taxwithholding']);
            $total_paid_amount = 0;
            $res = mysqli_query($con, "SELECT * FROM payment WHERE client = '$payment_clinet' AND remained != 0 ORDER BY payment_id DESC");
            while ($payment = mysqli_fetch_assoc($res)) {
                $testd = $payment['remained'] - $amount;
                $id = $payment['payment_id'];
                if ($testd < 0) {
                    $amount = $testd * -1;
                    $total_paid_amount = $bankPaidData['amount'] - $amount + $bankPaidData['taxwithholding'];
                    $qry = mysqli_query($con, "UPDATE payment SET remained='0' WHERE payment_id='$id'");
                    $qry2 = mysqli_query($con, "UPDATE bank SET amount='$total_paid_amount', taxwithholding = 0 WHERE bank_id='$bank_id'");
                } else {
                    $total_paid_amount = $bankPaidData['amount'] - $_POST['amount_paid'] + $bankPaidData['taxwithholding'];
                    $qry = mysqli_query($con, "UPDATE payment SET remained='$testd' WHERE payment_id='$id'");
                    $qry2 = mysqli_query($con, "UPDATE bank SET amount='$total_paid_amount', taxwithholding = 0 WHERE bank_id='$bank_id'");
                    break;
                }
            }
        }
    } else $status = "error='Amount cannot be empty or non-numeric'";
    header("Location: payment_managment.php?$status");
}
?>
<script>
    $(document).ready(function() {
        $('button[data-fc-target=payModal]').click(function(event) {
            let client = event.target.getAttribute('data-fc-client')

            $('#payModal input[name="client"]').val(client)

            $.get(`api/pay.php?client=${client}`, paidAmount => {
                $('#payModal input[name="paid_amount"]').val(paidAmount)
            })
        })
    })
</script>