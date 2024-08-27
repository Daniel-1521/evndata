<?php
require_once 'error_handler.php';
require_once 'config.php';
require_admin();

$db = db_connect();

$stmt = $db->query("SELECT t.id, u.email, p.name AS plan_name, t.amount, t.status, t.created_at 
                    FROM transactions t 
                    JOIN users u ON t.user_id = u.id 
                    JOIN internet_plans p ON t.plan_id = p.id 
                    ORDER BY t.created_at DESC");
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=transactions.csv');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, array('ID', 'User Email', 'Plan', 'Amount', 'Status', 'Date'));

// Loop over the rows, outputting them
foreach ($transactions as $transaction) {
    fputcsv($output, $transaction);
}

fclose($output);
exit();
