<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $title ?></title>

    <!-- Custom fonts for this template-->
    <link href="/View/layout/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="/View/layout/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <?php if (isset($_SESSION['admin']) && strpos($_SERVER['REQUEST_URI'], "/admin") === 0): ?>
                    <div class="sidebar-brand-text mx-3">Admin</div>
                <?php endif ?>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="
                    <?php if(isset($_SESSION['admin']) && strpos($_SERVER['REQUEST_URI'], "/admin") === 0): ?>
                        /admin/home
                    <?php else: ?>
                        /
                    <?php endif ?>
                ">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Addons
            </div>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="
                    <?php if (isset($_SESSION['admin']) && strpos($_SERVER['REQUEST_URI'], "/admin") === 0): ?>
                        /admin/faq
                    <?php else: ?>
                        /faq
                    <?php endif ?>
                ">
                    <i class="fas fa-fw fa-question"></i>
                    <span>
                        <?php if (isset($_SESSION['admin']) && strpos($_SERVER['REQUEST_URI'], "/admin") === 0): ?>
                            Manage Faq
                        <?php else: ?>
                            Faq
                        <?php endif ?>
                    </span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="
                    <?php if(isset($_SESSION['admin']) && strpos($_SERVER['REQUEST_URI'], "/admin") === 0): ?>
                        /admin/contact
                    <?php else: ?>
                        /contact
                    <?php endif ?>
                ">
                    <i class="fas fa-fw fa-envelope"></i>
                    <span>
                        <?php if (isset($_SESSION['admin']) && strpos($_SERVER['REQUEST_URI'], "/admin") === 0): ?>
                            Manage Contact
                        <?php else: ?>
                            Contact
                        <?php endif ?>
                    </span></a>
            </li>

            <?php if (isset($_SESSION['admin']) && strpos($_SERVER['REQUEST_URI'], "/admin") === 0): ?>
                <li class="nav-item">
                    <a href="/admin/messages" class="nav-link">
                        <i class="fas fa-fw fa-comments"></i>
                        <span>Messages</span></a>
                    </a>
                </li>
            <?php endif ?>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <?php if (isset($_SESSION['admin'])): ?>
                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                    <img class="img-profile rounded-circle" src="/View/layout/img/undraw_profile.svg">
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="/admin/reset-password">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Reset Password
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/admin/logout">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                </div>
                            </li>
                        <?php endif ?>
                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">