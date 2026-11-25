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
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $school_name = trim($_POST['school_name'] ?? '');
    $education_level = trim($_POST['education_level'] ?? '');
    $grade = trim($_POST['grade'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $skills = trim($_POST['skills'] ?? '');

    $resumeStmt = $pdo->prepare("
        INSERT INTO resumes (user_id, title, template_name, payment_status, resume_type)
        VALUES (?, ?, ?, ?, ?)
    ");
    $resumeStmt->execute([
        $userId,
        'السيرة الذاتية المدرسية',
        'قالب مدرسي',
        'غير مدفوعة',
        'school'
    ]);

    $resumeId = $pdo->lastInsertId();

    $personalStmt = $pdo->prepare("
        INSERT INTO personal_info (resume_id, full_name, job_title, email, phone, address, summary)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $personalStmt->execute([
        $resumeId,
        $full_name,
        'طالبة',
        $email,
        $phone,
        $city,
        $summary
    ]);

    $educationStmt = $pdo->prepare("
        INSERT INTO education (resume_id, school_name, degree, graduation_year)
        VALUES (?, ?, ?, ?)
    ");
    $educationStmt->execute([
        $resumeId,
        $school_name,
        $education_level,
        $grade
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
            <h1>إنشاء سيرة ذاتية مدرسية</h1>
            <a href="choose_resume_type.php" class="btn btn-light">رجوع</a>
        </div>

        <div class="resume-form-card">
            <form method="POST" class="resume-form">

                <div class="form-section">
                    <h2>البيانات الأساسية</h2>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>الاسم الكامل</label>
                            <input type="text" name="full_name" class="form-control" required>
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
                            <label>المدينة</label>
                            <select name="city" class="form-control" required>
                                <option value="">اختاري المدينة</option>
                                <option value="الرياض">الرياض</option>
                                <option value="جدة">جدة</option>
                                <option value="بريدة">بريدة</option>
                                <option value="الدمام">الدمام</option>
                                <option value="مكة">مكة</option>
                                <option value="المدينة">المدينة</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h2>البيانات الدراسية</h2>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>اسم المدرسة / الجهة التعليمية</label>
                            <input type="text" name="school_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>المرحلة الدراسية</label>
                            <select name="education_level" class="form-control" required>
                                <option value="">اختاري المرحلة</option>
                                <option value="ابتدائية">ابتدائية</option>
                                <option value="متوسطة">متوسطة</option>
                                <option value="ثانوية">ثانوية</option>
                                <option value="دبلوم">دبلوم</option>
                                <option value="بكالوريوس">بكالوريوس</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>المعدل / النسبة</label>
                            <input type="text" name="grade" class="form-control" placeholder="مثال: 95% أو 4.8/5">
                        </div>
                    </div>
                </div>


                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">حفظ ومعاينة السيرة المدرسية</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>