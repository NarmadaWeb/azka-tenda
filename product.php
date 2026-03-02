<?php
include 'includes/config.php';

if (!isset($_GET['id'])) {
    header('Location: /catalog');
    exit;
}

$product_id = (int)$_GET['id'];
$query = "SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id WHERE p.id = $product_id";
$result = $conn->query($query);

if ($result->num_rows == 0) {
    header('Location: /catalog');
    exit;
}

$product = $result->fetch_assoc();
?>

<?php include 'includes/header.php'; ?>

<main class="max-w-[1200px] mx-auto w-full px-6 py-8 flex-1">
    <div class="mb-4">
        <a href="/catalog" class="text-sm text-primary hover:underline">&larr; Kembali ke Katalog</a>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <div class="lg:col-span-7 flex flex-col gap-4">
            <div class="w-full aspect-[4/3] rounded-xl overflow-hidden shadow-lg bg-slate-200 dark:bg-slate-800">
                <div class="w-full h-full bg-cover bg-center" style="background-image: url('<?= htmlspecialchars($product['image']) ?>')"></div>
            </div>
        </div>
        <div class="lg:col-span-5 flex flex-col gap-6">
            <div>
                <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider"><?= htmlspecialchars($product['category_name']) ?></span>
                <h1 class="text-4xl font-black text-slate-900 dark:text-slate-100 mt-2"><?= htmlspecialchars($product['name']) ?></h1>
            </div>
            <p class="text-slate-600 dark:text-slate-400 leading-relaxed"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            <div class="p-5 rounded-xl bg-primary/5 dark:bg-primary/10 border border-primary/10 mt-auto">
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-primary">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                    <span class="text-slate-500 text-sm"><?= htmlspecialchars($product['unit']) ?></span>
                </div>
            </div>

            <form action="/cart_action" method="POST" class="flex flex-col gap-4">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                <div class="flex items-center gap-4">
                    <label for="quantity" class="font-semibold text-slate-700 dark:text-slate-300">Kuantitas:</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" class="w-24 p-2 border border-slate-300 rounded-lg text-center dark:bg-slate-800 dark:border-slate-700">
                </div>

                <button type="submit" class="w-full bg-primary text-white font-bold rounded-lg px-6 h-12 flex items-center justify-center gap-2 hover:bg-blue-700 transition-colors">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    Tambahkan ke Pesanan
                </button>
            </form>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
