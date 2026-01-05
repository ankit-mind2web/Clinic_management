<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
define('BASE_URL', '');

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../app/Helpers/auth_helper.php';
require_once __DIR__ . '/../app/Helpers/rbac_helper.php';
require_once __DIR__ . '/../app/Helpers/timezone_helper.php';

date_default_timezone_set('Asia/Kolkata');
