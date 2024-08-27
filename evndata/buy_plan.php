<?php
require_once 'error_handler.php';
require_once 'config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $db = db_connect();

    $plan_id = filter_input(INPUT_POST, 'plan_id', FILTER_SANITIZE_NUMBER_INT);

    $stmt = $db->prepare("SELECT * FROM internet_plans WHERE id = ?");
    $stmt->execute([$plan_id]);
    $plan = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($plan) {
        $stmt = $db->prepare("SELECT wallet_balance FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user['wallet_balance'] >= $plan['price']) {
            $db->beginTransaction();

            $stmt = $db->prepare("UPDATE users SET wallet_balance = wallet_balance - ? WHERE id = ?");
            $stmt->execute([$plan['price'], $_SESSION['user_id']]);

            $stmt = $db->prepare("INSERT INTO transactions (user_id, plan_id, amount, status) VALUES (?, ?, ?, 'COMPLETED')");
            $stmt->execute([$_SESSION['user_id'], $plan_id, $plan['price']]);

            $db->commit();

            $_SESSION['message'] = "Successfully purchased " . $plan['name'] . " plan";
        } else {
            $_SESSION['error'] = "Insufficient wallet balance";
        }
    } else {
        $_SESSION['error'] = "Invalid plan selected";
    }

    header("Location: dashboard.php");
    exit();
}
