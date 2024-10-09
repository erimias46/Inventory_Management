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


       


        $emailButtonVisible = ($module['email'] == 1) ? true : false;

       
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
    $title = 'Add Email';
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
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Add Email Accounts</h4>
                            <div>

                                <?php if ($emailButtonVisible) : ?>

                                    <button type="button" data-fc-type="modal" data-fc-target="addModal"
                                        class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                        <i class="msr text-base me-2">add</i>
                                        Add Email
                                    </button>
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
                                                <th>Email</th>
                                                <th>Date</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $result = mysqli_query($con, "SELECT * FROM email_subscribers ORDER BY id DESC");
                                            while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr
                                                    class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                        <?php if ($emailButtonVisible) : ?>

                                                            <a id="del-btn" href="remove.php?id=<?php echo $row['id']; ?>&from=email_subscribers"
                                                                class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i
                                                                    class="mgc_delete_2_line text-base me-2"></i> Delete</a>

                                                        <?php endif; ?>

                                                        <?php if ($emailButtonVisible) : ?>


                                                            <button type="button" class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full"
                                                                data-fc-type="modal" data-fc-target="edit<?= $row['id'] ?>">
                                                                <i class="mgc_pencil_line text-base me-2"></i>
                                                                Edit
                                                            </button>
                                                        <?php endif; ?>

                                                    </td>
                                                    <td
                                                        class="px-6 90-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['id'] ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['email'] ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row['created_at'] ?></td>


                                                </tr>
                                                <!-- Edit modal -->
                                                <div id="edit<?= $row['id'] ?>"
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
                                                                <input type="hidden" name="id"
                                                                    value="<?= $row['id'] ?>">
                                                                <input type="hidden" name="date"
                                                                    value="<?= $row['created_at'] ?>">

                                                                <div class="mb-3">
                                                                    <label
                                                                        class="text-gray-800 text-sm font-medium inline-block mb-2">
                                                                        Email</label>
                                                                    <input type="email" name="email" class="form-input"
                                                                        value="<?= $row['email'] ?>" required>
                                                                </div>




                                                            </div>
                                                            <div
                                                                class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                                                <button
                                                                    class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                                                                    data-fc-dismiss type="button">Close
                                                                </button>
                                                                <button name="update_email" type="submit"
                                                                    class="btn bg-success text-white">Edit Email</button>
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
                                Add Email
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
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Email
                                    </label>
                                    <input type="email" name="email" class="form-input" required   placeholder="joe@example.com">
                                </div>
                                

                            </div>
                            <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                                <button
                                    class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                                    data-fc-dismiss type="button">Close
                                </button>
                                <button name="add_email" type="submit"
                                    class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Add
                                    Email </button>
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

if (isset($_POST['add_email'])) {
   $email= $_POST['email'];

    $check_duplicate = "SELECT * FROM email_subscribers WHERE email = '$email'";
    $result_check = mysqli_query($con, $check_duplicate);

    if (mysqli_num_rows($result_check) > 0) {
        // Customer name already exists, show an alert message
        echo "<script>alert('There already exists an email by that name.'); window.location = 'email.php'; </script>";
    } else {
        // Proceed with adding the new customer
        

            // Insert into customer table using the retrieved management_id
            $add_customer_cust = "INSERT INTO email_subscribers(email) 
                              VALUES ('$email')";

            $result_add_cust = mysqli_query($con, $add_customer_cust);

            if ($result_add_cust) {
                echo "<script>window.location = 'action.php?status=success&redirect=email.php'; </script>";
            } else {
                echo "<script>window.location = 'action.php?status=error&redirect=email.php'; </script>";
            }
       
    }
}


if (isset($_POST['update_email'])) {
    $email_id = $_POST['id'];
    $email = $_POST['email'];
   

    // Retrieve management_id from the customer table using the customer_id
    
        // Update the customer table
        $customer_update = "UPDATE `email_subscribers` SET 
                            `email`='$email'
                           
                            WHERE `id` = '$email_id'";

        $result_update = mysqli_query($con, $customer_update);

        if ($result_update) {
            echo "<script>window.location = 'action.php?status=success&redirect=email.php'; </script>";
        } else {
            echo "<script>window.location = 'action.php?status=error&redirect=email.php'; </script>";
        }
    } 



?>