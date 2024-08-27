<?php
function sanitize_input($input)
{
    return htmlspecialchars(strip_tags(trim($input)));
}

function validate_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false; // Adjusted to return true/false
}

function validate_phone($phone)
{
    return preg_match("/^\+?[0-9\s\-()]{10,15}$/", $phone) === 1;
}

function validate_password($password)
{
    // Password must be at least 8 characters long
    if (strlen($password) < 8) {
        return false;
    }

    // Check for at least one uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
        return false;
    }

    // Check for at least one lowercase letter
    if (!preg_match('/[a-z]/', $password)) {
        return false;
    }

    // Check for at least one digit
    if (!preg_match('/\d/', $password)) {
        return false;
    }

    // Check for at least one special character
    if (!preg_match('/[^a-zA-Z\d]/', $password)) {
        return false;
    }

    return true;
}




