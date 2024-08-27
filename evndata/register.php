<?php
require_once 'error_handler.php';
require_once 'config.php';
require_once 'test/validation.php';

// Check for payment verification (example code, adjust as needed)
if (isset($_POST['payment_verified']) && $_POST['payment_verified'] == 'yes') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize input
        $first_name = htmlspecialchars($_POST['first_name'], ENT_QUOTES, 'UTF-8');
        $last_name = htmlspecialchars($_POST['last_name'], ENT_QUOTES, 'UTF-8');
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $phone_number = htmlspecialchars($_POST['phone_number'], ENT_QUOTES, 'UTF-8');
        $business_name = htmlspecialchars($_POST['business_name'], ENT_QUOTES, 'UTF-8');
        $password = $_POST['password'];

        $errors = [];

        // Validate inputs
        if (empty($first_name) || empty($last_name) || empty($email) || empty($phone_number) || empty($business_name) || empty($password)) {
            $errors[] = "All fields are required.";
        }

        if (!validate_email($email)) {
            $errors[] = "Invalid email address.";
        }

        if (!validate_phone($phone_number)) {
            $errors[] = "Invalid phone number.";
        }

        if (!validate_password($password)) {
            $errors[] = "Password must be at least 8 characters long.";
        }

        if (empty($errors)) {
            $db = db_connect();

            // Check for duplicate email
            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = "This email is already registered.";
            } else {
                // Hash the password and insert into the database
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (first_name, last_name, email, phone_number, business_name, password_hash) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$first_name, $last_name, $email, $phone_number, $business_name, $hashed_password]);

                // Redirect to login page after successful registration
                header("Location: login.php");
                exit();
            }
        }
    }
} else {
    // Handle case where payment is not verified
    // You might want to redirect the user or show an error message
    echo "Payment verification failed. Please try again.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://js.paystack.co/v1/inline.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2>Register</h2>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <form id="registration-form" method="post">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" name="phone_number" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Business Name</label>
                <input type="text" name="business_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button type="button" class="btn btn-primary" onclick="payWithPaystack()">Pay GHS 20 and Register</button>
        </form>

        <!-- Sign In Button -->
        <div class="text-center mt-3">
            <p>If you already have an account, <a href="login.php" class="btn btn-link">Sign in here</a>.</p>
        </div>
    </div>

    <script>
        function payWithPaystack() {
            var handler = PaystackPop.setup({
                key: 'pk_test_4d04dc2f569e5e7bacdfaafddb05b483891d7305', // Your test public key
                email: document.querySelector('input[name="email"]').value,
                amount: 2000, // Amount in kobo (GHS 20.00)
                currency: 'GHS',
                callback: function(response) {
                    // After payment, send form data to server
                    var form = document.getElementById('registration-form');
                    var paymentInput = document.createElement('input');
                    paymentInput.setAttribute('type', 'hidden');
                    paymentInput.setAttribute('name', 'payment_verified');
                    paymentInput.setAttribute('value', 'yes');
                    form.appendChild(paymentInput);
                    form.submit();
                },
                onClose: function() {
                    alert('Payment was not completed.');
                }
            });
            handler.openIframe();
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>