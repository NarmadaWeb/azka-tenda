<?php
session_start();
include 'includes/config.php';

$order_id = $_GET['order_id'] ?? ($_SESSION['order_id'] ?? null);

if (!$order_id) {
    header('Location: index.php');
    exit;
}

$stmt = $conn->prepare("SELECT status FROM orders WHERE order_id = ?");
$stmt->bind_param("s", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if ($order && $order['status'] == 'Pending') {
    // Manually mark success for testing / flow simulation
    $stmt_update = $conn->prepare("UPDATE orders SET status = 'Success' WHERE order_id = ?");
    $stmt_update->bind_param("s", $order_id);
    $stmt_update->execute();
}

unset($_SESSION['order_id']);
?>

<?php include 'includes/header.php'; ?>

<main class="flex-1 flex flex-col items-center justify-center px-4 py-12 w-full">
    <div class="max-w-[560px] w-full bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 flex flex-col items-center text-center">
        <span class="material-symbols-outlined text-green-500 text-6xl mb-4">check_circle</span>
        <h1 class="text-3xl font-bold mb-2 text-slate-900 dark:text-white">Pembayaran Berhasil</h1>
        <p class="text-slate-500 mb-8">Transaksi Anda telah berhasil diproses. Detail telah dikirim ke email Anda.</p>
        <a href="track.php?order_id=<?= urlencode($order_id) ?>" class="w-full bg-primary text-white font-bold h-12 rounded-lg flex items-center justify-center hover:bg-primary/90 transition-all">Lacak Pesanan</a>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
