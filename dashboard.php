<?php
include 'includes/header.php';
require_once 'config/database.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user']['id'];

$stmt = $pdo->prepare("SELECT * FROM resumes WHERE user_id = ? ORDER BY id DESC");
$stmt->execute([$userId]);
$resumes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="dashboard">
    <div class="container">
        <div class="dashboard-header">
            <h1>مرحبًا <?php echo htmlspecialchars($_SESSION['user']['name']); ?></h1>
            <a href="choose_resume_type.php" class="btn btn-primary">إنشاء سيرة جديدة</a>
        </div>

        <?php if (!empty($resumes)): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم السيرة</th>
                            <th>القالب</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resumes as $resume): ?>
                            <tr>
                                <td><?php echo $resume['id']; ?></td>
                                <td><?php echo htmlspecialchars($resume['title']); ?></td>
                                <td><?php echo htmlspecialchars($resume['template_name']); ?></td>
                                <td>
                                    <?php if ($resume['payment_status'] === 'مدفوعة'): ?>
                                        <span class="status status-paid">مدفوعة</span>
                                    <?php else: ?>
                                        <span class="status status-unpaid">غير مدفوعة</span>
                                    <?php endif; ?>
                                </td>
                               <td>
    <div class="action-buttons">
        <a href="preview.php?id=<?php echo $resume['id']; ?>" class="btn btn-light">معاينة</a>

        

        <a href="delete_resume.php?id=<?php echo $resume['id']; ?>" 
           class="btn btn-danger"
           onclick="return confirm('هل أنت متأكد من الحذف؟')">
           حذف
        </a>
    </div>
</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>لا يوجد لديك سير ذاتية بعد</p>
                
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>