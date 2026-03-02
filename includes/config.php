<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'azka_tenda';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Midtrans Configuration
$server_key = 'SB-Mid-server-YOUR_SERVER_KEY_HERE';
$client_key = 'SB-Mid-client-YOUR_CLIENT_KEY_HERE';
$is_production = false;
$api_url = $is_production ? 'https://app.midtrans.com/snap/v1/transactions' : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
?>
