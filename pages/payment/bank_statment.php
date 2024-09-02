<?php
$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
?>

<head>
    <?php
    $title = 'Bank Statment';
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


        $calculateButtonVisible = ($module['bankview'] == 1) ? true : false;


        $addButtonVisible = ($module['bankadd'] == 1) ? true : false;
        $deleteButtonVisible = ($module['bankdelete'] == 1) ? true : false;

        $verifyButtonVisible = ($module['bankverify'] == 1) ? true : false;



        $updateButtonVisible = ($module['bankedit'] == 1) ? true : false;


        $generateButtonVisible = ($module['bankgenerate'] == 1) ? true : false;
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
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Bank Statments</h4>
                            <div>

                                <?php if ($addButtonVisible) : ?>
                                    <button type="button" data-fc-type="modal" data-fc-target="addModal" class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                        <i class="msr text-base me-2">add</i>
                                        Add Bank Statment
                                    </button>
                                <?php endif; ?>
                                <?php if ($generateButtonVisible) : ?>
                                    <a href="<?= $redirect_link . 'pages/export.php?type=bank' ?>" class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
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
                                                <th>Client</th>
                                                <th>Job Number</th>
                                                <th>Bank</th>
                                                <th>Ref. Number</th>
                                                <th>Amount</th>
                                                <th>With Holding</th>
                                                <th>Check No</th>
                                                <th>Date</th>
                                                <th>Verify</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM `bank` ORDER BY `bank_id` DESC";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['bank_id'] ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php if ($row['verified'] != 1) { ?>

                                                            <?php if ($verifyButtonVisible) : ?>
                                                                <button id="verify" class="btn bg-success/25 text-success hover:bg-success hover:text-white btn-sm rounded-full" data-fc-id="<?= $row['bank_id'] ?>">
                                                                    <i class="mgc_check_circle_line text-base me-2"></i>
                                                                    Verify
                                                                </button>
                                                            <?php endif; ?>
                                                        <?php } ?>

                                                        <?php if ($updateButtonVisible) : ?>
                                                            <button type="button" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full" data-fc-type="modal" data-fc-target="edit" data-fc-id="<?= $row['bank_id'] ?>">
                                                                <i class="mgc_pencil_line text-base me-2"></i>
                                                                Edit
                                                            </button>
                                                        <?php endif; ?>

                                                        <?php if ($deleteButtonVisible) : ?>

                                                            <a href="remove.php?id=<?php echo $row['bank_id']; ?>&from=bank" id="del-btn" class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full">
                                                                <i class="mgc_delete_2_line text-base me-2"></i>
                                                                Delete
                                                            </a>

                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['client']; ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['jobnumber']; ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['name']; ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['reference_number']; ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['amount']; ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['taxwithholding']; ?>
                                                    </td>

                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['check_no']; ?>
                                                    </td>



                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['date']; ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php
                                                        $verify = $row['verified'];
                                                        if ($verify == 1) {
                                                        ?>
                                                            <span class="text-success">Verified</span>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <span class="text-danger">Unverified</span>
                                                        <?php
                                                        }
                                                        ?>
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
                <!-- Add Modal -->
                <div id="addModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Add Bank Statment
                            </h3>
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST" class="overflow-y-auto">
                            <div class="px-4 py-8">
                                <div class="grid grid-cols-1 md:grid-cols-2  gap-3">
                                    <!-- <input type="hidden" name="payment_id"> -->
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Client</label>
                                        <select name="client" class="form-input" id="client-select">
                                            <?php
                                            $sql_1 = "SELECT * FROM customer ORDER BY customer_id DESC";
                                            $result_1 = mysqli_query($con, $sql_1);
                                            while ($row_1 = mysqli_fetch_assoc($result_1)) {
                                            ?>
                                                <option value="<?php echo $row_1['customer_name'] ?>"
                                                    <?php if (isset($customer) && $row_1['customer_name'] == $customer) echo "selected"; ?>>
                                                    <?php echo $row_1['customer_name']; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <!-- Job Number Dropdown -->
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Job Number</label>
                                        <select name="job_number" class="search-select" id="job-number-select">
                                            <?php
                                            $sql = "SELECT * FROM payment ORDER BY job_number DESC";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <option value="<?php echo $row['job_number'] ?>" data-client="<?php echo $row['client']; ?>">
                                                    <?php echo $row['job_number']; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Bank Name </label>
                                        <select name="bank_name" class="search-select" id="search-select">
                                            <?php
                                            $sql = "SELECT * FROM bankdb";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <option value="<?php echo $row['bankname'] ?>">
                                                    <?php echo $row['bankname']; ?>
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
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Reference Number </label>
                                        <input type="text" name="reference_number" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Amount </label>
                                        <input type="number" min="0" step=".0000000001" name="amount" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> With Hold </label>
                                        <input type="number" min="0" step=".01" name="taxwithholding" class="form-input" >
                                    </div>

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Check No </label>
                                        <input type="text" name="checkno" class="form-input">
                                    </div>




                                </div>
                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                </button>
                                <button name="add_bank" type="submit" class="btn bg-success text-white">Add Statment</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Edit Modal -->
                <div id="edit" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Edit Bank Statment
                            </h3>
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST" class="overflow-y-auto">
                            <div class="px-4 py-8">
                                <div class="grid grid-cols-1 md:grid-cols-2  gap-3">
                                    <input type="hidden" name="bank_id">
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
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Job Number </label>
                                        <select name="job_number" class="search-select" id="search-select">
                                            <?php
                                            $sql = "SELECT * FROM payment";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <option value="<?php echo $row['job_number'] ?>">
                                                    <?php echo $row['job_number']; ?>
                                                </option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Bank Name </label>
                                        <select name="bank_name" class="search-select" id="search-select">
                                            <?php
                                            $sql = "SELECT * FROM bankdb";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <option value="<?php echo $row['bankname'] ?>">
                                                    <?php echo $row['bankname']; ?>
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
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Reference Number </label>
                                        <input type="text" name="reference_number" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Amount</label>
                                        <input type="number" name="amount" step="0.00000000000001" class="form-input" required>
                                    </div>

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> With Hold </label>
                                        <input type="number" min="0" step="0.00000000000001" name="taxwithholding" class="form-input" >
                                    </div>

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Check No </label>
                                        <input type="text" name="check_no" class="form-input">
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                </button>
                                <button name="update_bank" type="submit" class="btn bg-success text-white">Edit payment</button>
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



    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>






    <script>
        $(document).ready(function() {
            // Apply Select2 to the client dropdown
            $('#client-select').select2({
                placeholder: "Select a client",
                allowClear: true
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Pre-select client using a PHP value if available
            var preselectedClient = '<?php echo isset($customer) ? addslashes($customer) : ''; ?>';

            if (preselectedClient) {
                console.log('Preselected Client:', preselectedClient); // Debugging

                var clientFound = false;
                $('#client-select option').each(function() {
                    if ($(this).val().trim() === preselectedClient.trim()) {
                        $(this).prop('selected', true);
                        clientFound = true;
                        return false; // Break the loop once the client is found
                    }
                });

                if (!clientFound) {
                    console.warn('Preselected client not found in the dropdown:', preselectedClient);
                }

                // Trigger change event in case other scripts are dependent on it
                $('#client-select').trigger('change');
            }

            $('#job-number-select').change(function() {
                var selectedClient = $(this).find(':selected').data('client').trim();

                console.log('Selected Client:', selectedClient); // Debugging

                // Ensure the client name is being selected in the dropdown
                var clientFound = false;
                $('#client-select option').each(function() {
                    if ($(this).val().trim() === selectedClient) {
                        $(this).prop('selected', true);
                        clientFound = true;
                        return false; // Break the loop once the client is found
                    }
                });

                if (!clientFound) {
                    console.warn('Client not found in the dropdown:', selectedClient);
                }

                // Trigger change event in case other scripts are dependent on it
                $('#client-select').trigger('change');
            });
        });
    </script>



    <script>
        $(document).ready(function() {
            $('button[data-fc-target=edit]').on('click', function(event) {
                let id = event.currentTarget.getAttribute('data-fc-id')
                $.get(`api/bank.php?id=${id}`, data => {
                    for (key in data) {
                        if (key == 'client') {
                            $(`#edit select[name='client']`).val(data[key])
                        } else {
                            $(`#edit input[name='${key}']`).val(data[key]);
                        }
                    }
                }, 'json')
            })

            $('form#edit').on('submit', function(event) {
                event.preventDefault();
                $.post('api/bank.php', $(this).serialize(), (data, status) => {
                    if (status == 'success') {
                        $('#payModal').modal('hide')
                        swal({
                            title: "Succssfully updated bank statement",
                            text: data || "successfully updated bank statement.",
                            icon: "success",
                        }).then(result => {
                            location.reload()
                        })
                    } else {
                        swal({
                            title: "Unknow error occured",
                            text: data || "error occured while updating bank statement",
                            icon: "error",
                        })
                    }
                }).catch(err => {
                    console.log(err);
                    swal({
                        title: "Unknow error occured: " + err.statusText,
                        text: "error occured while updating bank statement",
                        icon: "error",
                    })
                })
            })

            $('#verify').on('click', function(e) {
                let id = e.target.getAttribute('data-fc-id')
                $.post('api/verify.php', {
                    bank_id: id
                }, function(data, status) {
                    if (status == 'success') {
                        swal({
                            title: "Succssfully verified bank statement",
                            text: data || "successfully verified bank statement.",
                            icon: "success",
                        }).then(result => {
                            location.reload()
                        })
                    } else {
                        swal({
                            title: "Unknow error occured",
                            text: data || "error occured while verifying bank statement",
                            icon: "error",
                        })
                    }
                }).catch(err => {
                    console.log(err);
                    swal({
                        title: "Unknow error occured: " + err.statusText,
                        text: "error occured while updating bank statement",
                        icon: "error",
                    })
                })

            })
        })
    </script>
    </script>
</body>

</html>
<?php
if (isset($_POST['add_bank'])) {
    $client = $_POST['client'];
    $job_number = $_POST['job_number'];
    $bank_name = $_POST['bank_name'];
    $date = $_POST['date'];
    $reference_number = $_POST['reference_number'];
    $amount = $_POST['amount'];
    $with_hold = $_POST['taxwithholding'];
    $check_no = $_POST['checkno'];

    $add_paper = "INSERT INTO bank(client, name, date, reference_number, info_amount, amount, verified, jobnumber, taxwithholding,check_no) 
                    VALUES ('$client', '$bank_name', '$date', '$reference_number', '$amount', '$amount', '0', '$job_number', '$with_hold','$check_no')";
    $result_add = mysqli_query($con, $add_paper);

    if ($result_add) {
        echo "<script>window.location = 'action.php?status=success&redirect=bank_statment.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=bank_statment.php'; </script>";
    }
}

if (isset($_POST['update_bank'])) {
    $bank_id = $_POST['bank_id'];
    $client = $_POST['client'];
    $job_number = $_POST['job_number'];
    $bank_name = $_POST['bank_name'];
    $date = $_POST['date'];
    $reference_number = $_POST['reference_number'];
    $amount = $_POST['amount'];
    $with_hold = $_POST['taxwithholding'];
    $check_no = $_POST['check_no'];

    $bank_update = "UPDATE `bank` SET `client`='$client', `name`='$bank_name', `date`='$date',
                    `reference_number`= '$reference_number', `info_amount`='$amount', `amount`='$amount',
                    `jobnumber` = '$job_number', `taxwithholding` = '$with_hold' , `check_no`= '$check_no' WHERE `bank_id` = '$bank_id'";
    $result_update = mysqli_query($con, $bank_update);

    if ($result_update) {
        echo "<script>window.location = 'action.php?status=success&redirect=bank_statment.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=bank_statment.php'; </script>";
    }
}

?>