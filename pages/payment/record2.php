<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';


?>

<head>
    <?php
    $title = "Payments from";
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
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">
                                Upaid Bills
                            </h4>
                            <div>
                                <?php if ($generateButtonVisible) : ?>
                                    <a href="<?php $redirect_link . 'pages/export.php?type=bankdb' ?>" class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
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
                                    <table id="zero_config" data-order='[[ 0, "dsc" ]]' class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Action</th>
                                                <th>Customer</th>
                                                <th>Job Number</th>
                                                <th>User</th>
                                                <th>Job Description</th>
                                                <th>Size</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Advance</th>
                                                <th>Remained</th>
                                                <th>Total</th>
                                                <th>Date</th>
                                                <th>Date Passed</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $sql = "SELECT * FROM payment WHERE remained != 0  ORDER BY payment_id DESC";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {

                                                $client = $row['client'];
                                            ?>


                                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800" data-href="record.php?id=<?php echo $row['client']; ?>">
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['payment_id'] ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                        <?php if ($updateButtonVisible) : ?>
                                                            <button type="button" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full"
                                                                data-fc-type="modal" data-fc-target="edit" data-fc-id="<?= $row['payment_id'] ?>">
                                                                <i class="mgc_pencil_line text-base me-2"></i>
                                                                Edit
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if ($deleteButtonVisible) : ?>

                                                            <a href="remove.php?client=<?= $client ?>&id=<?php echo $row['payment_id']; ?>&from=payment"
                                                                id="del-btn"
                                                                class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full">
                                                                <i class="mgc_delete_2_line text-base me-2"></i>
                                                                Delete
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if ($row['remained'] != 0) { ?>



                                                            <?php if ($addButtonVisible) : ?>
                                                                <button type="button" class="btn bg-success/25 text-success hover:bg-success hover:text-white btn-sm rounded-full"
                                                                    data-fc-type="modal" data-fc-target="payModal" data-fc-id="<?= $row['payment_id'] ?>">
                                                                    <i class="mgc_check_circle_line text-base me-2"></i>
                                                                    Pay
                                                                </button>
                                                            <?php endif; ?>
                                                        <?php } ?>
                                                    </td>

                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['client'] ?>
                                                    </td>


                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['job_number'] ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php
                                                        $user_id = $row['user_id'];
                                                        $sql_0 = "SELECT * FROM user WHERE user_id = '$user_id'";
                                                        $result_0 = mysqli_query($con, $sql_0);
                                                        while ($row_0 = mysqli_fetch_assoc($result_0)) {
                                                            echo $row_0['user_name'];
                                                        }
                                                        ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['job_description'] ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['size'] ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['quantity'] ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['unit_price'] ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['advance'] ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['remained'] ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['total'] ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['date'] ?>
                                                    </td>

                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php $rowDate = $row['date'];

                                                        // Convert the date strings to timestamps
                                                        $rowTimestamp = strtotime($rowDate);
                                                        $currentTimestamp = time(); // current timestamp

                                                        // Calculate the difference in seconds
                                                        $secondsDifference = $currentTimestamp - $rowTimestamp;

                                                        // Convert the difference to days
                                                        $daysDifference = floor($secondsDifference / (60 * 60 * 24));

                                                        // Output the result
                                                        echo "$daysDifference days"; ?>

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
                <!-- Edit modal -->
                <div id="edit" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Edit Payment
                            </h3>
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200"
                                data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST" class="overflow-y-auto">
                            <div class="px-4 py-8">
                                <div class="grid grid-cols-1 md:grid-cols-2  gap-3">
                                    <input type="hidden" name="payment_id">
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Job Number </label>
                                        <input type="text" name="job_number" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Client </label>
                                        <select name="client" class="search-select" id="search-select">
                                            <?php
                                            $sql_1 = "SELECT * FROM customer ORDER BY customer_id ASC";
                                            $result_1 = mysqli_query($con, $sql_1);
                                            while ($row_1 = mysqli_fetch_assoc($result_1)) {
                                            ?>
                                                <option value="<?php echo $row_1['customer_name'] ?>">
                                                    <?php echo $row_1['customer_name']; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Date </label>
                                        <input type="date" name="date" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Job Description </label>
                                        <input type="text" name="job_description" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Size </label>
                                        <input type="text" name="size" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Lamination </label>
                                        <select name="lamination" class="selectize" id="selectize">
                                            <option value="No Lamination">No Lamination</option>
                                            <option value="One Side Lamination">One Side Lamination</option>
                                            <option value="Two Side Lamination">Two Side Lamination</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Unit Price </label>
                                        <input type="text" name="unit_price" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Quantity </label>
                                        <input type="number" min="0" name="quantity" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Advance </label>
                                        <input type="text" name="advance" class="form-input" required>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                                    data-fc-dismiss type="button">Close
                                </button>
                                <button name="update_payment" type="submit" class="btn bg-success text-white">Edit payment</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="payModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Pay
                            </h3>
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200"
                                data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST" class="overflow-y-auto">
                            <div class="px-4 py-8">
                                <input type="hidden" name="payment_id" class="form-control">
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Paid amount </label>
                                    <input type="text" name="paid_amount" class="form-input" required readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Total remaining </label>
                                    <?php
                                    $result = mysqli_query($con, "select sum(remained) as total from payment where client = '$client'");
                                    $total = mysqli_fetch_assoc($result)['total'];
                                    ?>
                                    <input type="text" name="total" class="form-input" value="<?= $total ?>" required readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Remaining(<i>only for this project</i>)</label>
                                    <input type="text" name="remained" class="form-input" required readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Amount </label>
                                    <input type="number" min="0" step=".01" name="amount_paid" class="form-input" required>
                                </div>
                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                                    data-fc-dismiss type="button">Close
                                </button>
                                <button name="paid_payment" type="submit" class="btn bg-success text-white">Add payment</button>
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


<script>
    $(document).ready(function() {
        $('button[data-fc-target=edit]').click(function(event) {
            let id = event.currentTarget.getAttribute('data-fc-id')
            $.get(`api/payment.php?id=${id}`, data => {
                for (key in data) {
                    if (key == 'client') {
                        $(`#edit select[name='client']`).val(data[key])
                    } else {
                        $(`#edit input[name='${key}']`).val(data[key]);
                    }
                }
            }, 'json')
        })

        $('button[data-fc-target=payModal]').click(function(event) {
            console.log('open paid Modal')
            let id = event.currentTarget.getAttribute('data-fc-id')

            $('#payModal input[name="client"]').val('<?= $client ?>')
            $('#payModal input[name="payment_id"]').val(id)

            $.get(`api/pay.php?id=${id}&client=<?= $client ?>`, paidAmount => {
                $('#payModal input[name="paid_amount"]').val(paidAmount)
            })
            $.get(`api/payment.php?id=${id}`, data => {
                $('#payModal input[name="remained"]').val(data.remained)
            }, 'json')
        })

    })
</script>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('tr[data-href]');
        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                // Check if the clicked element is not the delete button or a child of the delete button
                if (!e.target.closest('#del-btn')) {
                    window.location.href = row.dataset.href;
                }
            });
        });
    });
