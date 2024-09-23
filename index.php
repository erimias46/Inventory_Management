<?php
$redirect_link = "";
include 'partials/main.php';


include 'include/db.php';



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


        $calculateButtonVisible = ($module['userview'] == 1) ? true : false;


        $addButtonVisible = ($module['useradd'] == 1) ? true : false;

        $deleteButtonVisible = ($module['userdelete'] == 1) ? true : false;


        $updateButtonVisible = ($module['useredit'] == 1) ? true : false;


        $generateButtonVisible = ($module['usergenerate'] == 1) ? true : false;
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
    <?php $title = "Dashboard";
    include 'partials/title-meta.php'; ?>

    <?php include 'partials/head-css.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


</head>

<?php
$from_date = empty($_GET['from']) ? "1000-01-01" : $_GET['from'];
$to_date = empty($_GET['to']) ? "3000-01-01" : $_GET['to'];
?>

<body>

    <!-- Begin page -->
    <div class="flex wrapper">

        <?php include 'partials/menu.php'; ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <?php include 'partials/topbar.php'; ?>

            <main class="flex-grow p-6">

                <?php
                $subtitle = "Menu";
                $pagetitle = "Dashboard";
                include 'partials/page-title.php'; ?>

                <div class="grid 2xl:grid-cols-4 gap-6 mb-6">

                    <div class="2xl:col-span-3">
                        <div class="grid xl:grid-cols-4 md:grid-cols-2 gap-6 mb-6">
                            <div class="card">
                                <div class="p-6">
                                    <div class="flex justify-between items-center">
                                        <div
                                            class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-primary/25 ">
                                            <i class="mgc_bill_line text-4xl text-primary"></i>
                                        </div>


                                        <div class="text-right">

                                            <div>
                                                <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                                    data-fc-placement="left-start" type="button">
                                                    <i class="mgc_more_1_fill text-xl"></i>
                                                </button>
                                                <div
                                                    class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">

                                                    <a href="pages/payment/record2.php"
                                                        class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                                        href="javascript:void(0)">
                                                        <i class="mgc_info_circle_line"></i> Info
                                                    </a>

                                                </div>
                                            </div>
                                            <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">
                                                <?php
                                                $result = mysqli_query($con, "SELECT count(*) AS total, SUM(remained) as total_remaining FROM payment WHERE remained != 0");
                                                $row = mysqli_fetch_array($result);
                                                echo $row['total'];
                                                ?>

                                            </h3>


                                            <h3 class="text-gray-500 mb-1 truncate dark:text-gray-400">Total Unpaid
                                                bills
                                            </h3>

                                        </div>




                                    </div>


                                </div>
                            </div>

                            <div class="card">
                                <div class="p-6">
                                    <div class="flex justify-between items-center">
                                        <div
                                            class="w-20 h-20 mx-2 rounded-full inline-flex items-center justify-center bg-yellow-100">
                                            <i class="mgc_currency_dollar_fill text-5xl text-yellow-500"></i>
                                        </div>
                                        <div class="text-right">
                                            <div>
                                                <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                                    data-fc-placement="left-start" type="button">
                                                    <i class="mgc_more_1_fill text-xl"></i>
                                                </button>
                                                <div
                                                    class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">

                                                    <a href="pages/payment/record2.php"
                                                        class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                                        href="javascript:void(0)">
                                                        <i class="mgc_info_circle_line"></i> Info
                                                    </a>

                                                </div>
                                            </div>
                                            <h4 class="text-gray-700 mt-1 text-xl font-bold mb-5 dark:text-gray-300">
                                                <?= number_format($row['total_remaining'], 2) ?>
                                            </h4>
                                            <p class="text-gray-500 mb-1 truncate dark:text-gray-400"> Remaining
                                                Birr</p>
                                        </div>


                                    </div>


                                </div>
                            </div>


                            <?php

                            if ($privileged == 'stock'  || $privileged == 'administrator') {



                            ?>

                                <div class="card">
                                    <div class="p-6">
                                        <div class="flex justify-between  items-center">
                                            <div
                                                class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-red-100">
                                                <i class="mgc_warning_line text-4xl text-red-500"></i>
                                            </div>
                                            <div class="text-right">

                                                <div>
                                                    <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                                        data-fc-placement="left-start" type="button">
                                                        <i class="mgc_more_1_fill text-xl"></i>
                                                    </button>
                                                    <div
                                                        class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">

                                                        <a href="pages/stock/stock_managment.php"
                                                            class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                                            href="javascript:void(0)">
                                                            <i class="mgc_info_circle_line"></i> Info
                                                        </a>

                                                    </div>
                                                </div>
                                                <?php
                                                $query = "
    SELECT COUNT(*) as danger_zone 
    FROM (
        SELECT stock_quantity FROM stock WHERE stock_quantity < dangerzone
        UNION ALL
        SELECT stock_quantity FROM office_stock WHERE stock_quantity < dangerzone
    ) as combined_stock
";

                                                $result = mysqli_query($con, $query);
                                                $row = mysqli_fetch_assoc($result);
                                                ?>
                                                <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">
                                                    <?= $row['danger_zone'] ?>
                                                </h3>
                                                <p class="text-gray-500 mb-1 truncate dark:text-gray-400">Danger Zone
                                                </p>
                                            </div>


                                        </div>


                                    </div>
                                </div>


                            <?php } ?>

                            <div class="card">
                                <div class="p-6">
                                    <div class="flex justify-between items-center">
                                        <div
                                            class="w-20 h-20 rounded-full inline-flex items-center justify-center bg-green-100">
                                            <i class="mgc_check_circle_line text-4xl text-green-500"></i>
                                        </div>
                                        <div class="text-right">

                                            <div>
                                                <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                                    data-fc-placement="left-start" type="button">
                                                    <i class="mgc_more_1_fill text-xl"></i>
                                                </button>
                                                <div
                                                    class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">

                                                    <a href="pages/payment/payment_filter.php"
                                                        class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                                        href="javascript:void(0)">
                                                        <i class="mgc_info_circle_line"></i> Info
                                                    </a>

                                                </div>
                                            </div>
                                            <?php
                                            $result = mysqli_query($con, 'SELECT COUNT(*) as unverified FROM bank WHERE verified = 0');
                                            $row = mysqli_fetch_assoc($result);
                                            ?>
                                            <h3 class="text-gray-700 mt-1 text-2xl font-bold mb-5 dark:text-gray-300">
                                                <?= $row['unverified'] ?>
                                            </h3>
                                            <p class="text-gray-500 mb-1 truncate dark:text-gray-400">Unverified
                                            </p>
                                        </div>


                                    </div>


                                </div>
                            </div>
                        </div>

                        <div class="grid lg:grid-cols-3 gap-6">
                            <div class="col-span-1">
                                <div class="card">
                                    <div class="p-6">
                                        <div class="flex flex-row  justify-between">
                                            <h4 class="card-title">Vat Status</h4>

                                            <div class="card-title">
                                                <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                                    data-fc-placement="left-start" type="button">
                                                    <i class="mgc_more_1_fill text-xl"></i>
                                                </button>
                                                <div
                                                    class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">

                                                    <a href="pages/payment/vat_status.php "
                                                        class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                                        href="javascript:void(0)">
                                                        <i class="mgc_info_circle_line"></i> Info
                                                    </a>

                                                </div>
                                            </div>

                                        </div>





                                        <div id="vat-status" class="apex-charts my-8" data-colors="#0acf97,#3073F1">
                                        </div>

                                        <?php
                                        $current_date = date('Y-m-d');


                                        $sql1 = "SELECT from_date, to_date FROM compare 
                                        WHERE '$current_date' BETWEEN from_date AND to_date;";
                                        $result1 = mysqli_query($con, $sql1);

                                        if ($result1->num_rows > 0) {
                                            $row1 = $result1->fetch_assoc();

                                            $from_date1 = $row1['from_date'];
                                            $to_date1 = $row1['to_date'];


                                            //check the date range if it exists in the database

                                            $sql = "SELECT * FROM summary_table WHERE from_date = '$from_date1' AND to_date = '$to_date1';";
                                            $result = mysqli_query($con, $sql);

                                            if ($result->num_rows > 0) {
                                                // Output the data
                                                $row = mysqli_fetch_assoc($result);
                                                $from_date1 = $row['from_date'];
                                                $to_date1 = $row['to_date'];
                                            } else {
                                                //register it 
                                                // Check if this is the first insert
                                                $sql_check = "SELECT COUNT(*) as count FROM summary_table";
                                                $result_check = mysqli_query($con, $sql_check);
                                                $row_check = mysqli_fetch_assoc($result_check);

                                                if ($row_check['count'] == 0) {
                                                    // First insert, just insert from_date and to_date
                                                    $sql = "INSERT INTO summary_table (from_date, to_date) VALUES ('$from_date1', '$to_date1')";
                                                    $result = mysqli_query($con, $sql);
                                                } else {
                                                    // Not the first insert, retrieve the last inserted difference value
                                                    $sql_last = "SELECT difference FROM summary_table ORDER BY id DESC LIMIT 1";
                                                    $result_last = mysqli_query($con, $sql_last);
                                                    $row_last = mysqli_fetch_assoc($result_last);

                                                    if ($row_last['difference'] > 0) {
                                                        // Insert from_date, to_date, and last_month with the positive difference value
                                                        $sql = "INSERT INTO summary_table (from_date, to_date, last_month) VALUES ('$from_date1', '$to_date1', '{$row_last['difference']}')";
                                                        $result = mysqli_query($con, $sql);
                                                    } else {
                                                        // Insert from_date and to_date only if difference is not positive
                                                        $sql = "INSERT INTO summary_table (from_date, to_date) VALUES ('$from_date1', '$to_date1')";
                                                        $result = mysqli_query($con, $sql);
                                                    }
                                                }
                                            }

                                            //register it  
                                            //if it exists then not created 
                                            //if it does not exist then create it

                                            //    10000 sales and 20000 purchase   


                                            // 2024-01-01 
                                            // 2024-12-31


                                            // 2025-01-01   10000 last month 
                                            // 2025-12-31
                                            //check the difference between the sales and purchase  if the purchase is greater than the sales then 
                                            //differnce amount last season lay add yadregew 

                                            // last price+ purchase add and show it on the chart 


                                            $sql = "SELECT * FROM summary_table WHERE from_date = '$from_date1' AND to_date = '$to_date1';";
                                            $result = mysqli_query($con, $sql);

                                            if ($result->num_rows > 0) {
                                                // Output the data
                                                $row = mysqli_fetch_assoc($result);
                                                $last_month = $row['last_month'];
                                            }
                                        } else {
                                            echo "No matching date range found";
                                            // Set default values if no records found
                                            $from_date1 = $to_date1 = 'default_value'; // Change 'default_value' to your desired default value
                                        }
                                        ?>

                                        <?php
                                        $sql = "SELECT
                                    (SELECT SUM(price_including_vat) 
                                        FROM sales
                                        WHERE sales_date >= '$from_date1' AND sales_date < '$to_date1') AS purchase,
                                    
                                    (SELECT SUM(price_including_vat)  
                                        FROM sales_withvat
                                        WHERE sales_date >= '$from_date1' AND sales_date < '$to_date1') AS sale;";
                                        $result = mysqli_query($con, $sql);

                                        if ($result) {
                                            $row = mysqli_fetch_assoc($result);
                                            $purchase = $row['purchase'];
                                            $sale = $row['sale'];
                                        } else {
                                            // Handle the error (e.g., display an error message)
                                            echo "Error executing the query: " . mysqli_error($con);
                                            // Set default values
                                            $purchase = $sale = 0; // Change to your desired default values
                                        }
                                        ?>


                                        <script>
                                            var options = {
                                                chart: {
                                                    height: 280,
                                                    type: 'donut',
                                                },
                                                legend: {
                                                    show: false
                                                },
                                                stroke: {
                                                    colors: ['transparent']
                                                },
                                                series: [<?php echo $purchase + $last_month; ?>, <?php echo $sale; ?>],
                                                labels: ["Purchase", "Sales"],
                                                colors: ['#0acf97', '#3073F1'],

                                                responsive: [{
                                                    breakpoint: 480,
                                                    options: {
                                                        chart: {
                                                            width: 200
                                                        },
                                                        legend: {
                                                            position: 'bottom'
                                                        }
                                                    }
                                                }]
                                            }


                                            var chart = new ApexCharts(document.querySelector("#vat-status"), options);
                                            chart.render();
                                        </script>

                                        <div class="status">
                                            <div class="status-circle"></div>

                                            <p>Last Month : <?php echo $last_month ?></p>
                                            <div class="status-text">Status Net :
                                                <?php if ($purchase + $last_month > $sale) {
                                                    $nets = $purchase - $sale;
                                                    $net = number_format($nets, 2);
                                                    echo $net . 'Sale The Products ';
                                                } elseif ($purchase + $last_month < $sale) {
                                                    $nets = $sale - $purchase;
                                                    $net = number_format($nets, 2,);
                                                    echo $net . ' Purchase The Products ';
                                                } else {
                                                    echo 'Best position ✅';
                                                } ?></div>
                                        </div>




                                        <div class="flex justify-center">
                                            <div class="w-1/2 text-center">
                                                <h5>$ <?php echo $sale; ?></h5>
                                                <p class="fw-semibold text-muted">
                                                    <i class="mgc_round_fill text-primary"></i> Sales
                                                </p>
                                            </div>
                                            <div class="w-1/2 text-center">
                                                <h5>$ <?php echo $purchase; ?></h5>
                                                <p class="fw-semibold text-muted">
                                                    <i class="mgc_round_fill text-success"></i> Purchase
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="lg:col-span-2">
                                <div class="card">
                                    <div class="p-6">
                                        <div class="flex flex-row justify-between ">


                                            <h4 class="card-title">Unverified Bank Statments </h4>

                                            <div class="card-title">
                                                <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                                    data-fc-placement="left-start" type="button">
                                                    <i class="mgc_more_1_fill text-xl"></i>
                                                </button>
                                                <div
                                                    class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">

                                                    <a href="pages/payment/bank_statment.php"
                                                        class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                                        href="javascript:void(0)">
                                                        <i class="mgc_info_circle_line"></i> Info
                                                    </a>

                                                </div>
                                            </div>

                                        </div>
                                        <?php
                                        $defaultStartDate = date("Y-m-d", strtotime("-3 months"));

                                        $data = array();
                                        $totalProjectsData = array();


                                        $sql = "SELECT * FROM ( SELECT *, SUM(CASE WHEN verified = 0 THEN 1 ELSE 0 END) AS unverified_count, COUNT(*) AS verified_count, SUM(amount) AS total_amount FROM bank GROUP BY name ) AS t ORDER BY unverified_count DESC LIMIT 6; ";

                                        $result = mysqli_query($con, $sql);

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $data[] = array(
                                                    'client' => $row['name'],
                                                    'remained' => $row['unverified_count'],
                                                    'total_projects' => $row['verified_count'] + $row['unverified_count']
                                                );
                                            }
                                        }



                                        // Return data as JSON
                                        ?>

                                        <!-- HTML code for the form and chart -->




                                        <div dir="ltr" class="mt-2">
                                            <div id="Completed" class="apex-charts" data-colors="#cbdcfc,#3073F1"></div>
                                        </div>

                                        <!-- JavaScript code for ApexCharts -->
                                        <script>
                                            var colors = ["#3073F1", "#0acf97"];
                                            var dataColors = document.querySelector("#Completed").dataset.colors;

                                            if (dataColors) {
                                                colors = dataColors.split(",");
                                            }

                                            var chartData = <?php echo json_encode($data); ?>; // Initial data
                                            var totalProjectsData =
                                                <?php echo json_encode($totalProjectsData); ?>; // Total projects data

                                            var options = {
                                                chart: {
                                                    height: 350,
                                                    type: 'bar',
                                                    toolbar: {
                                                        show: false
                                                    }
                                                },
                                                plotOptions: {
                                                    bar: {
                                                        horizontal: false,
                                                        endingShape: 'rounded',
                                                        columnWidth: '25%',
                                                    },
                                                },
                                                dataLabels: {
                                                    enabled: false
                                                },
                                                stroke: {
                                                    show: true,
                                                    width: 3,
                                                    colors: ['transparent']
                                                },
                                                colors: colors,
                                                series: [{
                                                        name: 'Unverfied Count',
                                                        data: chartData.map(item => item.remained)
                                                    },
                                                    {
                                                        name: 'Total Projects',
                                                        data: chartData.map(item => item.total_projects)

                                                    }
                                                ],
                                                xaxis: {
                                                    categories: chartData.map(item => item.client)
                                                },
                                                legend: {
                                                    offsetY: 7,
                                                },
                                                fill: {
                                                    opacity: 1
                                                },
                                                grid: {
                                                    row: {
                                                        colors: ['transparent', 'transparent'],
                                                        opacity: 0.2
                                                    },
                                                    borderColor: '#9ca3af20',
                                                    padding: {
                                                        bottom: 5,
                                                    }
                                                }
                                            };

                                            var chart = new ApexCharts(
                                                document.querySelector("#Completed"),
                                                options
                                            );

                                            chart.render();

                                            // Add event listener to the select input for changing the date range
                                            document.getElementById('dateRangeSelect').addEventListener('change',
                                                function() {
                                                    var selectedValue = this.value;
                                                    var startDate;

                                                    switch (selectedValue) {
                                                        case '1 Month':
                                                            startDate = new Date();
                                                            startDate.setMonth(startDate.getMonth() - 1);
                                                            break;
                                                        case '3 Month':
                                                            startDate = new Date();
                                                            startDate.setMonth(startDate.getMonth() - 3);
                                                            break;
                                                        case '6 Month':
                                                            startDate = new Date();
                                                            startDate.setMonth(startDate.getMonth() - 6);
                                                            break;
                                                        case '1 Year':
                                                            startDate = new Date();
                                                            startDate.setFullYear(startDate.getFullYear() - 1);
                                                            break;
                                                    }

                                                    fetchData(startDate);
                                                });

                                            function fetchData(startDate) {
                                                var formattedStartDate = startDate.toISOString().split('T')[0];
                                                var endDate = new Date().toISOString().split('T')[0];

                                                $.ajax({
                                                    url: 'index3.php',
                                                    data: {
                                                        startDate: formattedStartDate,
                                                        endDate: endDate
                                                    },
                                                    success: function(newData) {
                                                        chartData = newData;
                                                        chart.updateOptions({
                                                            xaxis: {
                                                                categories: chartData.map(item => item
                                                                    .client)
                                                            }
                                                        });
                                                        chart.updateSeries([{
                                                                name: 'Remaining Amount',
                                                                data: chartData.map(item => item
                                                                    .remained)
                                                            },
                                                            {
                                                                name: 'Total Projects',
                                                                data: chartData.map(item => item
                                                                    .total_projects)
                                                            }
                                                        ]);
                                                    }
                                                });
                                            }
                                        </script>







                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="col-span-1">

                        <?php if ($privileged == 'stock' || $privileged == 'administrator') { ?>
                            <div class="card mb-6">
                                <div class="px-6 py-5 flex justify-between items-center">
                                    <h4 class="header-title">Stock Summary</h4>
                                    <div>
                                        <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                            data-fc-placement="left-start" type="button">
                                            <i class="mgc_more_1_fill text-xl"></i>
                                        </button>

                                        <div
                                            class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">
                                            <a href="pages/stock/stock_managment.php "
                                                class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                                href="javascript:void(0)">
                                                <i class="mgc_info_circle_line"></i> Info
                                            </a>

                                        </div>
                                    </div>
                                    <?php
                                    // Query to count records from both stock and office_stock tables
                                    $query = "
    SELECT COUNT(*) as danger_zone 
    FROM (
        SELECT stock_quantity FROM stock WHERE stock_quantity < dangerzone
        UNION ALL
        SELECT stock_quantity FROM office_stock WHERE stock_quantity < dangerzone
    ) as combined_stock
";

                                    // Execute the query
                                    $result = mysqli_query($con, $query);

                                    // Fetch the result
                                    $row = mysqli_fetch_assoc($result);

                                    // Get the total danger zone count
                                    $total_danger = $row['danger_zone'];

                                    ?>
                                </div>
                                <div class="px-4 py-2 bg-warning/20 text-warning" role="alert">
                                    <i class="mgc_folder_star_line me-1 text-lg align-baseline"></i> <b>
                                        <?= $total_danger ?></b> Total Stock Warning

                                </div>

                                <div class="p-3" data-simplebar style="max-height: 304px;">

                                    <?php
                                    // Define the query to include both stock and office_stock tables
                                    $query = "
    SELECT 'stock' as source, stock_type, stock_quantity, dangerzone
    FROM stock
    WHERE stock_quantity < dangerzone
    UNION ALL
    SELECT 'office_stock' as source, stock_type, stock_quantity, dangerzone
    FROM office_stock
    WHERE stock_quantity < dangerzone
";

                                    // Execute the query
                                    $result = mysqli_query($con, $query);

                                    // Check if there are any records
                                    if ($result->num_rows > 0) {
                                        $int = 1; // Initialize counter or any other variable if needed

                                        // Loop through the results
                                        while ($row = $result->fetch_assoc()) {
                                            $source = $row['source']; // Indicates which table the record is from
                                            $stock = $row['stock_type'];
                                            $qty = $row['stock_quantity'];
                                            $dangerzone = $row['dangerzone'];

                                    ?>

                                            <div
                                                class="flex items-center border border-gray-200 dark:border-gray-700 rounded px-3 py-2">
                                                <div class="flex-shrink-0 me-2">
                                                    <div
                                                        class="w-12 h-12 flex justify-center items-center rounded-full text-warning bg-warning/25">
                                                        <i class="mgc_tag_fill text-xl"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow">
                                                    <h5 class="font-semibold mb-1"><?php echo $stock; ?></h5>
                                                    <p class="text-gray-400"><?php echo $qty; ?> out of <?php echo $dangerzone; ?>
                                                    </p>
                                                </div>
                                                <div>
                                                    <a href="pages/stock/stock_managment.php"><button class="text-gray-400"
                                                            data-fc-type="tooltip" data-fc-placement="top">
                                                            <i class="mgc_information_line text-xl"></i>
                                                        </button>
                                                    </a>
                                                    <div class="bg-slate-700 hidden px-2 py-1 rounded transition-all text-white opacity-0 z-50"
                                                        role="tooltip">

                                                        Info <div class="bg-slate-700 w-2.5 h-2.5 rotate-45 -z-10 rounded-[1px]"
                                                            data-fc-arrow> </div>

                                                    </div>
                                                </div>
                                            </div>
                                    <?php $int++;
                                        }
                                    } ?>

                                </div>



                            </div>

                        <?php } ?>



                        <div class="card p-6" data-simplebar style="max-height: 304px;">


                            <div class="flex flex-row justify-between ">


                                <h4 class="card-title">Completed But Not Paid </h4>

                                <div class="card-title">
                                    <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                        data-fc-placement="left-start" type="button">
                                        <i class="mgc_more_1_fill text-xl"></i>
                                    </button>
                                    <div
                                        class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">

                                        <a href="pages/payment/payment_filter.php"
                                            class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                            href="javascript:void(0)">
                                            <i class="mgc_info_circle_line"></i> Info
                                        </a>

                                    </div>
                                </div>

                            </div>

                            <?php
                            $sql = "SELECT * FROM payment WHERE status = 'complete' AND remained > 0 and '$from_date' <= DATE(`date`) and DATE(`date`) <= '$to_date';";
                            $result = mysqli_query($con, $sql);

                            if ($result->num_rows > 0) {

                                while ($row = $result->fetch_assoc()) {

                                    $client = $row['client'];
                                    $job = $row['job_description'];
                                    $advance = $row['advance'];
                                    $remained = $row['remained'];
                                    $total = $row['total'];
                                    $status = $row['status'];

                                    $int = 1;
                            ?>
                                    <div class="my-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <h5 class="text-base font-semibold"><?php echo $client ?></h5>
                                            <h5 class="text-gray-600 dark:text-gray-300"><b><?php echo $remained ?></b>
                                                <span class="text-gray-500 dark:text-gray-400 text-sm">of</span>
                                                <b><?php echo $total ?></b>
                                            </h5>
                                        </div>


                                        <div class="flex w-full h-1 bg-gray-200 rounded-full overflow-hidden dark:bg-gray-700 ">
                                            <div class="flex flex-col justify-center overflow-hidden bg-primary w-1/2"
                                                role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>

                            <?php }
                            } ?>


                        </div>
                    </div>
                </div> <!-- Grid End -->

                <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6 mb-6">
                    <div class="col-span-1">
                        <?php

                        $query1 = 'SELECT COUNT(*) AS count FROM payment WHERE STATUS = "start"';
                        $query2 = 'SELECT COUNT(*) AS count2 FROM payment WHERE STATUS = "complete"';
                        $query3 = 'SELECT COUNT(*) AS count3 FROM payment WHERE STATUS = "delivered"';
                        $query4 = 'SELECT COUNT(*) AS count4 FROM payment WHERE STATUS = "progress"';
                        $query5 = 'SELECT COUNT(*) AS count5 FROM payment';

                        $result1 = mysqli_query($con, $query1);
                        $result2 = mysqli_query($con, $query2);
                        $result3 = mysqli_query($con, $query3);
                        $result4 = mysqli_query($con, $query4);
                        $result5 = mysqli_query($con, $query5);

                        $row1 = mysqli_fetch_assoc($result1);
                        $row2 = mysqli_fetch_assoc($result2);
                        $row3 = mysqli_fetch_assoc($result3);
                        $row4 = mysqli_fetch_assoc($result4);
                        $row5 = mysqli_fetch_assoc($result5);

                        $count = $row1['count'];
                        $count2 = $row2['count2'];
                        $count3 = $row3['count3'];
                        $count4 = $row4['count4'];
                        $count5 = $row5['count5'];

                        ?>
                        <div class="card">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div
                                            class="w-12 h-12 flex justify-center items-center rounded text-primary bg-primary/25">
                                            <i class="mgc_document_2_line text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow">
                                        <h5 class="mb-1">Not Started Projects</h5>
                                        <p><?php echo $count; ?></p>
                                    </div>


                                    <div>
                                        <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                            data-fc-placement="left-start" type="button">
                                            <i class="mgc_more_2_fill text-xl"></i>
                                        </button>
                                        <div
                                            class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">
                                            <a href="pages/job_status.php "
                                                class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                                href="javascript:void(0)">
                                                <i class="mgc_info_circle_line"></i> Info
                                            </a>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="card">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div
                                            class="w-12 h-12 flex justify-center items-center rounded text-success bg-success/25">

                                            <i class="mgc_compass_line text-xl"></i>

                                        </div>
                                    </div>
                                    <div class="flex-grow">
                                        <h5 class="mb-1">In Progress Projects</h5>
                                        <p><?php echo $count4; ?></p>
                                    </div>
                                    <div>
                                        <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                            data-fc-placement="left-start" type="button">
                                            <i class="mgc_more_2_fill text-xl"></i>
                                        </button>
                                        <div
                                            class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">
                                            <a href="pages/job_status.php "
                                                class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                                href="javascript:void(0)">
                                                <i class="mgc_info_circle_line"></i> Info
                                            </a>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="card">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div
                                            class="w-12 h-12 flex justify-center items-center rounded-full text-info bg-info/25">
                                            <i class="mgc_check_circle_line text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow">
                                        <h5 class="mb-1">Completed Projects</h5>
                                        <p><?php echo $count3; ?></p>
                                    </div>
                                    <div>
                                        <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                            data-fc-placement="left-start" type="button">
                                            <i class="mgc_more_2_fill text-xl"></i>
                                        </button>
                                        <div
                                            class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">
                                            <a href="pages/job_status.php "
                                                class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                                href="javascript:void(0)">
                                                <i class="mgc_info_circle_line"></i> Info
                                            </a>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <div class="card">
                            <div class="p-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div
                                            class="w-12 h-12 flex justify-center items-center roundedtext-success bg-success/25">
                                            <i class="mgc_send_line text-xl"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow">
                                        <h5 class="mb-1">Delivered Projects</h5>
                                        <p><?php echo $count4; ?></p>
                                    </div>
                                    <div>
                                        <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                            data-fc-placement="left-start" type="button">
                                            <i class="mgc_more_2_fill text-xl"></i>
                                        </button>
                                        <div
                                            class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">
                                            <a href="pages/job_status.php "
                                                class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                                href="javascript:void(0)">
                                                <i class="mgc_info_circle_line"></i> Info
                                            </a>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Grid End -->

                <div class="grid 2xl:grid-cols-4 md:grid-cols-2 gap-6">
                    <div class="2xl:col-span-2 md:col-span-2">
                        <div class="card">
                            <div class="p-6">
                                <div class="flex justify-space-evenly items-center">
                                    <h4 class="card-title  px-10">Unverfied Bank Statements</h4>


                                    <div class="flex flex-row gap-2 ">
                                        <form method="POST">
                                            <div class="flex">

                                                <select id="date-range" class="form-select flex" name="daterange">
                                                    <option value="1 Month">1 Month</option>
                                                    <option value="3 Month">3 Months</option>
                                                    <option value="6 Month">6 Months</option>
                                                    <option value="1 Year">1 Year</option>
                                                </select>
                                            </div>


                                    </div>
                                    <button type="submit" class=" mx-4 btn bg-primary text-white ">Go</button>


                                </div>

                                </form>

                                <div class="grid md:grid-cols-2 items-center gap-4">
                                    <div class="md:order-1 order-2">
                                        <div class="flex flex-col gap-6">


                                            <?php
                                            $defaultStartDate = date("Y-m-d", strtotime("-8 months"));

                                            if (isset($_POST['daterange'])) {
                                                $daterange = $_POST['daterange'];

                                                if ($daterange == '1 Month') {
                                                    $defaultStartDate = date("Y-m-d", strtotime("-1 months"));
                                                } elseif ($daterange == '3 Month') {
                                                    $defaultStartDate = date("Y-m-d", strtotime("-3 months"));
                                                } elseif ($daterange == '6 Month') {
                                                    $defaultStartDate = date("Y-m-d", strtotime("-6 months"));
                                                } elseif ($daterange == '1 Year') {
                                                    $defaultStartDate = date("Y-m-d", strtotime("-1 years"));
                                                } else {
                                                    $defaultStartDate = date("Y-m-d", strtotime("-3 months"));
                                                }
                                            }
                                            $sql = "SELECT
      p1.client,
      p1.remained,
      p2.total_projects
    FROM (
      SELECT
        client,
        SUM(CASE WHEN status = 'complete' AND remained > 0 THEN remained ELSE 0 END) AS remained
      FROM payment
      WHERE status = 'complete'
      AND remained > 0
      AND DATE(`date`) >= '$defaultStartDate'
      GROUP BY client
      LIMIT 12
    ) AS p1
    JOIN (
      SELECT
        client,
        COUNT(*) AS total_projects
      FROM payment
      GROUP BY client
    ) AS p2
    ON p1.client = p2.client ";
                                            $result = mysqli_query($con, $sql);
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    $name = $row['client'];
                                                    $unverified_count = $row['remained'];
                                                    $verified_count = $row['remained'];
                                                    $total_amount = $row['total_projects'];







                                            ?>
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0">
                                                            <i
                                                                class="mgc_round_fill h-10 w-10 flex justify-center items-center rounded-full bg-primary/25 text-lg text-primary"></i>
                                                        </div>
                                                        <div class="flex-grow ms-3">

                                                            <h5 class="fw-semibold mb-1"><?php echo $name ?></h5>
                                                            <ul class="flex items-center gap-2">
                                                                <li class="list-inline-item"><b><?php echo $unverified_count ?>
                                                                        Birr</b></li>
                                                                <li class="list-inline-item">
                                                                    <div class="w-1 h-1 rounded bg-gray-400"></div>
                                                                </li>
                                                                <li class="list-inline-item"><b> <?php echo $total_amount ?>
                                                                        Projects</b> </li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                            <?php $int++;
                                                }
                                            }

                                            ?>






                                        </div>
                                    </div>

                                    <div class="md:order-2 order-1">
                                        <div id="project-overview-chart" class="apex-charts"
                                            data-colors="#3073F1,#ff679b,#0acf97,#ffbc00"></div>


                                        <?php
                                        $defaultStartDate = date("Y-m-d", strtotime("-8 months"));

                                        if (isset($_POST['daterange'])) {
                                            $daterange = $_POST['daterange'];

                                            if ($daterange == '1 Month') {
                                                $defaultStartDate = date("Y-m-d", strtotime("-1 months"));
                                            } elseif ($daterange == '3 Month') {
                                                $defaultStartDate = date("Y-m-d", strtotime("-3 months"));
                                            } elseif ($daterange == '6 Month') {
                                                $defaultStartDate = date("Y-m-d", strtotime("-6 months"));
                                            } elseif ($daterange == '1 Year') {
                                                $defaultStartDate = date("Y-m-d", strtotime("-1 years"));
                                            } else {
                                                $defaultStartDate = date("Y-m-d", strtotime("-3 months"));
                                            }
                                        }
                                        $sql = "SELECT
