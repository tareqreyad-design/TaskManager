<?php
session_start();
require_once 'db.php';

// حماية الصفحة: للأدمن فقط [cite: 13]
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: dashbord.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: users.php"); exit; }

// جلب بيانات المستخدم الحالية [cite: 52]
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    // تحديث البيانات باستخدام Prepared Statements لحماية من SQL Injection [cite: 51, 52, 59]
    $update = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ?, is_active = ? WHERE id = ?");
    $update->execute([$username, $email, $role, $is_active, $id]);

    header("Location: users.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>تعديل مستخدم</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include 'nav.php'; ?>
    <?php include 'aside.php'; ?>
    <div class="content-wrapper">
        <section class="content">
            <div class="card card-warning">
                <div class="card-header"><h3 class="card-title">تعديل بيانات المستخدم: <?= htmlspecialchars($user['username']) ?></h3></div>
                <form method="POST">
                    <div class="card-body">
                        <div class="form-group">
                            <label>اسم المستخدم</label>
                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>الدور الوظيفي</label>
                            <select name="role" class="form-control">
                                <option value="Admin" <?= $user['role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="Member" <?= $user['role'] == 'Member' ? 'selected' : '' ?>>Member</option>
                            </select>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" <?= $user['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label">حساب مفعل</label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                        <a href="users.php" class="btn btn-default">إلغاء</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
</body>
</html>