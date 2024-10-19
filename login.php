<?php
session_start();
include_once 'include/db.php';

$_SESSION['error'] = null;
$_SESSION['error_password'] = null;
$_SESSION['error_username'] = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (strlen($username) == 0) {
            $_SESSION['error_username'] =  "Please enter a username";
            $_POST['username'] = null;
        } else if (strlen($password) == 0) {
            $_SESSION['error_password'] =  "Please enter a password";
            $_POST['password'] = null;
        } else {
            $sql = "SELECT * FROM user WHERE user_name = '$username'";
            $result = mysqli_query($con, $sql);

            if ($result) {
                $row = mysqli_fetch_assoc($result);

                if ($row) {
                    $user_name = $row['user_name'];
                    $password_db = $row['password'];
                    $user_id = $row['user_id'];

                    if ($username == $user_name && $password == $password_db) {
                        $_SESSION['username'] = $username;
                        $_SESSION['user_id'] = $user_id;
                        $_SESSION['user'] = true;
                        $redirect = $_GET['redirect'] ?? 'index.php';
                        header("Location: $redirect");
                        die();
                    } else {
                        $_SESSION['error'] = "Invalid credentials";
                    }
                } else {
                    $_SESSION['error'] = "User not found";
                }
            } else {
                $_SESSION['error'] = "Database query failed";
            }
        }
    }
}

?>

<head>
    <?php $title = "Login";
    $redirect_link = "";
    $side_link = "";
    include 'partials/title-meta.php'; ?>

    <?php include 'partials/head-css.php'; ?>
</head>

<body>

    <div class="bg-gradient-to-r from-rose-100 to-teal-100 dark:from-gray-700 dark:via-gray-900 dark:to-black">

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="h-screen w-screen flex justify-center items-center">

            <div class="2xl:w-1/4 lg:w-1/3 md:w-1/2 w-full">
                <div class="card overflow-hidden sm:rounded-md rounded-none">
                    <form class="p-6" method="POST">
                        <a href="index.php" class="block mb-8">
                            <img class="h-50 w-50 block mx-auto dark:hidden" src="assets/images/zuqemens.JPG" alt="">
                            <img class="h-50 w-50 hidden mx-auto dark:block" src="assets/images/zuqemens.JPG" alt="">
                            <!-- <img class="h-14 w-14 block dark:hidden" src="assets/images/logo2.png" alt="">
                            <img class="h-14 w-14 hidden dark:block" src="assets/images/logo2.png" alt=""> -->

                        </a>

                        <?php if (isset($_SESSION['error'])) {
                        ?>
                            <div class="bg-danger/25 text-danger text-sm rounded-md px-4 py-3 mb-1" role="alert">
                                <span class="font-bold">Error</span> <?php echo $_SESSION['error'] ?>.
                            </div>
                        <?php
                        } ?>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2" for="LoggingUsernameAddress">Username</label>
                            <input id="LoggingUsernameAddress" name="username" class="form-input" type="username" value="<?php echo ($_POST['username'] ?? "") ?>" placeholder="Enter your username" required>
                            <span class="text-danger text-xs"><?php echo $_SESSION['error_username'] ?></span>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2" for="loggingPassword">Password</label>
                            <input id="loggingPassword" class="form-input" type="password" placeholder="Enter your password" name="password" required>
                            <span class="text-danger text-xs"><?php echo $_SESSION['error_password'] ?></span>
                        </div>

                        <div class="flex justify-center mb-6">
                            <button class="btn w-full text-white bg-success"> Log In </button>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-center">Don't have an account ? Contact Admin</p>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- ============================================================== -->
    <!-- End Page content -->
    <!-- ============================================================== -->

</body>

</html>