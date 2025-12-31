<?php
include __DIR__ . '/../../layout/header.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Appointment</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/patient_appointments.css">
</head>
<body>

<h2 class="page-title">Book Appointment</h2>

<div class="appointment-box">

    <label for="doctor_id">Select Doctor</label>
    <select id="doctor_id">
        <option value="">-- Select Doctor --</option>
        <?php if (!empty($doctors)): ?>
            <?php foreach ($doctors as $doctor): ?>
                <option value="<?= (int) $doctor['id'] ?>">
                    <?= htmlspecialchars($doctor['full_name']) ?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>

    <div id="slots" class="slots">
        <p class="empty">No Doctor selected</p>
    </div>

</div>
<script src="<?= BASE_URL ?>/assets/js/patient_appointments.js"></script>
<?php include __DIR__ . '/../../layout/footer.php' ?>
