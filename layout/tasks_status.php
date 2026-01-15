<?php
session_start();
require_once 'db.php'; // التأكد من الاتصال بقاعدة البيانات [cite: 51]

// 1. حماية الصفحة
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 2. الحصول على ID المهمة من الرابط
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: tasks.php");
    exit;
}

// 3. جلب البيانات الحالية للمهمة
$stmt = $pdo->prepare("SELECT title, status FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch();

if (!$task) {
    die("المهمة غير موجودة!");
}

// 4. معالجة تحديث الحالة عند إرسال الفورم
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newStatus = $_POST['status'];

    // التحديث باستخدام Prepared Statements [cite: 52]
    $updateStmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");

    if ($updateStmt->execute([$newStatus, $id])) {
        // العودة لصفحة المهام مع رسالة نجاح
        header("Location: tasks.php?success=status_updated");
        exit;
    } else {
        $error = "فشل في تحديث الحالة.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>تغيير حالة المهمة</title>
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

    <div class="content-wrapper p-4">
        <div class="card card-info shadow">
            <div class="card-header">
                <h3 class="card-title">تغيير حالة المهمة: <?= htmlspecialchars($task['title']) ?></h3>
            </div>
            <form method="POST" action="">
                <div class="card-body">
                    <div class="form-group">
                        <label>الحالة الحالية: <b><?= $task['status'] ?></b></label>
                        <select name="status" class="form-control select2">
                            <option value="To Do" <?= $task['status'] == 'To Do' ? 'selected' : '' ?>>To Do</option>
                            <option value="In Progress" <?= $task['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                            <option value="Done" <?= $task['status'] == 'Done' ? 'selected' : '' ?>>Done</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info">حفظ الحالة الجديدة</button>
                    <a href="tasks.php" class="btn btn-default float-left">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>