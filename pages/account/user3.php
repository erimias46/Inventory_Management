<?php
$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';



$id = $_GET['id'];
$from = $_GET['from'];
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

                        </div>
                    </div>
                    <div class="p-2">

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                            <div class="md:mr-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium"><?= $title ?>
                                        </h4>
                                    </div>
                                    <div class="p-4">




                                        <?php

                                        $id = $_GET['id'];
                                        $result = mysqli_query($con, "SELECT * FROM user WHERE user_id = $id");


                                        if ($result) {

                                            $row = mysqli_fetch_assoc($result);


                                            if ($row) {

                                                $user_id = $row['user_id'];
                                                $user_name = $row['user_name'];
                                                $password = $row['password'];
                                                $privileged = $row['previledge'];
                                                $module = $row['module'];
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


                                        <form method="post" class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-1 gap-3">
                                            <div class="px-4 py-8 overflow-y-auto">
                                                <input type="hidden" name="user_id"
                                                    value="<?= $row['user_id'] ?>">
                                                <div class="mb-3">
                                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                                        Username
                                                    </label>
                                                    <input type="text" name="user_name" value="<?= $row['user_name'] ?>" class="form-input" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="text-gray-800 text-sm font-medium inline-block mb-2">
                                                        Password
                                                    </label>
                                                    <input type="text" name="password" value="<?= $row['password'] ?>" class="form-input" required>
                                                </div>


                                                <div class="mb-3">
                                                    <label class="form-label">Privileged</label>
                                                    <select class="form-select" name="privileged" id="inputGroupSelect04" required>
                                                        <option value="administrator" <?= ($row['previledge'] == 'administrator') ? 'selected' : '' ?>>Administrator</option>
                                                        <option value="user" <?= ($row['previledge'] == 'user') ? 'selected' : '' ?>>User</option>
                                                        <option value="finance" <?= ($row['previledge'] == 'finance') ? 'selected' : '' ?>>Finance</option>
                                                    </select>
                                                </div>








                                            </div>




                                    </div>
                                </div>
                            </div>
                            <div class="card  col-span-3">
                                <div class="card-header">
                                    <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium">Assign
                                        Permission</h4>
                                </div>

                                <div class="overflow-x-auto">
                                    <div class="p-4">

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


                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                View Product</td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['viewjeans', 'viewshoes', 'viewtop', 'viewcomplete',  'viewaccessory', 'viewwig', 'viewcosmetics'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>
                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Add Product</td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['addjeans', 'addshoes', 'addtop', 'addcomplete',  'addaccessory', 'addwig', 'addcosmetics'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>



                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Edit Product</td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['editjeans', 'editshoes', 'edittop', 'editcomplete',  'editaccessory', 'editwig', 'editcosmetics'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>


                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Delete Product</td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['deletejeans', 'deleteshoes', 'deletetop', 'deletecomplete',  'deleteaccessory', 'deletewig', 'deletecosmetics'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>


                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Verify Product</td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['verifyjeans', 'verifyshoes', 'verifytop', 'verifycomplete',  'verifyaccessory', 'verifywig', 'verifycosmetics'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>

                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Add Sale </td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['salejeans', 'saleshoes', 'saletop', 'salecomplete',  'saleaccessory', 'salewig', 'salecosmetics'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>


                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Edit Sale </td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['editsalejeans', 'editsaleshoes', 'editsaletop', 'editsalecomplete',  'editsaleaccessory', 'editsalewig', 'editsalecosmetics'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>


                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Delete Sale</td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['deletesalejeans', 'deletesaleshoes', 'deletesaletop', 'deletesalecomplete',  'deletesaleaccessory', 'deletesalewig', 'deletesalecosmetics'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>


                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Refund Sale</td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['refundsalejeans', 'refundsaleshoes', 'refundsaletop', 'refundsalecomplete',  'refundsaleaccessory', 'refundsalewig', 'refundsalecosmetics'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>



                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Exchange Sale</td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['exchangesalejeans', 'exchangesaleshoes', 'exchangesaletop', 'exchangesalecomplete',  'exchangesaleaccessory', 'exchangesalewig', 'exchangesalecosmetics'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>







                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Delivery</td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['deliverysalejeans', 'deliverysaleshoes', 'deliverysaletop', 'deliverysalecomplete',  'deliverysaleaccessory', 'deliverysalewig', 'deliverysalecosmetics'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>



                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Log </td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['logjeans', 'logshoes', 'logtop', 'logcomplete',  'logaccessory', 'logwig', 'logcosmetics'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>


                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Constant </td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['constant'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>


                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Backup </td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['backup'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>



                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Email </td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['email'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>


                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                User Account </td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['user'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>


                                                        <tr
                                                            class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                User Account </td>



                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['editbuyprice'];

                                                            // Loop through each checkbox name
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                // Check if the value is 1 in the JSON data
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';

                                                                // Generate the checkbox input

                                                                echo '<td
                                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';

                                                                // You can also display the checkbox label if needed
                                                                echo '</td>';
                                                            }
                                                            ?>





                                                        </tr>










                                                    </tbody>
                                                </table>
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
                            <button name="update_user" type="submit"
                                class="py-2.5 px-4 inline-flex justify-center items-center gap-2 rounded bg-success hover:bg-success-600 text-white">Edit
                                User</button>
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



if (isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    $privileged = $_POST['privileged'];



    $addjeans = $_POST['addjeans'];
    $addshoes = $_POST['addshoes'];
    $addtop = $_POST['addtop'];
    $addcomplete = $_POST['addcomplete'];
    $addaccessory = $_POST['addaccessory'];
    $addwig = $_POST['addwig'];
    $addcosmetics = $_POST['addcosmetics'];

    if ($addjeans == 1) {
        $addjeans = 1;
    } else {
        $addjeans = 0;
    }
    if ($addshoes == 1) {
        $addshoes = 1;
    } else {
        $addshoes = 0;
    }
    if ($addtop == 1) {
        $addtop = 1;
    } else {
        $addtop = 0;
    }
    if ($addcomplete == 1) {
        $addcomplete = 1;
    } else {
        $addcomplete = 0;
    }
    if ($addaccessory == 1) {
        $addaccessory = 1;
    } else {
        $addaccessory = 0;
    }
    if ($addwig == 1) {
        $addwig = 1;
    } else {
        $addwig = 0;
    }
    if ($addcosmetics == 1) {
        $addcosmetics = 1;
    } else {
        $addcosmetics = 0;
    }

    $editjeans = $_POST['editjeans'];
    $editshoes = $_POST['editshoes'];
    $edittop = $_POST['edittop'];
    $editcomplete = $_POST['editcomplete'];
    $editaccessory = $_POST['editaccessory'];
    $editwig = $_POST['editwig'];
    $editcosmetics = $_POST['editcosmetics'];

    if ($editjeans == 1) {
        $editjeans = 1;
    } else {
        $editjeans = 0;
    }
    if ($editshoes == 1) {
        $editshoes = 1;
    } else {
        $editshoes = 0;
    }
    if ($edittop == 1) {
        $edittop = 1;
    } else {
        $edittop = 0;
    }
    if ($editcomplete == 1) {
        $editcomplete = 1;
    } else {
        $editcomplete = 0;
    }
    if ($editaccessory == 1) {
        $editaccessory = 1;
    } else {
        $editaccessory = 0;
    }
    if ($editwig == 1) {
        $editwig = 1;
    } else {
        $editwig = 0;
    }
    if ($editcosmetics == 1) {
        $editcosmetics = 1;
    } else {
        $editcosmetics = 0;
    }

    $deletejeans = $_POST['deletejeans'];
    $deleteshoes = $_POST['deleteshoes'];
    $deletetop = $_POST['deletetop'];
    $deletecomplete = $_POST['deletecomplete'];
    $deleteaccessory = $_POST['deleteaccessory'];
    $deletewig = $_POST['deletewig'];
    $deletecosmetics = $_POST['deletecosmetics'];

    if ($deletejeans == 1) {
        $deletejeans = 1;
    } else {
        $deletejeans = 0;
    }
    if ($deleteshoes == 1) {
        $deleteshoes = 1;
    } else {
        $deleteshoes = 0;
    }
    if ($deletetop == 1) {
        $deletetop = 1;
    } else {
        $deletetop = 0;
    }
    if ($deletecomplete == 1) {
        $deletecomplete = 1;
    } else {
        $deletecomplete = 0;
    }
    if ($deleteaccessory == 1) {
        $deleteaccessory = 1;
    } else {
        $deleteaccessory = 0;
    }

    if ($deletewig == 1) {
        $deletewig = 1;
    } else {
        $deletewig = 0;
    }

    if ($deletecosmetics == 1) {
        $deletecosmetics = 1;
    } else {
        $deletecosmetics = 0;
    }


    $verifyjeans = $_POST['verifyjeans'];
    $verifyshoes = $_POST['verifyshoes'];
    $verifytop = $_POST['verifytop'];
    $verifycomplete = $_POST['verifycomplete'];
    $verifyaccessory = $_POST['verifyaccessory'];
    $verifywig = $_POST['verifywig'];
    $verifycosmetics = $_POST['verifycosmetics'];

    if ($verifyjeans == 1) {
        $verifyjeans = 1;
    } else {
        $verifyjeans = 0;
    }

    if ($verifyshoes == 1) {
        $verifyshoes = 1;
    } else {
        $verifyshoes = 0;
    }

    if ($verifytop == 1) {
        $verifytop = 1;
    } else {
        $verifytop = 0;
    }

    if ($verifycomplete == 1) {
        $verifycomplete = 1;
    } else {
        $verifycomplete = 0;
    }

    if ($verifyaccessory == 1) {
        $verifyaccessory = 1;
    } else {
        $verifyaccessory = 0;
    }

    if ($verifywig == 1) {
        $verifywig = 1;
    } else {
        $verifywig = 0;
    }

    if ($verifycosmetics == 1) {
        $verifycosmetics = 1;
    } else {
        $verifycosmetics = 0;
    }

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



    $viewjeans = (int) boolval($_POST['viewjeans']);
    $viewshoes = (int) boolval($_POST['viewshoes']);
    $viewtop = (int) boolval($_POST['viewtop']);
    $viewcomplete = (int) boolval($_POST['viewcomplete']);
    $viewaccessory = (int) boolval($_POST['viewaccessory']);
    $viewwig = (int) boolval($_POST['viewwig']);
    $viewcosmetics = (int) boolval($_POST['viewcosmetics']);






















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

        'constant' => $constant,
        'email' => $email,
        'backup' => $backup,
        'user' => $user,
        'editbuyprice' => $editbuyprice







    );


    // Convert the JSON array to a JSON-formatted string
    $jsonData = json_encode($jsonDataArray);

    $user_update = "UPDATE `user` SET `user_name`='$user_name', `password`='$password', `previledge`='$privileged', `module`='$jsonData' WHERE `user_id` = '$user_id'";
    $result_update = mysqli_query($con, $user_update);

    if ($result_update) {
        echo "<script>window.location = 'action.php?status=success&redirect=users.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=users.php'; </script>";
    }
}

?>