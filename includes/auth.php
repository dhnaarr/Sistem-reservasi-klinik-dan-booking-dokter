<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


define('BASE_URL', '/klinik');


function require_login() {
    if (!isset($_SESSION['id_user'])) {
        header("Location: " . BASE_URL . "/auth/login.php");
        exit;
    }
}


function require_role($roles) {
    require_login();
    $roles = (array) $roles;
    if (!in_array($_SESSION['role'], $roles)) {
        http_response_code(403);
        die("Akses ditolak. Anda tidak berhak membuka halaman ini.");
    }
}


function dashboard_url($role) {
    return BASE_URL . "/" . $role . "/dashboard.php";
}
