<?php
// $appointment is passed from controller
if (!isset($appointment)) {
    die('No appointment data');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Details</title>
    <link rel="stylesheet" href="/assets/css/admin-dashboard.css">
</head>
<body>

<div class="main">

    <div class="topbar">
        <h2>Appointment Details</h2>
        <a href="/admin/appointments" style="text-decoration:none;">⬅ Back</a>
    </div>

    <div class="panel">
        <table class="table">
            <tr>
                <th>Appointment ID</th>
                <td><?= htmlspecialchars($appointment['id']) ?></td>
            </tr>

            <tr>
                <th>Patient Name</th>
                <td><?= htmlspecialchars($appointment['patient_name']) ?></td>
            </tr>

            <tr>
                <th>Patient Email</th>
                <td><?= htmlspecialchars($appointment['patient_email']) ?></td>
            </tr>

            <tr>
                <th>Doctor Name</th>
                <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
            </tr>

            <tr>
                <th>Doctor Email</th>
                <td><?= htmlspecialchars($appointment['doctor_email']) ?></td>
            </tr>

            <tr>
                <th>Start Time</th>
                <td><?= htmlspecialchars($appointment['start_utc']) ?></td>
            </tr>

            <tr>
                <th>End Time</th>
                <td><?= htmlspecialchars($appointment['end_utc']) ?></td>
            </tr>

            <tr>
                <th>Status</th>
                <td>
                    <span class="status <?= strtolower($appointment['status']) ?>">
                        <?= ucfirst($appointment['status']) ?>
                    </span>
                </td>
            </tr>

            <tr>
                <th>Created At</th>
                <td><?= htmlspecialchars($appointment['created_at'] ?? '-') ?></td>
            </tr>
        </table>
    </div>

</div>

</body>
</html>
