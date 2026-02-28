<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$pending_orders = $conn->query("SELECT COUNT(*) as count FROM orders WHERE status = 'Pending'")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status = 'Success'")->fetch_assoc()['total'];

$recent_orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Admin Dashboard - Azka Tenda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>body { font-family: 'Work Sans', sans-serif; }</style>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: "#135bec", "background-light": "#f6f6f8" } } }
        }
    </script>
</head>
<body class="bg-background-light flex min-h-screen">
    <aside class="w-64 bg-slate-900 text-white flex flex-col p-6 fixed h-full z-10">
        <h1 class="text-xl font-bold mb-8">Azka Tenda Admin</h1>
        <nav class="space-y-4 flex-1">
            <a href="index.php" class="flex gap-2 text-primary bg-slate-800 p-2 rounded-lg"><span class="material-symbols-outlined">dashboard</span> Dashboard</a>
            <a href="products.php" class="flex gap-2 text-slate-300 hover:text-white p-2 hover:bg-slate-800 rounded-lg transition-colors"><span class="material-symbols-outlined">inventory_2</span> Products</a>
            <a href="orders.php" class="flex gap-2 text-slate-300 hover:text-white p-2 hover:bg-slate-800 rounded-lg transition-colors"><span class="material-symbols-outlined">shopping_bag</span> Orders</a>
            <a href="logout.php" class="flex gap-2 text-slate-400 hover:text-red-400 p-2 mt-auto"><span class="material-symbols-outlined">logout</span> Logout</a>
        </nav>
    </aside>

    <main class="flex-1 p-8 ml-64 overflow-y-auto">
        <h2 class="text-3xl font-bold mb-8 text-slate-800">Dashboard</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="p-6 bg-white shadow-sm rounded-xl border border-slate-200">
                <h3 class="text-slate-500 text-sm font-semibold uppercase mb-2">Total Orders</h3>
                <p class="text-3xl font-bold text-slate-800"><?= number_format($total_orders) ?></p>
            </div>
            <div class="p-6 bg-white shadow-sm rounded-xl border border-slate-200">
                <h3 class="text-slate-500 text-sm font-semibold uppercase mb-2">Pending Orders</h3>
                <p class="text-3xl font-bold text-yellow-600"><?= number_format($pending_orders) ?></p>
            </div>
            <div class="p-6 bg-white shadow-sm rounded-xl border border-slate-200">
                <h3 class="text-slate-500 text-sm font-semibold uppercase mb-2">Total Revenue</h3>
                <p class="text-3xl font-bold text-green-600">Rp <?= number_format($total_revenue ?: 0, 0, ',', '.') ?></p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200 flex justify-between items-center">
                <h3 class="font-bold text-lg">Recent Orders</h3>
                <a href="orders.php" class="text-primary text-sm font-semibold hover:underline">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="p-4 text-sm font-semibold text-slate-600">Order ID</th>
                            <th class="p-4 text-sm font-semibold text-slate-600">Customer</th>
                            <th class="p-4 text-sm font-semibold text-slate-600">Event Date</th>
                            <th class="p-4 text-sm font-semibold text-slate-600">Amount</th>
                            <th class="p-4 text-sm font-semibold text-slate-600">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php while($row = $recent_orders->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-4 font-medium text-slate-800"><?= htmlspecialchars($row['order_id']) ?></td>
                            <td class="p-4 text-slate-600"><?= htmlspecialchars($row['customer_name']) ?></td>
                            <td class="p-4 text-slate-600"><?= date('d M Y', strtotime($row['event_date'])) ?></td>
                            <td class="p-4 font-semibold text-slate-800">Rp <?= number_format($row['total_amount'], 0, ',', '.') ?></td>
                            <td class="p-4">
                                <?php
                                $status_classes = [
                                    'Pending' => 'bg-yellow-100 text-yellow-800',
                                    'Success' => 'bg-green-100 text-green-800',
                                    'Failed' => 'bg-red-100 text-red-800',
                                ];
                                $class = $status_classes[$row['status']] ?? 'bg-slate-100 text-slate-800';
                                ?>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $class ?>"><?= htmlspecialchars($row['status']) ?></span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
