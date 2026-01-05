<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . '/../../layout/header.php';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/patient_appointments.css">

<h2 class="page-title">Book Appointment</h2>

<div class="appointment-box">

    <label for="doctor_id">Select Doctor</label>
    <select id="doctor_id">
        <option value="">-- Select Doctor --</option>
        <?php foreach ($doctors as $doctor): ?>
            <option value="<?= (int)$doctor['id'] ?>">
                <?= htmlspecialchars($doctor['full_name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!--  SEARCH BUTTON -->
    <button id="searchSlots" class="primary-btn">
        Search Available Slots
    </button>

    <div id="slots" class="slots">
        <p class="empty">Select doctor and click search</p>
    </div>

</div>
<script>window.BASE_URL = "<?= BASE_URL ?>";</script>
<script src="<?= BASE_URL ?>/assets/js/patient_appointments.js"></script>

<?php include __DIR__ . '/../../layout/footer.php'; ?>
