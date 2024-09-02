<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');

// if (!isset($type)) {
//     echo "<script>window.location = 'index.php'; </script>";
// }

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


        $calculateButtonVisible = ($module['dataview'] == 1) ? true : false;


        $addButtonVisible = ($module['dataadd'] == 1) ? true : false;


        $updateButtonVisible = ($module['dataedit'] == 1) ? true : false;
        $deleteButtonVisible = ($module['datadelete'] == 1) ? true : false;


        $generateButtonVisible = ($module['datagenerate'] == 1) ? true : false;
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
    $title = "Database";
    include $redirect_link . 'partials/title-meta.php'; ?>

    <?php include $redirect_link . 'partials/head-css.php'; ?>


    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

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
                        <p class="text-sm text-gray-500 dark:text-gray-500">
                            Filter
                        </p>
                    </div>

                    <div class="p-6">
                        <form method="POST">
                            <div class="flex flex-wrap">
                                <div class="me-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                        From Date </label>
                                    <input type="date" name="from_date" class="form-input" required>
                                </div>
                                <div class="me-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                        To Date </label>
                                    <input type="date" name="to_date" class="form-input" required>
                                </div>

                            </div>
                            <div class="flex justify-end">
                                <a href="payment.php" class="btn btn-sm bg-danger text-white rounded-full me-2">
                                    <i class="msr text-base me-2">restart_alt</i>
                                    Reset</a>
                                <button name="filter" type="submit" class="btn btn-sm bg-success text-white rounded-full">
                                    <i class="msr text-base me-2">filter_list</i>
                                    Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium"><?= $title ?></h4>
                            <div>
                                <?php if ($generateButtonVisible) : ?>
                                    <a href='<?= $redirect_link . "pages/export.php" ?>' class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
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
                                                <th scope="col" class="py-3 ps-4" data-searchable="true" data-orderable="true">
                                                    <div class="flex items-center h-5">
                                                        <input id="checkAll" type="checkbox" class="form-checkbox rounded">
                                                        <label for="table-checkbox-all" class="sr-only">Checkbox</label>
                                                    </div>
                                                </th>
                                                <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                                <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Type </th>
                                                <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                                <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Total Price </th>
                                                <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Total Price VAT</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php //$primary_key = 'book_id';

                                            if ($_SESSION['username'] == 'masteradmin') {

                                                $sql = "SELECT 'brocher' AS type, brocher_id AS id, unit_price, total_price, including_vat, date FROM brocher UNION ALL SELECT 'design' AS type, digital_id AS id, unit_price, total_price, total_price_vat, date FROM design UNION ALL SELECT 'banner' AS type, banner_id AS id, unit_price, total_price, total_price_vat, date FROM banner UNION ALL SELECT 'single_page' AS type, single_page_id AS id, unit_price, total_price, total_price_vat, date FROM single_page UNION ALL SELECT 'multipage' AS type, multi_page_id AS id, unit_price, total_price, total_price_vat, date FROM multi_page UNION ALL SELECT 'digital' AS type, digital_id AS id, unit_price, total_price, total_price_vat, date FROM digital UNION ALL SELECT 'other_digital' AS type, otherdigital_id AS id, unit_price, total_price, total_price_vat, date FROM otherdigital ORDER BY date DESC;";
                                            } else {
                                                $sql = "SELECT * FROM book WHERE private != 'yes' ORDER BY book_id DESC";
                                            }

                                            $result22 = mysqli_query($con, $sql);
                                            while ($row = mysqli_fetch_assoc($result22)) {

                                            ?>
                                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                    <td class="py-3 ps-4">
                                                        <div class="flex items-center h-5">
                                                            <input id="table-checkbox-5" name="update[]" type="checkbox" class="box form-checkbox rounded" value="">
                                                            <label for="table-checkbox-5" class="sr-only">Checkbox</label>
                                                        </div>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                        <?php if ($deleteButtonVisible) : ?>
                                                            <a id="del-btn" href="remove.php?type=<?= $row['type'] ?>&key=<?php echo $row['id']; ?>&from=<?= $row['type']; ?>" class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i class="mgc_delete_2_line text-base me-2"></i> Delete</a>
                                                        <?php endif; ?>

                                                        <?php


                                                        $type = $row['type'];

                                                        if ($updateButtonVisible) : ?>



                                                            <button type="button" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full" data-fc-type="modal" data-fc-target="edit" data-fc-id="<?= $row['id'] ?>">
                                                                <i class="mgc_pencil_line text-base me-2"></i>
                                                                Edit <?= $row['id'] ?>
                                                            </button>
                                                        <?php endif; ?>

                                                        <?php

                                                        $type = $row['type'];


                                                        echo  $type;


                                                        if ($addButtonVisible) : ?>
                                                            <button type="button" class="btn bg-primary/25 text-primary hover:bg-primary hover:text-white btn-sm rounded-full" data-fc-type="modal" data-fc-target="payModal" data-fc-id="<?= $row['id'] ?>" data-fc-b-type="<?= $row['type']  ?>">
                                                                <i class="mgc_currency_dollar_2_fill text-base me-2"></i>
                                                                Add payment
                                                            </button>
                                                        <?php endif; ?>


                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['id']; ?> </td>
                                                    <td> <?php echo $row['type']; ?> </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200 text-ellipsis overflow-hidden" style="max-width: 32ch"> <?php echo $row['unit_price']; ?> </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['total_price']; ?> </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"> <?php echo $row['including_vat']; ?> </td>

                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <?php if (mysqli_num_rows($result22) == 0) { ?>
                                        <div class="flex justify-center items-center h-96">
                                            <h1 class="text-2xl text-gray-400">No data found</h1>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <?php if ($generateButtonVisible) : ?>
                                <button type="submit" id="generate" class="btn bg-success text-white rounded-full">
                                    <i class="mgc_pdf_line text-base me-2"></i>
                                    Generate
                                </button>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Edit modal -->
                <div id="edit" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Edit >
                            </h3>
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST" id="update">
                            <div class="px-4 py-8 overflow-y-auto">
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">


                                </div>
                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                </button>
                                <button type="submit" class="btn bg-success text-white">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Add payment -->
                <div id="payModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div class="md:w-lg fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Add Payment
                            </h3>
                            <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST" id="pay">
                            <div class="px-4 py-8 overflow-y-auto">
                                <div class="grid grid-cols-1 md:grid-cols-2  gap-3">
                                    <input type="hidden" name="vat" />
                                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>" />


                                    <input type="text" name="type" id="payModalType" />
                                    <input type="text" name="id" id="payModalId" />
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Customer(<i>readonly</i>) </label>
                                        <input type="text" name="customer" class="form-input" readonly>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Date </label>
                                        <input type="date" name="date" value="<?= date('Y-m-d') ?>" class="form-input" required>
                                    </div>

                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">End Date </label>
                                        <input type="date" name="enddate" value="<?= date('Y-m-d') ?>" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Job Description
                                        </label>
                                        <input type="text" name="description" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Size </label>
                                        <input type="text" name="size" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Lamination </label>
                                        <select class="form-select" name="lamination" id="inputGroupSelect04" required>
                                            <option value="No Lamination">No Lamination
                                            </option>
                                            <option value="One Side Lamination">One Side
                                                Lamination</option>
                                            <option value="Two Side Lamination">Two Side
                                                Lamination</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Unit Price </label>
                                        <input type="number" min="0" step=".0000000000000001" name="unit_price" id="unit_price" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Unit Price VAT </label>
                                        <input type="number" min="0" step=".0000000000000001" name="unit_price_vat" id=" unit_price_vat" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Quantity </label>
                                        <input type="number" min="0" name="quantity" id="quantity" class="form-input" required>
                                    </div>
                                    <div>
                                        <label class="text-gray-800 text-sm font-medium inline-block mb-2">Advance </label>
                                        <input type="number" min="0" step=".01" name="advance" id="advance" value="0" class="form-input" required>
                                    </div>

                                    <?php
                                    echo $row['id'];
                                    echo $type;
                                    if ($type == 'banner') { ?>
                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Unit Banner </label>
                                            <input type="text" min="0" step=".01" name="unitbanner_type" id="unitbanner_type" class="form-input" required>
                                        </div>
                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Width</label>
                                            <input type="text" min="0" step=".01" name="width" id="width" class="form-input" required>
                                        </div>

                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Length </label>
                                            <input type="text" min="0" step=".01" name="lengths" id="lengths" class="form-input" required>
                                        </div>
                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">commitonprice </label>
                                            <input type="text" min="0" step=".01" name="commitonprice" id="commitonprice" class="form-input" required>
                                        </div>
                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Cvat </label>
                                            <input type="text" min="0" step=".01" name="cvat" id="cvat" class="form-input" required>
                                        </div>

                                        <div>
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">Production </label>
                                            <input type="text" name="product" id="product" class="form-input" required>
                                        </div>



                                    <?php } ?>
                                    <div class="col-span-1 md:col-span-2 flex justify-between">
                                        <div>
                                            <h4 id="remedial"></h4>
                                        </div>
                                        <div>
                                            <div class="form-group mb-3">
                                                <h4 id="total"></h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close
                                </button>
                                <button type="submit" class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Add Payment</button>
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
        document.querySelectorAll('button[data-fc-target="payModal"]').forEach(button => {
            button.addEventListener('click', function() {
                const type = this.getAttribute('data-fc-b-type');
                const id = this.getAttribute('data-fc-id');

                document.getElementById('payModalType').value = type;
                document.getElementById('payModalId').value = id;

                console.log(type, id)
            });
        });
    </script>
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

        $(document).ready(function() {
                    const calculate = () => {
                        let unitPrice = $('#payModal #unit_price').val()
                        let qty = $('#payModal #quantity').val()
                        let advance = $('#payModal #advance').val()
                        let vat = $('#payModal input[name=vat]').val()
                        if (unitPrice != '' && qty != '' && advance != '' && parseInt(unitPrice) >= 0 && parseInt(qty) >=
                            0 && parseInt(advance) >= 0) {
                            $.post("calculate/index.php", {
                                    unitPrice,
                                    qty,
                                    advance,
                                    vat
                                },
                                function(response) {
                                    $('#total').html(`Total: <b>${response.total}</b>`);
                                    $('#remedial').html(`Remaining: <b>${response.remaining}</b>`);
                                },
                                'json'
                            )
                        }
                    }

                    $('#unit_price, #quantity, #advance').on('input', calculate)


                    $('#generate').click(function(e) {
                        e.preventDefault();
                        const checkboxes = $('table#zero_config').find('[type="checkbox"]:checked.box')
                        const update = $.map(checkboxes, c => c.value)
                        $.post("generate.php?type=<?= $type ?>&primary_key=<?= $primary_key ?>", {
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

                    // editModal - populate inputs when editModal is displayed
                    $(document).ready(function() {
                        // Edit modal - populate edit modal when it is visible
                        $("button[data-fc-target=edit]").click(function(event) {
                            let id = $(this).data('fc-id');
                            console.log('Edit button clicked, id:', id);

                            $.get(`api/get.php?id=${id}&type=${type}`, function(row) {
                                const data = JSON.parse(row);
                                console.log('Data received for edit:', data);

                                for (let key in data) {
                                    if (key == 'customer') {
                                        $(`#edit select[name='customer']`).val(data[key]);
                                    } else {
                                        $(`#edit input[name='${key}']`).val(data[key]);
                                    }
                                }
                            });
                        });

                        // PayModal - populate pay modal when it is visible
                        // JavaScript to populate the pay modal when it is visible
                        $('button[data-fc-modal-type="payModal"]').click(function(event) {
                            console.log("clicked");

                            let id = $(this).data('data-fc-id');
                            let type = $(this).data('data-fc-b-type'); // Ensure you get the correct row type
                            console.log('Pay button clicked, id:', id, 'type:', type);

                            $.get(`api/get.php?id=${id}&type=${type}`, function(result) {
                                console.log('Data received for pay:', result);

                                let unit_price = parseFloat(result.unit_price);
                                let vat_rate = parseFloat(result.vat);
                                let unit_price_vats = unit_price + (unit_price * (vat_rate / 100));

                                $('input[name="customer"]').val(result.customer);
                                $('input[name="description"]').val(result.job_type);
                                $('input[name="size"]').val(result.size);
                                $('input[name="unit_price"]').val(result.unit_price);

                                if (result.unit_price_vat != null) {
                                    $('input[name="unit_price_vat"]').val(result.unit_price_vat);
                                } else {
                                    $('input[name="unit_price_vat"]').val(unit_price_vats);
                                }

                                $('input[name="quantity"]').val(result.required_quantity);
                                $('input[name="vat"]').val(result.vat);

                                if (type == 'banner') {
                                    $('input[name="unitbanner_type"]').val(result.unitbanner_type);
                                    $('input[name="width"]').val(result.width);
                                    $('input[name="lengths"]').val(result.lengths);
                                    $('input[name="commitonprice"]').val(result.commitonprice);
                                    $('input[name="cvat"]').val(result.cvat);
                                    $('input[name="product"]').val(result.product);
                                }

                                calculate();
                            }, 'json');
                        });



                        $('form#pay').on('submit', function(event) {
                            event.preventDefault()
                            $.post('api/pay.php?type=<?= $type ?>', $(this).serialize(), (data, status) => {
                                if (status == 'success') {
                                    swal({
                                        title: "Succssfully added payment",
                                        text: data || "successfully added payment for <?= $type ?>.",
                                        icon: "success",
                                    }).then(result => {
                                        location.reload()
                                    })
                                } else {
                                    swal({
                                        title: "Unknow error occured",
                                        text: data || "error occured while adding payment for <?= $type ?>",
                                        icon: "error",
                                    })
                                }
                            }).catch(err => {
                                console.log(err);
                                swal({
                                    title: "Unknow error occured: " + err.statusText,
                                    text: "error occured while adding payment for <?= $type ?>",
                                    icon: "error",
                                })
                            })
                        })

                        // update
                        $('form#update').on('submit', function(event) {
                            event.preventDefault()
                            $.post(`api/get.php?type=<?= $type ?>`, $(this).serialize(), (data, status) => {
                                if (status == 'success') {
                                    swal({
                                        title: "Succssfully updated",
                                        text: data || "successfully updated the <?= $type ?>.",
                                        icon: "success",
                                    }).then(result => {
                                        location.reload()
                                    })
                                } else {
                                    swal({
                                        title: "Unknow error occured",
                                        text: data || "error occured while updating the <?= $type ?>",
                                        icon: "error",
                                    })
                                }
                            }).catch(err => {
                                swal({
                                    title: "Unknow error occured: " + err.statusText,
                                    text: "error occured while updating <?= $type ?>",
                                    icon: "error",
                                })
                            })
                        })
                    })
    </script>

</body>

</html>



<?php

if (isset($_POST['filter'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];

    echo "<script>window.location = 'database.php?type=$type&from_date=$from_date&to_date=$to_date'; </script>";
}



?>