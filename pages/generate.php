<?php
$redirect_link = "../";
$side_link = "../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
?>

<head>
    <?php
    $title = 'Generate PDF';
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
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Generate</h4>
                            <div>
                                <a href="<?= $redirect_link . 'pages/export.php?type=generate' ?>" class=" btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                    <i class="msr text-base me-2">picture_as_pdf</i>
                                    Export
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <form method="POST" action="../include/pdf/index4.php">
                            <div class="overflow-x-auto">
                                <div class="min-w-full inline-block align-middle">
                                    <div class="overflow-hidden">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="py-3 ps-4" data-searchable="false" data-orderable="false">
                                                        <div class="flex items-center h-5">
                                                            <input name="checkAll" id="checkAll" type="checkbox" class="form-checkbox rounded">
                                                            <label for="table-checkbox-all" class="sr-only">Checkbox</label>
                                                        </div>
                                                    </th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Job Description</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Size</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Price Vat</th>
                                                    <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">Types</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $sql = "SELECT * FROM generate";
                                                $result = mysqli_query($con, $sql);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                        <td class="py-3 ps-4">
                                                            <div class="flex items-center h-5">
                                                                <input id="table-checkbox-5" name="update[]" value="<?php echo $row['generate_id'] ?>" type="checkbox" class="form-checkbox rounded box" value="<?php if (isset($row['id'])) echo $row['id']  ?>">
                                                                <label for="table-checkbox-5" class="sr-only">Checkbox</label>
                                                            </div>
                                                        </td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['generate_id']; ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['customer']; ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['job_description']; ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['size']; ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['quantity']; ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['unit_price']; ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['total_price']; ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['price_vat']; ?></td>
                                                        <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200"><?php echo $row['types']; ?></td>
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
                                <button type="button" id="generateBtn" class="btn bg-success text-white rounded-full">
                                    <i class="mgc_pdf_line text-base me-2"></i>
                                    Generate
                                </button>

                                <?php
                                if (isset($_GET['file'])) {
                                    $file = $_GET['file'];
                                ?>
                                    <h4>Output: <b><a href="../include/pdf/invoice/<?php echo $file; ?>"><?php echo $file; ?></a> </b></h4>
                                <?php } ?>
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



    <!-- Modal -->
    <div id="quoteModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
            <h4 class="text-lg font-medium mb-4">Additional Information</h4>
            <form id="modalForm">
                <div class="mb-4">
                    <label for="quoteSender" class="block text-sm font-medium text-gray-700">Quote Sender</label>
                    <input type="text" id="quoteSender" name="quote_sender" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="requestedBy" class="block text-sm font-medium text-gray-700">Requested By</label>
                    <input type="text" id="requestedBy" name="requested_by" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="validity" class="block text-sm font-medium text-gray-700">Validity</label>
                    <input type="text" id="validity" name="validity" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                </div>
                <div class="flex justify-end">
                    <button type="button" id="modalCancelBtn" class="btn bg-gray-500 text-white rounded-full mr-2">Cancel</button>
                    <button type="submit" class="btn bg-success text-white rounded-full">Submit</button>
                </div>
            </form>
        </div>
    </div>



    <script>
        $(document).ready(function() {
            $('#generateBtn').click(function() {
                $('#quoteModal').removeClass('hidden');
            });

            $('#modalCancelBtn').click(function() {
                $('#quoteModal').addClass('hidden');
            });

            $('#modalForm').submit(function(e) {
                e.preventDefault();

                // Add the modal inputs to the main form
                var quoteSender = $('#quoteSender').val();
                var requestedBy = $('#requestedBy').val();
                var validity = $('#validity').val();

                $('<input>').attr({
                    type: 'hidden',
                    name: 'quote_sender',
                    value: quoteSender
                }).appendTo('form');

                $('<input>').attr({
                    type: 'hidden',
                    name: 'requested_by',
                    value: requestedBy
                }).appendTo('form');

                $('<input>').attr({
                    type: 'hidden',
                    name: 'validity',
                    value: validity
                }).appendTo('form');

                // Submit the main form
                $('form').submit();
            });

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