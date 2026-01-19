<?php
// 1. لازم تبدأ بالوسم ده عشان السيرفر يفهم إنه PHP
session_start();
require_once 'db.php';

// حماية: التأكد إن المستخدم مسجل دخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: tasks.php");
    exit;
}

// جلب بيانات المهمة الحالية من قاعدة البيانات باستخدام PDO [cite: 51, 66]
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
$stmt->execute([$id]);
$task = $stmt->fetch();

if (!$task) {
    die("المهمة غير موجودة!");
}

// معالجة البيانات عند الضغط على "حفظ"
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = $_POST['status'];
    $priority = $_POST['priority'];
    $due_date = $_POST['due_date'];

    // تحديث البيانات باستخدام Prepared Statements [cite: 52, 59]
    $updateSql = "UPDATE tasks SET title = ?, description = ?, status = ?, priority = ?, due_date = ? WHERE id = ?";
    $stmt = $pdo->prepare($updateSql);
    $stmt->execute([$title, $description, $status, $priority, $due_date, $id]);

    header("Location: tasks.php?success=updated");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تعديل المهمة</title>
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

            <div class="card card-info">
                <div class="card-header"><h3 class="card-title"> تعديل المهمة: <?= htmlspecialchars($task['title']) ?></h3>
                    <div class="card-tools">
                        <a href="tasks.php" class="btn btn-sm btn-success">
                            <i class="nav-icon fas fa-tasks" ></i> المهام
                        </a>
                    </div>
                </div>
            </div>

        </section>

        <section class="content">
            <div class="card card-warning">
                <form method="POST">
                    <div class="card-body">
                        <div class="form-group">
                            <label>عنوان المهمة</label>
                            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($task['title']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>الوصف</label>
                            <textarea name="description" class="form-control"><?= htmlspecialchars($task['description']) ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>الحالة</label>
                            <select name="status" class="form-control">
                                <option value="To Do" <?= $task['status'] == 'To Do' ? 'selected' : '' ?>>To Do</option>
                                <option value="In Progress" <?= $task['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                <option value="Done" <?= $task['status'] == 'Done' ? 'selected' : '' ?>>Done</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>الأولوية</label>
                            <select name="priority" class="form-control">
                                <option value="Low" <?= $task['priority'] == 'Low' ? 'selected' : '' ?>>Low</option>
                                <option value="Medium" <?= $task['priority'] == 'Medium' ? 'selected' : '' ?>>Medium</option>
                                <option value="High" <?= $task['priority'] == 'High' ? 'selected' : '' ?>>High</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>تاريخ الاستحقاق</label>
                            <input type="date" name="due_date" class="form-control" value="<?= $task['due_date'] ?>">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-warning">تحديث المهمة</button>
                        <a href="tasks.php" class="btn btn-default">إلغاء</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
</body>
</html>