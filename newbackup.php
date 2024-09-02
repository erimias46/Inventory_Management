<?php
$redirect_link = "";
$side_link = "";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
require_once($redirect_link.'pages/backup/backup.php');


$back = new Backup;
if (isset($_POST['backup'])) {
    $message = $back->backup_tables();
    echo "<script>alert('" . $message . "')</script>";
  }
  if (isset($_POST['backuplc'])) {
    $message = $back->backup_tableslc();
    echo "<script>alert('" . $message . "')</script>";
  }

  header('Access-Control-Allow-Origin: *');


  $backup = '<button class="btn" name="backup"><span class="menu-icon"><i class="msr">download</i></span>Back up</span></button>';
    $localbackup = '<button class="btn" name="backuplc"> <span><i class="msr">download</i></span> Local Back up</span></button>';
?>






<head>
    <?php
    $title = 'Add banks';
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
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Backup</h4>
                            <div>
           
                            <!-- <form method="POST"> <button name="backuplc">Local BackUp</button></form> -->
                            <form action="" method="post">
                            <?= $backup ?>
                        <br><br><br>
                        
                        <?= $localbackup ?>

                        </form>
                            </div>
                        </div>
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




