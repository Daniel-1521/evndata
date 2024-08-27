<?php


require_once 'error_handler.php';
define('DB_HOST', 'localhost');
define('DB_NAME', 'internet_data_app');
define('DB_USER', 'root');
define('DB_PASS', '');
define('PAYSTACK_SECRET_KEY', 'sk_test_your_secret_key');
define('PAYSTACK_PUBLIC_KEY', 'pk_test_4d04dc2f569e5e7bacdfaafddb05b483891d7305');

session_start();

function db_connect() {
    try {
        $db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}

function is_admin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
}

function require_admin() {
    require_login();
    if (!is_admin()) {
        header("Location: dashboard.php");
        exit();
    }
}