<?php
$title = 'Doctor Details';

include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main">

    <div class="topbar">
        <h2>Doctor Details</h2>
    </div>

    <div class="panel">

        <table class="table">
            <tr>
                <th style="width: 200px;">Name</th>
                <td><?= htmlspecialchars($doctor['full_name'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
            </tr>

            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($doctor['email'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
            </tr>

            <tr>
                <th>Mobile</th>
                <td><?= htmlspecialchars($doctor['mobile'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
            </tr>

            <tr>
                <th>Status</th>
                <td>
                    <?php
                        $status = $doctor['status'] ?? 'pending';
                    ?>
                    <span class="status <?= htmlspecialchars(strtolower($status), ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars(ucfirst($status), ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </td>
            </tr>
        </table>

        <div style="margin-top:20px;">
            <a href="/admin/doctors" class="btn">← Back to Doctors</a>
        </div>

    </div>

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
