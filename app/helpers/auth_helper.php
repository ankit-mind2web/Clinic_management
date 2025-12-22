<?php

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
