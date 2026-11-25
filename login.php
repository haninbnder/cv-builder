<?php
include 'includes/header.php';
require_once 'config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['full_name'],
            'email' => $user['email']
        ];

        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
    }
}
?>

<div class="form-wrapper">
    <div class="form-box">
        <h2>تسجيل الدخول</h2>

        <?php if (!empty($error)): ?>
            <p style="color:#dc2626; margin-bottom:15px; text-align:center;">
                <?php echo $error; ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label>كلمة المرور</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">دخول</button>
        </form>

        <p class="helper-text">
            ليس لديك حساب؟ <a href="register.php">إنشاء حساب جديد</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>