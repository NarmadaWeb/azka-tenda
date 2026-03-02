<!DOCTYPE html>
<html lang="id" class="">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Azka Tenda - Solusi Terop & Dekorasi Acara</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Work Sans", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Work Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .masonry-grid { columns: 1; gap: 1.5rem; }
        @media (min-width: 640px) { .masonry-grid { columns: 2; } }
        @media (min-width: 1024px) { .masonry-grid { columns: 3; } }
        .masonry-item { break-inside: avoid; margin-bottom: 1.5rem; }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 transition-colors duration-300 flex flex-col min-h-screen">
    <header class="sticky top-0 z-50 flex items-center justify-between border-b border-slate-200 dark:border-slate-800 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md px-6 md:px-20 py-4">
        <a href="/" class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary text-white">
                <span class="material-symbols-outlined">farsight_digital</span>
            </div>
            <h2 class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">Azka Tenda</h2>
        </a>
        <nav class="hidden md:flex items-center gap-8">
            <a href="/" class="text-sm font-semibold hover:text-primary transition-colors">Beranda</a>
            <a href="/catalog" class="text-sm font-semibold hover:text-primary transition-colors">Katalog</a>
            <a href="/packages" class="text-sm font-semibold hover:text-primary transition-colors">Paket</a>
            <a href="/gallery" class="text-sm font-semibold hover:text-primary transition-colors">Galeri</a>
            <a href="/blog" class="text-sm font-semibold hover:text-primary transition-colors">Blog</a>
            <a href="/about" class="text-sm font-semibold hover:text-primary transition-colors">Tentang Kami</a>
            <a href="/contact" class="text-sm font-semibold hover:text-primary transition-colors">Kontak</a>
        </nav>
        <div class="flex items-center gap-4">
            <a href="/track" class="hidden md:flex items-center justify-center text-slate-600 dark:text-slate-400 hover:text-primary font-semibold text-sm">
                Lacak Pesanan
            </a>
            <a href="/catalog" class="flex items-center justify-center rounded-lg bg-primary px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-primary/25 hover:bg-primary/90 transition-all active:scale-95">
                Pesan
            </a>
            <?php
            // Calculate total items in cart
            $cart_count = 0;
            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    $cart_count += $item['quantity'];
                }
            }
            ?>
            <a href="/order" class="flex items-center justify-center gap-2 rounded-lg bg-slate-200 dark:bg-slate-800 px-4 py-2.5 text-sm font-bold text-slate-800 dark:text-slate-200 hover:bg-slate-300 dark:hover:bg-slate-700 transition-all">
                <span class="material-symbols-outlined text-lg">shopping_cart</span>
                <span><?= $cart_count ?></span>
            </a>
        </div>
    </header>
