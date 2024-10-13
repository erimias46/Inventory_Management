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


        $calculateButtonVisible = ($module['custview'] == 1) ? true : false;


        $addButtonVisible = ($module['custadd'] == 1) ? true : false;

        $deleteButtonVisible = ($module['custdelete'] == 1) ? true : false;


        $updateButtonVisible = ($module['custedit'] == 1) ? true : false;


        $generateButtonVisible = ($module['custgenerate'] == 1) ? true : false;
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
    $title = 'Add Customers';
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
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Customers</h4>
                            <div>

                                <?php if ($addButtonVisible) : ?>

                                    <button type="button" data-fc-type="modal" data-fc-target="addModal"
                                        class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                        <i class="msr text-base me-2">add</i>
                                        Add Customers
                                    </button>
                                <?php endif; ?>

                                <?php if ($generateButtonVisible) : ?>

                                    <a href="<?php $redirect_link . 'pages/export.php?type=bankdb' ?>"
                                        class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
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
                                    <table id="zero_config" data-order='[[ 1, "dsc" ]]'
                                        class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Tin Number</th>
                                                <th>Phone Number</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $result = mysqli_query($con, "SELECT * FROM customer ORDER BY customer_id DESC");
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr
                                                    class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                        <?php if ($addButtonVisible) : ?>

                                                            <a id="del-btn" href="remove.php?id=<?php echo $row['customer_id']; ?>&from=customer"
                                                                class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i
                                                                    class="mgc_delete_2_line text-base me-2"></i> Delete</a>

                                                        <?php endif; ?>

                                                        <?php if ($updateButtonVisible) : ?>


                                                            <button type="button" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full"
                                                                data-fc-type="modal" data-fc-target="edit<?= $row['customer_id'] ?>">
                                                                <i class="mgc_pencil_line text-base me-2"></i>
                                                                Edit
                                                            </button>
                                                        <?php endif; ?>

                                                    </td>
                                                    <td
                                                        class="px-6 90-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['customer_id'] ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['customer_name'] ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['tin_number'] ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['phone_number'] ?></td>

                                                </tr>
                                                <!-- Edit modal -->
                                                <div id="edit<?= $row['customer_id'] ?>"
                                                    class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                                                    <div
                                                        class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                                                        <div
                                                            class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                                                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                                                Edit User
                                                            </h3>
                                                            <button
                                                                class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200"
                                                                data-fc-dismiss type="button">
                                                                <span class="material-symbols-rounded">close</span>
                                                            </button>
                                                        </div>
                                                        <form method="POST">
                                                            <div class="px-4 py-8 overflow-y-auto">
                                                                <input type="hidden" name="customer_id"
                                                                    value="<?= $row['customer_id'] ?>">
                                                                    
                                                                <div class="mb-3">
                                                                    <label
                                                                        class="text-gray-800 text-sm font-medium inline-block mb-2">
                                                                        Client Name</label>
                                                                    <input type="text" name="name" class="form-input"
                                                                        value="<?= $row['customer_name'] ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label
                                                                        class="text-gray-800 text-sm font-medium inline-block mb-2">
                                                                        Tin Number </label>
                                                                    <input type="text" name="tin_number" class="form-input"
                                                                        value="<?= $row['tin_number'] ?>" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label
                                                                        class="text-gray-800 text-sm font-medium inline-block mb-2">
                                                                        Phone Number </label>
                                                                    <input type="text" name="phone_number" class="form-input"
                                                                        value="<?= $row['phone_number'] ?>" required>
                                                                </div>

                                                                

                                                            </div>
                                                            <div
                                                                class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                                                <button
                                                                    class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                                                                    data-fc-dismiss type="button">Close
                                                                </button>
                                                                <button name="update_customer" type="submit"
                                                                    class="btn bg-success text-white">Edit Customers</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="addModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                    <div
                        class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto  bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                        <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg">
                                Add Customers
                            </h3>
                            <button
                                class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200"
                                data-fc-dismiss type="button">
                                <span class="material-symbols-rounded">close</span>
                            </button>
                        </div>
                        <form method="POST">
                            <div class="px-4 py-8 overflow-y-auto">
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Name
                                    </label>
                                    <input type="text" name="name" class="form-input" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Tin Number
                                    </label>
                                    <input type="text" name="tin_number" class="form-input" required>
                                </div>
                                <div class="mb-3">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Phone Number
                                    </label>
                                    <input type="text" name="phone_number" class="form-input" required>
                                </div>

                                <div class="mb-3">
                                    <label for="type" class="text-gray-800 text-sm font-medium inline-block mb-2">Type</label>
                                    <select name="type" id="type" class="form-input" required>
                                        <option value="">Select Type</option>
                                        <option value="organization">Organization</option>
                                        <option value="person">Person</option>
                                    </select>
                                </div>

                                








                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button
                                    class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                                    data-fc-dismiss type="button">Close
                                </button>
                                <button name="add_customer" type="submit"
                                    class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Add
                                    Customer</button>
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

