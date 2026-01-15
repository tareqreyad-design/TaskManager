<?php
session_start();
require_once 'db.php';

// 1. التأكد إن اللي بيحذف هو الأدمن فقط (حسب متطلبات المشروع)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: users.php?error=unauthorized");
    exit;
}

// 2. الحصول على معرف المستخدم (ID) من الرابط
$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // بنبدأ حاجة اسمها Transaction عشان نضمن إن كل خطوات الحذف تتم مع بعض أو لا
        $pdo->beginTransaction();

        // 3. حذف علاقة المستخدم بالمشاريع من الجدول الوسيط (Many-to-Many)
        // ده ضروري جداً عشان الـ Foreign Key ميعملش Error
        $stmt1 = $pdo->prepare("DELETE FROM project_users WHERE user_id = ?");
        $stmt1->execute([$id]);

        // 4. التعامل مع المهام (Tasks) المرتبطة بالمستخدم
        // بدل ما نحذف المهمة وتضيع، هنخلي الخانة بتاعة "المستخدم المسؤول" فاضية (NULL)
        $stmt2 = $pdo->prepare("UPDATE tasks SET assigned_user_id = NULL WHERE assigned_user_id = ?");
        $stmt2->execute([$id]);

        // 5. حذف المستخدم نفسه نهائياً
        $stmt3 = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt3->execute([$id]);

        // تنفيذ كل العمليات بنجاح
        $pdo->commit();

        header("Location: users.php?success=deleted");
        exit;

    } catch (Exception $e) {
        // لو حصل أي غلط في أي خطوة، بنلغي كل اللي فات عشان الداتا متتداخلش
        $pdo->rollBack();
        die("حدث خطأ أثناء الحذف: " . $e->getMessage());
    }
} else {
    // لو مفيش ID مبعوت نرجعه لصفحة المستخدمين
    header("Location: users.php");
    exit;
}