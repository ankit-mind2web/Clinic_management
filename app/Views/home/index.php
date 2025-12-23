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
        WellCare Clinic provides calm, clean, and patient-focused healthcare
        with modern tools for doctors and staff.
    </p>
</section>

<section class="features">

    <div class="feature-card" data-specialization="cardiology">
        ❤️
        <h3>Cardiology</h3>
        <p>Heart care & diagnostics</p>
    </div>

    <div class="feature-card" data-specialization="dermatology">
        🧴
        <h3>Dermatology</h3>
        <p>Skin & hair treatments</p>
    </div>

    <div class="feature-card" data-specialization="orthopedics">
        🦴
        <h3>Orthopedics</h3>
        <p>Bone & joint care</p>
    </div>

    <div class="feature-card" data-specialization="general">
        🩺
        <h3>General Medicine</h3>
        <p>Primary healthcare</p>
    </div>

</section>

<!-- DOCTORS LIST -->
<section class="doctors-section" id="doctors">
    <h2>👨‍⚕️ Our Doctors</h2>

    <div class="doctor-filter-actions">
        <button id="showAllDoctors">All Doctors</button>
    </div>

    <div class="doctors-grid">

        <div class="doctor-card" data-specialization="cardiology">
            <img src="/../assets/images/female_doctor.jpeg" alt="Dr. Anjali Sharma">
            <div class="doctor-info">
                <h3>Dr. Anjali Sharma</h3>
                <span>Cardiologist</span>
                <p>10+ years in heart care and diagnostics.</p>
            </div>
        </div>

        <div class="doctor-card" data-specialization="dermatology">
            <img src="/../assets/images/male_doctor.jpeg" alt="Dr. Rohan Mehta">
            <div class="doctor-info">
                <h3>Dr. Rohan Mehta</h3>
                <span>Dermatologist</span>
                <p>Expert in skin and cosmetic treatments.</p>
            </div>
        </div>

        <div class="doctor-card" data-specialization="orthopedics">
            <img src="/../assets/images/female_doctor.jpeg" alt="Dr. Neha Kapoor">
            <div class="doctor-info">
                <h3>Dr. Neha Kapoor</h3>
                <span>Orthopedic Surgeon</span>
                <p>Specialist in joint and bone care.</p>
            </div>
        </div>

        <div class="doctor-card" data-specialization="general">
            <img src="/../assets/images/male_doctor.jpeg" alt="Dr. Amit Verma">
            <div class="doctor-info">
                <h3>Dr. Amit Verma</h3>
                <span>General Physician</span>
                <p>Family and primary healthcare expert.</p>
            </div>
        </div>

    </div>
</section>

<script src="/../assets/js/doctor_filter.js"></script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
