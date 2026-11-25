<?php
include 'includes/header.php';
require_once 'config/database.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$resumeId = $_GET['id'] ?? null;

if (!$resumeId) {
    header('Location: dashboard.php');
    exit;
}

$resumeStmt = $pdo->prepare("
    SELECT r.*, p.full_name, p.job_title, p.email, p.phone, p.address, p.summary
    FROM resumes r
    JOIN personal_info p ON r.id = p.resume_id
    WHERE r.id = ? AND r.user_id = ?
");
$resumeStmt->execute([$resumeId, $_SESSION['user']['id']]);
$resume = $resumeStmt->fetch(PDO::FETCH_ASSOC);

if (!$resume) {
    header('Location: dashboard.php');
    exit;
}

$educationStmt = $pdo->prepare("SELECT * FROM education WHERE resume_id = ?");
$educationStmt->execute([$resumeId]);
$education = $educationStmt->fetch(PDO::FETCH_ASSOC);

$experienceStmt = $pdo->prepare("SELECT * FROM experiences WHERE resume_id = ?");
$experienceStmt->execute([$resumeId]);
$experience = $experienceStmt->fetch(PDO::FETCH_ASSOC);

$skillsStmt = $pdo->prepare("SELECT skill_name FROM skills WHERE resume_id = ?");
$skillsStmt->execute([$resumeId]);
$skills = $skillsStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<section class="dashboard">
    <div class="container">
        <div class="dashboard-header">
            <h1>معاينة السيرة الذاتية</h1>
            <div class="action-buttons">
    <a href="dashboard.php" class="btn btn-light">رجوع</a>

    <a href="print_resume.php?id=<?php echo $_GET['id']; ?>" 
       class="btn btn-primary" target="_blank">
       تحميل PDF
    </a>
</div>
        </div>

        <div class="cv-preview-card">
            <div class="cv-watermark">معاينة فقط</div>

            <div class="cv-layout">
                <aside class="cv-sidebar">
                    <div class="cv-block">
                        <h3>التواصل</h3>
                        <p><?php echo htmlspecialchars($resume['email'] ?: '—'); ?></p>
                        <p><?php echo htmlspecialchars($resume['phone'] ?: '—'); ?></p>
                        <p><?php echo htmlspecialchars($resume['address'] ?: '—'); ?></p>
                    </div>

                    <div class="cv-block">
                        <h3>المهارات</h3>
                        <?php if (!empty($skills)): ?>
                            <div class="skills-list">
                                <?php foreach ($skills as $skill): ?>
                                    <span class="skill-tag"><?php echo htmlspecialchars($skill); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p>لا توجد مهارات مضافة</p>
                        <?php endif; ?>
                    </div>
                </aside>

                <main class="cv-main">
                    <div class="cv-header">
                        <h2><?php echo htmlspecialchars($resume['full_name'] ?: 'الاسم الكامل'); ?></h2>
                        <p class="cv-job-title"><?php echo htmlspecialchars($resume['job_title'] ?: 'المسمى الوظيفي'); ?></p>
                    </div>

                    <div class="cv-block">
                        <h3>النبذة الشخصية</h3>
                        <p><?php echo nl2br(htmlspecialchars($resume['summary'] ?: 'لا توجد نبذة شخصية')); ?></p>
                    </div>

                    <div class="cv-block">
                        <h3>التعليم</h3>
                        <?php if ($education): ?>
                            <p><strong><?php echo htmlspecialchars($education['school_name'] ?: ''); ?></strong></p>
                            <p><?php echo htmlspecialchars($education['degree'] ?: ''); ?></p>
                            <p><?php echo htmlspecialchars($education['graduation_year'] ?: ''); ?></p>
                        <?php else: ?>
                            <p>لا توجد بيانات تعليمية</p>
                        <?php endif; ?>
                    </div>

                    <div class="cv-block">
                        <h3>الخبرات</h3>
                        <?php if ($experience): ?>
                            <p><strong><?php echo htmlspecialchars($experience['company_name'] ?: ''); ?></strong></p>
                            <p><?php echo htmlspecialchars($experience['job_role'] ?: ''); ?></p>
                            <p><?php echo htmlspecialchars($experience['years'] ?: ''); ?></p>
                            <p><?php echo nl2br(htmlspecialchars($experience['description'] ?: '')); ?></p>
                        <?php else: ?>
                            <p>لا توجد خبرات مضافة</p>
                        <?php endif; ?>
                    </div>
                </main>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>