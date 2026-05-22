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


        $user = ($module['user'] == 1) ? true : false;


      
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

                                <?php if ($user) : ?>

                                    <button type="button" data-fc-type="modal" data-fc-target="addModal"
                                        class="btn btn-sm rounded-full bg-success/25 text-success hover:bg-success hover:text-white">
                                        <i class="msr text-base me-2">add</i>
                                        Add Users
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
                                                <th>User Name</th>
                                                <th>Password</th>
                                                <th>Privileged</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $result = mysqli_query($con, "SELECT * FROM user");
                                            while ($row1 = mysqli_fetch_assoc($result)) {
                                            ?>
                                                <tr
                                                    class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">

                                                        <?php if ($user) : ?>
                                                            <a id="del-btn"
                                                                href="remove.php?id=<?php echo $row1['user_id']; ?>&from=user"
                                                                class="btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full"><i
                                                                    class="mgc_delete_2_line text-base me-2"></i> Delete</a>
                                                        <?php endif; ?>
                                                        <?php if ($user) : ?>
                                                            <a id="edit-btn"
                                                                href="user3.php?id=<?php echo $row1['user_id']; ?>&from=user"
                                                                class="btn bg-warning/25 text-warning hover:bg-warning hover:text-white btn-sm rounded-full"><i class="mgc_pencil_line text-base me-2"></i> Edit</a>
                                                        <?php endif; ?>


                                                    </td>
                                                    <td
                                                        class="px-6 90-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row1['user_id'] ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row1['user_name'] ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row1['password'] ?></td>
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?php echo $row1['previledge'] ?></td>




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
                        <?= $title ?>
                    </h3>
                    <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200"
                        data-fc-dismiss type="button">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>
                <div class="overflow-y-auto mt-3">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium"><?= $title ?>
                                </h4>
                            </div>
                            <div class="p-4">

                                <form method="post" class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 gap-3">
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

                                        <div class="mb-3">

                                            <label class="form-label">Privileged</label>
                                            <select class="form-select" name="privileged" id="inputGroupSelect04" required>
                                                <option value="administrator">Administrator</option>
                                                <option value="user">User</option>
                                                <option value="finance">Finance</option>
                                                <option value="stock">Stock</option>
                                            </select>

                                        </div>

                                       





                                    </div>




                            </div>
                        </div>
                        <div class="card  col-span-2">
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
                                                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                                            Jeans</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                                            Shoes </th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                                            Top</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                                            Complete</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                                            Accesssory</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                                            Wig</th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                                            Cosmetics</th>

                                                    </tr>
                                                </thead>
                                                <tbody>





                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                            View Product
                                                        </td>


                                                        <?php


                                                        // Define the checkbox names
                                                        $checkboxNames = [
                                                            'viewjeans',
                                                            'viewshoes',
                                                            'viewtop',
                                                            'viewcomplete',
                                                            'viewaccessory',
                                                            'viewwig',
                                                            'viewcosmetics'
                                                        ];

                                                        // Define the CSS classes for each column
                                                        $columnClasses = 'px-6 py-4 whitespace-nowrap text-center text-sm font-medium';


                                                        foreach ($checkboxNames as $index => $checkboxName) {


                                                            echo '<td class="' . $columnClasses . '">';
                                                            echo '<input type="checkbox" name="' . $checkboxName . '" value="1"  >';
                                                            echo '</td>';
                                                        }
                                                        ?>
                                                    </tr>



                                                    <?php
                                                    // Define the sets of checkbox names
                                                    $checkboxSets = [
                                                        'add' => ['addjeans', 'addshoes', 'addtop', 'addcomplete', 'addaccessory', 'addwig', 'addcosmetics'],
                                                        'edit' => ['editjeans', 'editshoes', 'edittop', 'editcomplete', 'editaccessory', 'editwig', 'editcosmetics'],
                                                        'delete' => ['deletejeans', 'deleteshoes', 'deletetop', 'deletecomplete', 'deleteaccessory', 'deletewig', 'deletecosmetics'],
                                                        'verify' => ['verifyjeans', 'verifyshoes', 'verifytop', 'verifycomplete', 'verifyaccessory', 'verifywig', 'verifycosmetics'],
                                                        'sale' => ['salejeans', 'saleshoes', 'saletop', 'salecomplete', 'saleaccessory', 'salewig', 'salecosmetics'],
                                                        'editsale' => ['editsalejeans', 'editsaleshoes', 'editsaletop', 'editsalecomplete', 'editsaleaccessory', 'editsalewig', 'editsalecosmetics'],
                                                        'deletesale' => ['deletesalejeans', 'deletesaleshoes', 'deletesaletop', 'deletesalecomplete', 'deletesaleaccessory', 'deletesalewig', 'deletesalecosmetics'],
                                                        'refundsale' => ['refundsalejeans', 'refundsaleshoes', 'refundsalestop', 'refundsalecomplete', 'refundsaleaccessory', 'refundsalewig', 'refundsalecosmetics'],
                                                        'exchangesale'=> ['exchangesalejeans', 'exchangesaleshoes', 'exchangesaletop', 'exchangesalecomplete', 'exchangesaleaccessory', 'exchangesalewig', 'exchangesalecosmetics'],
                                                        'deliverysale' => ['deliverysalejeans', 'deliverysaleshoes', 'deliverysaletop', 'deliverysalecomplete', 'deliverysaleaccessory', 'deliverysalewig', 'deliverysalecosmetics'],
                                                        'log' => ['logjeans', 'logshoes', 'logtop', 'logcomplete', 'logaccessory', 'logwig', 'logcosmetics'],
                                                        'constant'=>['constant'],
                                                        'email'=>['email'],
                                                        'backup'=>['backup'],
                                                        'user'=>['user'],
                                                        'editbuyprice'=>['editbuyprice'],
                                                        'addproduct'=>['addproduct'],
                                                        'fullsale'=>['fullsale'],
                                                        'allsale'=>['allsale'],
                                                        'logsale'=>['logsale'],
                                                        'searchproduct'=>['searchproduct'],
                                                        'deliverysale'=>['deliverysale'],
                                                        'producttypes'=>['producttypes'],
                                                        'productsin'=>['productsin'],
                                                        'verifyproducts'=>['verifyproducts'],



                                                    ];

                                                    // Define the CSS class for each column
                                                    $columnClasses = 'px-6 py-4 whitespace-nowrap text-center text-sm font-medium';

                                                    // Iterate through each set of checkbox names and generate the table rows
                                                    foreach ($checkboxSets as $setName => $checkboxNames) {
                                                        echo '<tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">';
                                                        echo '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">';
                                                        echo ucfirst($setName) . ' Product';
                                                        echo '</td>';

                                                        // Loop through each checkbox name in the current set
                                                        foreach ($checkboxNames as $checkboxName) {
                                                            echo '<td class="' . $columnClasses . '">';
                                                            echo '<input type="checkbox" name="' . $checkboxName . '" value="1">';
                                                            echo '</td>';
                                                        }

                                                        echo '</tr>';
                                                    }
                                                    ?>








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



    
    $viewjeans = (int) boolval($_POST['viewjeans']);
    $viewshoes = (int) boolval($_POST['viewshoes']);
    $viewtop = (int) boolval($_POST['viewtop']);
    $viewcomplete = (int) boolval($_POST['viewcomplete']);
    $viewaccessory = (int) boolval($_POST['viewaccessory']);
    $viewwig = (int) boolval($_POST['viewwig']);
    $viewcosmetics = (int) boolval($_POST['viewcosmetics']);

    $addjeans = (int) boolval($_POST['addjeans']);
    $addshoes = (int) boolval($_POST['addshoes']);
    $addtop = (int) boolval($_POST['addtop']);
    $addcomplete = (int) boolval($_POST['addcomplete']);
    $addaccessory = (int) boolval($_POST['addaccessory']);
    $addwig = (int) boolval($_POST['addwig']);
    $addcosmetics = (int) boolval($_POST['addcosmetics']);

    $editjeans = (int) boolval($_POST['editjeans']);
    $editshoes = (int) boolval($_POST['editshoes']);
    $edittop = (int) boolval($_POST['edittop']);
    $editcomplete = (int) boolval($_POST['editcomplete']);
    $editaccessory = (int) boolval($_POST['editaccessory']);
    $editwig = (int) boolval($_POST['editwig']);
    $editcosmetics = (int) boolval($_POST['editcosmetics']);

    $deletejeans = (int) boolval($_POST['deletejeans']);
    $deleteshoes = (int) boolval($_POST['deleteshoes']);
    $deletetop = (int) boolval($_POST['deletetop']);
    $deletecomplete = (int) boolval($_POST['deletecomplete']);
    $deleteaccessory = (int) boolval($_POST['deleteaccessory']);
    $deletewig = (int) boolval($_POST['deletewig']);
    $deletecosmetics = (int) boolval($_POST['deletecosmetics']);

    


    $verifyjeans = (int) boolval($_POST['verifyjeans']);
    $verifyshoes = (int) boolval($_POST['verifyshoes']);
    $verifytop = (int) boolval($_POST['verifytop']);
    $verifycomplete = (int) boolval($_POST['verifycomplete']);
    $verifyaccessory = (int) boolval($_POST['verifyaccessory']);
    $verifywig = (int) boolval($_POST['verifywig']);
    $verifycosmetics = (int) boolval($_POST['verifycosmetics']);






    $salejeans = (int) boolval($_POST['salejeans']);
    $saleshoes = (int) boolval($_POST['saleshoes']);
    $saletop = (int) boolval($_POST['saletop']);
    $salecomplete = (int) boolval($_POST['salecomplete']);
    $saleaccessory = (int) boolval($_POST['saleaccessory']);
    $salewig = (int) boolval($_POST['salewig']);
    $salecosmetics = (int) boolval($_POST['salecosmetics']);


    $editsalejeans = (int) boolval($_POST['editsalejeans']);
    $editsaleshoes = (int) boolval($_POST['editsaleshoes']);
    $editsaletop = (int) boolval($_POST['editsaletop']);
    $editsalecomplete = (int) boolval($_POST['editsalecomplete']);
    $editsaleaccessory = (int) boolval($_POST['editsaleaccessory']);
    $editsalewig = (int) boolval($_POST['editsalewig']);
    $editsalecosmetics = (int) boolval($_POST['editsalecosmetics']);


    $deletesalejeans = (int) boolval($_POST['deletesalejeans']);
    $deletesaleshoes = (int) boolval($_POST['deletesaleshoes']);
    $deletesaletop = (int) boolval($_POST['deletesaletop']);
    $deletesalecomplete = (int) boolval($_POST['deletesalecomplete']);
    $deletesaleaccessory = (int) boolval($_POST['deletesaleaccessory']);
    $deletesalewig = (int) boolval($_POST['deletesalewig']);
    $deletesalecosmetics = (int) boolval($_POST['deletesalecosmetics']);

    $refundsalejeans = (int) boolval($_POST['refundsalejeans']);
    $refundsaleshoes = (int) boolval($_POST['refundsaleshoes']);
    $refundsaletop = (int) boolval($_POST['refundsaletop']);
    $refundsalecomplete = (int) boolval($_POST['refundsalecomplete']);
    $refundsaleaccessory = (int) boolval($_POST['refundsaleaccessory']);
    $refundsalewig = (int) boolval($_POST['refundsalewig']);
    $refundsalecosmetics = (int) boolval($_POST['refundsalecosmetics']);

    $exchangesalejeans = (int) boolval($_POST['exchangesalejeans']);
    $exchangesaleshoes = (int) boolval($_POST['exchangesaleshoes']);
    $exchangesaletop = (int) boolval($_POST['exchangesaletop']);
    $exchangesalecomplete = (int) boolval($_POST['exchangesalecomplete']);
    $exchangesaleaccessory = (int) boolval($_POST['exchangesaleaccessory']);
    $exchangesalewig = (int) boolval($_POST['exchangesalewig']);
    $exchangesalecosmetics = (int) boolval($_POST['exchangesalecosmetics']);

    $deliverysalejeans = (int) boolval($_POST['deliverysalejeans']);
    $deliverysaleshoes = (int) boolval($_POST['deliverysaleshoes']);
    $deliverysaletop = (int) boolval($_POST['deliverysaletop']);
    $deliverysalecomplete = (int) boolval($_POST['deliverysalecomplete']);
    $deliverysaleaccessory = (int) boolval($_POST['deliverysaleaccessory']);
    $deliverysalewig = (int) boolval($_POST['deliverysalewig']);
    $deliverysalecosmetics = (int) boolval($_POST['deliverysalecosmetics']);

    $logjeans = (int) boolval($_POST['logjeans']);
    $logshoes = (int) boolval($_POST['logshoes']);
    $logtop = (int) boolval($_POST['logtop']);
    $logcomplete = (int) boolval($_POST['logcomplete']);
    $logaccessory = (int) boolval($_POST['logaccessory']);
    $logwig = (int) boolval($_POST['logwig']);
    $logcosmetics = (int) boolval($_POST['logcosmetics']);


    $constant = (int) boolval($_POST['constant']);
    $email = (int) boolval($_POST['email']);
    $backup = (int) boolval($_POST['backup']);
    $user = (int) boolval($_POST['user']);
    $editbuyprice = (int) boolval($_POST['editbuyprice']);


    $addproduct = (int) boolval($_POST['addproduct']);
    $fullsale = (int) boolval($_POST['fullsale']);
    $allsale = (int) boolval($_POST['allsale']);
    $logsale = (int) boolval($_POST['logsale']);
    $searchproduct = (int) boolval($_POST['searchproduct']);
    $deliverysale = (int) boolval($_POST['deliverysale']);
    $producttypes = (int) boolval($_POST['producttypes']);
    $productsin = (int) boolval($_POST['productsin']);
    $verifyproducts = (int) boolval($_POST['verifyproducts']);
    

   







   









    





    $jsonDataArray = array(
        
        'viewjeans' => $viewjeans,
        'viewshoes' => $viewshoes,
        'viewtop' => $viewtop,
        'viewcomplete' => $viewcomplete,
        'viewaccessory' => $viewaccessory,
        'viewwig' => $viewwig,
        'viewcosmetics' => $viewcosmetics,

        'addjeans' => $addjeans,
        'addshoes' => $addshoes,
        'addtop' => $addtop,
        'addcomplete' => $addcomplete,
        'addaccessory' => $addaccessory,
        'addwig' => $addwig,
        'addcosmetics' => $addcosmetics,

        'editjeans' => $editjeans,
        'editshoes' => $editshoes,
        'edittop' => $edittop,
        'editcomplete' => $editcomplete,
        'editaccessory' => $editaccessory,
        'editwig' => $editwig,
        'editcosmetics' => $editcosmetics,

        'deletejeans' => $deletejeans,
        'deleteshoes' => $deleteshoes,
        'deletetop' => $deletetop,
        'deletecomplete' => $deletecomplete,
        'deleteaccessory' => $deleteaccessory,
        'deletewig' => $deletewig,
        'deletecosmetics' => $deletecosmetics,

        'verifyjeans' => $verifyjeans,
        'verifyshoes' => $verifyshoes,
        'verifytop' => $verifytop,
        'verifycomplete' => $verifycomplete,
        'verifyaccessory' => $verifyaccessory,
        'verifywig' => $verifywig,
        'verifycosmetics' => $verifycosmetics,

        'salejeans' => $salejeans,
        'saleshoes' => $saleshoes,
        'saletop' => $saletop,
        'salecomplete' => $salecomplete,
        'saleaccessory' => $saleaccessory,
        'salewig' => $salewig,
        'salecosmetics' => $salecosmetics,

        'editsalejeans' => $editsalejeans,
        'editsaleshoes' => $editsaleshoes,
        'editsaletop' => $editsaletop,
        'editsalecomplete' => $editsalecomplete,
        'editsaleaccessory' => $editsaleaccessory,
        'editsalewig' => $editsalewig,
        'editsalecosmetics' => $editsalecosmetics,

        'deletesalejeans' => $deletesalejeans,
        'deletesaleshoes' => $deletesaleshoes,
        'deletesaletop' => $deletesaletop,
        'deletesalecomplete' => $deletesalecomplete,
        'deletesaleaccessory' => $deletesaleaccessory,
        'deletesalewig' => $deletesalewig,
        'deletesalecosmetics' => $deletesalecosmetics,


        'refundsalejeans' => $refundsalejeans,
        'refundsaleshoes' => $refundsaleshoes,
        'refundsaletop' => $refundsaletop,
        'refundsalecomplete' => $refundsalecomplete,
        'refundsaleaccessory' => $refundsaleaccessory,
        'refundsalewig' => $refundsalewig,
        'refundsalecosmetics' => $refundsalecosmetics,

        'exchangesalejeans' => $exchangesalejeans,
        'exchangesaleshoes' => $exchangesaleshoes,

        'exchangesaletop' => $exchangesaletop,
        'exchangesalecomplete' => $exchangesalecomplete,
        'exchangesaleaccessory' => $exchangesaleaccessory,
        'exchangesalewig' => $exchangesalewig,
        'exchangesalecosmetics' => $exchangesalecosmetics,

        'deliverysalejeans' => $deliverysalejeans,
        'deliverysaleshoes' => $deliverysaleshoes,
        'deliverysaletop' => $deliverysaletop,
        'deliverysalecomplete' => $deliverysalecomplete,
        'deliverysaleaccessory' => $deliverysaleaccessory,
        'deliverysalewig' => $deliverysalewig,
        'deliverysalecosmetics' => $deliverysalecosmetics,

        'logjeans' => $logjeans,
        'logshoes' => $logshoes,
        'logtop' => $logtop,
        'logcomplete' => $logcomplete,
        'logaccessory' => $logaccessory,
        'logwig' => $logwig,
        'logcosmetics' => $logcosmetics,


        'constant'=>$constant,
        'email'=>$email,
        'backup'=>$backup,
        'user'=>$user,
        'editbuyprice'=>$editbuyprice,






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



?>