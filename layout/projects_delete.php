<?php
session_start();
require_once 'db.php';

// يفضل الأدمن بس اللي يحذف مشاريع
if ($_SESSION['role'] !== 'Admin') {
    header("Location: projects.php?error=no_permission");
    exit;
}

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