<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$action = $_GET['action'] ?? '';
$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name = $_POST['name'];
        $category_id = $_POST['category_id'];
        $price = $_POST['price'];
        $unit = $_POST['unit'];
        $image = $_POST['image'];
        $description = $_POST['description'];

        $stmt = $conn->prepare("INSERT INTO products (name, category_id, price, unit, image, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sissss", $name, $category_id, $price, $unit, $image, $description);
        $stmt->execute();
        header("Location: products.php?msg=added");
        exit;
    } elseif (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $category_id = $_POST['category_id'];
        $price = $_POST['price'];
        $unit = $_POST['unit'];
        $image = $_POST['image'];
        $description = $_POST['description'];

        $stmt = $conn->prepare("UPDATE products SET name=?, category_id=?, price=?, unit=?, image=?, description=? WHERE id=?");
        $stmt->bind_param("sissssi", $name, $category_id, $price, $unit, $image, $description, $id);
        $stmt->execute();
        header("Location: products.php?msg=updated");
        exit;
    }
}

if ($action === 'delete' && $id > 0) {
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: products.php?msg=deleted");
    exit;
}

$products = $conn->query("SELECT p.*, c.name as category_name FROM products p LEFT JOIN categories c ON p.category_id = c.id");
$categories = $conn->query("SELECT * FROM categories");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Manage Products - Azka Tenda Admin</title>
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
            <a href="products.php" class="flex gap-2 text-primary bg-slate-800 p-2 rounded-lg"><span class="material-symbols-outlined">inventory_2</span> Products</a>
            <a href="orders.php" class="flex gap-2 text-slate-300 hover:text-white p-2 hover:bg-slate-800 rounded-lg transition-colors"><span class="material-symbols-outlined">shopping_bag</span> Orders</a>
            <a href="logout.php" class="flex gap-2 text-slate-400 hover:text-red-400 p-2 mt-auto"><span class="material-symbols-outlined">logout</span> Logout</a>
        </nav>
    </aside>

    <main class="flex-1 p-8 ml-64 overflow-y-auto">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-slate-800">Manage Products</h2>
            <?php if($action !== 'add' && $action !== 'edit'): ?>
                <a href="?action=add" class="bg-primary text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2 hover:bg-blue-700 transition-colors">
                    <span class="material-symbols-outlined text-sm">add</span> Add New Product
                </a>
            <?php endif; ?>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                <?= htmlspecialchars($_GET['msg']) ?> successfully.
            </div>
        <?php endif; ?>

        <?php if ($action === 'add' || $action === 'edit'): ?>
            <?php
                $product = null;
                if ($action === 'edit' && $id > 0) {
                    $stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
                    $stmt->bind_param("i", $id);
                    $stmt->execute();
                    $product = $stmt->get_result()->fetch_assoc();
                }
            ?>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 max-w-2xl">
                <form method="POST" action="products.php" class="space-y-4">
                    <?php if($product): ?>
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                    <?php endif; ?>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Product Name</label>
                            <input type="text" name="name" required class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?= htmlspecialchars($product['name'] ?? '') ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Category</label>
                            <select name="category_id" required class="w-full border border-slate-300 rounded-lg px-4 py-2 bg-white">
                                <?php $categories->data_seek(0); while($cat = $categories->fetch_assoc()): ?>
                                    <option value="<?= $cat['id'] ?>" <?= ($product && $product['category_id'] == $cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Price (Rp)</label>
                            <input type="number" name="price" required class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?= htmlspecialchars($product['price'] ?? '') ?>">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Unit (e.g., /hari, /m²)</label>
                            <input type="text" name="unit" required class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?= htmlspecialchars($product['unit'] ?? '') ?>">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Image URL</label>
                            <input type="text" name="image" required class="w-full border border-slate-300 rounded-lg px-4 py-2" value="<?= htmlspecialchars($product['image'] ?? '') ?>">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                            <textarea name="description" rows="4" required class="w-full border border-slate-300 rounded-lg px-4 py-2"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4 border-t border-slate-100">
                        <button type="submit" name="<?= $action ?>" class="bg-primary text-white font-bold py-2 px-6 rounded-lg hover:bg-blue-700">Save</button>
                        <a href="products.php" class="bg-slate-200 text-slate-800 font-bold py-2 px-6 rounded-lg hover:bg-slate-300">Cancel</a>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="p-4 text-sm font-semibold text-slate-600">ID</th>
                            <th class="p-4 text-sm font-semibold text-slate-600">Product</th>
                            <th class="p-4 text-sm font-semibold text-slate-600">Category</th>
                            <th class="p-4 text-sm font-semibold text-slate-600">Price</th>
                            <th class="p-4 text-sm font-semibold text-slate-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php while($row = $products->fetch_assoc()): ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-4 text-slate-500 text-sm"><?= $row['id'] ?></td>
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="" class="w-10 h-10 rounded object-cover">
                                    <span class="font-medium text-slate-800"><?= htmlspecialchars($row['name']) ?></span>
                                </div>
                            </td>
                            <td class="p-4 text-slate-600 text-sm"><?= htmlspecialchars($row['category_name']) ?></td>
                            <td class="p-4 font-semibold text-slate-800">Rp <?= number_format($row['price'], 0, ',', '.') ?> <span class="text-xs text-slate-500 font-normal"><?= htmlspecialchars($row['unit']) ?></span></td>
                            <td class="p-4">
                                <a href="?action=edit&id=<?= $row['id'] ?>" class="text-primary hover:text-blue-700 font-semibold text-sm mr-3">Edit</a>
                                <a href="?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="text-red-500 hover:text-red-700 font-semibold text-sm">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
