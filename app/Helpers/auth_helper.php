<?php
namespace App\Helpers;
function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header("Location: /auth/login");
        exit;
    }
}

function logout(): void
{
    session_unset();
    session_destroy();
    header("Location: /auth/login");
    exit;
}

function requireVerifiedEmail()
{
    if (!isset($_SESSION['user'])) {
        header('Location: /auth/login');
        exit;
    }

    if ($_SESSION['user']['email_verified'] == 0) {
        header('Location: /patient/profile');
        exit;
    }
}
function requirePatient()
{
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'patient') {
        header('Location: /');
        exit;
    }
}