p1.client,
p1.remained,
p2.total_projects
FROM (
SELECT
  client,
  SUM(CASE WHEN status = 'complete' AND remained > 0 THEN remained ELSE 0 END) AS remained
FROM payment
WHERE status = 'complete'
AND remained > 0
AND DATE(`date`) >= '$defaultStartDate'
GROUP BY client
LIMIT 12
) AS p1
JOIN (
SELECT
  client,
  COUNT(*) AS total_projects
FROM payment
GROUP BY client
) AS p2
ON p1.client = p2.client";
                                        $result = mysqli_query($con, $sql);
                                        $bankData = array();

                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $bankData[] = array(
                                                    'name' => $row['client'],
                                                    'unverified_count' => $row['remained']
                                                );
                                            }
                                        }
                                        ?>

                                        <script>
                                            var colors = ["#3073F1", "#ff679b", "#0acf97", "#ffbc00"];
                                            var dataColors = document.querySelector("#project-overview-chart").dataset
                                                .colors;
                                            if (dataColors) {
                                                colors = dataColors.split(",");
                                            }

                                            // Extract the bank names and their corresponding unverified counts
                                            var bankNames = <?php echo json_encode(array_column($bankData, 'name')); ?>;
                                            var unverifiedCounts =
                                                <?php echo json_encode(array_column($bankData, 'unverified_count')); ?>;

                                            var options = {
                                                chart: {
                                                    height: 350,
                                                    type: 'radialBar'
                                                },
                                                colors: colors,
                                                series: unverifiedCounts,
                                                labels: bankNames,
                                                plotOptions: {
                                                    radialBar: {
                                                        track: {
                                                            margin: 5,
                                                        },
                                                        dataLabels: {
                                                            value: {
                                                                show: true,
                                                                fontSize: '20px',
                                                                fontWeight: 600,
                                                                formatter: function(val) {
                                                                    return parseInt(
                                                                        val); // Format the value as an integer
                                                                }
                                                            },
                                                        }
                                                    }
                                                }
                                            };

                                            var chart = new ApexCharts(
                                                document.querySelector("#project-overview-chart"),
                                                options
                                            );

                                            chart.render();
                                        </script>





                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1">
                        <?php
                        $result = mysqli_query($con, "SELECT count(*) AS total, SUM(remained) as total_remaining FROM payment WHERE remained != 0");
                        $row = mysqli_fetch_array($result);
                        $total =  $row['total'];
                        $total_remained = number_format($row['total_remaining'], 2);
                        ?>
                        <div class="card">
                            <div class="card-header">
                                <div class="flex justify-between items-center">
                                    <h4 class="card-title"><span class="font-bold"><?= $total ?></span> Unpaid Bills
                                    </h4>
                                    <div class="text-warning"><?= $total_remained ?> ETB</div>


                                    <div>
                                        <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                            data-fc-placement="left-start" type="button">
                                            <i class="mgc_more_2_fill text-xl"></i>
                                        </button>
                                        <div
                                            class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">
                                            <a href="pages/payment/record2.php"
                                                class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                                href="javascript:void(0)">
                                                <i class="mgc_info_circle_line"></i> Info
                                            </a>

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="py-6">
                                <div class="px-6" data-simplebar style="max-height: 304px;">
                                    <div class="space-y-3">
                                        <?php
                                        $result = mysqli_query($con, "SELECT * FROM payment WHERE remained != 0 ORDER BY remained DESC");
                                        if ($result->num_rows > 0) {
                                            $int = 1;
                                            while ($row = $result->fetch_assoc()) {

                                                $client = $row['client'];
                                                $job = $row['job_description'];
                                                $remained = number_format($row['remained'], 2);
                                                $total = number_format($row['total'], 2);

                                        ?>

                                                <div
                                                    class="flex items-center w-full border border-gray-200 dark:border-gray-700 rounded p-2">
                                                    <p class="me-3 text-lg text-gray-300 dark:text-gray-500"><?= $int ?></p>
                                                    <div
                                                        class="w-full overflow-hidden border-l border-gray-200 dark:border-gray-700 ps-3">
                                                        <div class="flex justify-between align-center">
                                                            <h5
                                                                class="font-semibold uppercase truncate text-gray-600 dark:text-gray-400">
                                                                <?php echo $client ?></a>
                                                            </h5>
                                                            <div class="font-semibold"><?= $remained ?></div>
                                                        </div>
                                                        <p class="text-xs truncate"><?php echo $job ?></p>
                                                    </div>
                                                </div>

                                        <?php $int++;
                                            }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-1">

                        <div class="card">
                            <div class="card-header">
                                <div class="flex justify-between items-center">
                                    <h4 class="card-title"><span class="font-bold"> Unfinished Projects
                                    </h4>
                                    <div>
                                        <button class="text-gray-600 dark:text-gray-400" data-fc-type="dropdown"
                                            data-fc-placement="left-start" type="button">
                                            <i class="mgc_more_2_fill text-xl"></i>
                                        </button>
                                        <div
                                            class="hidden fc-dropdown fc-dropdown-open:opacity-100 opacity-0 w-36 z-50 mt-2 transition-[margin,opacity] duration-300 bg-white dark:bg-gray-800 shadow-lg border border-gray-200 dark:border-gray-700 rounded-lg p-2">
                                            <a href="pages/job_status.php"
                                                class="flex items-center gap-1.5 py-1.5 px-3.5 rounded text-sm transition-all duration-300 bg-transparent text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                                                href="javascript:void(0)">
                                                <i class="mgc_info_circle_line"></i> Info
                                            </a>

                                        </div>
                                    </div>


                                </div>
                            </div>


                            <div class="py-6">
                                <div class="px-6" data-simplebar style="max-height: 304px;">
                                    <div class="space-y-3">
                                        <?php
                                        $sql = "SELECT * FROM payment WHERE (status = 'progress' OR status= 'start') AND DATE(`date`) >= '$from_date' AND DATE(`date`) <= '$to_date' ;";
                                        $result = mysqli_query($con, $sql);
                                        $int = 1;
                                        if ($result->num_rows > 0) {


                                            while ($row = $result->fetch_assoc()) {

                                                $client = $row['client'];
                                                $job = $row['job_description'];
                                                $advance = $row['advance'];
                                                $remained = $row['remained'];
                                                $total = $row['total'];
                                                $status = $row['status'];


                                        ?>

                                                <div
                                                    class="flex items-center w-full border border-gray-200 dark:border-gray-700 rounded p-2">
                                                    <p class="me-3 text-lg text-gray-300 dark:text-gray-500"><?= $int ?></p>
                                                    <div
                                                        class="w-full overflow-hidden border-l border-gray-200 dark:border-gray-700 ps-3">
                                                        <div class="flex justify-between align-center">
                                                            <h5
                                                                class="font-semibold uppercase truncate text-gray-600 dark:text-gray-400">
                                                                <?php echo $client ?></a>
                                                            </h5>
                                                            <div class="font-semibold"><?= $status ?></div>
                                                        </div>
                                                        <p class="text-xs truncate"><?php echo $job ?></p>
                                                    </div>
                                                </div>

                                        <?php $int++;
                                            }
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div> <!-- Grid End -->

            </main>

            <?php include 'partials/footer.php'; ?>

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>


    <?php include 'partials/customizer.php'; ?>

    <?php include 'partials/footer-scripts.php'; ?>

    <!-- Apexcharts js -->


    <!-- Dashboard Project Page js -->
    <script src="assets/js/pages/dashboard.js"></script>

</body>

</html>