<?php
session_start();
require_once 'db.php';

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: projects.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$id]);
$project = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // حماية المدخلات (Server-side validation) [cite: 53]
    if (!empty($name)) {
        $update = $pdo->prepare("UPDATE projects SET name = ?, description = ? WHERE id = ?");
        $update->execute([$name, $description, $id]);
        header("Location: projects.php?success=updated");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>تعديل المشروع</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <?php include 'nav.php'; ?>
    <?php include 'aside.php'; ?>
    <div class="content-wrapper">
        <section class="content">
            <div class="card card-info">
                <div class="card-header"><h3 class="card-title">تعديل المشروع: <?= htmlspecialchars($project['name']) ?></h3></div>
                <form method="POST">
                    <div class="card-body">
                        <div class="form-group">
                            <label>اسم المشروع</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($project['name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>وصف المشروع</label>
                            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($project['description']) ?></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">تحديث المشروع</button>
                        <a href="projects.php" class="btn btn-default">رجوع</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
</body>
</html>