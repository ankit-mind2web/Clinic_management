<?php
$title = 'Doctor Dashboard';
include __DIR__ . '/../layout/header.php';

$user = $_SESSION['user'] ?? [];
$isVerified = ($user['email_verified'] ?? 0) == 1;
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/doctor_dashboard.css">

<div class="doctor-dashboard">

    <h2>Welcome, Dr. <?= htmlspecialchars($doctorName) ?></h2>

    <div class="dashboard-cards">

        <div class="card">
            <h3>Total Appointments</h3>
            <p><?= $totalAppointments ?></p>
        </div>

        <div class="card">
            <h3>Today's Appointments</h3>
            <p><?= $todayAppointments ?></p>
        </div>

    </div>

    <div class="dashboard-actions">
        <a href="/doctor/profile" class="action-btn">My Profile</a>

        <?php if ($isVerified): ?>
            <a href="/doctor/specialization" class="action-btn">Manage Specialization</a>
        <?php else: ?>
            <span class="action-btn disabled" title="Verify email first">
                Manage Specialization
            </span>
        <?php endif; ?>

        <a href="/doctor/availability" class="action-btn">Set Availability</a>
        <a href="/doctor/appointments" class="action-btn">View Appointments</a>
    </div>

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
