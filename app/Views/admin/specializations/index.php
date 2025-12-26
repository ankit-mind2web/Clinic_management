<?php
$title = 'Specializations';
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/specialization.css">

<div class="main">

    <div class="topbar">
        <h2>Specializations</h2>

        <div style="display:flex; gap:10px; align-items:center;">

            <div class="search-box">
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search specialization...">
                <button type="button" id="clearSearch">&times;</button>
            </div>

            <a href="/admin/specializations/create" class="btn success">+ Add</a>
        </div>
    </div>


    <div class="panel">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="specializationTable">
                <?php if (!empty($specializations)): ?>
                    <?php foreach ($specializations as $s): ?>
                        <tr id="row-<?= $s['id'] ?>">
                            <td><?= htmlspecialchars($s['name']) ?></td>
                            <td><?= htmlspecialchars($s['description'] ?? '') ?></td>
                            <td>
                                <a href="/admin/specializations/edit?id=<?= $s['id'] ?>" class="btn info">Edit</a>
                                <button class="btn danger deleteBtn" data-id="<?= $s['id'] ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No specializations found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="<?= BASE_URL ?>/assets/js/specialization.js"></script>

<?php include __DIR__ . '/../layout/footer.php'; ?>