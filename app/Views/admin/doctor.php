<!DOCTYPE html>
<html>
<head>
    <title>Doctors</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body>

<div class="admin-wrapper">
    <aside class="sidebar">
        <a href="/admin/dashboard">🏠</a>
        <a href="/admin/doctors">👨‍⚕️</a>
        <a href="/auth/logout">🚪</a>
    </aside>

    <main class="admin-main">
        <h2>Doctors</h2>

        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php foreach ($doctors as $d): ?>
                <tr>
                    <td><?= $d['full_name'] ?></td>
                    <td><?= $d['email'] ?></td>
                    <td><?= ucfirst($d['status']) ?></td>
                    <td>
                        <?php if ($d['status'] === 'pending'): ?>
                            <a href="/admin/approve-doctor/<?= $d['id'] ?>" class="btn green">Approve</a>
                            <a href="/admin/reject-doctor/<?= $d['id'] ?>" class="btn red">Reject</a>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

    </main>
</div>

</body>
</html>
