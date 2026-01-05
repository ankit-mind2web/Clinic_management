<?php
if (session_status() === PHP_SESSION_NONE) session_start();
date_default_timezone_set('Asia/Kolkata');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Appointments</title>
    <link rel="stylesheet" href="/assets/css/doctor_appointments.css">
    <?php require_once __DIR__ . '/../../layout/header.php'; ?>
</head>
<body>

<div class="page-wrapper">

    <div class="page-header">
        <h2>My Appointments</h2>
        <button class="back-btn" onclick="history.back()">← Back</button>
    </div>

    <?php if (empty($appointments)): ?>
        <div class="empty">No appointments found</div>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Email</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($appointments as $a): ?>
                    <?php
                        $start = new DateTime($a['start_utc'], new DateTimeZone('UTC'));
                        $end   = new DateTime($a['end_utc'], new DateTimeZone('UTC'));

                        $start->setTimezone(new DateTimeZone(date_default_timezone_get()));
                        $end->setTimezone(new DateTimeZone(date_default_timezone_get()));
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($a['patient_name']) ?></td>
                        <td><?= htmlspecialchars($a['patient_email']) ?></td>
                        <td><?= $start->format('d M Y') ?></td>
                        <td><?= $start->format('h:i A') ?> - <?= $end->format('h:i A') ?></td>
                        <td>
                            <span class="status <?= htmlspecialchars($a['status']) ?>">
                                <?= ucfirst($a['status']) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>
<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
</body>
</html>
