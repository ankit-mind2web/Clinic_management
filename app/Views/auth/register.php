<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth.css">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Expose BASE_URL -->
    <script>
        window.BASE_URL = "<?= BASE_URL ?>";
    </script>
</head>
<body>
<?php require_once __DIR__ . '/../layout/header.php'; ?>
<div class="auth-wrapper">
    <div class="auth-container">

        <h2>Register</h2>

        <div id="registerError" class="auth-error" style="display:none;"></div>

        <form id="registerForm">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="mobile" placeholder="Mobile" required>
            <input type="password" name="password" placeholder="Password" required>

            <select name="role" required>
                <option value="">Select Role</option>
                <option value="patient">Patient</option>
                <option value="doctor">Doctor</option>
            </select>

            <button type="submit">Register</button>
        </form>

        <div class="auth-footer">
            Already registered?
            <a href="<?= BASE_URL ?>/auth/login">Login</a>
        </div>

    </div>
</div>

<!-- JS -->
<script src="<?= BASE_URL ?>/assets/js/register.js"></script>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
