<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
include_once $redirect_link . 'include/mdb.php';

$client = $_GET['id'];
?>

<head>
    <?php
    $title = "Payments from $client";
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
                                Payments for <?= ucfirst($_GET['id']) ?>
                            </h4>
                            <div>
                                <?php if ($generateButtonVisible) : ?>
                                    <a href="export3.php?client=<?php echo $_GET['id'] ?> " class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
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
                                    <table id="zero_config" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="py-3 ps-4" data-searchable="false" data-orderable="false">
                                                    <div class="flex items-center h-5">
                                                        <input id="checkAll" type="checkbox" class="form-checkbox rounded">
                                                        <label for="table-checkbox-all" class="sr-only">Checkbox</label>
                                                    </div>
                                                </th>
                                                <th>#</th>
                                                <th>Action</th>
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM payment WHERE client = '$client' ORDER BY payment_id DESC";
                                            $result = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                    <td class="py-3 ps-4">
                                                        <div class="flex items-center h-5">
                                                            <input id="table-checkbox-5" name="update[]" type="checkbox" class="box form-checkbox rounded" value="<?php echo $row['payment_id'] ?>">
                                                            <label for="table-checkbox-5" class="sr-only">Checkbox</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['payment_id'] ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                        <?php if ($updateButtonVisible) : ?>
                                                            <button type="button" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full" data-fc-type="modal" data-fc-target="edit" data-fc-id="<?= $row['payment_id'] ?>">
                                                                <i class="mgc_pencil_line text-base me-2"></i>
                                                                Edit
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php if ($deleteButtonVisible) : ?>

                                                            <a href="remove.php?client=<?= $client ?>&id=<?php echo $row['payment_id']; ?>&from=payment" id="del-btn" class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full">
                                                                <i class="mgc_delete_2_line text-base me-2"></i>
                                                                Delete
                                                            </a>
                                                        <?php endif; ?>
                                                        <?php if ($row['remained'] != 0) { ?>



                                                            <?php if ($addButtonVisible) : ?>
                                                                <button type="button" class="btn bg-success/25 text-success hover:bg-success hover:text-white btn-sm rounded-full" data-fc-type="modal" data-fc-target="payModal" data-fc-id="<?= $row['payment_id'] ?>">
                                                                    <i class="mgc_check_circle_line text-base me-2"></i>
                                                                    Pay
                                                                </button>
                                                            <?php endif; ?>

                                                            <?php if ($addButtonVisible) : ?>
                                                                <button type="button" class="btn bg-info/25 text-info hover:bg-info hover:text-white btn-sm rounded-full"
                                                                    data-fc-type="modal" data-fc-target="bankstatmentModal" data-fc-id="<?= $row['payment_id'] ?>"
                                                                    onclick="openBankStatementModal(<?= $row['payment_id'] ?>)">
                                                                    <i class="mgc_check_circle_line text-base me-2"></i>
                                                                    Add Bank Statement
                                                                </button>
                                                            <?php endif; ?>
                                                        <?php } ?>
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
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="mt-4">

                                <?php if ($generateButtonVisible) : ?>
                                    <button type="submit" id="generate" class="btn bg-success text-white rounded-full">
                                        <i class="mgc_pdf_line text-base me-2"></i>
                                        Generate
                                    </button>
                                    <button type="submit" id="remainder" class="btn bg-info text-white rounded-full">
                                        <i class="mgc_print_line text-base me-2"></i>
                                        Remainder
                                    </button>


                                    <button type="submit" id="deliver" class="btn bg-warning text-white rounded-full">
                                        <i class="mgc_print_line text-base me-2"></i>
                                        Deliver Form
                                    </button>

                                <?php endif; ?>
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
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
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
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
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
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
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
                                    <input type="number" min="0" step=".00000000001" name="amount_paid" class="form-input" required>
                                </div>
                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                </button>
                                <button name="paid_payment" type="submit" class="btn bg-success text-white">Add payment</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="bankstatmentModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
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
                                        <select name="job_number" class="form-input" id="job-number-select">
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
                                        <input type="number" min="0" step=".00000001" name="amount" id="amount" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> With Hold </label>
                                        <input type="number" min="0" step=".01" name="taxwithholding" class="form-input" >
                                    </div>

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Check No </label>
                                        <input type="text"  name="check_no" class="form-input" >
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
    function openBankStatementModal(paymentId) {
        // Fetch the payment details using AJAX
        fetch('get_payment_details.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'payment_id=' + paymentId
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    document.getElementById('job-number-select').value = data.job_number;
                    document.getElementById('client-select').value = data.client;
                    document.getElementById('amount').value = data.remained;
                    // Open the modal
                    document.getElementById('bankstatmentModal').classList.remove('hidden');
                }
            })
            .catch(error => console.error('Error fetching payment details:', error));
    }
