<?php
session_start();
include 'includes/config.php';

$order_id = $_GET['order_id'] ?? ($_SESSION['order_id'] ?? null);

if (!$order_id) {
    header('Location: catalog.php');
    exit;
}

// Fetch order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->bind_param("s", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Pesanan tidak ditemukan.");
}

// Generate Midtrans Snap Token if not exists
if (empty($order['snap_token'])) {
    // Basic setup for midtrans cURL
    $serverKey = $server_key;
    $authString = base64_encode($serverKey . ':');

    // Prepare item details
    $item_details = [];
    $stmt_items = $conn->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
    $stmt_items->bind_param("s", $order_id);
    $stmt_items->execute();
    $items = $stmt_items->get_result();

    while($item = $items->fetch_assoc()) {
        $item_details[] = array(
            'id' => $item['product_id'],
            'price' => (int)$item['price'],
            'quantity' => (int)$item['quantity'],
            'name' => mb_strimwidth($item['name'], 0, 50, '...')
        );
    }

    $transaction_details = array(
        'order_id' => $order_id,
        'gross_amount' => (int)$order['total_amount'],
    );

    $customer_details = array(
        'first_name'    => $order['customer_name'],
        'email'         => $order['customer_email'],
        'phone'         => $order['customer_phone'],
    );

    $params = array(
        'transaction_details' => $transaction_details,
        'item_details'        => $item_details,
        'customer_details'    => $customer_details
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Basic ' . $authString
    ));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 201) {
        $response = json_decode($result, true);
        $snapToken = $response['token'];

        // Update database with snap token
        $stmt_update = $conn->prepare("UPDATE orders SET snap_token = ? WHERE order_id = ?");
        $stmt_update->bind_param("ss", $snapToken, $order_id);
        $stmt_update->execute();

        $order['snap_token'] = $snapToken;
    } else {
        die("Terjadi kesalahan saat menghubungi Midtrans: " . $result);
    }
}

// Clear cart after reaching payment
unset($_SESSION['cart']);

?>

<?php include 'includes/header.php'; ?>

<!-- Midtrans Snap.js -->
<script src="<?= $is_production ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' ?>" data-client-key="<?= $client_key ?>"></script>

<main class="flex-1 w-full max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8 flex flex-col items-center justify-center">
    <div class="max-w-[600px] w-full bg-white dark:bg-slate-900 rounded-xl shadow-lg border border-slate-200 dark:border-slate-800 p-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white mb-6 text-center">Pembayaran Pesanan</h1>

        <div class="space-y-4 mb-8">
            <div class="flex justify-between border-b border-slate-200 dark:border-slate-700 pb-4">
                <span class="text-slate-500">Nomor Pesanan</span>
                <span class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($order_id) ?></span>
            </div>
            <div class="flex justify-between border-b border-slate-200 dark:border-slate-700 pb-4">
                <span class="text-slate-500">Nama Pemesan</span>
                <span class="font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($order['customer_name']) ?></span>
            </div>
            <div class="flex justify-between border-b border-slate-200 dark:border-slate-700 pb-4">
                <span class="text-slate-500">Total Pembayaran</span>
                <span class="text-2xl font-black text-primary">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
            </div>
        </div>

        <div class="text-center mb-6">
            <p class="text-sm text-slate-500 mb-4">Silakan selesaikan pembayaran untuk mengonfirmasi pesanan Anda.</p>
            <button id="pay-button" class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-4 rounded-xl shadow-lg shadow-primary/25 transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">payment</span>
                Pilih Metode Pembayaran
            </button>
        </div>
        <div class="text-center">
            <a href="track.php?order_id=<?= urlencode($order_id) ?>" class="text-sm text-slate-500 hover:text-primary transition-colors underline">Bayar Nanti / Lacak Pesanan</a>
        </div>
    </div>
</main>

<script type="text/javascript">
    var payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        window.snap.pay('<?= $order['snap_token'] ?>', {
            onSuccess: function(result){
                window.location.href = 'success.php?order_id=<?= urlencode($order_id) ?>';
            },
            onPending: function(result){
                window.location.href = 'track.php?order_id=<?= urlencode($order_id) ?>';
            },
            onError: function(result){
                alert("Pembayaran gagal!");
            },
            onClose: function(){
                console.log('User closed the popup without finishing the payment');
            }
        });
    });
</script>

<?php include 'includes/footer.php'; ?>
