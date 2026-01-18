<?php
session_start();
require_once 'db.php';

// حماية: لو مش أدمن، اطرده برة الصفحة فوراً
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: projects.php?error=access_denied');
    exit;
}


// حماية: التأكد من تسجيل الدخ
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: projects.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
$stmt->execute([$id]);
$project = $stmt->fetch();

if (!$project) {
    die("المشروع غير موجود!");
}
// 2. جلب جميع المستخدمين (عشان نعرضهم في القائمة)
$userStmt = $pdo->query("SELECT id, username FROM users WHERE is_active = 1 AND role != 'Admin'");
$allUsers = $userStmt->fetchAll();
// 3. جلب الأعضاء الحاليين للمشروع وصلاحياتهم
// بنستخدم FETCH_KEY_PAIR عشان النتيجة تكون مصفوفة بالشكل ده: [user_id => can_edit]
$teamStmt = $pdo->prepare("SELECT user_id, can_edit FROM project_users WHERE project_id = ?");
$teamStmt->execute([$id]);
$current_team = $teamStmt->fetchAll(PDO::FETCH_KEY_PAIR);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (empty($name)) {
        $error = "اسم المشروع مطلوب";
    } else {
        try {
            $pdo->beginTransaction();

            // أ. تحديث بيانات المشروع
            $updateStmt = $pdo->prepare("UPDATE projects SET name = ?, description = ? WHERE id = ?");
            $updateStmt->execute([$name, $description, $id]);

            // ب. تحديث قائمة الأعضاء (الأسهل: نحذف الكل ونضيف الجديد)
            // 1. حذف القديم
            $deleteStmt = $pdo->prepare("DELETE FROM project_users WHERE project_id = ?");
            $deleteStmt->execute([$id]);

            // 2. إضافة المختارين بالصلاحيات الجديدة
            if (!empty($selected_users)) {
                $assignStmt = $pdo->prepare("INSERT INTO project_users (project_id, user_id, can_edit) VALUES (?, ?, ?)");
                foreach ($selected_users as $user_id) {
                    $can_edit = isset($permissions[$user_id]) ? 1 : 0;
                    $assignStmt->execute([$id, $user_id, $can_edit]);
                }
            }

            $pdo->commit();
            // تحديث البيانات في الصفحة عشان تظهر التعديلات فوراً
            header("Location: projects_edit.php?id=$id&success=updated");
            exit;

        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "حدث خطأ: " . $e->getMessage();
        }
    }
}

// رسالة نجاح جاية من الرابط
if (isset($_GET['success'])) {
    $success = "تم تحديث المشروع وصلاحيات الفريق بنجاح!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>تعديل المشروع</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

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
                    <hr>
                    <h5>تعديل فريق العمل والصلاحيات</h5>
                    <div class="form-group">
                        <?php if(empty($allUsers)): ?>
                            <p class="text-muted">لا يوجد مستخدمين لإضافتهم.</p>
                        <?php else: ?>
                            <table class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th style="width: 10px">عضو؟</th>
                                    <th>المستخدم</th>
                                    <th>صلاحية التعديل/الحذف</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($allUsers as $user): ?>
                                    <?php
                                    // بنشوف هل اليوزر ده موجود في الفريق الحالي ولا لأ
                                    $is_member = array_key_exists($user['id'], $current_team);
                                    // بنشوف هل واخد صلاحية تعديل (القيمة 1)
                                    $has_edit_perm = $is_member && $current_team[$user['id']] == 1;
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="custom-control custom-checkbox">
                                                <input class="custom-control-input" type="checkbox"
                                                       id="user_<?php echo $user['id']; ?>"
                                                       name="users[]"
                                                       value="<?php echo $user['id']; ?>"
                                                        <?php echo $is_member ? 'checked' : ''; ?>>
                                                <label for="user_<?php echo $user['id']; ?>" class="custom-control-label"></label>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="perm_<?php echo $user['id']; ?>"
                                                       name="can_edit[<?php echo $user['id']; ?>]"
                                                       value="1"
                                                        <?php echo $has_edit_perm ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="perm_<?php echo $user['id']; ?>">تفعيل</label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info"> حفظ التعديلات </button>
                        <a href="projects.php" class="btn btn-default">رجوع</a>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
</body>
</html>