<?php
// login_process.php
global $pdo;
session_start();

// اتصال قاعدة البيانات
require_once 'db.php';  // لازم يكون في نفس المجلد، أو عدل المسار لو في مكان تاني

// جيب البيانات من الـ form
$email = trim($_POST['email'] );
$password = trim($_POST['password'] );

// تحقق من الحقول
if (empty($email) || empty($password)) {
    $_SESSION['error'] = "اسم المستخدم وكلمة المرور مطلوبين";
    header('Location: login.php');
    exit;
}

try {
    // استعلام آمن
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
//print_r($user);
//die();
    if ($user && password_verify($password, $user['password'])) {
        // نجح اللوجين
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header('Location: dashbord.php');  // أو dashboard.php
        exit;
    } else {
        $_SESSION['error'] = "اسم المستخدم أو كلمة المرور غير صحيحة";
        header('Location: login.php');
        exit;
    }
} catch (Exception $e) {
    $_SESSION['error'] = "خطأ فني: " . $e->getMessage();
    header('Location: login.php');
    exit;
}
?>