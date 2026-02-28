<?php
session_start();
include '../includes/config.php';

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            header('Location: index.php');
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Admin Login - Azka Tenda</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700;800&display=swap" rel="stylesheet"/>
    <style>body { font-family: 'Work Sans', sans-serif; }</style>
    <script>
        tailwind.config = {
            theme: { extend: { colors: { primary: "#135bec", "background-light": "#f6f6f8" } } }
        }
    </script>
</head>
<body class="bg-background-light flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-900">Admin Login</h1>
            <p class="text-sm text-slate-500">Azka Tenda Management System</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Username</label>
                <input type="text" name="username" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-primary focus:border-primary" />
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-primary focus:border-primary" />
            </div>
            <button type="submit" class="w-full bg-primary text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors mt-4">Login</button>
        </form>
    </div>
</body>
</html>
