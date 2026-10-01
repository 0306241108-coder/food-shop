<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin - Food And Drink</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="../assets/css/style.css?v=20"
    >
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand" href="dashboard.php">
            ☕ Food And Drink - Admin
        </a>

        <div class="d-flex align-items-center gap-3">

            <a class="text-white text-decoration-none"
               href="dashboard.php">
                🏠 Trang chủ
            </a>

            <a class="text-white text-decoration-none"
               href="products.php">
                📦 Sản phẩm
            </a>

            <a class="text-white text-decoration-none"
               href="categories.php">
                🗂️ Danh mục
            </a>

            <a class="text-white text-decoration-none"
               href="orders.php">
                🛒 Đơn hàng
            </a>

            <a class="text-white text-decoration-none"
               href="inventory.php">
                📊 Kho hàng
            </a>

            <a class="text-white text-decoration-none"
               href="users.php">
                👤 Tài khoản
            </a>

            <a class="btn btn-danger btn-sm"
               href="logout.php">
                Đăng xuất
            </a>

        </div>

    </div>
</nav>