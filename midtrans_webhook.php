<?php
include 'includes/config.php';

$payload = file_get_contents('php://input');
$notification = json_decode($payload, true);

if ($notification) {
    $order_id = $notification['order_id'];
    $transaction_status = $notification['transaction_status'];
    $fraud_status = $notification['fraud_status'];

    // Hash verification (simplified for example)
    $server_key = $server_key;
    $hash = hash("sha512", $notification['order_id'] . $notification['status_code'] . $notification['gross_amount'] . $server_key);

    if ($hash == $notification['signature_key']) {
        $status = 'Pending';
        if ($transaction_status == 'capture') {
            if ($fraud_status == 'challenge') {
                $status = 'Challenge';
            } else if ($fraud_status == 'accept') {
                $status = 'Success';
            }
        } else if ($transaction_status == 'settlement') {
            $status = 'Success';
        } else if ($transaction_status == 'cancel' || $transaction_status == 'deny' || $transaction_status == 'expire') {
            $status = 'Failed';
        } else if ($transaction_status == 'pending') {
            $status = 'Pending';
        }

        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
        $stmt->bind_param("ss", $status, $order_id);
        $stmt->execute();

        http_response_code(200);
        echo "OK";
    } else {
        http_response_code(400);
        echo "Invalid Signature";
    }
} else {
    http_response_code(400);
    echo "Bad Request";
}
?>
