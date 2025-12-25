<?php
$title = 'Doctors';
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>
<link rel="stylesheet" href="/assets/css/admin-doctors.css">

<div class="main">

    <div class="topbar">
        <h2>Doctors</h2>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table doctors-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="center">Status</th>
                        <th class="center">Action</th>
                    </tr>
                </thead>
                <tbody>

                <?php if (!empty($doctors)): ?>
                    <?php foreach ($doctors as $doc): ?>
                        <tr>
                            <td><?= htmlspecialchars($doc['full_name']) ?></td>
                            <td><?= htmlspecialchars($doc['email']) ?></td>

                            <td class="center">
                                <span class="status-badge <?= $doc['status'] ?>">
                                    <?= ucfirst($doc['status']) ?>
                                </span>
                            </td>

                            <td class="center">
                                <!-- VIEW BUTTON -->
                                <a href="/admin/doctors/view?id=<?= $doc['id'] ?>"
                                   class="btn info">
                                    View
                                </a>

                                <!-- BLOCK BUTTON -->
                                <?php if ($doc['status'] === 'active'): ?>
                                    <form method="POST"
                                          action="/admin/doctors/block"
                                          style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $doc['id'] ?>">
                                        <button class="btn danger">Block</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="center">No doctors found</td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
