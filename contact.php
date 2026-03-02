<?php include 'includes/config.php'; ?>
<?php include 'includes/header.php'; ?>

<main class="max-w-7xl mx-auto px-6 py-12 flex-1 w-full">
    <h1 class="text-4xl font-black mb-8">Contact Us</h1>
    <div class="grid lg:grid-cols-2 gap-12">
        <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <form class="space-y-4">
                <input type="text" class="w-full p-4 bg-slate-50 dark:bg-slate-800 rounded-lg border-none text-slate-900 dark:text-white" placeholder="Full Name" required />
                <input type="email" class="w-full p-4 bg-slate-50 dark:bg-slate-800 rounded-lg border-none text-slate-900 dark:text-white" placeholder="Email" required />
                <textarea class="w-full p-4 bg-slate-50 dark:bg-slate-800 rounded-lg border-none text-slate-900 dark:text-white" rows="5" placeholder="Your Message" required></textarea>
                <button type="submit" class="w-full bg-primary text-white font-bold p-4 rounded-lg hover:bg-primary/90 transition-all">Send Message</button>
            </form>
        </div>
        <div class="space-y-4">
            <div class="bg-primary/5 p-6 rounded-2xl border border-primary/10">
                <h3 class="font-bold mb-2 text-slate-900 dark:text-white">Office Address</h3>
                <p class="text-slate-600 dark:text-slate-400">Jl. Raya Event No. 123, Indonesia<br>Telepon: +62 812 3456 7890<br>Email: info@azkatenda.com</p>
            </div>
            <div class="bg-slate-100 dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700">
                <h3 class="font-bold mb-2 text-slate-900 dark:text-white">Jam Operasional</h3>
                <p class="text-slate-600 dark:text-slate-400">Senin - Jumat: 08:00 - 17:00<br>Sabtu: 08:00 - 15:00<br>Minggu: Tutup</p>
            </div>
        </div>
    </div>

    <div class="mt-20">
        <h2 class="text-3xl font-bold tracking-tight text-slate-900 dark:text-white mb-8 text-center">Frequently Asked Questions</h2>
        <div class="max-w-3xl mx-auto space-y-4">
            <details class="group rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 [&_summary::-webkit-details-marker]:hidden">
                <summary class="flex cursor-pointer items-center justify-between gap-1.5 p-6 text-slate-900 dark:text-white">
                    <h3 class="font-bold">Berapa lama waktu minimal untuk pemesanan sebelum hari H?</h3>
                    <span class="material-symbols-outlined transition duration-300 group-open:-rotate-180">expand_more</span>
                </summary>
                <p class="px-6 pb-6 text-slate-600 dark:text-slate-400">Kami menyarankan Anda untuk melakukan pemesanan setidaknya 1 bulan sebelum acara untuk memastikan ketersediaan perlengkapan yang Anda butuhkan.</p>
            </details>

            <details class="group rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 [&_summary::-webkit-details-marker]:hidden">
                <summary class="flex cursor-pointer items-center justify-between gap-1.5 p-6 text-slate-900 dark:text-white">
                    <h3 class="font-bold">Apakah harga sewa sudah termasuk biaya bongkar pasang?</h3>
                    <span class="material-symbols-outlined transition duration-300 group-open:-rotate-180">expand_more</span>
                </summary>
                <p class="px-6 pb-6 text-slate-600 dark:text-slate-400">Ya, semua harga yang tertera di website kami sudah termasuk biaya bongkar pasang oleh tim profesional kami.</p>
            </details>

            <details class="group rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 [&_summary::-webkit-details-marker]:hidden">
                <summary class="flex cursor-pointer items-center justify-between gap-1.5 p-6 text-slate-900 dark:text-white">
                    <h3 class="font-bold">Apakah melayani pengiriman ke luar kota?</h3>
                    <span class="material-symbols-outlined transition duration-300 group-open:-rotate-180">expand_more</span>
                </summary>
                <p class="px-6 pb-6 text-slate-600 dark:text-slate-400">Saat ini kami fokus melayani area Jabodetabek. Untuk pengiriman ke luar kota, dapat didiskusikan lebih lanjut dengan tim customer service kami mengenai biaya transportasi tambahan.</p>
            </details>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
