<?php
$title = 'Specializations';
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main">
    <div class="topbar">
        <h2>Specializations</h2>
        <a href="/admin/specializations/create" class="btn success">+ Add</a>
    </div>

    <div class="panel">
        <table class="table">
            <tr>
                <th>Name</th>
                <th>Action</th>
            </tr>

            <?php if ($specializations): foreach ($specializations as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['name']) ?></td>
                    <td>
                        <a href="/admin/specializations/edit?id=<?= $s['id'] ?>" class="btn info">Edit</a>
                        <form method="POST" action="/admin/specializations/delete" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $s['id'] ?>">
                            <button class="btn danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr><td colspan="2">No specializations found</td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
