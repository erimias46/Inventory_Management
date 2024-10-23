<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');
?>

<head>
    <?php
    $title = 'Main Dashboard';
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
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 md:gap-6">
                    <div class="card">
                        <div class="flex overflow-hidden flex-wrap w-full relative bg-dark-100 hover:shadow-lg shadow-sm rounded-xl dark:bg-gray-800 dark:shadow-slate-700/[.7]">
                            <div class="text-center relative w-full">
                                <div class="p-6">
                                    <h3 class="text-4xl drop-shadow-lg font-bold font-semibold mb-3">
                                        Add Product
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="add/add_product.php">
                                        Go To Product
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ms-2" viewBox="0 0 24 24">
                                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="flex overflow-hidden flex-wrap w-full relative bg-dark-100 hover:shadow-lg shadow-sm rounded-xl dark:bg-gray-800 dark:shadow-slate-700/[.7]">
                            <div class="text-center relative w-full">
                                <div class="p-6">
                                    <h3 class="text-4xl drop-shadow-lg font-bold font-semibold mb-3">
                                        Sale
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="sale.php">
                                        Go To Sale
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ms-2" viewBox="0 0 24 24">
                                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="flex overflow-hidden flex-wrap w-full relative bg-dark-100 hover:shadow-lg shadow-sm rounded-xl dark:bg-gray-800 dark:shadow-slate-700/[.7]">
                            <div class="text-center relative w-full">
                                <div class="p-6">
                                    <h3 class="text-4xl drop-shadow-lg font-bold font-semibold mb-3">
                                        All Sales
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="all_sales.php">
                                        Go To All Sales
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ms-2" viewBox="0 0 24 24">
                                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="flex overflow-hidden flex-wrap w-full relative bg-dark-100 hover:shadow-lg shadow-sm rounded-xl dark:bg-gray-800 dark:shadow-slate-700/[.7]">
                            <div class="text-center relative w-full">
                                <div class="p-6">
                                    <h3 class="text-4xl drop-shadow-lg font-bold font-semibold mb-3">
                                        All Sale Log
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="sale_log.hp">
                                        Sales Log
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ms-2" viewBox="0 0 24 24">
                                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="flex overflow-hidden flex-wrap w-full relative bg-dark-100 hover:shadow-lg shadow-sm rounded-xl dark:bg-gray-800 dark:shadow-slate-700/[.7]">
                            <div class="text-center relative w-full">
                                <div class="p-6">
                                    <h3 class="text-4xl drop-shadow-lg font-bold font-semibold mb-3">
                                        Search Product
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="search.php">
                                        Go To Search product
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ms-2" viewBox="0 0 24 24">
                                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="flex overflow-hidden flex-wrap w-full relative bg-dark-100 hover:shadow-lg shadow-sm rounded-xl dark:bg-gray-800 dark:shadow-slate-700/[.7]">
                            <div class="text-center relative w-full">
                                <div class="p-6">
                                    <h3 class="text-4xl drop-shadow-lg font-bold font-semibold mb-3">
                                        Multi Search 
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="search_multi.php">
                                        Go To Search product
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ms-2" viewBox="0 0 24 24">
                                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="flex overflow-hidden flex-wrap w-full relative bg-dark-100 hover:shadow-lg shadow-sm rounded-xl dark:bg-gray-800 dark:shadow-slate-700/[.7]">
                            <div class="text-center relative w-full">
                                <div class="p-6">
                                    <h3 class="text-4xl drop-shadow-lg font-bold font-semibold mb-3">
                                        Delivery
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="delivery.php">
                                        Go To Banner
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ms-2" viewBox="0 0 24 24">
                                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="flex overflow-hidden flex-wrap w-full relative bg-dark-100 hover:shadow-lg shadow-sm rounded-xl dark:bg-gray-800 dark:shadow-slate-700/[.7]">
                            <div class="text-center relative w-full">
                                <div class="p-6">
                                    <h3 class="text-4xl drop-shadow-lg font-bold font-semibold mb-3">
                                        Verify Product
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="verify_products.php">
                                        Verify Product
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ms-2" viewBox="0 0 24 24">
                                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="flex overflow-hidden flex-wrap w-full relative bg-dark-100 hover:shadow-lg shadow-sm rounded-xl dark:bg-gray-800 dark:shadow-slate-700/[.7]">
                            <div class="text-center relative w-full">
                                <div class="p-6">
                                    <h3 class="text-4xl drop-shadow-lg font-bold font-semibold mb-3">
                                        Multi Sale Log
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="multi_log.php">
                                        Multi Sale Log
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ms-2" viewBox="0 0 24 24">
                                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
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