<?php
require_once 'error_handler.php';
require_once 'config.php';
require_login();

$pageTitle = 'Dashboard';
include 'header.php';

$db = db_connect();

$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $db->prepare("SELECT * FROM internet_plans WHERE data_amount BETWEEN 1 AND 5");
$stmt->execute();
$plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <h1 class="mt-4">Welcome, <?php echo htmlspecialchars($user['first_name']); ?>!</h1>
    <p>Your wallet balance: GHS <?php echo number_format($user['wallet_balance'], 2); ?></p>

    <h2 class="mt-4">Available Plans</h2>
    <div class="row">
        <?php foreach ($plans as $plan): ?>
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($plan['name']); ?></h5>
                        <p class="card-text"><?php echo $plan['data_amount']; ?>GB for GHS <?php echo number_format($plan['price'], 2); ?></p>
                        <form action="buy_plan.php" method="post">
                            <input type="hidden" name="plan_id" value="<?php echo $plan['id']; ?>">
                            <button type="submit" class="btn btn-primary">Buy Now</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<link rel="stylesheet" href="styles.css">
<?php include 'footer.php'; ?>