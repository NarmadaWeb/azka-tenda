<?php
session_start();
include 'includes/config.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : (isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0);
$quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

if ($action === 'add' && $product_id > 0) {
    // Fetch product details
    $stmt = $conn->prepare("SELECT id, name, price, image FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($product = $result->fetch_assoc()) {
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity
            ];
        }
    }
    header('Location: order.php');
    exit;
} elseif ($action === 'remove' && $product_id > 0) {
    if (isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
    }
    header('Location: order.php');
    exit;
} elseif ($action === 'update' && $product_id > 0) {
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] = $quantity;
    }
    header('Location: order.php');
    exit;
}

header('Location: catalog.php');
exit;
