<?php
$title = 'Add Specialization';
include __DIR__ . '/../layout/header.php';
include __DIR__ . '/../layout/sidebar.php';
?>

<div class="main">
    <div class="topbar"><h2>Add Specialization</h2></div>

    <div class="panel">
        <form method="POST">
            <label>Name</label><br>
            <input type="text" name="name" required>
            <br><br>
            <button class="btn success">Save</button>
            <a href="/admin/specializations" class="btn">Cancel</a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
