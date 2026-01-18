<?php
// projects_assign.php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_id = $_POST['project_id'];
    $user_ids = $_POST['user_ids']; // دي بتكون Array من الـ Checkboxes

    try {
        $pdo->beginTransaction();

        // 1. مسح العلاقات القديمة للمشروع ده
        $stmt = $pdo->prepare("DELETE FROM project_users WHERE project_id = ?");
        $stmt->execute([$project_id]);

        // 2. إضافة المستخدمين الجدد (Many-to-Many)
        if (!empty($user_ids)) {
            $insert = $pdo->prepare("INSERT INTO project_users (project_id, user_id) VALUES (?, ?)");
            foreach ($user_ids as $u_id) {
                $insert->execute([$project_id, $u_id]);
            }
        }

        $pdo->commit();
        header("Location: projects.php?success=assigned");
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error: " . $e->getMessage());
    }
}
?>