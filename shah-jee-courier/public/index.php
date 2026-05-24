<?php
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/auth.php';

// Redirect to dashboard if logged in, otherwise require_login will redirect to login.php
require_login();
header('Location: dashboard.php');
exit;
