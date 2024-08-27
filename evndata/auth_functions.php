<?php
// Function to check if a user is logged in
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

// Function to check if the current user is an admin
function is_admin()
{
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
}

// Function to log in a user
function login_user($user_id, $is_admin = false)
{
    $_SESSION['user_id'] = $user_id;
    $_SESSION['is_admin'] = $is_admin;
}

// Function to log out a user
function logout_user()
{
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Function to get the user ID of the currently logged-in user
function get_user_id()
{
    return $_SESSION['user_id'] ?? null;
}

// Function to ensure that only logged-in users can access a page
function require_login()
{
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}

// Function to ensure that only admin users can access a page
function require_admin()
{
    if (!is_admin()) {
        header("Location: index.php"); // Redirect to a page for non-admin users
        exit();
    }
}
