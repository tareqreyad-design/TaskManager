<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'db.php';

// جيب المشاريع والمستخدمين للاختيار
$projects = $pdo->query("SELECT id, name FROM projects")->fetchAll();
$users = $pdo->query("SELECT id, username FROM users WHERE is_active = 1")->fetchAll();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_id = $_POST['project_id'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'To Do';
    $priority = $_POST['priority'] ?? 'Medium';
    $due_date = $_POST['due_date'] ?? null;
    $assigned_user_id = $_POST['assigned_user_id'] ?? null;

    if (empty($project_id) || empty($title)) {
        $error = 'المشروع والعنوان مطلوبين';
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO tasks (project_id, title, description, status, priority, due_date, assigned_user_id)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$project_id, $title, $description, $status, $priority, $due_date, $assigned_user_id]);
            $success = 'تم إضافة المهمة بنجاح!';
        } catch (PDOException $e) {
            $error = 'خطأ في الإضافة';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إضافة مهمة جديدة</title>
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
                <h1>إضافة مهمة جديدة</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">نموذج إضافة مهمة</h3>
                        <div class="card-tools">
                            <a href="tasks.php" class="btn btn-sm btn-success">
                                <i class="nav-icon fas fa-tasks" ></i> المهام
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <?php echo $success; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="form-group">
                                <label>المشروع</label>
                                <select name="project_id" class="form-control" required>
                                    <option value="">اختر المشروع</option>
                                    <?php foreach ($projects as $project): ?>
                                        <option value="<?php echo $project['id']; ?>"><?php echo htmlspecialchars($project['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>العنوان</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label>الوصف</label>
                                <textarea name="description" class="form-control" rows="5"></textarea>
                            </div>

                            <div class="form-group">
                                <label>الحالة</label>
                                <select name="status" class="form-control">
                                    <option value="To Do" selected>قيد الانتظار</option>
                                    <option value="In Progress">قيد التنفيذ</option>
                                    <option value="Done">مكتملة</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>الأولوية</label>
                                <select name="priority" class="form-control">
                                    <option value="Low">منخفضة</option>
                                    <option value="Medium" selected>متوسطة</option>
                                    <option value="High">عالية</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>تاريخ التسليم</label>
                                <input type="date" name="due_date" class="form-control">
                            </div>

                            <div class="form-group">
                                <label>المعين لها</label>
                                <select name="assigned_user_id" class="form-control">
                                    <option value="">غير معين</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">إضافة المهمة</button>
                        </form>
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
