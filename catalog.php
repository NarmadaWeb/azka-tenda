<?php
include 'includes/config.php';

$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;

$categories_query = "SELECT * FROM categories";
$categories_result = $conn->query($categories_query);

$products_query = "SELECT * FROM products";
if ($category_filter > 0) {
    $products_query .= " WHERE category_id = " . $category_filter;
}
$products_result = $conn->query($products_query);
?>

<?php include 'includes/header.php'; ?>

<main class="mx-auto flex w-full max-w-7xl flex-1 gap-8 px-6 py-8 md:px-20">
    <aside class="hidden lg:flex w-64 shrink-0 flex-col gap-8">
        <div class="flex flex-col gap-4">
            <h3 class="text-lg font-bold">Kategori Acara</h3>
            <div class="flex flex-col gap-1">
                <a href="catalog.php" class="flex items-center gap-3 rounded-lg <?= $category_filter == 0 ? 'bg-primary/10 text-primary font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' ?> px-3 py-2.5">
                    <span class="material-symbols-outlined">grid_view</span>
                    <span class="text-sm">Semua Produk</span>
                </a>
                <?php while($row = $categories_result->fetch_assoc()): ?>
                <a href="catalog.php?category=<?= $row['id'] ?>" class="flex items-center gap-3 rounded-lg <?= $category_filter == $row['id'] ? 'bg-primary/10 text-primary font-semibold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' ?> px-3 py-2.5">
                    <span class="material-symbols-outlined">label</span>
                    <span class="text-sm"><?= htmlspecialchars($row['name']) ?></span>
                </a>
                <?php endwhile; ?>
            </div>
        </div>
    </aside>
    <section class="flex flex-1 flex-col gap-6">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <h1 class="text-3xl font-black tracking-tight">Katalog Sewa Alat Event</h1>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            <?php while($product = $products_result->fetch_assoc()): ?>
            <div onclick="window.location.href='product.php?id=<?= $product['id'] ?>'" class="group flex flex-col overflow-hidden rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 transition-all hover:shadow-xl hover:shadow-primary/5 cursor-pointer">
                <div class="relative aspect-[4/3] w-full overflow-hidden bg-slate-200">
                    <img class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110" src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" />
                </div>
                <div class="flex flex-1 flex-col p-5">
                    <h4 class="text-lg font-bold text-slate-900 dark:text-slate-100"><?= htmlspecialchars($product['name']) ?></h4>
                    <div class="mt-auto pt-5">
                        <div class="flex items-baseline gap-1">
                            <span class="text-xl font-black text-primary">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                            <span class="text-xs font-medium text-slate-400"><?= htmlspecialchars($product['unit']) ?></span>
                        </div>
                        <form action="cart_action.php" method="POST" class="mt-4" onclick="event.stopPropagation();">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-lg bg-primary py-2.5 text-sm font-bold text-white hover:bg-blue-700">
                                <span class="material-symbols-outlined text-lg">add_shopping_cart</span>
                                <span>Pesan</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>
