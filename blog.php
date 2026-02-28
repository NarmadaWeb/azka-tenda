<?php include 'includes/config.php'; ?>
<?php include 'includes/header.php'; ?>

<main class="max-w-[1200px] mx-auto px-6 py-8 flex-1 w-full">
    <h1 class="text-4xl font-black mb-10 text-slate-900 dark:text-white">Inspirasi & Tips Dekorasi</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php for($i=1; $i<=3; $i++): ?>
        <div class="bg-white dark:bg-slate-900 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800 transition-transform hover:-translate-y-1 hover:shadow-lg">
            <div class="h-48 bg-slate-300 dark:bg-slate-700 bg-cover bg-center" style="background-image: url('https://picsum.photos/seed/<?= $i+20 ?>/400/300')"></div>
            <div class="p-6">
                <h3 class="font-bold text-lg mb-2 text-slate-900 dark:text-white">Trend Dekorasi <?= 2023 + $i ?></h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Pelajari lebih lanjut tentang warna dan gaya yang akan populer di acara pernikahan dan event lainnya tahun ini.</p>
                <a href="#" class="text-primary font-bold text-sm hover:underline">Read More</a>
            </div>
        </div>
        <?php endfor; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
