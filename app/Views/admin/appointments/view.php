<?php
// Set local timezone (change if needed)
date_default_timezone_set('Asia/Kolkata');

// $appointment is passed from controller
if (!isset($appointment)) {
    die('No appointment data');
}
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Appointment Details</title>
    <link rel="stylesheet" href="/assets/css/admin_appointment.css">
</head>

<body>

    <div class="main">

        <div class="topbar">
            <h2>Appointment Details</h2>
            <a href="/admin/appointments" style="text-decoration:none;">⬅ Back</a>
        </div>

        <?php
        // UTC → Local for slot times
        $start = new DateTime($appointment['start_utc'], new DateTimeZone('UTC'));
        $end   = new DateTime($appointment['end_utc'], new DateTimeZone('UTC'));

        $start->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $end->setTimezone(new DateTimeZone(date_default_timezone_get()));

        // created_at is ALREADY local (MySQL NOW())
        $createdAt = null;
        if (!empty($appointment['created_at'])) {
            $createdAt = new DateTime($appointment['created_at']);
        }
        ?>


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
                    <td><?= $start->format('d M Y, h:i A') ?></td>
                </tr>

                <tr>
                    <th>End Time</th>
                    <td><?= $end->format('d M Y, h:i A') ?></td>
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
                    <td><?= $createdAt ? $createdAt->format('d M Y, h:i A') : '-' ?></td>
                </tr>
            </table>
        </div>

    </div>
<?php include __DIR__ . '/../layout/footer.php'; ?>
</body>

</html>