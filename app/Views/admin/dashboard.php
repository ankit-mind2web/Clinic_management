<?php
$title = 'Admin Dashboard';

include __DIR__ . '/../admin/layout/header.php';
include __DIR__ . '/../admin/layout/sidebar.php';
?>

<div class="main">

    <div class="topbar">
        <h2>Dashboard</h2>
        <div class="admin">Admin Panel</div>
    </div>

    <div class="stats">
        <div class="stat-box bg-green">
            <h4>TOTAL DOCTORS</h4>
            <p><?= $totalDoctors ?? 0 ?></p>
        </div>

        <div class="stat-box bg-purple">
            <h4>PENDING DOCTORS</h4>
            <p><?= $pendingDoctors ?? 0 ?></p>
        </div>

        <div class="stat-box bg-blue">
            <h4>APPOINTMENTS</h4>
            <p><?= $totalAppointments ?? 0 ?></p>
        </div>

        <div class="stat-box bg-orange">
            <h4>SPECIALIZATIONS</h4>
            <p><?= $totalSpecializations ?? 0 ?></p>
        </div>
    </div>

    <div class="panels">

        <div class="panel">
            <h3>Recent Appointments</h3>

            <table class="table">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentAppointments)): ?>
                        <?php foreach ($recentAppointments as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                                <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                                <td>
                                    <span class="status <?= htmlspecialchars(strtolower($row['status'])) ?>">
                                        <?= ucfirst($row['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No recent appointments</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="panel">
            <h3>All Doctors</h3>

            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($doctors)): ?>
                        <?php foreach ($doctors as $doc): ?>
                            <tr>
                                <td><?= htmlspecialchars($doc['full_name']) ?></td>
                                <td>
                                    <span class="status <?= htmlspecialchars(strtolower($doc['status'])) ?>">
                                        <?= ucfirst($doc['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No doctors found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>



    </div>

</div>

<?php include __DIR__ . '/../admin/layout/footer.php'; ?>