<?php
session_start();

// حماية: أدمن فقط
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}

require_once 'db.php';

// جيب كل المستخدمين
$stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>عرض المستخدمين</title>
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
                <h1>إدارة المستخدمين</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">قائمة المستخدمين</h3>
                        <div class="card-tools">
                            <a href="users_create.php" class="btn btn-sm btn-success">
                                <i class="fas fa-plus"></i> إضافة مسنخدم جديد
                            </a>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>اسم المستخدم</th>
                                <th>الإيميل</th>
                                <th>الدور</th>
                                <th>الحالة</th>
                                <th>إجراءات</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($users)): ?>
                                <tr><td colspan="6" class="text-center">لا يوجد مستخدمين</td></tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                                <span class="badge badge-<?php echo $user['role'] == 'Admin' ? 'danger' : 'info'; ?>">
                                                    <?php echo $user['role']; ?>
                                                </span>
                                        </td>
                                        <td>
                                                <span class="badge badge-<?php echo $user['is_active'] ? 'success' : 'danger'; ?>">
                                                    <?php echo $user['is_active'] ? 'مفعل' : 'معطل'; ?>
                                                </span>
                                        </td>
                                        <td>
                                            <a href="users_edit.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> تعديل
                                            </a>
                                            <a href="users_toggle.php?id=<?php echo $user['id']; ?>" class="btn btn-sm <?php echo $user['is_active'] ? 'btn-danger' : 'btn-success'; ?>">
                                                <i class="fas fa-power-off"></i> <?php echo $user['is_active'] ? 'تعطيل' : 'تفعيل'; ?>
                                            </a>
                                            <a href="users_delete.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('متأكد من حذف المستخدم؟');">
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
