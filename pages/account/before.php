<?php
$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
?>

<head>
    <?php
    $title = 'Profile';
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
                        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Profile</h4>
                    </div>
                    <?php
                    $user_name_0 = $_SESSION['username'];
                    $sql = "SELECT * FROM user WHERE user_name = '$user_name_0'";
                    $result = mysqli_query($con, $sql);
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <form method="POST" class="p-6 grid grid-cols-1 gap-3">
                        <div class="mx-auto">
                            <img src="../../assets/images/users/1.jpg" class="rounded-full" style="width:200px;" alt="">
                        </div>
                        <div>
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Username </label>
                            <input type="text" name="user_name" class="form-input" value="<?php echo $row['user_name']; ?>" required>
                        </div>
                        <div>
                            <label class="text-gray-800 text-sm font-medium inline-block mb-2"> Password </label>
                            <input type="password" name="password" class="form-input"  required>
                        </div>
                        <button name="update_profile" type="submit" class="btn bg-success text-white">Login</button>
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

if (isset($_POST['update_profile'])) {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $con->prepare("SELECT * FROM user WHERE user_name = ?");
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // If user is found and passwords match
    if ($row && $password===$row['password']) {

        echo "<script>window.location = 'action.php?status=success&redirect=users.php'; </script>";
        //header("Location: users.php");
        exit();
    } else {
        // Handle incorrect password or user not found
        echo "<script>window.location = 'action.php?status=error&redirect=before.php'; </script>";
    }




   

   
}

?>