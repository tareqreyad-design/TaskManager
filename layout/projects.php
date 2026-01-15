<?php
session_start();

// حماية الصفحة (كل المستخدمين يشوفوا، لكن الأدمن يقدر يعدل/يحذف)
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'db.php';

// جيب كل المشاريع
$stmt = $pdo->prepare("
    SELECT p.*, COUNT(pu.user_id) as user_count
    FROM projects p
    LEFT JOIN project_users pu ON p.id = pu.project_id
    GROUP BY p.id
    ORDER BY p.created_at DESC
");
$stmt->execute();
$projects = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>عرض المشاريع</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../css/adminlte.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <?php include 'nav.php'; ?>
    <?php include 'aside.php'; ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>قائمة المشاريع</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="dashbord.php">الرئيسية</a></li>
                            <li class="breadcrumb-item active">المشاريع</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">جميع المشاريع</h3>
                        <div class="card-tools">
                            <a href="projects_create.php" class="btn btn-sm btn-success">
                                <i class="fas fa-plus"></i> إضافة مشروع جديد
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>اسم المشروع</th>
                                <th>الوصف</th>
                                <th>عدد المستخدمين</th>
                                <th>تاريخ الإنشاء</th>
                                <th>إجراءات</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($projects)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-folder-open fa-3x"></i><br>
                                        لا توجد مشاريع حاليًا
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($projects as $project): ?>
                                    <tr>
                                        <td><?php echo $project['id']; ?></td>
                                        <td><?php echo htmlspecialchars($project['name']); ?></td>
                                        <td><?php echo htmlspecialchars($project['description'] ?: 'لا يوجد وصف'); ?></td>
                                        <td><span class="badge badge-info"><?php echo $project['user_count']; ?></span></td>
                                        <td><?php echo date('Y-m-d', strtotime($project['created_at'])); ?></td>
                                        <td>
                                            <a href="projects_edit.php?id=<?php echo $project['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> تعديل
                                            </a>
                                            <a href="projects_delete.php?id=<?php echo $project['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('متأكد من حذف المشروع؟');">
                                                <i class="fas fa-trash"></i> حذف
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'footer.php'; ?>
</div>
<script src="../js/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../js/bootstrap.bundle.js"></script>
<!-- AdminLTE App -->
<script src="../js/adminlte.js"></script>

</body>
</html>