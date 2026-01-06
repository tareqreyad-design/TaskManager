<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>starter</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../css/adminlte.css">

</head>
<body class="hold-transition sidebar-mini layout-fixed sidebar-collapse">

<div class="wrapper">
    <!-- Navbar -->
    <?php include 'nav.php'; ?>

    <!-- /.navbar -->


    <!--     Main Sidebar Container-->
    <?php include 'aside.php'; ?>

    <!--     Content Wrapper. Contains page content-->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Project</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
        <!-- Main content -->
        <?php include 'content.php'; ?>

          </div>
        </section>
    </div>
<!--    footer-->
    <?php include 'footer.php'; ?>


  </div>


<!-- jQuery -->
 <script src="../js/jquery.min.js"></script>
<!-- Bootstrap 4 -->
 <script src="../js/bootstrap.bundle.js"></script>
<!-- AdminLTE App -->
 <script src="../js/adminlte.js"></script>
<!---->
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
<!---->
<!--<!-- Bootstrap Bundle (فيه Popper) -->-->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>-->
<!---->
<!--<!-- AdminLTE JS (أخيرًا) -->-->
<!-- <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>-->

  </body>
  </html>


