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
                        <th>Action</th> 
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($appointments as $a): ?>
                    <?php
                        $now   = new DateTime('now', new DateTimeZone(date_default_timezone_get()));

                        $start = new DateTime($a['start_utc'], new DateTimeZone('UTC'));
                        $end   = new DateTime($a['end_utc'], new DateTimeZone('UTC'));

                        $start->setTimezone(new DateTimeZone(date_default_timezone_get()));
                        $end->setTimezone(new DateTimeZone(date_default_timezone_get()));

                        $end->setTimezone(new DateTimeZone(date_default_timezone_get()));

                        // Status is now handled by backend auto-completion logic
                        $status = $a['status'];
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($a['patient_name']) ?></td>
                        <td><?= htmlspecialchars($a['patient_email']) ?></td>
                        <td><?= $start->format('d M Y') ?></td>
                        <td><?= $start->format('h:i A') ?> - <?= $end->format('h:i A') ?></td>

                        <td>
                            <span class="status <?= htmlspecialchars($status) ?>">
                                <?= ucfirst($status) ?>
                            </span>
                        </td>

                        <td>
                            <?php if ($status === 'pending'): ?>
                                <form method="post" action="/doctor/appointments/confirm" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                                    <button class="btn success">Confirm</button>
                                </form>

                                <form method="post" action="/doctor/appointments/cancel" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                                    <button class="btn danger">Cancel</button>
                                </form>

                            <?php elseif ($status === 'confirmed'): ?>
                                <form method="post" action="/doctor/appointments/cancel" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
                                    <button class="btn danger">Cancel</button>
                                </form>

                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?= $pagination ?? '' ?>
    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
</body>
</html>
