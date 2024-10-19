<!-- Topbar Start -->
<header class="app-header flex items-center px-4 gap-3">
    <!-- Sidenav Menu Toggle Button -->
    <button id="button-toggle-menu" class="nav-link p-2">
        <span class="sr-only">Menu Toggle Button</span>
        <span class="flex items-center justify-center h-6 w-6">
            <i class="mgc_menu_line text-xl"></i>
        </span>
    </button>

    

    <!-- Topbar Brand Logo -->
    <a href="index.php" class="logo-box">
        <!-- Light Brand Logo -->
        <div class="logo-light">
            <img src="<?php echo $redirect_link ?>assets/images/yunasbrand.jpg" class="logo-lg h-6" alt="Light logo">
            <img src="<?php echo $redirect_link ?>assets/images/yunasbrand.jpg" class="logo-sm" alt="Small logo">
        </div>


        <!-- Dark Brand Logo -->
        <div class="logo-dark">
            <img src="<?php echo $redirect_link ?>assets/images/yunasbrand.jpg" class="logo-lg h-6" alt="Dark logo">
            <img src="<?php echo $redirect_link ?>assets/images/yunasbrand.jpg" class="logo-sm" alt="Small logo">
        </div>
    </a>

    <div class="me-auto"></div>
    <div class="md:flex hidden">
       



    </div>




    <!-- <div class="relative md:flex hidden">
        <a class="nav-link" href="<?= $redirect_link ?>pages/deliver.php">
            <button type="button" class="btn bg-warning text-white rounded-full">
                <i class="mgc_pdf_line text-base me-2"></i>
                Deliver Form
            </button>
        </a>
    </div>
    <div class="relative md:flex hidden">
        <a class="nav-link" href="<?= $redirect_link ?>pages/remainder.php">
            <button type="button" class="btn bg-info text-white rounded-full">
                <i class="mgc_pdf_line text-base me-2"></i>
                Remainder
            </button>
        </a>
    </div>
    <div class="relative md:flex hidden">
        <a class="nav-link" href="<?= $redirect_link ?>pages/generate.php">
            <button type="button" class="btn bg-success text-white rounded-full">
                <i class="mgc_pdf_line text-base me-2"></i>
                Generate
            </button>
        </a>
    </div> -->

    <!-- Light/Dark Toggle Button -->
    <div class="flex">
        <button id="light-dark-mode" type="button" class="nav-link p-2">
            <span class="sr-only">Light/Dark Mode</span>
            <span class="flex items-center justify-center h-6 w-6">
                <i class="mgc_moon_line text-2xl"></i>
            </span>
        </button>
    </div>

    <!-- Profile Dropdown Button -->
    <div class="relative">
        <button data-fc-type="dropdown" data-fc-placement="bottom-end" type="button" class="nav-link">
            <img src="<?php echo $redirect_link ?>assets/images/users/1.jpg" alt="user-image" class="rounded-full h-10">
        </button>
        <div class="fc-dropdown fc-dropdown-open:opacity-100 hidden opacity-0 w-44 z-50 transition-[margin,opacity] duration-300 mt-2 bg-white shadow-lg border rounded-lg p-2 border-gray-200 dark:border-gray-700 dark:bg-gray-800">
            <a class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="<?= $redirect_link . 'pages/account/profile.php' ?>">
                <i class="mgc_user_3_line me-2"></i>
                <span>My Profile</span>
            </a>
            <a class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="<?= $redirect_link . 'pages/account/before.php' ?>">
                <i class="mgc_settings_4_line me-2"></i>
                <span>Users Setting</span>
            </a>
            <a class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="<?= $redirect_link . 'login.php' ?>">
                <i class="mgc_lock_line  me-2"></i>
                <span>Lock Screen</span>
            </a>
            <hr class="my-2 -mx-2 border-gray-200 dark:border-gray-700">
            <a class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="<?php echo $redirect_link ?>logout.php">
                <i class="mgc_exit_line  me-2"></i>
                <span>Log Out</span>
            </a>
        </div>
    </div>
</header>
<!-- Topbar End -->


