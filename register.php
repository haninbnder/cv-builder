<?php
include 'includes/header.php';
require_once 'config/database.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name === '' || $email === '' || $password === '') {
        $error = 'جميع الحقول مطلوبة';
    } else {
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->execute([$email]);

        if ($checkStmt->fetch()) {
            $error = 'البريد الإلكتروني مستخدم بالفعل';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $insertStmt = $pdo->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
            $insertStmt->execute([$name, $email, $hashedPassword]);

            $success = 'تم إنشاء الحساب بنجاح، يمكنك الآن تسجيل الدخول';
        }
    }
}
?>

<div class="form-wrapper">
    <div class="form-box">
        <h2>إنشاء حساب جديد</h2>

        <?php if (!empty($success)): ?>
            <p style="color:#16a34a; margin-bottom:15px; text-align:center;">
                <?php echo $success; ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <p style="color:#dc2626; margin-bottom:15px; text-align:center;">
                <?php echo $error; ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>الاسم الكامل</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label>كلمة المرور</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">إنشاء الحساب</button>
        </form>

        <p class="helper-text">
            لديك حساب بالفعل؟ <a href="login.php">تسجيل الدخول</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>