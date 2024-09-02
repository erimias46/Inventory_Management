<?php
$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
?>

<head>
    <?php
    $title = 'Add Users';
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
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Users</h4>
                            <div>
                                <button type="button" data-fc-type="modal" data-fc-target="addModal"
                                    class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                    <i class="msr text-base me-2">add</i>
                                    Add Users
                                </button>

                                <a href="<?php $redirect_link . 'pages/export.php?type=bankdb' ?>"
                                    class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                    <i class="msr text-base me-2">picture_as_pdf</i>
                                    Export
                                </a>
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
                                                <th>User Name</th>
                                                <th>Password</th>
                                                <th>Privileged</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php   
                                                $result = mysqli_query($con, "SELECT * FROM user");
                                                while ($row = mysqli_fetch_assoc($result)){
                                                    ?>
                                            <tr
                                                class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <a id="del-btn"
                                                        href="remove.php?id=<?php echo $row['user_id'];?>&from=user"
                                                        class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i
                                                            class="mgc_delete_2_line text-base me-2"></i> Delete</a>
                                                            <a id="edit-btn"
                                                        href="user3.php?id=<?php echo $row['user_id'];?>&from=user"
                                                        class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i
                                                            class="mgc_delete_2_line text-base me-2"></i> Edit</a>
                                                    

                                                </td>
                                                <td
                                                    class="px-6 90-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <?php echo $row['user_id'] ?></td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <?php echo $row['user_name'] ?></td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <?php echo $row['password'] ?></td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                    <?php echo $row['previledge'] ?></td>

                                            </tr>
                                            <!-- Edit modal -->



                                </div>

                               

                                <?php } ?>
                                </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </div>



        <div id="addModal" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 fc-modal hidden">
            <div
                class="fc-modal-open:opacity-100 duration-500 h-screen w-screen opacity-0 ease-out transition-all flex flex-col bg-white p-8 dark:bg-slate-800 dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="font-medium text-gray-800 dark:text-white text-2xl">
                        <?=$title ?>
                    </h3>
                    <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200"
                        data-fc-dismiss type="button">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                <div class="overflow-y-auto mt-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium"><?= $title ?>
                                </h4>
                            </div>
                            <div class="p-4">

                                <form method="post" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                    <div class="px-4 py-8 overflow-y-auto">
                                        <div class="mb-3">
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                                Username
                                            </label>
                                            <input type="text" name="user_name" class="form-input" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                                Password
                                            </label>
                                            <input type="text" name="password" class="form-input" required>
                                        </div>

                                        <label class="form-label">Privileged</label>
                                        <select class="form-select" name="privileged" id="inputGroupSelect04" required>
                                            <option value="administrator">Administrator</option>
                                            <option value="user">User</option>
                                            <option value="finance">Finance</option>
                                        </select>
                                        </select>


                                    </div>




                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Assign
                                    Permission</h4>
                            </div>
                            <div class="p-4">
                                <div class="overflow-x-auto">
                                    <div class="min-w-full inline-block align-middle">
                                        <div class="overflow-hidden">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead>
                                                    <tr>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                            Modules</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                            Features</th>

                                                        <th scope="col"
                                                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                                            View</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                                            Add</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                                            Edit</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                                            Delete</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                                            Generate</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr
                                                        class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            Calculator</td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            Calculate</td>

                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            <input type="checkbox" name="calcview" value="1">
                                                        </td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">

                                                            <input type="checkbox" name="calcadd" value="1">
                                                        </td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">

                                                            <input type="checkbox" name="calcedit" value="1">
                                                        </td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">

                                                            <input type="checkbox" name="calcdelete" value="1">
                                                        </td>
                                                        <td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                            <input type="checkbox" name="calcgenerate" value="1">
                                                        </td>

                                                    </tr>



                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>





                            </div>

                        </div>
                    </div>
                </div>
                <div class="flex justify-end items-center gap-4 mt-auto">
                    <button
                        class="btn dark:text-gray-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 hover:dark:bg-slate-700 transition-all"
                        data-fc-dismiss type="button">Close
                    </button>
                    <button name="add_user" type="submit"
                        class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Add
                        User</button>
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



if (isset($_GET['status'])) {
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

if (isset($_POST['add_user'])) {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    $privileged = $_POST['privileged'];
    $calcview = $_POST['calcview'];
    $calcadd = $_POST['calcadd'];
    $calcedit = $_POST['calcedit'];
    $calcdelete = $_POST['calcdelete'];
    $calcgenerate = $_POST['calcgenerate'];

    if ($calcview == 1) {
        $calcview = 1;
    } else {
        $calcview = 0;
    }
    if ($calcadd == 1) {
        $calcadd = 1;
    } else {
        $calcadd = 0;
    }
    if ($calcedit == 1) {
        $calcedit = 1;
    } else {
        $calcedit = 0;
    }
    if ($calcdelete == 1) {
        $calcdelete = 1;
    } else {
        $calcdelete = 0;
    }
    if ($calcgenerate == 1) {
        $calcgenerate = 1;
    } else {
        $calcgenerate = 0;
    }
    

    $jsonDataArray = array(
        'calcview' => $calcview,
        'calcadd' => $calcadd,
        'calcedit' => $calcedit,
        'calcdelete' => $calcdelete,
        'calcgenerate' => $calcgenerate
    );
    
    // Convert the JSON array to a JSON-formatted string
    $jsonData = json_encode($jsonDataArray);
    
    


    $add_user = "INSERT INTO user(user_name, password, previledge,module) 
                    VALUES ('$user_name', '$password', '$privileged','$jsonData')";
    $result_add = mysqli_query($con, $add_user);

    if ($result_add) {
        echo "<script>window.location = 'action.php?status=success&redirect=users.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=users.php'; </script>";
    }
}

if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    $privileged = $_POST['privileged'];

    $user_update = "UPDATE `user` SET `user_name`='$user_name', `password`='$password', `previledge`='$privileged' WHERE `user_id` = '$user_id'"; 
    $result_update = mysqli_query($con, $user_update);

    if ($result_update) {
        echo "<script>window.location = 'action.php?status=success&redirect=users.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=users.php'; </script>";
    }
}

?>