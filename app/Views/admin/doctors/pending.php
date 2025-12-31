<?php
$title = 'Pending Doctors';
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>
<link rel="stylesheet" href="/assets/css/admin-doctors.css">

<div class="main">

    <div class="topbar">
        <h2>Pending Doctors</h2>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table doctors-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="center">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($doctors): foreach ($doctors as $d): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['full_name']) ?></td>
                        <td><?= htmlspecialchars($d['email']) ?></td>
                        <td class="center">
                            <div class="action-group">

                                <!-- APPROVE -->
                                <form method="POST" action="/admin/doctors/approve" class="inline-form">
                                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                    <button class="btn success">Approve</button>
                                </form>

                                <!-- REJECT -->
                                <form method="POST" action="/admin/doctors/reject"
                                      class="inline-form"
                                      onsubmit="return confirm('Reject this doctor?');">
                                    <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                    <button class="btn danger">Reject</button>
                                </form>

                            </div>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="3" class="center">No pending doctors</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
