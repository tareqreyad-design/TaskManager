<?php
session_start();
require_once 'db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    // حذف المهمة مباشرة باستخدام Prepared Statement
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: tasks.php?msg=deleted");
exit;