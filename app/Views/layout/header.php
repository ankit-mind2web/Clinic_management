<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'WellCare Clinic' ?></title>

    <link rel="stylesheet" href="/assets/css/layout.css">
    <link rel="icon" type="image/png" href="/assets/images/favicon.png">

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
$username = $user['name'] ?? '';
$initial = $username ? strtoupper($username[0]) : '';
?>

<body>

<header class="site-header">
    <div class="header-container">

        <!-- LOGO -->
        <div class="logo">
            <img src="/assets/images/wellcarelogo.png" alt="WellCare Clinic Logo">
        </div>

        <!-- HAMBURGER -->
        <div class="menu-toggle" onclick="toggleMenu()">☰</div>

        <!-- NAV -->
        <nav class="nav" id="navMenu">
            <a href="/">Home</a>
            <a href="/services">Services</a>
            <a href="/contact">Contact</a>

            <?php if ($user): ?>
                <!-- PROFILE DROPDOWN -->
                <div class="profile-wrapper">

                    <!-- avatar -->
                    <button class="profile-toggle" onclick="toggleProfile(event)">
                        <?= htmlspecialchars($initial) ?>
                    </button>

                    <!-- dropdown -->
                    <div class="profile-dropdown" id="profileDropdown">

                        <div class="profile-name">
                            <img src="/assets/images/user.png" alt="User">
                            <?= htmlspecialchars($username) ?>
                        </div>

                        <a href="/patient/profile" class="dropdown-item">
                            <img src="/assets/images/user-details.png" class="dropdown-icon">
                            Profile Details
                        </a>

                        <a href="/appointments/create" class="dropdown-item">
                            <img src="/assets/images/add-user.png" class="dropdown-icon">
                            Book Appointment
                        </a>

                        <a href="/auth/logout" class="dropdown-item">
                            <img src="/assets/images/quit.png" class="dropdown-icon">
                            Logout
                        </a>

                    </div>
                </div>
            <?php else: ?>
                <a href="/auth/login">Login</a>
            <?php endif; ?>
        </nav>

    </div>
</header>

<main class="main-content">
