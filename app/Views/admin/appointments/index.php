<?php
$title = 'Appointments';
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Appointments</title>
    <link rel="stylesheet" href="/assets/css/admin-dashboard.css">
</head>
<body>

<div class="main">

    <div class="topbar">
        <h2>All Appointments</h2>
    </div>

    <div class="panel">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            <?php if (empty($appointments)): ?>
                <tr>
                    <td colspan="6">No appointments found</td>
                </tr>
            <?php else: ?>
                <?php foreach ($appointments as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['patient_name']) ?></td>
                        <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                        <td><?= htmlspecialchars($row['start_utc']) ?></td>
                        <td>
                            <span class="status <?= strtolower($row['status']) ?>">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="/admin/appointments/show?id=<?= $row['id'] ?>">
                                View
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            </tbody>
        </table>
    </div>

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
