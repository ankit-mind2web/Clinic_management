<?php
require_once __DIR__ . '/../../../Helpers/Datehelper.php';
$title = 'Patients';
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>
<link rel="stylesheet" href="/assets/css/admin-search.css">

<div class="main">
    <div class="topbar">
        <h2>Patients</h2>
        <div class="search-box">
            <form id="searchForm" method="GET" action="/admin/patients" style="display:flex; align-items:center;">
                <input 
                    type="text" 
                    id="searchInput"
                    name="search" 
                    value="<?= htmlspecialchars($search ?? '') ?>" 
                    placeholder="Search patients..."
                >
                <button type="button" id="clearSearch">&times;</button>
            </form>
        </div>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($patients)): ?>
                    <?php foreach ($patients as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['full_name']) ?></td>
                            <td><?= htmlspecialchars($p['email']) ?></td>
                            <td><?= htmlspecialchars($p['mobile']) ?></td>
                            <td><?= $p['gender'] ?? '—' ?></td>
                            <td><?= DateHelper::calculateAge($p['dob'] ?? null) ?></td>
                            <td>
                                <span class="status <?= $p['status'] ?>">
                                    <?= ucfirst($p['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="center">No patients found</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?= $pagination ?? '' ?>
    </div>
</div>

<script src="/assets/js/admin-search.js"></script>
<?php include __DIR__ . '/../layout/footer.php'; ?>
