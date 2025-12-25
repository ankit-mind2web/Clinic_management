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
                <td><?= htmlspecialchars($doctor['full_name']) ?></td>
            </tr>

            <tr>
                <th>Email</th>
                <td><?= htmlspecialchars($doctor['email']) ?></td>
            </tr>

            <tr>
                <th>Mobile</th>
                <td><?= htmlspecialchars($doctor['mobile'] ?? '-') ?></td>
            </tr>

            <tr>
                <th>Status</th>
                <td>
                    <span class="status <?= strtolower($doctor['status']) ?>">
                        <?= ucfirst($doctor['status']) ?>
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
