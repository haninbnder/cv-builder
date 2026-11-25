<?php
include 'includes/header.php';
require_once 'config/database.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if ($name === '' || $email === '' || $message === '') {
        $error = 'جميع الحقول مطلوبة';
    } else {
        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);

        $success = 'تم إرسال رسالتك بنجاح';
    }
}
?>

<div class="form-wrapper">
    <div class="form-box">
        <h2>تواصل معنا</h2>

        <?php if ($success): ?>
            <p style="color:green; text-align:center;"><?php echo $success; ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p style="color:red; text-align:center;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>الاسم</label>
                <input type="text" name="name" class="form-control">
            </div>

            <div class="form-group">
                <label>البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="form-group">
                <label>الرسالة</label>
                <textarea name="message" class="form-control textarea-control"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">إرسال</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>