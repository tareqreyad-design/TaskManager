<?php
session_start();
require_once 'db.php';

// التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// جلب المستخدمين
$userStmt = $pdo->query("SELECT id, username FROM users WHERE is_active = 1 AND role != 'Admin'");
$allUsers = $userStmt->fetchAll();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // استقبال البيانات
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';

    // استقبال المستخدمين وإزالة التكرار (الحل الجذري للمشكلة)
    $selected_users = $_POST['users'] ?? [];
    $selected_users = array_unique($selected_users); // <--- السطر ده هو الحل

    $permissions = $_POST['can_edit'] ?? [];

    if (empty($name)) {
        $error = "اسم المشروع مطلوب";
    } else {
        try {
            $pdo->beginTransaction();

            // 1. إنشاء المشروع
            $stmt = $pdo->prepare("INSERT INTO projects (name, description) VALUES (?, ?)");
            $stmt->execute([$name, $description]);
            $project_id = $pdo->lastInsertId();

            // 2. إضافة الأعضاء (بعد حذف التكرار)
            if (!empty($selected_users)) {
                $assignStmt = $pdo->prepare("INSERT INTO project_users (project_id, user_id, can_edit) VALUES (?, ?, ?)");

                foreach ($selected_users as $user_id) {
                    // تأكد إن الـ ID رقم صحيح ومش فاضي
                    if(!empty($user_id)) {
                        $can_edit = isset($permissions[$user_id]) ? 1 : 0;
                        $assignStmt->execute([$project_id, $user_id, $can_edit]);
                    }
                }
            }

            $pdo->commit();
            header("Location: projects.php?success=created");
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            // لو الخطأ تكرار، نطلع رسالة مفهومة
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $error = "يبدو أنك حاولت إضافة نفس المستخدم للمشروع مرتين.";
            } else {
                $error = "حدث خطأ: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إضافة مشروع جديد</title>
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
                <h1>إضافة مشروع جديد</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">نموذج إضافة مشروع</h3>
                        <div class="card-tools">
                            <a href="projects.php" class="btn btn-sm btn-primary">
                                <i class="nav-icon fas fa-project-diagram" ></i> المشاريع
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
                                <label>اسم المشروع</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>الوصف</label>
                                <textarea name="description" class="form-control" rows="5"></textarea>
                            </div>
                            <hr>
                            <h5>تعيين أعضاء الفريق والصلاحيات</h5>
                            <div class="form-group">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th style="width: 10px">اختر</th>
                                        <th>المستخدم</th>
                                        <th>إعطاء صلاحية التعديل؟</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($allUsers as $user): ?>
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="user_<?php echo $user['id']; ?>" name="users[]" value="<?php echo $user['id']; ?>">
                                                    <label for="user_<?php echo $user['id']; ?>" class="custom-control-label"></label>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="perm_<?php echo $user['id']; ?>" name="can_edit[<?php echo $user['id']; ?>]" value="1">
                                                    <label class="custom-control-label" for="perm_<?php echo $user['id']; ?>">سماح بالتعديل/الحذف</label>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <button type="submit" class="btn btn-success">إضافة المشروع</button>
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
<script>
    // كود لمنع الضغط على الزر مرتين
    $('form').on('submit', function() {
        var btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true);
        btn.html('<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...');
    });
</script>

</body>
</html>
