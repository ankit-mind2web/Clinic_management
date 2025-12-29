<table class="data-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Specialization</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($specializations as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><?= htmlspecialchars($s['name']) ?></td>
                <td>
                    <a href="/admin/specializations/edit?id=<?= $s['id'] ?>">Edit</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../../components/pagination.php'; ?>
