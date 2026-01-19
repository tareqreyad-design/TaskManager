<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$success = '';
$error = '';
$user_id = $_SESSION['user_id'];

// جلب البيانات الحالية
$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // الباسورد الجديدة (اختياري)

    if (empty($username)) {
        $error = "اسم المستخدم لا يمكن أن يكون فارغاً";
    } else {
        try {
            if (!empty($password)) {
                // لو كتب باسورد جديدة، نحدث الاسم والباسورد
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $updateStmt = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
                $updateStmt->execute([$username, $hashed, $user_id]);
            } else {
                // لو مكتبش باسورد، نحدث الاسم بس
                $updateStmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
                $updateStmt->execute([$username, $user_id]);
            }

            // تحديث السيشن بالاسم الجديد
            $_SESSION['username'] = $username;
            $success = "تم تحديث بياناتك بنجاح!";

            // تحديث البيانات المعروضة
            $user['username'] = $username;

        } catch (PDOException $e) {
            $error = "حدث خطأ: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>الملف الشخصي</title>
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
                <h1>إعدادات الملف الشخصي</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">تعديل البيانات</h3>
                            </div>
                            <form method="POST">
                                <div class="card-body">
                                    <?php if ($success): ?>
                                        <div class="alert alert-success"><?php echo $success; ?></div>
                                    <?php endif; ?>

                                    <div class="form-group">
                                        <label>اسم المستخدم</label>
                                        <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label>كلمة المرور الجديدة</label>
                                        <input type="password" name="password" class="form-control" placeholder="اتركها فارغة إذا كنت لا تريد تغييرها">
                                        <small class="text-muted">اكتب الباسورد فقط لو عايز تغيرها.</small>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php include 'footer.php'; ?>
</div>

<!-- jQuery -->
<script src="../js/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../js/bootstrap.bundle.js"></script>
<!-- AdminLTE App -->
<script src="../js/adminlte.js"></script>


</body>
</html>
