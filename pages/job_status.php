
<?php

$redirect_link = "../";
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

        
        $calculateButtonVisible = ($module['jobview'] == 1) ? true : false;

        
        $updateButtonVisible = ($module['jobedit'] == 1) ? true : false;

        
        
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
    <?php $title = "Project List";
    include $redirect_link .'partials/title-meta.php'; ?>

    <?php include $redirect_link .'partials/head-css.php'; ?>
</head>

<body>

    <div class="flex wrapper">

        <?php include $redirect_link .'partials/menu.php'; ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <?php include $redirect_link .'partials/topbar.php'; ?>

            <main class="flex-grow p-6">

                <?php
                $subtitle = "Project";
                $pagetitle = "Project List";
                include $redirect_link .'partials/page-title.php'; ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Filter</h4>
                    </div>
                    <div class="p-6">
                        <form>
                            <div class="flex flex-wrap">
                                <div class="m-2 flex-1"> 
                                    <form method="GET">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Choose Client </label>
                                    <select name="client" class="search-select " style="max-width: 15ch"
                            onchange="form.submit()">
                            <option value="">All</option>
                            <?php
                                    $result = mysqli_query($con, "SELECT DISTINCT(client) FROM payment WHERE client != ''");
                                    while($row = mysqli_fetch_assoc($result)) {
                                ?>
                            <option value="<?php if(isset($row['client'])) echo $row['client']; ?>"
                            
                                <?php if(isset($_GET['client'])) $_GET['client'] == $row['client'] ? 'selected':'' ?>><?= $row['client'] ?></option>
                            <?php } ?>
                        </select>
                        </form>
                                </div>
                                
                                <div class="m-2 flex-1">
                                <form method="GET">
                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">Choose Status </label>
                                    <select name="status" class="form-select" style="max-width: 15ch"
                            onchange="form.submit()">
                            <option value="">All</option>
                            <?php
                                    $result = mysqli_query($con, "SELECT DISTINCT(status) FROM payment WHERE status != ''");
                                    while($row = mysqli_fetch_assoc($result)) {
                                ?>
                            <option value="<?php if(isset($row['status'])) echo $row['status']; ?>"
                            
                                <?php if(isset($_GET['status'])) $_GET['status'] == $row['status'] ? 'selected':'' ?>><?= $row['status'] ?></option>
                            <?php } ?>
                        </select>
                        </form>
                                </div>
                            </div>
                            
                        </form>

                    </div>
                </div>





                


                <div class="flex flex-auto flex-col">

                    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">

                    <?php

                    if(isset($_GET['client'])){
                        $filter_status = empty($_GET['client'])   ? '': "WHERE client = '{$_GET['client']}'";
                    }
                    else if (isset($_GET['status'])){

                        $filter_status = empty($_GET['status'])   ? '': "WHERE status = '{$_GET['status']}'";}
                        else {
                            $filter_status =  '';
                        }
                                $sql = "SELECT * FROM payment $filter_status ORDER BY payment_id DESC";
                                 $result = mysqli_query($con, $sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                    
                        <div class="card">
                            <div class="card-header">
                                <div class="flex justify-between items-center">
                                    <h5 class="card-title"><?php echo $row['client'] ?></h5>
                                    <?php if( $row['status']=='start' ) {?>
                                    <div class="bg-danger text-xs text-white rounded-md py-1 px-1.5 font-medium" role="alert">
                                        <span><?php echo strtoupper( $row['status']) ?>ED</span>
                                    </div>
                                    <?php }
                                    elseif( $row['status']=='progress' ) {?>
                                    <div class="bg-warning/60 text-xs text-white rounded-md py-1 px-1.5 font-medium" role="alert">
                                        <span>On <?php echo strtoupper( $row['status']) ?></span>
                                    </div>
                                    <?php }
                                    elseif( $row['status']=='complete' ) {?>
                                    <div class="bg-info text-xs text-white rounded-md py-1 px-1.5 font-medium" role="alert">
                                        <span><?php echo strtoupper( $row['status']) ?>ED</span>
                                    </div>
                                    <?php }
                                    elseif( $row['status']=='delivered' ) {?>
                                    <div class="bg-success text-xs text-white rounded-md py-1 px-1.5 font-medium" role="alert">
                                        <span><?php echo strtoupper( $row['status']) ?>ED</span>
                                    </div>
                                    <?php }
                                    ?>
                                    


                                    <?php if ($updateButtonVisible) : ?>  
                                <button data-fc-type="dropdown" type="button" class="py-2 px-3 inline-flex justify-center items-center rounded-md border font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 transition-all text-sm dark:bg-gray-800 dark:hover:bg-gray-700 dark:border-gray-700 dark:text-gray-400 dark:hover:text-white">
                                    Actions <i class="mgc_down_line text-base ms-1"></i>
                                </button>

                                <?php endif; ?>


                                <div class="hidden fc-dropdown-open:opacity-100 opacity-0 z-50 transition-all duration-300 bg-white border shadow-md rounded-lg p-2 dark:bg-slate-800 dark:border-slate-700">
                                    <a class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="status1.php?id=<?php echo $row['payment_id'] ?>&data=start">
                                        Start
                                    </a>
                                    <a class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="status1.php?id=<?php echo $row['payment_id'] ?>&data=progress">
                                        On Progress
                                    </a>
                                    <a class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="status1.php?id=<?php echo $row['payment_id'] ?>&data=complete">
                                        Completed
                                    </a>
                                    <a class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="status1.php?id=<?php echo $row['payment_id'] ?>&data=delivered">
                                        Delivered
                                    </a>

                                </div>
                                
                                    
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <div class="py-3 px-6">
                                    <h5 class="my-2"><a href="#" class="text-slate-900 dark:text-slate-200">Job Description</a></h5>
                                    <p class="text-gray-500 text-sm mb-9"><?php echo $row['job_description'] ?></p>

                                    
                                </div>

                                <div class="border-t p-5 border-gray-300 dark:border-gray-700">
                                    <div class="grid lg:grid-cols-2 gap-4">
                                        <div class="flex items-center justify-between gap-2">
                                            <a href="#" class="text-sm">
                                                <i class="mgc_calendar_line text-lg me-2"></i>
                                                <span class="align-text-bottom">Date  <?php echo $row['date'] ?></span>
                                            </a>

                                            

                                        </div>
                                        <div class="flex items-center gap-2">
                                       
                                    
                                            <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">

                                               <?php if( $row['status']=='start' ){  ?> 
                                                <div class="bg-danger h-1.5 rounded-full dark:bg-danger w-1"></div>
                                                <?php }
                                                else if( $row['status']=='progress' ){  ?> 
                                                    <div class="bg-warning h-1.5 rounded-full dark:bg-warning w-1/2"></div>
                                                    <?php }
                                                else  if( $row['status']=='complete' ){  ?> 
                                                        <div class="bg-success h-1.5 rounded-full dark:bg-success w-3/4"></div>
                                                        <?php }
                                                else  if( $row['status']=='delivered' ){  ?> 
                                                            <div class="bg-success h-1.5 rounded-full dark:bg-success w"></div>
                                                            <?php } ?>
                                                

                                            </div>
                                            
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php } ?>

                        
                    </div>

                    <div class="text-center mt-6">
                        <button type="button" class="btn bg-transparent border-gray-300 dark:border-gray-700">
                            <i class="mgc_loading_4_line me-2 animate-spin"></i>
                            <span>Load More</span>
                        </button>
                    </div>

                </div>

            </main>

            <?php include $redirect_link .'partials/footer.php'; ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>

    <?php include $redirect_link .'partials/customizer.php'; ?>

    <?php include $redirect_link .'partials/footer-scripts.php'; ?>

</body>

</html>