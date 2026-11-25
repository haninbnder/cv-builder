<?php
require_once 'config/database.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT r.*, p.full_name, p.job_title, p.email, p.phone, p.address, p.summary
    FROM resumes r
    LEFT JOIN personal_info p ON r.id = p.resume_id
    WHERE r.id = ?
");
$stmt->execute([$id]);
$resume = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$resume) {
    die('السيرة غير موجودة');
}

$educationStmt = $pdo->prepare("SELECT * FROM education WHERE resume_id = ?");
$educationStmt->execute([$id]);
$education = $educationStmt->fetch(PDO::FETCH_ASSOC);

$experienceStmt = $pdo->prepare("SELECT * FROM experiences WHERE resume_id = ?");
$experienceStmt->execute([$id]);
$experience = $experienceStmt->fetch(PDO::FETCH_ASSOC);

$skillsStmt = $pdo->prepare("SELECT skill_name FROM skills WHERE resume_id = ?");
$skillsStmt->execute([$id]);
$skills = $skillsStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>السيرة الذاتية</title>
<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: Tahoma, Arial, sans-serif;
        background: #f3f4f6;
        color: #1f2937;
        padding: 24px;
    }

    .print-btn {
        position: fixed;
        top: 20px;
        left: 20px;
        background: #2563eb;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px 18px;
        cursor: pointer;
        font-size: 14px;
        z-index: 999;
    }

    .resume-sheet {
        max-width: 1000px;
        margin: 0 auto;
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
    }

    .resume-header {
        padding: 32px 36px 24px;
        border-bottom: 1px solid #e5e7eb;
    }

    .resume-header h1 {
        margin: 0 0 8px;
        font-size: 40px;
        color: #0f172a;
    }

    .resume-header .job-title {
        margin: 0;
        font-size: 20px;
        color: #2563eb;
        font-weight: bold;
    }

    .resume-body {
        display: grid;
        grid-template-columns: 300px 1fr;
        min-height: 700px;
    }

    .sidebar {
        background: #f8fafc;
        padding: 28px 24px;
        border-left: 1px solid #e5e7eb;
    }

    .main-content {
        padding: 28px 32px;
    }

    .section {
        margin-bottom: 28px;
    }

    .section h2 {
        margin: 0 0 14px;
        font-size: 22px;
        color: #1e3a8a;
        border-bottom: 2px solid #e5e7eb;
        padding-bottom: 8px;
    }

    .section p {
        margin: 0 0 10px;
        line-height: 1.9;
        font-size: 15px;
        color: #374151;
    }

    .contact-item {
        margin-bottom: 10px;
        font-size: 15px;
        color: #374151;
        word-break: break-word;
    }

    .skills-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .skill-badge {
        display: inline-block;
        background: #dbeafe;
        color: #1d4ed8;
        padding: 8px 12px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: bold;
    }

    .item-title {
        font-weight: bold;
        color: #111827;
        margin-bottom: 6px;
    }

    .muted {
        color: #6b7280;
    }

    @media print {
        body {
            background: #fff;
            padding: 0;
        }

        .print-btn {
            display: none;
        }

        .resume-sheet {
            max-width: 100%;
            border-radius: 0;
            box-shadow: none;
            border: none;
        }

        .resume-body {
            grid-template-columns: 280px 1fr;
        }
    }
</style>
</head>
<body>

<button class="print-btn" onclick="window.print()">تحميل PDF</button>

<div class="resume-sheet">
    <div class="resume-header">
        <h1><?php echo htmlspecialchars($resume['full_name'] ?: 'الاسم الكامل'); ?></h1>
        <p class="job-title"><?php echo htmlspecialchars($resume['job_title'] ?: 'المسمى الوظيفي'); ?></p>
    </div>

    <div class="resume-body">
        <aside class="sidebar">
            <div class="section">
                <h2>التواصل</h2>
                <div class="contact-item"><?php echo htmlspecialchars($resume['email'] ?: '—'); ?></div>
                <div class="contact-item"><?php echo htmlspecialchars($resume['phone'] ?: '—'); ?></div>
                <div class="contact-item"><?php echo htmlspecialchars($resume['address'] ?: '—'); ?></div>
            </div>

            <div class="section">
                <h2>المهارات</h2>
                <?php if (!empty($skills)): ?>
                    <div class="skills-list">
                        <?php foreach ($skills as $skill): ?>
                            <span class="skill-badge"><?php echo htmlspecialchars($skill); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="muted">لا توجد مهارات مضافة</p>
                <?php endif; ?>
            </div>
        </aside>

        <main class="main-content">
            <div class="section">
                <h2>النبذة الشخصية</h2>
                <p><?php echo nl2br(htmlspecialchars($resume['summary'] ?: 'لا توجد نبذة شخصية')); ?></p>
            </div>

            <div class="section">
                <h2>التعليم</h2>
                <?php if ($education): ?>
                    <p class="item-title"><?php echo htmlspecialchars($education['school_name'] ?: ''); ?></p>
                    <p><?php echo htmlspecialchars($education['degree'] ?: ''); ?></p>
                    <p><?php echo htmlspecialchars($education['graduation_year'] ?: ''); ?></p>
                <?php else: ?>
                    <p class="muted">لا توجد بيانات تعليمية</p>
                <?php endif; ?>
            </div>

            <div class="section">
                <h2>الخبرات</h2>
                <?php if ($experience): ?>
                    <p class="item-title"><?php echo htmlspecialchars($experience['company_name'] ?: ''); ?></p>
                    <p><?php echo htmlspecialchars($experience['job_role'] ?: ''); ?></p>
                    <p><?php echo htmlspecialchars($experience['years'] ?: ''); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($experience['description'] ?: '')); ?></p>
                <?php else: ?>
                    <p class="muted">لا توجد خبرات مضافة</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

</body>
</html>