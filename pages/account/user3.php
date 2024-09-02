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

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
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


                                        <form method="post" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
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
                                                        <option value="administrator">Administrator</option>
                                                        <option value="user">User</option>
                                                        <option value="finance">Finance</option>
                                                    </select>

                                                </div>




                                                <div class="mb-3">

                                                    <label class="form-label">Payment</label>
                                                    <select class="form-select" name="payment" id="inputGroupSelect04" required>
                                                        <option value="yes">Yes</option>
                                                        <option value="no">No</option>

                                                    </select>

                                                </div>



                                            </div>




                                    </div>
                                </div>
                            </div>
                            <div class="card">
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
                                                            <th scope="col"
                                                                class="px-6 py-3 text-end text-xs font-medium text-gray-500 uppercase">
                                                                Verify</th>

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

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['calcview', 'calcadd', 'calcedit', 'calcdelete', 'calcgenerate'];

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
                                                                Constants</td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                Calculate</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['constview', 'constadd', 'constedit', 'constdelete', 'constgenerate'];

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
                                                                Payment</td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                pay</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['payview', 'payadd', 'payedit', 'paydelete', 'paygenerate', 'payverify'];

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
                                                                Bank Statment </td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                pay</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['bankview', 'bankadd', 'bankedit', 'bankdelete', 'bankgenerate', 'bankverify'];

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
                                                                Vat Status</td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                vat</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['vatview', 'vatadd', 'vatedit', 'vatdelete', 'vatgenerate'];

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
                                                                Bank</td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                Bank Operations</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['banksview', 'banksadd', 'banksedit', 'banksdelete', 'banksgenerate'];

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
                                                                Stocks</td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                Calculate</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['stockview', 'stockadd', 'stockedit', 'stockdelete', 'stockgenerate'];

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
                                                                Database</td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                actions</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['dataview', 'dataadd', 'dataedit', 'datadelete', 'datagenerate'];

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
                                                                Job Status</td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                actions</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['jobview', 'jobedit',];

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
                                                                Sales</td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                actions</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['saleview', 'saleadd', 'saleedit', 'saledelete', 'salegenerate'];

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
                                                                Report</td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                actions</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['reportview'];

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
                                                                User</td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                actions</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['userview', 'useradd', 'useredit', 'userdelete', 'usergenerate'];

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
                                                                Customer</td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                actions</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['custview', 'custadd', 'custedit', 'custdelete', 'custgenerate'];

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
                                                                Generate </td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                actions</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['generateview'];

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
                                                                FileManager</td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                actions</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['fileview'];

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
                                                                BackUp</td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                actions</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['backview'];

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
                                                                Profile</td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                actions</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['profileview'];

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
                                                                Brocher</td>

                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                actions</td>

                                                            <?php
                                                            $moduleData = json_decode($module, true);

                                                            // Define the checkbox names
                                                            $checkboxNames = ['brocherview'];

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



                                                        <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Book
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                Actions
                                                            </td>
                                                            <?php
                                                            $checkboxNames = ['bookview'];
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';
                                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';
                                                                echo '</td>';
                                                            }
                                                            ?>
                                                        </tr>

                                                        <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Manual
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                Actions
                                                            </td>
                                                            <?php
                                                            $checkboxNames = ['manualview'];
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';
                                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';
                                                                echo '</td>';
                                                            }
                                                            ?>
                                                        </tr>

                                                        <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Digital
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                Actions
                                                            </td>
                                                            <?php
                                                            $checkboxNames = ['digitalview'];
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';
                                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';
                                                                echo '</td>';
                                                            }
                                                            ?>
                                                        </tr>

                                                        <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Banner
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                Actions
                                                            </td>
                                                            <?php
                                                            $checkboxNames = ['bannerview'];
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';
                                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';
                                                                echo '</td>';
                                                            }
                                                            ?>
                                                        </tr>

                                                        <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Design
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                Actions
                                                            </td>
                                                            <?php
                                                            $checkboxNames = ['designview'];
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';
                                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';
                                                                echo '</td>';
                                                            }
                                                            ?>
                                                        </tr>

                                                        <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Single Page
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                Actions
                                                            </td>
                                                            <?php
                                                            $checkboxNames = ['singlepageview'];
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';
                                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';
                                                                echo '</td>';
                                                            }
                                                            ?>
                                                        </tr>

                                                        <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                Multi Page
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                                                Actions
                                                            </td>
                                                            <?php
                                                            $checkboxNames = ['multipageview'];
                                                            foreach ($checkboxNames as $checkboxName) {
                                                                $checked = ($moduleData[$checkboxName] == 1) ? 'checked' : '';
                                                                echo '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">';
                                                                echo '<input type="checkbox" name="' . $checkboxName . '" value="1" ' . $checked . '>';
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
    $payment = $_POST['payment'];
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

    //constants
    $constview = $_POST['constview'];
    $constadd = $_POST['constadd'];
    $constedit = $_POST['constedit'];
    $constdelete = $_POST['constdelete'];
    $constgenerate = $_POST['constgenerate'];



    if ($constview == 1) {
        $constview = 1;
    } else {
        $constview = 0;
    }
    if ($constadd == 1) {
        $constadd = 1;
    } else {
        $constadd = 0;
    }
    if ($constedit == 1) {
        $constedit = 1;
    } else {
        $constedit = 0;
    }
    if ($constdelete == 1) {
        $constdelete = 1;
    } else {
        $constdelete = 0;
    }
    if ($constgenerate == 1) {
        $constgenerate = 1;
    } else {
        $constgenerate = 0;
    }




    $stockview = $_POST['stockview'];
    $stockadd = $_POST['stockadd'];
    $stockedit = $_POST['stockedit'];
    $stockdelete = $_POST['stockdelete'];
    $stockgenerate = $_POST['stockgenerate'];






    if ($stockview == 1) {
        $stockview = 1;
    } else {
        $stockview = 0;
    }
    if ($stockadd == 1) {
        $stockadd = 1;
    } else {
        $stockadd = 0;
    }
    if ($stockedit == 1) {
        $stockedit = 1;
    } else {
        $stockedit = 0;
    }
    if ($stockdelete == 1) {
        $stockdelete = 1;
    } else {
        $stockdelete = 0;
    }
    if ($stockgenerate == 1) {
        $stockgenerate = 1;
    } else {
        $stockgenerate = 0;
    }


    $dataview = $_POST['dataview'];
    $dataadd = $_POST['dataadd'];
    $dataedit = $_POST['dataedit'];
    $datadelete = $_POST['datadelete'];
    $datagenerate = $_POST['datagenerate'];






    if ($dataview == 1) {
        $dataview = 1;
    } else {
        $dataview = 0;
    }
    if ($dataadd == 1) {
        $dataadd = 1;
    } else {
        $dataadd = 0;
    }
    if ($dataedit == 1) {
        $dataedit = 1;
    } else {
        $dataedit = 0;
    }
    if ($datadelete == 1) {
        $datadelete = 1;
    } else {
        $datadelete = 0;
    }
    if ($datagenerate == 1) {
        $datagenerate = 1;
    } else {
        $datagenerate = 0;
    }


    //job

    $jobview = $_POST['jobview'];
    $jobedit = $_POST['jobedit'];


    if ($jobview == 1) {
        $jobview = 1;
    } else {
        $jobview = 0;
    }

    if ($jobedit == 1) {
        $jobedit = 1;
    } else {
        $jobedit = 0;
    }


    $saleview = $_POST['saleview'];
    $saleadd = $_POST['saleadd'];
    $saleedit = $_POST['saleedit'];
    $saledelete = $_POST['saledelete'];
    $salegenerate = $_POST['salegenerate'];






    if ($saleview == 1) {
        $saleview = 1;
    } else {
        $saleview = 0;
    }
    if ($saleadd == 1) {
        $saleadd = 1;
    } else {
        $saleadd = 0;
    }
    if ($saleedit == 1) {
        $saleedit = 1;
    } else {
        $saleedit = 0;
    }
    if ($saledelete == 1) {
        $saledelete = 1;
    } else {
        $saledelete = 0;
    }
    if ($salegenerate == 1) {
        $salegenerate = 1;
    } else {
        $salegenerate = 0;
    }


    $reportview = $_POST['reportview'];

    if ($reportview == 1) {
        $reportview = 1;
    } else {
        $reportview = 0;
    }


    //user managment
    $userview = $_POST['userview'];
    $useradd = $_POST['useradd'];
    $useredit = $_POST['useredit'];
    $userdelete = $_POST['userdelete'];
    $usergenerate = $_POST['usergenerate'];


    if ($userview == 1) {
        $userview = 1;
    } else {
        $userview = 0;
    }
    if ($useradd == 1) {
        $useradd = 1;
    } else {
        $useradd = 0;
    }
    if ($useredit == 1) {
        $useredit = 1;
    } else {
        $useredit = 0;
    }
    if ($userdelete == 1) {
        $userdelete = 1;
    } else {
        $userdelete = 0;
    }
    if ($usergenerate == 1) {
        $usergenerate = 1;
    } else {
        $usergenerate = 0;
    }


    //customer managment
    $custview = $_POST['custview'];
    $custadd = $_POST['custadd'];
    $custedit = $_POST['custedit'];
    $custdelete = $_POST['custdelete'];
    $custgenerate = $_POST['custgenerate'];


    if ($custview == 1) {
        $custview = 1;
    } else {
        $custview = 0;
    }
    if ($custadd == 1) {
        $custadd = 1;
    } else {
        $custadd = 0;
    }
    if ($custedit == 1) {
        $custedit = 1;
    } else {
        $custedit = 0;
    }
    if ($custdelete == 1) {
        $custdelete = 1;
    } else {
        $custdelete = 0;
    }
    if ($custgenerate == 1) {
        $custgenerate = 1;
    } else {
        $custgenerate = 0;
    }

    //randoms
    $generateview = $_POST['generateview'];
    $fileview = $_POST['fileview'];
    $backview = $_POST['backview'];


    if ($generateview == 1) {
        $generateview = 1;
    } else {
        $generateview = 0;
    }
    if ($fileview == 1) {
        $fileview = 1;
    } else {
        $fileview = 0;
    }
    if ($backview == 1) {
        $backview = 1;
    } else {
        $backview = 0;
    }


    //payment 
    $payview = $_POST['payview'];
    $payadd = $_POST['payadd'];
    $payedit = $_POST['payedit'];
    $paydelete = $_POST['paydelete'];
    $paygenerate = $_POST['paygenerate'];
    $payverify = $_POST['payverify'];

    if ($payview == 1) {
        $payview = 1;
    } else {
        $payview = 0;
    }
    if ($payadd == 1) {
        $payadd = 1;
    } else {
        $payadd = 0;
    }
    if ($payedit == 1) {
        $payedit = 1;
    } else {
        $payedit = 0;
    }
    if ($paydelete == 1) {
        $paydelete = 1;
    } else {
        $paydelete = 0;
    }
    if ($paygenerate == 1) {
        $paygenerate = 1;
    } else {
        $paygenerate = 0;
    }
    if ($payverify == 1) {
        $payverify = 1;
    } else {
        $payverify = 0;
    }


    //bank statment
    $bankview = $_POST['bankview'];
    $bankadd = $_POST['bankadd'];
    $bankedit = $_POST['bankedit'];
    $bankdelete = $_POST['bankdelete'];
    $bankgenerate = $_POST['bankgenerate'];
    $bankverify = $_POST['bankverify'];


    if ($bankview == 1) {
        $bankview = 1;
    } else {
        $bankview = 0;
    }
    if ($bankadd == 1) {
        $bankadd = 1;
    } else {
        $bankadd = 0;
    }
    if ($bankedit == 1) {
        $bankedit = 1;
    } else {
        $bankedit = 0;
    }
    if ($bankdelete == 1) {
        $bankdelete = 1;
    } else {
        $bankdelete = 0;
    }
    if ($bankgenerate == 1) {
        $bankgenerate = 1;
    } else {
        $bankgenerate = 0;
    }
    if ($bankverify == 1) {
        $bankverify = 1;
    } else {
        $bankverify = 0;
    }


    //vat status
    $vatview = $_POST['vatview'];
    $vatadd = $_POST['vatadd'];
    $vatedit = $_POST['vatedit'];
    $vatdelete = $_POST['vatdelete'];
    $vatgenerate = $_POST['vatgenerate'];

    if ($vatview == 1) {
        $vatview = 1;
    } else {
        $vatview = 0;
    }
    if ($vatadd == 1) {
        $vatadd = 1;
    } else {
        $vatadd = 0;
    }
    if ($vatedit == 1) {
        $vatedit = 1;
    } else {
        $vatedit = 0;
    }
    if ($vatdelete == 1) {
        $vatdelete = 1;
    } else {
        $vatdelete = 0;
    }
    if ($vatgenerate == 1) {
        $vatgenerate = 1;
    } else {
        $vatgenerate = 0;
    }

    //bank
    $banksview = $_POST['banksview'];
    $banksadd = $_POST['banksadd'];
    $banksedit = $_POST['banksedit'];
    $banksdelete = $_POST['banksdelete'];
    $banksgenerate = $_POST['banksgenerate'];

    if ($banksview == 1) {
        $banksview = 1;
    } else {
        $banksview = 0;
    }
    if ($banksadd == 1) {
        $banksadd = 1;
    } else {
        $banksadd = 0;
    }
    if ($banksedit == 1) {
        $banksedit = 1;
    } else {
        $banksedit = 0;
    }
    if ($banksdelete == 1) {
        $banksdelete = 1;
    } else {
        $banksdelete = 0;
    }
    if ($banksgenerate == 1) {
        $banksgenerate = 1;
    } else {
        $banksgenerate = 0;
    }


    $profileview = $_POST['profileview'];

    if ($profileview == 1) {
        $profileview = 1;
    } else {
        $profileview = 0;
    }

    $brocherview = $_POST['brocherview'];

    if ($brocherview == 1) {
        $brocherview = 1;
    } else {
        $brocherview = 0;
    }

    $bookview = $_POST['bookview'];

    if ($bookview == 1) {
        $bookview = 1;
    } else {
        $bookview = 0;
    }

    $manualview = $_POST['manualview'];

    if ($manualview == 1) {
        $manualview = 1;
    } else {
        $manualview = 0;
    }

    $digitalview = $_POST['digitalview'];

    if ($digitalview == 1) {
        $digitalview = 1;
    } else {
        $digitalview = 0;
    }

    $bannerview = $_POST['bannerview'];

    if ($bannerview == 1) {
        $bannerview = 1;
    } else {
        $bannerview = 0;
    }

    $designview = $_POST['designview'];

    if ($designview == 1) {
        $designview = 1;
    } else {
        $designview = 0;
    }

    $singlepageview = $_POST['singlepageview'];

    if ($singlepageview == 1) {
        $singlepageview = 1;
    } else {
        $singlepageview = 0;
    }

    $multipageview = $_POST['multipageview'];

    if ($multipageview == 1) {
        $multipageview = 1;
    } else {
        $multipageview = 0;
    }





    $jsonDataArray = array(
        'calcview' => $calcview,
        'calcadd' => $calcadd,
        'calcedit' => $calcedit,
        'calcdelete' => $calcdelete,
        'calcgenerate' => $calcgenerate,


        'constview' => $constview,
        'constadd' => $constadd,
        'constedit' => $constedit,
        'constdelete' => $constdelete,
        'constgenerate' => $constgenerate,



        'stockview' => $stockview,
        'stockadd' => $stockadd,
        'stockedit' => $stockedit,
        'stockdelete' => $stockdelete,
        'stockgenerate' => $stockgenerate,


        'dataview' => $dataview,
        'dataadd' => $dataadd,
        'dataedit' => $dataedit,
        'datadelete' => $datadelete,
        'datagenerate' => $datagenerate,

        'jobview' => $jobview,
        'jobedit' => $jobedit,

        'saleview' => $saleview,
        'saleadd' => $saleadd,
        'saleedit' => $saleedit,
        'saledelete' => $saledelete,
        'salegenerate' => $salegenerate,

        'reportview' => $reportview,

        'userview' => $userview,
        'useradd' => $useradd,
        'useredit' => $useredit,
        'userdelete' => $userdelete,
        'usergenerate' => $usergenerate,


        'custview' => $custview,
        'custadd' => $custadd,
        'custedit' => $custedit,
        'custdelete' => $custdelete,
        'custgenerate' => $custgenerate,

        'generateview' => $generateview,
        'fileview' => $fileview,
        'backview' => $backview,

        'payview' => $payview,
        'payadd' => $payadd,
        'payedit' => $payedit,
        'paydelete' => $paydelete,
        'paygenerate' => $paygenerate,
        'payverify' => $payverify,

        'bankview' => $bankview,
        'bankadd' => $bankadd,
        'bankedit' => $bankedit,
        'bankdelete' => $bankdelete,
        'bankgenerate' => $bankgenerate,
        'bankverify' => $bankverify,

        'vatview' => $vatview,
        'vatadd' => $vatadd,
        'vatedit' => $vatedit,
        'vatdelete' => $vatdelete,
        'vatgenerate' => $vatgenerate,

        'banksview' => $banksview,
        'banksadd' => $banksadd,
        'banksedit' => $banksedit,
        'banksdelete' => $banksdelete,
        'banksgenerate' => $banksgenerate,

        'profileview' => $profileview,
        'brocherview' => $brocherview,
        'bookview' => $bookview,
        'manualview' => $manualview,
        'digitalview' => $digitalview,
        'bannerview' => $bannerview,
        'designview' => $designview,
        'singlepageview' => $singlepageview,
        'multipageview' => $multipageview




    );


    // Convert the JSON array to a JSON-formatted string
    $jsonData = json_encode($jsonDataArray);

    $user_update = "UPDATE `user` SET `user_name`='$user_name', `password`='$password', `previledge`='$privileged', `payment`='$payment',`module`='$jsonData' WHERE `user_id` = '$user_id'";
    $result_update = mysqli_query($con, $user_update);

    if ($result_update) {
        echo "<script>window.location = 'action.php?status=success&redirect=users.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=users.php'; </script>";
    }
}

?>