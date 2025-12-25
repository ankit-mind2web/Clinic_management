<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/auth.css">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Expose BASE_URL to JS -->
    <script>
        window.BASE_URL = "<?= BASE_URL ?>";
    </script>
</head>
<body>

<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-container">

        <h2>Login</h2>

        <div id="loginError" class="auth-error" style="display:none;"></div>

        <form id="loginForm">
            <input type="text" name="login" placeholder="Email or Mobile" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <div class="auth-footer">
            Don't have an account?
            <a href="<?= BASE_URL ?>/auth/register">Register</a>
        </div>

    </div>
</div>

<!-- JS -->
<script src="<?= BASE_URL ?>/assets/js/login.js"></script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
</body>
</html>
