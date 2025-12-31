<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$title = 'Doctor Availability';
include __DIR__ . '/../layout/header.php';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/doctor_availability.css">

<div class="availability-container">

    <div class="availability-header">
        <h2>Set Availability</h2>
        <p>Select date and time to mark availability or block slots</p>
    </div>

    <button id="openForm" class="primary-btn"> Add Availability</button>

    <!-- FORM (HIDDEN INITIALLY) -->
    <form id="availabilityForm" class="availability-form" style="display:none;">

        <div class="form-group">
            <label>Date</label>
            <input type="date" id="date" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Start Time</label>
                <input type="time" id="start" required>
            </div>

            <div class="form-group">
                <label>End Time</label>
                <input type="time" id="end" required>
            </div>
        </div>

        <div class="form-group">
            <label>Slot Type</label>
            <select id="type">
                <option value="available">Available</option>
                <option value="blocked">Blocked</option>
            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="save-btn">Save Slot</button>
            <button type="button" id="cancelForm" class="cancel-btn">Cancel</button>
        </div>

    </form>

</div>

<script src="<?= BASE_URL ?>/assets/js/doctor_availability.js"></script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
