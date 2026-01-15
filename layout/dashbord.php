
<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
try {
    // 1. حساب عدد المهام بناءً على الحالة
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM tasks GROUP BY status");
    $tasksByStatus = [];
    while ($row = $stmt->fetch()) {
        $tasksByStatus[$row['status']] = $row['count'];
    }

    // 2. المهام المتأخرة (التاريخ أصغر من النهاردة ومش Done)
    $stmt = $pdo->prepare("
        SELECT t.*, p.name as project_name 
        FROM tasks t 
        LEFT JOIN projects p ON t.project_id = p.id 
        WHERE t.due_date < CURDATE() AND t.status != 'Done'
        ORDER BY t.due_date ASC
    ");
    $stmt->execute();
    $overdueTasks = $stmt->fetchAll();

    // 3. المهام المعينة للمستخدم الحالي (مع التأكد من جلب كل العواميد)
    $stmt = $pdo->prepare("
        SELECT t.*, p.name as project_name 
        FROM tasks t 
        LEFT JOIN projects p ON t.project_id = p.id 
        WHERE t.assigned_user_id = ?
        ORDER BY t.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $assignedTasks = $stmt->fetchAll();

    // 4. عدد المشاريع الكلي (عشان الـ Content ما يضربش)
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM projects");
    $projectData = $stmt->fetch();
    $TotalProjectsCount = $projectData['total'];

} catch (PDOException $e) {
    die("Database Error " . $e->getMessage());
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
<body class="hold-transition sidebar-mini layout-fixed">

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
                            <li class="breadcrumb-item"><a href="projects.php">Project</a></li>
                            <li class="breadcrumb-item active"><a href="tasks.php">Task</a> </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->


        <section class="content">
            <div class="container-fluid">
        <!-- Main content -->
                <?php
                // جلب البيانات الحقيقية من قاعدة البيانات
                require_once 'db.php';
                // 1. عدد المهام حسب الحالة
                $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM tasks GROUP BY status");
                $results = $stmt->fetchAll();
                $tasksByStatus = ['To Do' => 0, 'In Progress' => 0, 'Done' => 0];
                foreach ($results as $row) {
                    $tasksByStatus[$row['status']] = $row['count'];
                }
                $TotalProjects = $pdo->query("SELECT id FROM projects")->fetchAll();

                // 2. المهام المتأخرة (due_date < اليوم ومش Done)
                $stmt = $pdo->prepare("
    SELECT t.*, p.name as project_name, u.username as assigned_username
    FROM tasks t
    JOIN projects p ON t.project_id = p.id
    LEFT JOIN users u ON t.assigned_user_id = u.id
    WHERE t.due_date < CURDATE() AND t.status != 'Done'
");
                $stmt->execute();
                $overdueTasks = $stmt->fetchAll();

                // 3. المهام المعينة للمستخدم الحالي
                $stmt = $pdo->prepare("
    SELECT t.*, p.name as project_name
    FROM tasks t
    JOIN projects p ON t.project_id = p.id
    WHERE t.assigned_user_id = ?
");
                $stmt->execute([$_SESSION['user_id']]);
                $assignedTasks = $stmt->fetchAll();
                ?>


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


