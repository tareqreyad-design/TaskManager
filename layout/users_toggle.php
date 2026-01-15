<?php
session_start();
require_once 'db.php';

// التأكد إن اللي داخل أدمن [cite: 13, 17]
if ($_SESSION['role'] !== 'Admin') {
    die("غير مسموح لك بالدخول");
}

$id = $_GET['id'];
// استعلام يقلب الحالة (لو 1 تبقى 0 ولو 0 تبقى 1) [cite: 16]
$stmt = $pdo->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
$stmt->execute([$id]);

header("Location: users.php");
exit;
?>