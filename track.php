<?php
session_start();
include 'includes/config.php';

$order_id = $_GET['order_id'] ?? ($_POST['order_id'] ?? null);
$order = null;

if ($order_id) {
    $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
    $stmt->bind_param("s", $order_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
}
?>

<?php include 'includes/header.php'; ?>

<main class="max-w-[1200px] mx-auto w-full px-6 py-8 flex-1">
    <h1 class="text-3xl font-black mb-8 text-slate-900 dark:text-white">Lacak Pesanan</h1>

    <?php if(!$order): ?>
        <div class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm text-center">
            <h2 class="text-xl font-bold mb-4 text-slate-700 dark:text-slate-300">Masukkan Nomor Pesanan</h2>
            <form method="GET" action="track.php" class="max-w-md mx-auto flex flex-col gap-4">
                <input type="text" name="order_id" placeholder="Contoh: AT-16781293-456" class="w-full p-4 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700 text-center" required>
                <button type="submit" class="w-full bg-primary text-white font-bold p-4 rounded-lg hover:bg-primary/90 transition-all flex justify-center items-center gap-2">
                    <span class="material-symbols-outlined">search</span>
                    Cari Pesanan
                </button>
            </form>
        </div>
    <?php else: ?>
        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white dark:bg-slate-900 p-8 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Order #<?= htmlspecialchars($order['order_id']) ?></h2>
                    <span class="px-4 py-2 <?= $order['status'] == 'Success' ? 'bg-green-100 text-green-700' : ($order['status'] == 'Pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') ?> rounded-full text-sm font-bold">
                        <?= htmlspecialchars($order['status']) ?>
                    </span>
                </div>

                <div class="space-y-8">
                    <div class="flex gap-4 items-start">
                        <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-full">person</span>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white">Detail Pemesan</h4>
                            <p class="text-sm text-slate-600 dark:text-slate-400"><?= htmlspecialchars($order['customer_name']) ?></p>
                            <p class="text-sm text-slate-600 dark:text-slate-400"><?= htmlspecialchars($order['customer_email']) ?> | <?= htmlspecialchars($order['customer_phone']) ?></p>
                        </div>
                    </div>

                    <div class="flex gap-4 items-start">
                        <span class="material-symbols-outlined text-primary bg-primary/10 p-2 rounded-full">event</span>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white">Detail Acara</h4>
                            <p class="text-sm text-slate-600 dark:text-slate-400"><?= htmlspecialchars($order['event_type']) ?> - <?= date('d M Y', strtotime($order['event_date'])) ?></p>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1"><?= nl2br(htmlspecialchars($order['location'])) ?></p>
                        </div>
                    </div>

                    <?php if($order['status'] == 'Pending'): ?>
                        <div class="bg-yellow-50 dark:bg-yellow-900/30 p-6 rounded-xl border border-yellow-200 dark:border-yellow-800 mt-8 text-center">
                            <h3 class="font-bold text-yellow-800 dark:text-yellow-200 mb-2">Menunggu Pembayaran</h3>
                            <p class="text-sm text-yellow-700 dark:text-yellow-300 mb-4">Silakan selesaikan pembayaran untuk memproses pesanan Anda.</p>
                            <a href="payment.php?order_id=<?= urlencode($order['order_id']) ?>" class="inline-block bg-primary text-white font-bold px-6 py-3 rounded-lg hover:bg-primary/90 transition-all shadow-md flex items-center justify-center gap-2 max-w-xs mx-auto">
                                <span class="material-symbols-outlined">payment</span>
                                Bayar Sekarang
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="lg:col-span-1 bg-slate-200 dark:bg-slate-800 rounded-xl overflow-hidden flex flex-col shadow-sm">
                 <div class="p-6 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex-1">
                    <h3 class="font-bold mb-4 text-slate-900 dark:text-white">Item yang Dipesan</h3>
                    <div class="space-y-4 max-h-[300px] overflow-y-auto no-scrollbar">
                        <?php
                        $stmt_items = $conn->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                        $stmt_items->bind_param("s", $order_id);
                        $stmt_items->execute();
                        $items = $stmt_items->get_result();
                        while($item = $items->fetch_assoc()):
                        ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-700 dark:text-slate-300"><?= $item['quantity'] ?>x <?= htmlspecialchars($item['name']) ?></span>
                            <span class="font-semibold text-slate-900 dark:text-white">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></span>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <div class="p-6 bg-slate-50 dark:bg-slate-900/50">
                     <div class="flex justify-between items-center text-lg font-bold">
                        <span class="text-slate-900 dark:text-white">Total</span>
                        <span class="text-primary">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
                     </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
