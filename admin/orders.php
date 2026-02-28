<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
    $stmt->bind_param("ss", $status, $order_id);
    $stmt->execute();
    header("Location: orders.php?msg=status_updated");
    exit;
}

$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Manage Orders - Azka Tenda Admin</title>
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
            <a href="index.php" class="flex gap-2 text-slate-300 hover:text-white p-2 hover:bg-slate-800 rounded-lg transition-colors"><span class="material-symbols-outlined">dashboard</span> Dashboard</a>
            <a href="products.php" class="flex gap-2 text-slate-300 hover:text-white p-2 hover:bg-slate-800 rounded-lg transition-colors"><span class="material-symbols-outlined">inventory_2</span> Products</a>
            <a href="orders.php" class="flex gap-2 text-primary bg-slate-800 p-2 rounded-lg"><span class="material-symbols-outlined">shopping_bag</span> Orders</a>
            <a href="logout.php" class="flex gap-2 text-slate-400 hover:text-red-400 p-2 mt-auto"><span class="material-symbols-outlined">logout</span> Logout</a>
        </nav>
    </aside>

    <main class="flex-1 p-8 ml-64 overflow-y-auto">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-slate-800">Manage Orders</h2>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                Status updated successfully.
            </div>
        <?php endif; ?>

        <?php if ($action === 'view' && isset($_GET['id'])): ?>
            <?php
                $order_id = $_GET['id'];
                $stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
                $stmt->bind_param("s", $order_id);
                $stmt->execute();
                $order = $stmt->get_result()->fetch_assoc();
            ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <h3 class="font-bold text-lg mb-4 border-b pb-2">Order #<?= htmlspecialchars($order['order_id']) ?></h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-slate-500 block">Customer</span>
                                <span class="font-semibold text-slate-800"><?= htmlspecialchars($order['customer_name']) ?></span>
                            </div>
                            <div>
                                <span class="text-slate-500 block">Contact</span>
                                <span class="text-slate-800"><?= htmlspecialchars($order['customer_email']) ?><br><?= htmlspecialchars($order['customer_phone']) ?></span>
                            </div>
                            <div>
                                <span class="text-slate-500 block">Event Type</span>
                                <span class="text-slate-800"><?= htmlspecialchars($order['event_type']) ?></span>
                            </div>
                            <div>
                                <span class="text-slate-500 block">Event Date</span>
                                <span class="font-semibold text-slate-800"><?= date('d M Y', strtotime($order['event_date'])) ?></span>
                            </div>
                            <div class="col-span-2">
                                <span class="text-slate-500 block">Location</span>
                                <span class="text-slate-800"><?= nl2br(htmlspecialchars($order['location'])) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                        <h3 class="font-bold text-lg mb-4 border-b pb-2">Order Items</h3>
                        <div class="space-y-4">
                            <?php
                            $stmt_items = $conn->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                            $stmt_items->bind_param("s", $order_id);
                            $stmt_items->execute();
                            $items = $stmt_items->get_result();
                            while($item = $items->fetch_assoc()):
                            ?>
                            <div class="flex justify-between border-b border-slate-100 pb-3">
                                <div>
                                    <span class="font-medium text-slate-800 block"><?= htmlspecialchars($item['name']) ?></span>
                                    <span class="text-sm text-slate-500"><?= $item['quantity'] ?> x Rp <?= number_format($item['price'], 0, ',', '.') ?></span>
                                </div>
                                <span class="font-semibold text-slate-800">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></span>
                            </div>
                            <?php endwhile; ?>
                            <div class="flex justify-between pt-2">
                                <span class="font-bold text-slate-800">Total</span>
                                <span class="text-xl font-black text-primary">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sticky top-8">
                        <h3 class="font-bold text-lg mb-4">Update Status</h3>
                        <form method="POST" action="orders.php">
                            <input type="hidden" name="order_id" value="<?= $order['order_id'] ?>">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-1">Current Status</label>
                                    <select name="status" class="w-full border border-slate-300 rounded-lg px-4 py-2 bg-white text-sm">
                                        <option value="Pending" <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="Success" <?= $order['status'] == 'Success' ? 'selected' : '' ?>>Success</option>
                                        <option value="Failed" <?= $order['status'] == 'Failed' ? 'selected' : '' ?>>Failed</option>
                                    </select>
                                </div>
                                <div class="flex gap-2 pt-2">
                                    <button type="submit" name="update_status" class="flex-1 bg-primary text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 text-sm">Update</button>
                                    <a href="orders.php" class="bg-slate-200 text-slate-800 font-bold py-2 px-4 rounded-lg hover:bg-slate-300 text-center text-sm">Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="p-4 text-sm font-semibold text-slate-600">Order ID</th>
                                <th class="p-4 text-sm font-semibold text-slate-600">Date Created</th>
                                <th class="p-4 text-sm font-semibold text-slate-600">Customer</th>
                                <th class="p-4 text-sm font-semibold text-slate-600">Total</th>
                                <th class="p-4 text-sm font-semibold text-slate-600">Status</th>
                                <th class="p-4 text-sm font-semibold text-slate-600">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php while($row = $orders->fetch_assoc()): ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="p-4 font-medium text-slate-800 text-sm"><?= htmlspecialchars($row['order_id']) ?></td>
                                <td class="p-4 text-slate-600 text-sm"><?= date('d M Y, H:i', strtotime($row['created_at'])) ?></td>
                                <td class="p-4">
                                    <div class="text-sm font-medium text-slate-800"><?= htmlspecialchars($row['customer_name']) ?></div>
                                    <div class="text-xs text-slate-500"><?= htmlspecialchars($row['customer_phone']) ?></div>
                                </td>
                                <td class="p-4 font-semibold text-slate-800 text-sm">Rp <?= number_format($row['total_amount'], 0, ',', '.') ?></td>
                                <td class="p-4">
                                    <?php
                                    $status_classes = [
                                        'Pending' => 'bg-yellow-100 text-yellow-800 border border-yellow-200',
                                        'Success' => 'bg-green-100 text-green-800 border border-green-200',
                                        'Failed' => 'bg-red-100 text-red-800 border border-red-200',
                                    ];
                                    $class = $status_classes[$row['status']] ?? 'bg-slate-100 text-slate-800 border border-slate-200';
                                    ?>
                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full <?= $class ?>"><?= htmlspecialchars($row['status']) ?></span>
                                </td>
                                <td class="p-4">
                                    <a href="?action=view&id=<?= $row['order_id'] ?>" class="text-primary hover:text-blue-700 font-semibold text-sm flex items-center gap-1">
                                        View
                                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
