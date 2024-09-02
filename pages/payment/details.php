<?php
$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';


$clientInfo = $_GET['client'];

?>

<head>
    <?php
    $title = 'Add Payment';
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

            <main class="flex-grow p-6 flex justify-center items-center">
                <div class="card flex-grow" style="max-width: 420px">
                    <div class="card-header">
                        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Add Payment</h4>
                    </div>

                    <form method="POST" class="p-6 grid grid-cols-1 gap-3">




                        <?php
                        $client = $_GET['client'];
                        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($client)) {
                        $sql = "SELECT SUM(amount + taxwithholding) as paid_amount FROM bank WHERE client = '$client'
                        AND verified = 1";
                        $res = mysqli_query($con, $sql);
                        $paid_amount = mysqli_fetch_assoc($res)['paid_amount'] ?? 0;
                        
                        }
                        ?>


                        <input type="hidden" name="client" value="<?php echo$client ?>" />
                        <div class=" mb-3">
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Paid amount
                            </label>
                            <input type="text" value="<?php if (isset($paid_amount)) echo $paid_amount?>"
                                name=" paid_amount" class="form-input" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Amount </label>
                            <input type="number" min="0" step=".01" name="amount_paid" class="form-input" required>
                        </div>
                        <button name="allpaid_payment" type="submit" class="btn bg-success text-white">Pay</button>
                    </form>
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

                     if ($qry&&$qry2) {
        echo "<script>window.location = 'action.php?status=success&redirect=payment_managment.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=payment_managment.php'; </script>";
    }
                } else {
                    $total_paid_amount = $bankPaidData['amount'] - $_POST['amount_paid'] + $bankPaidData['taxwithholding'];
                    $qry = mysqli_query($con, "UPDATE payment SET remained='$testd' WHERE payment_id='$id'");
                    $qry2 = mysqli_query($con, "UPDATE bank SET amount='$total_paid_amount', taxwithholding = 0 WHERE bank_id='$bank_id'");

                                    if ($qry&&$qry2) {
        echo "<script>window.location = 'action.php?status=success&redirect=payment_managment.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=payment_managment.php'; </script>";
    }
                    break;
                }
            }
        }
    } else $status = "error='Amount cannot be empty or non-numeric'";
    header("Location: payment_managment.php?$status");
}
?>