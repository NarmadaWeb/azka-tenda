<?php include 'includes/config.php'; ?>
<?php include 'includes/header.php'; ?>

<main class="max-w-7xl mx-auto w-full px-6 py-12 flex-1">
    <h1 class="text-4xl font-black mb-12">Galeri Portofolio</h1>
    <div class="masonry-grid">
        <?php for($i=1; $i<=6; $i++): ?>
            <div class="masonry-item relative overflow-hidden rounded-xl bg-slate-200">
                <img src="https://picsum.photos/seed/<?= $i+10 ?>/600/<?= 400 + ($i % 3) * 100 ?>" class="w-full h-auto" alt="Gallery Image <?= $i ?>" />
            </div>
        <?php endfor; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
