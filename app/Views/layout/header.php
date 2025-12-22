<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'WellCare Clinic' ?></title>

    <link rel="stylesheet" href="/../assets/css/layout.css">
    <link rel="icon" type="image/png" href="/../assets/images/favicon.png">
    <script>
        function toggleMenu() {
            document.getElementById('navMenu').classList.toggle('show');
        }
    </script>
</head>

<?php
$user = $_SESSION['user'] ?? null;
$username = $user['name'] ?? '';
?>

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
                <div class="profile-dropdown-wrapper">
                    <div class="profile-trigger">
                        <img src="/../assets/images/user.png" alt="User">
                        <!-- <span><?= htmlspecialchars($username) ?></span> -->
                    </div>

                    <div class="profile-dropdown">
                        <a href="/patient/profile" class="dropdown-item">
                            <img src="/assets/images/user-details.png">
                            Profile Details
                        </a>

                        <a href="/appointments/create" class="dropdown-item">
                            <img src="/assets/images/add-user.png">
                            Book Appointment
                        </a>

                        <a href="/auth/logout" class="dropdown-item">
                            <img src="/assets/images/quit.png">
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