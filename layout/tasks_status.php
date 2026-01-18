<?php
session_start();
require_once 'db.php';

// 1. التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// 2. استقبال رقم المهمة
$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // جلب الحالة الحالية
        $stmt = $pdo->prepare("SELECT status FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        $task = $stmt->fetch();

        if ($task) {
            // trim: بتمسح أي مسافات فاضية في الأول أو الآخر عشان المقارنة تنجح
            $current_status = trim($task['status']);
            $new_status = 'To Do'; // قيمة افتراضية

            // منطق التغيير (دورة الحالة)
            switch ($current_status) {
                case 'To Do':
                    $new_status = 'In Progress';
                    break;
                case 'In Progress':
                    $new_status = 'Done';
                    break;
                case 'Done':
                    $new_status = 'To Do';
                    break;
                default:
                    // لو الحالة غريبة (مثلاً فاضية)، خليها To Do
                    $new_status = 'To Do';
            }

            // تنفيذ التحديث
            $updateStmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ?");
            $updateStmt->execute([$new_status, $id]);
        }
    } catch (PDOException $e) {
        // لو حصل خطأ، مش هنعرضه لليوزر عشان الشكل، بس مش هيغير الحالة
        die("Error: " . $e->getMessage());
    }
}

// 3. العودة للصفحة السابقة
if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    header("Location: tasks.php");
}
exit;
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