if (isset($status)) {
    $status = $_GET['status'];
    if ($status == "success") {
?>
        <script>
            swal("Great!", "Task Done", "success");
        </script>
    <?php
    } else {
    ?>
        <script>
            swal("Opps!", "Have an error please contact admin", "error");
        </script>
<?php
    }
}

if (isset($_POST['add_customer'])) {
    $name = $_POST['name'];
    $tin_number = $_POST['tin_number'];
    $phone_number = $_POST['phone_number'];
    $type = $_POST['type'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $date = date('Y-m-d');
    $owner_id=$_POST['owner_id'];


    $check_duplicate = "SELECT * FROM customer WHERE customer_name = '$name'";
    $result_check = mysqli_query($con, $check_duplicate);

    if (mysqli_num_rows($result_check) > 0) {
        // Customer name already exists, show an alert message
        echo "<script>alert('There already exists a customer by that name.'); window.location = 'customer.php'; </script>";
    } else {
        // Proceed with adding the new customer
        $add_customer_oli = "INSERT INTO oli_clients(company_name, vat_number, phone, type, address, state, city, created_date,owner_id) 
                         VALUES ('$name', '$tin_number', '$phone_number', '$type', '$address', '$state', '$city', '$date', '$owner_id')";

        $result_add_oli = mysqli_query($conn, $add_customer_oli);

        if ($result_add_oli) {
            // Retrieve the last inserted ID from oli_clients
            $management_id = mysqli_insert_id($conn);

            // Insert into customer table using the retrieved management_id
            $add_customer_cust = "INSERT INTO customer(customer_name, tin_number, phone_number, management_id) 
                              VALUES ('$name', '$tin_number', '$phone_number', '$management_id')";

            $result_add_cust = mysqli_query($con, $add_customer_cust);

            if ($result_add_cust) {
                echo "<script>window.location = 'action.php?status=success&redirect=customer.php'; </script>";
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=customer.php'; </script>";
            }
        } else {
            echo "<script>window.location = 'action.php?status=error&redirect=customer.php'; </script>";
        }
    }


    
}


if (isset($_POST['update_customer'])) {
    $customer_id = $_POST['customer_id'];
    $name = $_POST['name'];
    $tin_number = $_POST['tin_number'];
    $phone_number = $_POST['phone_number'];
    $type = $_POST['type'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $owner_id = $_POST['owner_id'];

    // Retrieve management_id from the customer table using the customer_id
    $query = "SELECT management_id FROM customer WHERE customer_id = '$customer_id'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $management_id = $row['management_id'];

    // Update the oli_clients table
    $update_oli = "UPDATE `oli_clients` SET 
                    `company_name`='$name', 
                    `vat_number`='$tin_number', 
                    `phone`='$phone_number', 
                    `type`='$type', 
                    `address`='$address', 
                    `state`='$state', 
                    `city`='$city',
                    `owner_id`='$owner_id' 
                    WHERE `id`='$management_id'";

    $result_update_oli = mysqli_query($conn, $update_oli);

    if ($result_update_oli) {
        // Update the customer table
        $customer_update = "UPDATE `customer` SET 
                            `customer_name`='$name', 
                            `tin_number`='$tin_number', 
                            `phone_number`='$phone_number' 
                            WHERE `customer_id` = '$customer_id'";

        $result_update = mysqli_query($con, $customer_update);

        if ($result_update) {
            echo "<script>window.location = 'action.php?status=success&redirect=customer.php'; </script>";
        } else {
            echo "<script>window.location = 'action.php?status=error&redirect=customer.php'; </script>";
        }
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=customer.php'; </script>";
    }
}


?>