</script>
<?php
if (isset($_POST['update_payment'])) {
    $payment_id = $_POST['payment_id'];
    $job_number = $_POST['job_number'];
    $client = $_POST['client'];
    $date = $_POST['date'];
    $job_description = $_POST['job_description'];
    $size = $_POST['size'];
    $lamination = $_POST['lamination'];
    $unit_price = $_POST['unit_price'];
    $quantity = $_POST['quantity'];
    $advance = $_POST['advance'];
    $total = $unit_price * $quantity;
    $remained = $total - $advance;

    $paper_update = "UPDATE `payment` SET `job_number`='$job_number',`client`='$client',
                    `date`='$date',`job_description`='$job_description, $lamination',`size`='$size',
                    `quantity`='$quantity',`unit_price`='$unit_price',`advance`='$advance',
                    `remained`='$remained',`total`='$total' WHERE payment_id = '$payment_id'";
    $result_update = mysqli_query($con, $paper_update);

    if ($result_update) {
        echo "<script>
                window.location = \"record.php?id=$client&status=success\";
            </script>";
    } else {
        echo "<script>
                window.location = \"record.php?id=$client&error\";
            </script>";
    }
}

if (isset($_POST['paid_payment'])) {
    $status = '';
    if (isset($_POST['amount_paid']) && is_numeric($_POST['amount_paid'])) {
        if ($_POST['amount_paid'] > $_POST['paid_amount']) {
            $status =  "error='Amount to be paid cannot be greater than paid amount(the client does not have enough verified amount).'";
        } else {
            $payment_id = $_POST['payment_id'];
            $res = mysqli_query($con, "SELECT * FROM payment WHERE payment_id = $payment_id");
            $payment_data = mysqli_fetch_assoc($res);

            $res = mysqli_query($con, "SELECT * FROM bank WHERE amount != 0 AND verified = 1 AND client = '{$payment_data['client']}'");
            $bankPaidData = mysqli_fetch_assoc($res);
            $total_remained_amount = $payment_data['remained'] - $_POST['amount_paid'];
            // print_r($bankPaidData);
            // print_r($bankPaidData['taxwithholding']);

            $bank_id = $bankPaidData['bank_id'];
            $total_paid_amount = $bankPaidData['amount'] - $_POST['amount_paid'] + $bankPaidData['taxwithholding'];
            $qry = mysqli_query($con, "UPDATE payment SET remained='$total_remained_amount' WHERE payment_id='$payment_id'");
            $qry2 = mysqli_query($con, "UPDATE bank SET amount='$total_paid_amount',taxwithholding = 0 WHERE bank_id='$bank_id'");
            $status = $qry && $qry2 ? 'success=true' : "error='unable to perform payment'";
        }
    } else {
        $status =  "error='Amount cannot be empty or non-numeric'";
    }
    echo "<script>
            window.location = \"record.php?id={$_GET['id']}&$status\";
        </script>";
}

if (isset($_GET['success'])) {
?>
    <script>
        swal({
            title: 'Paid Succesfully',
            text: 'payement updated',
            icon: 'success'
        })
    </script>
<?php
} else if (isset($_GET['error'])) {
?>
    <script>
        swal({
            title: 'Error occured',
            text: <?= $_GET['error'] ?>,
            icon: 'error'
        })
    </script>
<?php
}

?>