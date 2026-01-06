<?php
// logout.php
session_start();

// تدمير كل الـ session
session_destroy();

// إعادة توجيه لصفحة اللوجين
header('Location: login.php');
exit;
?>