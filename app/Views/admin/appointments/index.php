<?php
$title = 'Appointments';
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main">

    <!-- TOP BAR -->
    <div class="topbar">
        <h2>All Appointments</h2>

        <div style="display:flex; gap:10px; align-items:center;">

            <!-- SEARCH -->
            <div class="search-box">
                <form id="searchForm" method="GET" action="/admin/appointments" data-live-search="true" style="display:flex; align-items:center;">
                    <input
                        type="text"
                        name="search"
                        id="searchInput"
                        value="<?= htmlspecialchars($search ?? '') ?>"
                        placeholder="Search appointments..."
                        data-search-columns="1,2,3">
                    <button type="button" id="clearSearch">&times;</button>
                </form>
            </div>

            <!-- FILTER -->
            <select id="sortSelect" class="filter-select">
                <option value="">Sort by</option>
                <option value="name_asc">Name ↑</option>
                <option value="name_desc">Name ↓</option>
                <option value="date_asc">Date ↑</option>
                <option value="date_desc">Date ↓</option>
            </select>

        </div>
    </div>

    <!-- PANEL -->
    <div class="panel">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody id="tableBody">
                <?php if (empty($appointments)): ?>
                    <tr>
                        <td colspan="6">No appointments found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($appointments as $row): ?>
                        <?php
                        $start = new DateTime($row['start_utc'], new DateTimeZone('UTC'));
                        $start->setTimezone(new DateTimeZone(date_default_timezone_get()));
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['patient_name']) ?></td>
                            <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                            <td><?= $start->format('d M Y, h:i A') ?></td>
                            <td>
                                <span class="status <?= strtolower($row['status']) ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="/admin/appointments/show?id=<?= $row['id'] ?>" class="btn info">
                                    View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- COMMON PAGINATION -->
        <?= $pagination ?? '' ?>

    </div>
</div>

<!-- COMMON SEARCH + FILTER JS -->
<link rel="stylesheet" href="/assets/css/admin-search.css">
<script src="/assets/js/common_sort.js"></script>
<script src="/assets/js/admin-search.js"></script>
<script src="/assets/js/appointment_filter.js"></script>

<?php include __DIR__ . '/../layout/footer.php'; ?>