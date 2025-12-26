<?php
$title = 'Add Specialization';
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/specialization.css">

<div class="main">
    <div class="topbar">
        <h2>Add Specialization</h2>
    </div>

    <div class="panel">
        <form id="specializationForm">

            <label>Specialization Name</label>
            <input type="text" name="name" id="name" required>

            <label>Description</label>
            <textarea name="description" id="description" rows="4"></textarea>

            <div style="margin-top: 10px;">
                <button type="submit" class="btn success">Save</button>
                <a href="/admin/specializations" class="btn">Cancel</a>
            </div>

            <div id="formMessage"></div>
        </form>
    </div>
</div>

<script src="<?= BASE_URL ?>/assets/js/specialization.js"></script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
