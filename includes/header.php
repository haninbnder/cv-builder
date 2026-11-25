<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منشئ السيرة الذاتية</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="container">
        <a href="index.php" class="logo">CV Builder</a>

        <div class="nav-links">
            <a href="index.php">الرئيسية</a>

            <?php if (isset($_SESSION['user'])): ?>
                <a href="dashboard.php">لوحة المستخدم</a>
                <a href="logout.php" class="btn btn-light">تسجيل خروج</a>
            <?php else: ?>
                <a href="login.php">تسجيل الدخول</a>
                <a href="register.php" class="btn btn-primary">حساب جديد</a>
                <a href="contact.php">تواصل معنا</a>
            <?php endif; ?>
        </div>
    </div>
</nav>