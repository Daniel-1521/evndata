<?php
require_once 'error_handler.php';
require_once 'config.php';

// Initialize cURL session
$curl = curl_init();
$reference = isset($_GET['reference']) ? $_GET['reference'] : '';

if (!$reference) {
    die('No reference supplied');
}

// Set cURL options
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "accept: application/json",
        "authorization: Bearer " . PAYSTACK_SECRET_KEY,
        "cache-control: no-cache"
    ],
));

// Execute cURL request and handle errors
$response = curl_exec($curl);
$err = curl_error($curl);

if ($err) {
    die('Curl returned error: ' . $err);
}

$tranx = json_decode($response);

if (!$tranx->status) {
    die('API returned error: ' . $tranx->message);
}

// Process the successful transaction
if ('success' == $tranx->data->status) {
    $amount = $tranx->data->amount / 100;  // Convert amount to GHS
    $user_id = $_SESSION['user_id'];  // Ensure user_id is set in session

    // Connect to the database
    $db = db_connect();
    $stmt = $db->prepare("UPDATE users SET wallet_balance = wallet_balance + ? WHERE id = ?");
    $stmt->execute([$amount, $user_id]);

    $_SESSION['message'] = "Top up of GHS " . number_format($amount, 2) . " successful";

    // Send receipt to user
    $to = $_SESSION['user_email'];  // Retrieve user email from session
    $subject = "Payment Receipt for Your Top Up";
    $message = "Dear User,\n\nYour top up of GHS " . number_format($amount, 2) . " was successful.\nThank you for using our service.\n\nBest regards,\nYour Company";
    $headers = "From: no-reply@yourwebsite.com";

    mail($to, $subject, $message, $headers);
}

// Redirect to dashboard
header("Location: dashboard.php");
exit();
