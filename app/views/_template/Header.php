<?php 
function base_url() {
    return 'http://localhost/MIS/';  // Adjust to your actual root URL
}?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.8/dist/sweetalert2.min.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../../public/css/employee.css">
        <link rel="stylesheet" href="../../../public/css/dashboard.css">
        <link rel="stylesheet" href="/public/css/formEmployee.css?v=<?php echo time(); ?>">
        <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>app/views/assets/images/favicon.png">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>app/views/assets/css/perfect-scrollbar.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>app/views/assets/css/style.css">
        <link defer="" rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>app/views/assets/css/animate.css">
        <script src="<?php echo base_url(); ?>app/views/assets/js/perfect-scrollbar.min.js"></script>
        <script defer="" src="<?php echo base_url(); ?>app/views/assets/js/popper.min.js"></script>
        <script defer="" src="<?php echo base_url(); ?>app/views/assets/js/tippy-bundle.umd.min.js"></script>
        <script defer="" src="<?php echo base_url(); ?>app/views/assets/js/sweetalert.min.js"></script>
        <!-- Alpine.js plugins -->
        <script src="<?php echo base_url(); ?>app/views/assets/js/alpine-collaspe.min.js"></script>
        <script src="<?php echo base_url(); ?>app/views/assets/js/alpine-persist.min.js"></script>
        <script defer="" src="<?php echo base_url(); ?>app/views/assets/js/alpine-ui.min.js"></script>
        <script defer="" src="<?php echo base_url(); ?>app/views/assets/js/alpine-focus.min.js"></script>
        <script defer="" src="<?php echo base_url(); ?>app/views/assets/js/alpine.min.js"></script>
        <script src="<?php echo base_url(); ?>app/views/assets/js/custom.js"></script>
        <script defer="" src="<?php echo base_url(); ?>app/views/assets/js/apexcharts.js"></script>
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <!-- jQuery for formEmployee.js -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>

    <body x-data="main" class="relative overflow-x-hidden font-nunito text-sm font-normal antialiased" :class="[ $store.app.sidebar ? 'toggle-sidebar' : '', $store.app.theme === 'dark' || $store.app.isDarkMode ?  'dark' : '', $store.app.menu, $store.app.layout,$store.app.rtlClass]">

        <!-- ** SETUP YOUR TEMPLATE HERE ** -->

    <!-- Sidebar menu overlay -->
    <div x-cloak="" class="fixed inset-0 z-50 bg-[black]/60 lg:hidden" :class="{'hidden' : !$store.app.sidebar}" @click="$store.app.toggleSidebar()"></div>

    <!-- Screen loader -->
    <?php include('Loader.php') ?>

    <!-- Scroll to top button -->
    <?php include('Scrolltop.php') ?>
    
    <?php include('Customizer.php') ?>

    <div class="main-container min-h-screen text-black dark:text-white-dark" :class="[$store.app.navbar]">

    <?php include('Sidebar.php'); ?>


    <div class="main-content flex min-h-screen flex-col">
    <!-- start header section -->

    <?php include('Topbar.php') ?>

    <!-- Main Page Content -->
    <div class="animate__animated p-6" :class="[$store.app.animation]">