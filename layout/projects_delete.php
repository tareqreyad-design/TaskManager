<?php
session_start();
require_once 'db.php';

session_start();
require_once 'db.php';

// حماية: لو مش أدمن، اطرده برة الصفحة فوراً
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header('Location: projects.php?error=access_denied');
    exit;
}

// ... كمل باقي الكود عادي

$id = $_GET['id'];

try {
    // لازم نحذف العلاقات الأول في جدول project_users عشان الـ Foreign Keys [cite: 65, 67]
    $pdo->prepare("DELETE FROM project_users WHERE project_id = ?")->execute([$id]);

    // بعدين نحذف المشروع نفسه [cite: 64]
    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: projects.php?success=deleted");
} catch (Exception $e) {
    header("Location: projects.php?error=failed");
}
exit;