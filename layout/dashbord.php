
<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'Member';

try {
    // 1. إحصائيات المهام (عدد المهام لكل حالة)
    // لو أدمن بيشوف كل حاجة، لو ميمبر بيشوف الإحصائيات بتاعته هو بس (أو ممكن تخليه يشوف ككل حسب رغبتك)
    // هنا هنعملها عامة للمشروع ككل عشان الداشبورد يبقى شكلها غني
    $stmt = $pdo->query("SELECT status, COUNT(*) as count FROM tasks GROUP BY status");
    $stats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // ['To Do' => 5, 'Done' => 3]
    // تجهيز الأرقام (عشان لو الحالة مش موجودة نكتب 0)
    $todo_count = $stats['To Do'] ?? 0;
    $progress_count = $stats['In Progress'] ?? 0;
    $done_count = $stats['Done'] ?? 0;

    // 2. المهام المتأخرة (تاريخها عدى ومش مكتملة)
    // بنجيب اسم التاسك واسم المشروع
    $overdueStmt = $pdo->prepare("
        SELECT t.title, t.due_date, p.name as project_name
        FROM tasks t
        JOIN projects p ON t.project_id = p.id
        WHERE t.due_date < CURDATE() AND t.status != 'Done'
        ORDER BY t.due_date ASC
        LIMIT 5
    ");
    $stmt->execute();
    $overdueTasks = $stmt->fetchAll();

    // 3. مهامي الحالية (Assigned to currently logged-in user)
    $myTasksStmt = $pdo->prepare("
        SELECT t.title, t.status, t.priority, p.name as project_name
        FROM tasks t
        JOIN projects p ON t.project_id = p.id
        WHERE t.assigned_user_id = ? AND t.status != 'Done'
        ORDER BY t.priority DESC, t.due_date ASC
        LIMIT 5
    ");

    // 3. مهامي الحالية (Assigned to currently logged-in user)
    $myTasksStmt = $pdo->prepare("
        SELECT t.title, t.status, t.priority, p.name as project_name
        FROM tasks t
        JOIN projects p ON t.project_id = p.id
        WHERE t.assigned_user_id = ? AND t.status != 'Done'
        ORDER BY t.priority DESC, t.due_date ASC
        LIMIT 5
    ");
    $myTasksStmt->execute([$user_id]);
    $myTasks = $myTasksStmt->fetchAll();

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// جلب تقدم المشاريع (نسبة الإنجاز)
$progStmt = $pdo->prepare("
    SELECT p.name, 
           COUNT(t.id) as total_tasks,
           SUM(CASE WHEN t.status = 'Done' THEN 1 ELSE 0 END) as done_tasks
    FROM projects p
    LEFT JOIN tasks t ON p.id = t.project_id
    GROUP BY p.id
    HAVING total_tasks > 0
    LIMIT 5
");
$progStmt->execute();
$projectProgress = $progStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> DashBord</title>

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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var ctx = document.getElementById('taskChart').getContext('2d');
    var donutData = {
        labels: [
            'مطلوبة (To Do)',
            'قيد التنفيذ (In Progress)',
            'مكتملة (Done)'
        ],
        datasets: [
            {
                data: [<?php echo $todo_count; ?>, <?php echo $progress_count; ?>, <?php echo $done_count; ?>],
                backgroundColor : ['#17a2b8', '#ffc107', '#28a745'],
            }
        ]
    }
    var pieChart = new Chart(ctx, {
        type: 'doughnut', // ممكن تخليها 'pie' لو عايزها دائرة مقفولة
        data: donutData,
        options: {
            maintainAspectRatio : false,
            responsive : true,
        }
    })
</script>

  </body>
  </html>


