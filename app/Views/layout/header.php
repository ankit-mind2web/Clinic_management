<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'WellCare Clinic' ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/layout.css">
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>/assets/images/favicon.png">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.BASE_URL = "<?= BASE_URL ?>";
    </script>

    <script>
        function toggleMenu() {
            document.getElementById('navMenu').classList.toggle('show');
        }

        function toggleProfile(e) {
            e.stopPropagation();
            document.getElementById('profileDropdown').classList.toggle('open');
        }

        document.addEventListener('click', function () {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown) dropdown.classList.remove('open');
        });
    </script>
</head>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$user = $_SESSION['user'] ?? null;
$username = $user['full_name'] ?? ($user['name'] ?? '');
$initial = $username ? strtoupper($username[0]) : '';
?>

<body>

<header class="site-header">
    <div class="header-container">

        <!-- LOGO -->
        <div class="logo">
            <a href="<?= BASE_URL ?>/">
                <img src="<?= BASE_URL ?>/assets/images/wellcarelogo.png" alt="WellCare Clinic Logo">
            </a>
        </div>

        <!-- HAMBURGER -->
        <div class="menu-toggle" onclick="toggleMenu()">☰</div>

        <!-- NAV -->
        <nav class="nav" id="navMenu">
            <a href="<?= BASE_URL ?>/">Home</a>
            <a href="<?= BASE_URL ?>/services">Services</a>
            <a href="<?= BASE_URL ?>/contact">Contact</a>

            <?php if ($user): ?>
                <!-- PROFILE DROPDOWN -->
                <div class="profile-wrapper">

                    <!-- Avatar -->
                    <button class="profile-toggle" onclick="toggleProfile(event)">
                        <?= htmlspecialchars($initial) ?>
                    </button>

                    <!-- Dropdown -->
                    <div class="profile-dropdown" id="profileDropdown">

                        <div class="profile-name">
                            <img src="<?= BASE_URL ?>/assets/images/user.png" alt="User">
                            <?= htmlspecialchars($username) ?>
                        </div>

                        <?php if ($user['role'] === 'patient'): ?>
                            <a href="<?= BASE_URL ?>/patient/profile" class="dropdown-item">
                                <img src="<?= BASE_URL ?>/assets/images/user-details.png" class="dropdown-icon">
                                Profile Details
                            </a>

                            <a href="<?= BASE_URL ?>/patient/appointments" class="dropdown-item">
                                <img src="<?= BASE_URL ?>/assets/images/add-user.png" class="dropdown-icon">
                                Book Appointment
                            </a>
                        <?php endif; ?>

                        <?php if ($user['role'] === 'doctor'): ?>
                            <a href="<?= BASE_URL ?>/doctor/dashboard" class="dropdown-item">
                                <img src="<?= BASE_URL ?>/assets/images/user-details.png" class="dropdown-icon">
                                Doctor Dashboard
                            </a>
                        <?php endif; ?>

                        <?php if ($user['role'] === 'admin'): ?>
                            <a href="<?= BASE_URL ?>/admin/dashboard" class="dropdown-item">
                                <img src="<?= BASE_URL ?>/assets/images/user-details.png" class="dropdown-icon">
                                Admin Dashboard
                            </a>
                        <?php endif; ?>

                        <a href="<?= BASE_URL ?>/auth/logout" class="dropdown-item">
                            <img src="<?= BASE_URL ?>/assets/images/quit.png" class="dropdown-icon">
                            Logout
                        </a>

                    </div>
                </div>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/auth/login">Login</a>
            <?php endif; ?>
        </nav>

    </div>
</header>

<main class="main-content">
