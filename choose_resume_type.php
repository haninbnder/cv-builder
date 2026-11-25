<?php
include 'includes/header.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>

<section class="dashboard">
    <div class="container">
        <div class="dashboard-header">
            <h1>اختر نوع السيرة الذاتية</h1>
            <a href="dashboard.php" class="btn btn-light">رجوع</a>
        </div>

        <div class="cards">
            <div class="card" style="text-align:center;">
                <h3>سيرة ذاتية وظيفية</h3>
                <p>مناسبة للتقديم على الوظائف وإبراز المهارات والخبرات.</p>
               <a href="create_resume.php?type=job" class="btn btn-primary" style="margin-top:15px;">اختيار</a>
            </div>

            <div class="card" style="text-align:center;">
                <h3>سيرة ذاتية مدرسية</h3>
                <p>مناسبة للطالبات وإبراز البيانات الأكاديمية والأنشطة.</p>
                <a href="create_school_resume.php" class="btn btn-primary" style="margin-top:15px;">اختيار</a>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>