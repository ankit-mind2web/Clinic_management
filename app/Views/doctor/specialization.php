<?php
$title = 'Manage Specialization';
require_once __DIR__ . '/../layout/header.php';
?>

<link rel="stylesheet" href="/assets/css/doctor_specialization.css">

<div class="page-wrapper">
    <div class="card">
        <h2>My Specializations</h2>

        <?php if (!empty($message)): ?>
            <div class="msg"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <!-- EXISTING SPECIALIZATIONS -->
        <?php if (!empty($doctorSpecs)): ?>
            <table class="spec-table">
                <tr>
                    <th>Specialization</th>
                    <th>Experience</th>
                </tr>
                <?php foreach ($doctorSpecs as $spec): ?>
                    <tr>
                        <td><?= htmlspecialchars($spec['specialization_name']) ?></td>
                        <td><?= (int)$spec['experience'] ?> years</td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No specialization added yet.</p>
        <?php endif; ?>

        <hr>

        <!-- ADD SPECIALIZATION -->
        <h3>Add Specialization</h3>

        <form method="post" class="spec-form">

            <label>Specialization</label>
            <select name="specialization_id" required>
                <option value="">Select</option>
                <?php foreach ($specializations as $s): ?>
                    <option value="<?= $s['id'] ?>">
                        <?= htmlspecialchars($s['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Experience (Years)</label>
            <input type="number" name="experience" min="0" max="60" required>

            <button type="submit">Add</button>
        </form>

        <a href="/doctor/dashboard" class="btn-cancel">Back to Dashboard</a>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