</script>


<script>
    $('#generate').click(function(e) {
        e.preventDefault();
        const checkboxes = $('table#zero_config').find('[type="checkbox"]:checked.box')
        const update = $.map(checkboxes, c => c.value)
        $.post("generate.php? ?>", {
                generate: '',
                update
            },
            function(data, status) {
                console.log(data, status)
                if (status == 'success') swal("Great!", data, "success");
                else swal("Oops!", data, "error");
                checkboxes.prop('checked', false)
            }
        ).catch(err => {
            swal("Oops!", err.responseText, "error")
        });
    });

    $('#remainder').click(function(e) {
        e.preventDefault();
        const checkboxes = $('table#zero_config').find('[type="checkbox"]:checked.box')
        const update = $.map(checkboxes, c => c.value)
        $.post("remainder.php? ?>", {
                remainder: '',
                update
            },
            function(data, status) {
                console.log(data, status)
                if (status == 'success') swal("Great!", data, "success");
                else swal("Oops!", data, "error");
                checkboxes.prop('checked', false)
            }
        ).catch(err => {
            swal("Oops!", err.responseText, "error")
        });
    });


    $('#deliver').click(function(e) {
        e.preventDefault();
        const checkboxes = $('table#zero_config').find('[type="checkbox"]:checked.box')
        const update = $.map(checkboxes, c => c.value)
        $.post("deliver.php? ?>", {
                deliver: '',
                update
            },
            function(data, status) {
                console.log(data, status)
                if (status == 'success') swal("Great!", data, "success");
                else swal("Oops!", data, "error");
                checkboxes.prop('checked', false)
            }
        ).catch(err => {
            swal("Oops!", err.responseText, "error")
        });
    });
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
           


            $sql="SELECT * FROM project_connect WHERE payment_id = '$payment_id'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $project_id = $row['project_id'];
            
            $invoice_id = $row['invoice_id'];

          $amount=  $_POST['amount_paid'];


            $user_id_managmenet= 1;

            $date= date('Y-m-d');


            $add_advance = "INSERT into  oli_invoice_payments(amount,payment_date,payment_method_id,note,invoice_id,deleted,created_by,created_at) VALUES 
                                                        ('$amount','$date','1','Payment','$invoice_id',0, '$user_id_managmenet',NOW())";

            $result_advance = mysqli_query($conn, $add_advance);



            $status = $qry && $qry2 && $add_advance ? 'success=true' : "error='unable to perform payment'";





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




if (isset($_POST['add_bank'])) {
    $client = $_POST['client'];
    $job_number = $_POST['job_number'];
    $bank_name = $_POST['bank_name'];
    $date = $_POST['date'];
    $reference_number = $_POST['reference_number'];
    $amount = $_POST['amount'];
    $with_hold = $_POST['taxwithholding'];
    $check_no = $_POST['check_no'];

    $add_paper = "INSERT INTO bank(client, name, date, reference_number, info_amount, amount, verified, jobnumber, taxwithholding, check_no) 
                    VALUES ('$client', '$bank_name', '$date', '$reference_number', '$amount', '$amount', '0', '$job_number', '$with_hold', '$check_no')";
    $result_add = mysqli_query($con, $add_paper);

    if ($result_add) {

        echo "<script>
                window.location = \"record.php?id=$client&status=success\";
            </script>";
    } else {
        echo "<script>
                window.location = \"record.php?id=$client&error\";
            </script>";
    }
}

?>