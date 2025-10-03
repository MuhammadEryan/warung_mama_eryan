<?php
include 'config.php';

function isAdminLoggedIn() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdminAuth() {
    if (!isAdminLoggedIn()) {
        header('Location: ../admin/login.php');
        exit;
    }
}

function redirectIfLoggedIn() {
    if (isAdminLoggedIn()) {
        header('Location: dashboard.php');
        exit;
    }
}
?>