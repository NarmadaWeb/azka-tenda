<?php include 'includes/config.php'; ?>
<?php include 'includes/header.php'; ?>

<main class="max-w-[1200px] mx-auto px-4 py-10 flex-1 w-full">
    <h1 class="text-4xl font-black mb-10 text-center text-slate-900 dark:text-white">Special Package Deals</h1>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php
        $packages = [
            ['name' => 'Paket Pernikahan Hemat', 'price' => 5000000, 'discount' => '20%'],
            ['name' => 'Paket Khitanan Spesial', 'price' => 3500000, 'discount' => '15%'],
            ['name' => 'Paket Corporate Event', 'price' => 12000000, 'discount' => '10%']
        ];
        foreach($packages as $pkg):
        ?>
        <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-lg transition-shadow">
            <span class="px-3 py-1 bg-red-100 text-red-600 text-xs font-bold rounded-full">Hemat <?= $pkg['discount'] ?></span>
            <h3 class="text-2xl font-bold mt-4 mb-2 text-slate-900 dark:text-white"><?= $pkg['name'] ?></h3>
            <p class="text-primary font-bold text-xl mb-6">Rp <?= number_format($pkg['price'], 0, ',', '.') ?></p>
            <a href="catalog.php" class="block w-full text-center bg-primary text-white font-bold py-3 rounded-lg hover:bg-primary/90 transition-all">Lihat Katalog</a>
        </div>
        <?php endforeach; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
