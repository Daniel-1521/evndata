<?php
define('PAYSTACK_SECRET_KEY', 'pk_test_4d04dc2f569e5e7bacdfaafddb05b483891d7305');

function initiatePayment($email, $phone, $amount)
{
    $url = "https://api.paystack.co/transaction/initialize";
    $data = [
        'email' => $email,
        'phone' => $phone,
        'amount' => $amount * 100,
        'callback_url' => 'https://yourwebsite.com/topup_callback.php'  // Set your callback URL
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . PAYSTACK_SECRET_KEY,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
}

function verifyPayment($reference)
{
    $url = "https://api.paystack.co/transaction/verify/$reference";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . PAYSTACK_SECRET_KEY
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response);
}
