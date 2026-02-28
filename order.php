<?php
include 'includes/config.php';

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_amount = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($cart)) {
        $error = "Keranjang belanja Anda kosong.";
    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $event_date = $_POST['event_date'];
        $event_type = $_POST['event_type'];
        $location = $_POST['location'];

        // Calculate total amount
        foreach ($cart as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }

        $order_id = 'AT-' . time() . '-' . rand(100, 999);

        $stmt = $conn->prepare("INSERT INTO orders (order_id, customer_name, customer_email, customer_phone, event_date, event_type, location, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssd", $order_id, $name, $email, $phone, $event_date, $event_type, $location, $total_amount);

        if ($stmt->execute()) {
            $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, price, quantity) VALUES (?, ?, ?, ?)");
            foreach ($cart as $item) {
                $stmt_item->bind_param("sidi", $order_id, $item['id'], $item['price'], $item['quantity']);
                $stmt_item->execute();
            }

            $_SESSION['order_id'] = $order_id;
            header('Location: payment.php');
            exit;
        } else {
            $error = "Terjadi kesalahan saat memproses pesanan Anda.";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<main class="flex-1 max-w-7xl mx-auto w-full px-4 md:px-10 py-10">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2 space-y-8">
            <h1 class="text-3xl font-black tracking-tight">Form Pemesanan</h1>

            <?php if(isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <?php if(empty($cart)): ?>
                <div class="bg-slate-50 dark:bg-slate-800 p-8 rounded-xl text-center">
                    <span class="material-symbols-outlined text-4xl text-slate-400 mb-4 block">shopping_cart</span>
                    <h2 class="text-xl font-bold text-slate-700 dark:text-slate-300">Keranjang Masih Kosong</h2>
                    <p class="text-slate-500 mb-4">Silakan pilih produk yang ingin disewa melalui katalog kami.</p>
                    <a href="catalog.php" class="inline-block bg-primary text-white font-bold px-6 py-2 rounded-lg">Ke Katalog</a>
                </div>
            <?php else: ?>
                <form id="orderForm" method="POST" action="order.php">
                    <section class="bg-white dark:bg-slate-900/50 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="material-symbols-outlined text-primary">person</span>
                            <h2 class="text-xl font-bold">1. Detail Pemesan</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <input type="text" name="name" placeholder="Nama Lengkap" required class="w-full h-12 px-4 rounded-lg bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800" />
                            <input type="email" name="email" placeholder="Email" required class="w-full h-12 px-4 rounded-lg bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800" />
                            <input type="tel" name="phone" placeholder="Nomor Telepon/WhatsApp" required class="w-full h-12 px-4 rounded-lg bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800 md:col-span-2" />
                        </div>
                    </section>

                    <section class="bg-white dark:bg-slate-900/50 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm mb-8">
                        <div class="flex items-center gap-3 mb-6">
                            <span class="material-symbols-outlined text-primary">event</span>
                            <h2 class="text-xl font-bold">2. Detail Acara</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <input type="date" name="event_date" required class="w-full h-12 px-4 rounded-lg bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800" />
                            <select name="event_type" required class="w-full h-12 px-4 rounded-lg bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800">
                                <option value="" disabled selected>Pilih Jenis Acara</option>
                                <option value="Pernikahan">Pernikahan</option>
                                <option value="Khitanan">Khitanan</option>
                                <option value="Corporate Event">Corporate Event</option>
                                <option value="Ulang Tahun">Ulang Tahun</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <textarea name="location" required class="md:col-span-2 w-full p-4 rounded-lg bg-slate-50 dark:bg-slate-900 border-slate-200 dark:border-slate-800" placeholder="Alamat Lengkap Lokasi Acara"></textarea>
                        </div>
                    </section>

                    <button type="submit" class="w-full bg-primary text-white font-bold h-14 rounded-xl flex items-center justify-center gap-2 hover:bg-primary/90 transition-colors">
                        Lanjutkan Pembayaran <span class="material-symbols-outlined">arrow_forward</span>
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <aside class="lg:col-span-1 h-fit sticky top-28">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-xl">
                <h2 class="text-xl font-bold mb-6">Ringkasan Pesanan</h2>

                <div class="space-y-4 mb-6 max-h-[400px] overflow-y-auto no-scrollbar">
                    <?php
                    $subtotal = 0;
                    foreach($cart as $item):
                        $item_total = $item['price'] * $item['quantity'];
                        $subtotal += $item_total;
                    ?>
                    <div class="flex justify-between items-start gap-4">
                        <div class="flex gap-3">
                            <div class="w-16 h-16 rounded bg-slate-200 bg-cover bg-center shrink-0" style="background-image: url('<?= htmlspecialchars($item['image']) ?>')"></div>
                            <div>
                                <h4 class="font-semibold text-sm line-clamp-2"><?= htmlspecialchars($item['name']) ?></h4>
                                <span class="text-xs text-slate-500">Qty: <?= $item['quantity'] ?></span>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="font-bold text-sm block">Rp <?= number_format($item_total, 0, ',', '.') ?></span>
                            <a href="cart_action.php?action=remove&product_id=<?= $item['id'] ?>" class="text-xs text-red-500 hover:underline">Hapus</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="flex justify-between items-end border-t border-dashed border-slate-200 dark:border-slate-700 pt-6">
                    <span class="text-base font-bold">Total Pembayaran</span>
                    <span class="text-2xl font-black text-primary">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                </div>
            </div>
        </aside>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
