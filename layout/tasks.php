<?php
session_start();
require_once 'db.php';
// التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$current_user_id = $_SESSION['user_id'];
$current_role = $_SESSION['role'] ?? 'Member';

if ($current_role === 'Admin') {
    // الأدمن: كل التاسكات
    $sql = "
        SELECT t.*, 
               p.name as project_name, 
               u.username as assigned_username,
               1 as user_can_edit -- الأدمن دايماً معاه صلاحية
        FROM tasks t
        JOIN projects p ON t.project_id = p.id
        LEFT JOIN users u ON t.assigned_user_id = u.id
        ORDER BY t.created_at DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
} else {
    // الميمبر: فقط تاسكات المشاريع المشترك فيها
    $sql = "
        SELECT t.*, 
               p.name as project_name, 
               u.username as assigned_username,
               pu.can_edit as user_can_edit
        FROM tasks t
        JOIN projects p ON t.project_id = p.id
        -- هنا بنعمل JOIN عشان نتأكد إن المستخدم عضو في المشروع ده
        JOIN project_users pu ON t.project_id = pu.project_id AND pu.user_id = ?
        LEFT JOIN users u ON t.assigned_user_id = u.id
        ORDER BY t.created_at DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$current_user_id]);
}

$tasks = $stmt->fetchAll();

// استقبال كلمة البحث
$search = $_GET['search'] ?? '';

// جملة الـ SQL (سواء للأدمن أو الميمبر) هنزود عليها شرط
$sql = "SELECT t.* ... (باقي الكود زي ما هو) ... WHERE 1=1";

// لو فيه بحث، زود الشرط ده
if ($search) {
    $sql .= " AND t.title LIKE :search";
}

$stmt = $pdo->prepare($sql);

// لو فيه بحث، ابعت القيمة
if ($search) {
    $stmt->bindValue(':search', "%$search%");
}

// كمل تنفيذ الـ execute عادي...
?>

<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>عرض المهام</title>
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
                <h1>قائمة المهام</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">جميع المهام</h3>
                        <div class="card-tools">
                            <a href="tasks_create.php" class="btn btn-sm btn-success">
                                <i class="fas fa-plus"></i> إضافة مهمة جديد
                            </a>
                            <br>
                            <br>
                            <div class="card-tools">
                                <form method="GET" class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="search" class="form-control float-right" placeholder="بحث عن مهمة..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th>العنوان</th>
                                <th>المشروع</th>
                                <th>الحالة</th>
                                <th>الأولوية</th>
                                <th>تاريخ التسليم</th>
                                <th>المعين</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($tasks)): ?>
                                <tr><td colspan="6" class="text-center">لا توجد مهام</td></tr>
                            <?php else: ?>
                                <?php foreach ($tasks as $task): ?>

                                    <?php
                                    // شرط عرض الأزرار: لو هو أدمن، أو لو واخد صلاحية تعديل في المشروع ده
                                    $can_modify = ($current_role === 'Admin' || $task['user_can_edit'] == 1);
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($task['title']); ?></td>
                                        <td><?php echo htmlspecialchars($task['project_name'] ?? '-'); ?></td>
                                        <td><span class="badge badge-<?php echo $task['status'] == 'Done' ? 'success' : ($task['status'] == 'In Progress' ? 'warning' : 'info'); ?>">
                                                <?php echo htmlspecialchars($task['status']); ?>
                                            </span></td>
                                        <td><span class="badge badge-<?php echo $task['priority'] == 'High' ? 'danger' : ($task['priority'] == 'Medium' ? 'warning' : 'secondary'); ?>">
                                                <?php echo htmlspecialchars($task['priority']); ?>
                                            </span></td>
                                        <td><?php echo $task['due_date'] ? htmlspecialchars($task['due_date']) : '-'; ?></td>
                                        <td><?php echo htmlspecialchars($task['assigned_username'] ?? 'غير معين'); ?></td>
                                        <td>
                                            <a href="tasks_status.php?id=<?php echo $task['id']; ?> ?>" class="btn btn-sm btn-info">
                                                <i class="fas fa-sync"></i> الحالة
                                            </a>

                                            <a href="tasks_edit.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> تعديل
                                            </a>

                                            <a href="tasks_delete.php?id=<?php echo $task['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('متأكد من حذف هذه المهمة؟');">
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
