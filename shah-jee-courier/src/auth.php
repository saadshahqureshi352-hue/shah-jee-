<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if user session does not exist
function require_login() {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

// Redirect to dashboard if user is already logged in
function require_guest() {
    if (isset($_SESSION['user'])) {
        header('Location: dashboard.php');
        exit;
    }
}

function logout() {
    unset($_SESSION['user']);
    session_destroy();
    header('Location: login.php');
    exit;
}
