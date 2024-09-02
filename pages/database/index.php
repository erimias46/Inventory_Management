<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');
?>

<head>
    <?php
    $title = 'Database';
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
                                        Brochure
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="database.php?type=brocher">
                                        Go To Brochure
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
                                        Book/Magazine
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="database.php?type=book">
                                        Go To Book
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
                                        Bag
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="database.php?type=bag">
                                        Go To Bag
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
                                        Manual
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="database.php?type=digital">
                                        Go To Manual
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
                                        Digital
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="database.php?type=otherdigital">
                                        Go To Digital
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
                                        Banner
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="database.php?type=banner">
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
                                        Banner out Source
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="database.php?type=banner_out">
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
                                        Design
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="database.php?type=design">
                                        Go To Design
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
                                        Single Page Digital
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="database.php?type=single_page">
                                        Go To Design
                                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ms-2" viewBox="0 0 24 24">
                                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="flex overflow-hidden flex-wrap w-full relative  bg-dark-100 hover:shadow-lg shadow-sm rounded-xl    dark:bg-gray-800 dark:shadow-slate-600/[.7]">
                            <div class="text-center relative w-full">
                                <div class="p-6">
                                    <h3 class="text-4xl drop-shadow-lg font-bold font-semibold mb-3">
                                        Multi Page Digital
                                    </h3>
                                    <a class="btn btn-sm border-info text-info hover:bg-info hover:text-white" href="database.php?type=multi_page">
                                        Go To Design
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