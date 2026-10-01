<?php
ob_start();

require_once "header_admin.php";
require_once "../config/database.php";


/* =========================
   THỐNG KÊ SẢN PHẨM
========================= */

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM products"
);

$product = mysqli_fetch_assoc($result);
$total_products = $product['total'];


/* =========================
   THỐNG KÊ DANH MỤC
========================= */

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM categories"
);

$category = mysqli_fetch_assoc($result);
$total_categories = $category['total'];


/* =========================
   THỐNG KÊ ĐƠN HÀNG
========================= */

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM orders"
);

$order = mysqli_fetch_assoc($result);
$total_orders = $order['total'];


/* =========================
   THỐNG KÊ TÀI KHOẢN
========================= */

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM users"
);

$user = mysqli_fetch_assoc($result);
$total_users = $user['total'];


/* =========================
   TỔNG DOANH THU
========================= */

$result = mysqli_query(
    $conn,
    "
    SELECT SUM(total) AS revenue
    FROM orders
    WHERE status IN ('confirmed', 'shipping', 'completed')
    "
);

$revenue = mysqli_fetch_assoc($result);

$total_revenue = $revenue['revenue'] ?? 0;


/* =========================
   SẢN PHẨM SẮP HẾT
========================= */

$result = mysqli_query(
    $conn,
    "
    SELECT COUNT(*) AS total
    FROM products
    WHERE quantity BETWEEN 1 AND 5
    "
);

$low = mysqli_fetch_assoc($result);
$total_low = $low['total'];


/* =========================
   SẢN PHẨM HẾT HÀNG
========================= */

$result = mysqli_query(
    $conn,
    "
    SELECT COUNT(*) AS total
    FROM products
    WHERE quantity <= 0
    "
);

$out = mysqli_fetch_assoc($result);
$total_out = $out['total'];

?>

<div class="container mt-5 mb-5">

    <!-- =========================
         TIÊU ĐỀ
    ========================= -->

    <div class="mb-4">

        <h2>
            🏠 Trang quản trị
        </h2>

        <p class="text-muted">
            Chào mừng bạn đến với hệ thống quản lý Food And Drink.
        </p>

    </div>


    <!-- =========================
         THỐNG KÊ
    ========================= -->

    <div class="row g-4">


        <!-- SẢN PHẨM -->

        <div class="col-md-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h5 class="card-title">
                        📦 Sản phẩm
                    </h5>

                    <h2 class="text-primary">
                        <?php echo $total_products; ?>
                    </h2>

                    <p class="text-muted">
                        Tổng số sản phẩm
                    </p>

                    <a
                        href="products.php"
                        class="btn btn-primary"
                    >
                        Quản lý sản phẩm
                    </a>

                </div>

            </div>

        </div>


        <!-- DANH MỤC -->

        <div class="col-md-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h5 class="card-title">
                        🗂️ Danh mục
                    </h5>

                    <h2 class="text-success">
                        <?php echo $total_categories; ?>
                    </h2>

                    <p class="text-muted">
                        Tổng số danh mục
                    </p>

                    <a
                        href="categories.php"
                        class="btn btn-success"
                    >
                        Quản lý danh mục
                    </a>

                </div>

            </div>

        </div>


        <!-- ĐƠN HÀNG -->

        <div class="col-md-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h5 class="card-title">
                        🧾 Đơn hàng
                    </h5>

                    <h2 class="text-warning">
                        <?php echo $total_orders; ?>
                    </h2>

                    <p class="text-muted">
                        Tổng số đơn hàng
                    </p>

                    <a
                        href="orders.php"
                        class="btn btn-warning"
                    >
                        Quản lý đơn hàng
                    </a>

                </div>

            </div>

        </div>


        <!-- TÀI KHOẢN -->

        <div class="col-md-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h5 class="card-title">
                        👥 Tài khoản
                    </h5>

                    <h2 class="text-info">
                        <?php echo $total_users; ?>
                    </h2>

                    <p class="text-muted">
                        Tổng số tài khoản khách hàng
                    </p>

                    <a
                        href="users.php"
                        class="btn btn-info"
                    >
                        Quản lý tài khoản
                    </a>

                </div>

            </div>

        </div>


        <!-- DOANH THU -->

        <div class="col-md-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h5 class="card-title">
                        💰 Doanh thu
                    </h5>

                    <h2 class="text-danger">

                        <?php

                        echo number_format(
                            $total_revenue,
                            0,
                            ',',
                            '.'
                        );

                        ?>đ

                    </h2>

                    <p class="text-muted">
                        Doanh thu từ đơn hàng
                    </p>

                    <a
                        href="orders.php"
                        class="btn btn-danger"
                    >
                        Xem đơn hàng
                    </a>

                </div>

            </div>

        </div>


        <!-- KHO HÀNG -->

        <div class="col-md-4">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <h5 class="card-title">
                        📊 Kho hàng
                    </h5>

                    <h2 class="text-secondary">
                        <?php echo $total_low + $total_out; ?>
                    </h2>

                    <p class="text-muted">
                        Sản phẩm cần chú ý về tồn kho
                    </p>

                    <a
                        href="inventory.php"
                        class="btn btn-secondary"
                    >
                        Quản lý kho
                    </a>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================
         CẢNH BÁO KHO
    ========================= -->

    <div class="card mt-5 shadow-sm">

        <div class="card-header bg-dark text-white">

            ⚠️ Tình trạng kho hàng

        </div>

        <div class="card-body">

            <div class="row text-center">

                <div class="col-md-6">

                    <h3 class="text-warning">
                        <?php echo $total_low; ?>
                    </h3>

                    <p>
                        🟠 Sản phẩm sắp hết
                    </p>

                </div>


                <div class="col-md-6">

                    <h3 class="text-danger">
                        <?php echo $total_out; ?>
                    </h3>

                    <p>
                        🔴 Sản phẩm hết hàng
                    </p>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================
         TRUY CẬP NHANH
    ========================= -->

    <div class="card mt-4 shadow-sm">

        <div class="card-header bg-dark text-white">

            ⚡ Truy cập nhanh

        </div>

        <div class="card-body">

            <div class="d-flex flex-wrap gap-2">

                <a
                    href="products.php"
                    class="btn btn-primary"
                >
                    📦 Sản phẩm
                </a>

                <a
                    href="categories.php"
                    class="btn btn-success"
                >
                    🗂️ Danh mục
                </a>

                <a
                    href="orders.php"
                    class="btn btn-warning"
                >
                    🧾 Đơn hàng
                </a>

                <a
                    href="inventory.php"
                    class="btn btn-secondary"
                >
                    📊 Kho hàng
                </a>

                <a
                    href="users.php"
                    class="btn btn-info"
                >
                    👥 Tài khoản
                </a>

            </div>

        </div>

    </div>

</div>


<footer class="text-center mt-5 mb-4">

    © 2026 Food And Drink - Admin

</footer>

<?php
ob_end_flush();
?>