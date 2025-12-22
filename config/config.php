<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../app/helpers/auth_helper.php';
require_once __DIR__ . '/../app/helpers/rbac_helper.php';
require_once __DIR__ . '/../app/helpers/timezone_helper.php';

date_default_timezone_set('UTC');
