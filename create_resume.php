<?php
include 'includes/header.php';
require_once 'config/database.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user']['id'];

    $full_name = trim($_POST['full_name'] ?? '');
    $job_title = trim($_POST['job_title'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $summary = trim($_POST['summary'] ?? '');

    $education_school = trim($_POST['education_school'] ?? '');
    $education_degree = trim($_POST['education_degree'] ?? '');
    $education_year = trim($_POST['education_year'] ?? '');

    $experience_company = trim($_POST['experience_company'] ?? '');
    $experience_role = trim($_POST['experience_role'] ?? '');
    $experience_years = trim($_POST['experience_years'] ?? '');
    $experience_desc = trim($_POST['experience_desc'] ?? '');

    $skills = trim($_POST['skills'] ?? '');

    $resumeStmt = $pdo->prepare("
        INSERT INTO resumes (user_id, title, template_name, payment_status)
        VALUES (?, ?, ?, ?)
    ");
    $resumeStmt->execute([$userId, 'السيرة الذاتية', 'قالب افتراضي', 'غير مدفوعة']);

    $resumeId = $pdo->lastInsertId();

    $personalStmt = $pdo->prepare("
        INSERT INTO personal_info (resume_id, full_name, job_title, email, phone, address, summary)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $personalStmt->execute([
        $resumeId,
        $full_name,
        $job_title,
        $email,
        $phone,
        $address,
        $summary
    ]);

    $educationStmt = $pdo->prepare("
        INSERT INTO education (resume_id, school_name, degree, graduation_year)
        VALUES (?, ?, ?, ?)
    ");
    $educationStmt->execute([
        $resumeId,
        $education_school,
        $education_degree,
        $education_year
    ]);

    $experienceStmt = $pdo->prepare("
        INSERT INTO experiences (resume_id, company_name, job_role, years, description)
        VALUES (?, ?, ?, ?, ?)
    ");
    $experienceStmt->execute([
        $resumeId,
        $experience_company,
        $experience_role,
        $experience_years,
        $experience_desc
    ]);

    $skillsArray = array_filter(array_map('trim', explode(',', $skills)));
    $skillStmt = $pdo->prepare("INSERT INTO skills (resume_id, skill_name) VALUES (?, ?)");

    foreach ($skillsArray as $skill) {
        $skillStmt->execute([$resumeId, $skill]);
    }

    header('Location: preview.php?id=' . $resumeId);
    exit;
}
?>

<section class="dashboard">
    <div class="container">
        <div class="dashboard-header">
            <h1>إنشاء السيرة الذاتية</h1>
            <a href="dashboard.php" class="btn btn-light">رجوع</a>
        </div>

        <div class="resume-form-card">
            <form method="POST" class="resume-form">

                <div class="form-section">
                    <h2>المعلومات الشخصية</h2>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>الاسم الكامل</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>المسمى الوظيفي</label>
                            <input type="text" name="job_title" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>رقم الجوال</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>العنوان</label>
                            <input type="text" name="address" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>نبذة شخصية</label>
                        <textarea name="summary" class="form-control textarea-control" required></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h2>التعليم</h2>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>اسم الجهة التعليمية</label>
                            <input type="text" name="education_school" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>الدرجة العلمية / المؤهل</label>
                            <input type="text" name="education_degree" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>سنة التخرج</label>
                            <input type="text" name="education_year" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2>الخبرات</h2>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>اسم الشركة</label>
                            <input type="text" name="experience_company" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>المسمى الوظيفي</label>
                            <input type="text" name="experience_role" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>مدة الخبرة</label>
                            <input type="text" name="experience_years" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>وصف الخبرة</label>
                        <textarea name="experience_desc" class="form-control textarea-control"></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h2>المهارات</h2>

                    <div class="form-group">
                        <label>المهارات</label>
                        <textarea name="skills" class="form-control textarea-control"></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">حفظ ومعاينة السيرة</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>