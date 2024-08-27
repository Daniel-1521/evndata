<?php
// logout.php
session_start(); // Start the session to access session variables

// Destroy all session data
session_unset();
session_destroy();

// Redirect to index.html
header("Location: main/index.html");
exit();
