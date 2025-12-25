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
                <tr>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Status</th>
                </tr>

                <?php if (!empty($recentAppointments)): ?>
                    <?php foreach ($recentAppointments as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['patient_name']) ?></td>
                            <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                            <td>
                                <span class="status <?= strtolower($row['status']) ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="3">No recent appointments</td></tr>
                <?php endif; ?>
            </table>
        </div>

        <div class="panel">
            <h3>Pending Doctors</h3>
            <table class="table">
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                </tr>

                <?php if (!empty($pendingDoctorList)): ?>
                    <?php foreach ($pendingDoctorList as $doc): ?>
                        <tr>
                            <td><?= htmlspecialchars($doc['full_name']) ?></td>
                            <td><span class="status pending">Pending</span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2">No pending doctors</td></tr>
                <?php endif; ?>
            </table>
        </div>

    </div>

</div>

<?php include __DIR__ . '/../admin/layout/footer.php'; ?>
