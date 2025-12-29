<?php
require_once __DIR__ . '/../../../Helpers/Datehelper.php';
$title = 'Doctors';
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>
<link rel="stylesheet" href="/../assets/css/admin-doctors.css">

<div class="main">

    <div class="topbar">
        <h2>Doctors</h2>

        <div style="display:flex; gap:10px; align-items:center;">

            <!-- SEARCH -->
            <div class="search-box">
                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search doctors..."
                    data-search-columns="0,1,2,3"
                >
                <button type="button" id="clearSearch">&times;</button>
            </div>

            <!-- SORT -->
            <select id="sortSelect" class="filter-select">
                <option value="">Sort by</option>
                <option value="name_asc">Name ↑</option>
                <option value="name_desc">Name ↓</option>
                <option value="age_asc">Age ↑</option>
                <option value="age_desc">Age ↓</option>
                <option value="date_asc">Date ↑</option>
                <option value="date_desc">Date ↓</option>
            </select>
        </div>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table doctors-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Age</th>
                        <th class="center">Status</th>
                        <th class="center">Action</th>
                    </tr>
                </thead>

                <tbody id="tableBody">
                <?php if (!empty($doctors)): ?>
                    <?php foreach ($doctors as $doc): ?>
                        <tr data-date="<?= htmlspecialchars($doc['created_at'] ?? '') ?>">
                            <td><?= htmlspecialchars($doc['full_name']) ?></td>
                            <td><?= htmlspecialchars($doc['email']) ?></td>
                            <td><?= DateHelper::calculateAge($doc['dob'] ?? null) ?></td>

                            <td class="center">
                                <span class="status-badge <?= htmlspecialchars($doc['status']) ?>">
                                    <?= ucfirst($doc['status']) ?>
                                </span>
                            </td>

                            <td class="center">
                                <a href="/admin/doctors/view?id=<?= $doc['id'] ?>"
                                   class="btn info">View</a>

                                <?php if ($doc['status'] === 'active'): ?>
                                    <form method="POST"
                                          action="/admin/doctors/block"
                                          style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $doc['id'] ?>">
                                        <button class="btn danger">Block</button>
                                    </form>

                                <?php elseif ($doc['status'] === 'blocked'): ?>
                                    <form method="POST"
                                          action="/admin/doctors/unblock"
                                          style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $doc['id'] ?>">
                                        <button class="btn success">Unblock</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="center">No doctors found</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php include __DIR__ . '/../../partials/pagination.php'; ?>
    </div>
</div>
<script src="/assets/js/common_sort.js"></script>
<?php include __DIR__ . '/../layout/footer.php'; ?>
