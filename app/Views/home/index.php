<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isLoggedIn = isset($_SESSION['user']);
?>

<?php require_once __DIR__ . '/../layout/header.php'; ?>

<link rel="stylesheet" href="/assets/css/homepage.css">

<section class="hero">
    <div class="hero-content">

        <div class="hero-text">
            <h1>
                Clinic Management<br>
                <span>Made Simple</span>
            </h1>

            <?php if (!$isLoggedIn): ?>
                <!-- LANDING PAGE TEXT -->
                <p>
                    Book appointments, manage doctors, and streamline patient care
                    with a secure and modern clinic management system.
                </p>

                <div class="hero-actions">
                    <a href="/auth/register" class="btn primary">Get Started</a>
                    <a href="/auth/login" class="btn secondary">Login</a>
                </div>
            <?php else: ?>
                <!-- PATIENT LOGGED-IN TEXT -->
                <p>
                    Take control of your healthcare with ease. Book appointments seamlessly,
                    consult trusted doctors, and manage your medical information securely —
                    all through a patient-friendly system designed to deliver a smooth and
                    stress-free healthcare experience.
                </p>
            <?php endif; ?>
        </div>

        <div class="hero-visual">
            <img
                src="/assets/images/doctor.jpg"
                alt="Happy doctor"
                class="hero-image">
        </div>

    </div>
</section>

<section class="info-section">
    <h2>Clinic Information</h2>
    <p>
        WellCare Clinic provides calm, clean, and patient-focused healthcare with modern tools for doctors and staff. WellCare Clinic is dedicated to providing compassionate, patient-centered healthcare in a clean and modern environment. Our experienced doctors and staff use cutting-edge tools to ensure you receive personalized treatment plans tailored to your unique needs. We strive to make your healthcare journey smooth, comfortable, and stress-free every step of the way.
    </p>
</section>
<!-- DOCTORS LIST -->
<section class="doctors-section" id="doctors">
    <h2>👨‍⚕️ Our Doctors</h2>

    <div class="doctor-filter-actions">
        <button id="showAllDoctors" class="active">All Doctors</button>
        <?php if (!empty($specializations)): ?>
            <?php foreach ($specializations as $spec): ?>
                <?php $slug = strtolower($spec['name']); ?>
                <button class="filter-btn" data-filter="<?= htmlspecialchars($slug) ?>">
                    <?= htmlspecialchars($spec['name']) ?>
                </button>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (empty($doctors)): ?>
        <p style="text-align:center; padding: 2rem;">No doctors found matching filters.</p>
    <?php else: ?>
        <div class="doctors-grid">
            <?php foreach ($doctors as $doctor): ?>
                <?php
                // Determine image based on gender (fallback to 'male' if not set)
                $gender = strtolower($doctor['gender'] ?? 'male');
                $imgName = ($gender === 'female') ? 'female_doctor.jpeg' : 'male_doctor.jpeg';
                // Primary specialization for display, or comma list
                $specToShow = htmlspecialchars($doctor['primary_specialization'] ?? 'General');
                // Slug for filtering - MATCHES THE FILTER BUTTON SLUG
                $specSlug = strtolower($doctor['primary_specialization'] ?? 'general');
                ?>
                <div class="doctor-card" data-specialization="<?= htmlspecialchars($specSlug) ?>">
                    <img src="/assets/images/<?= $imgName ?>" alt="<?= htmlspecialchars($doctor['full_name']) ?>">
                    <div class="doctor-info">
                        <h3>Dr. <?= htmlspecialchars($doctor['full_name']) ?></h3>
                        <span><?= $specToShow ?></span><br>
                        <?php if(!empty($doctor['experience'])): ?>
                            <small class="exp-badge"><?= $doctor['experience'] ?>+ Years Exp</small>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const doctorCards = document.querySelectorAll('.doctor-card');
    const showAllBtn = document.getElementById('showAllDoctors');
    const allBtns = document.querySelectorAll('.doctor-filter-actions button');

    function setActiveBtn(btn) {
        allBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    // Filter Button Click
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const filter = this.dataset.filter;
            setActiveBtn(this);

            doctorCards.forEach(card => {
                if (card.dataset.specialization === filter) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // Show All Click
    if (showAllBtn) {
        showAllBtn.addEventListener('click', function () {
            setActiveBtn(this);
            doctorCards.forEach(card => {
                card.style.display = 'flex';
            });
        });
    }
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
