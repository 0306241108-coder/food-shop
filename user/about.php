<?php

session_start();

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Giới thiệu - Food And Drink</title>


    <!-- BOOTSTRAP -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- CSS -->

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>


<body>


<!-- =================================
     MENU USER
================================= -->

<nav class="navbar navbar-dark bg-dark">

    <div class="container">


        <!-- LOGO -->

        <a
            class="navbar-brand"
            href="../index.php"
        >

            ☕ Food And Drink

        </a>


        <!-- MENU -->

        <div class="d-flex align-items-center">


            <!-- SẢN PHẨM -->

            <a
                class="nav-link text-white px-3"
                href="products.php"
            >

                Sản phẩm

            </a>


            <!-- GIỚI THIỆU -->

            <a
                class="nav-link text-white px-3"
                href="about.php"
            >

                Giới thiệu

            </a>


            <!-- LIÊN HỆ -->

            <a
                class="nav-link text-white px-3"
                href="contact.php"
            >

                Liên Hệ - Hỗ trợ

            </a>


            <!-- TÀI KHOẢN -->

            <?php if (
                isset($_SESSION['user_id'])
            ) { ?>


                <div class="dropdown">

                    <a
                        href="#"
                        class="nav-link text-white px-3 dropdown-toggle"
                        data-bs-toggle="dropdown"
                    >

                        👤

                        <?php

                        echo htmlspecialchars(
                            $_SESSION['user_name']
                        );

                        ?>

                    </a>


                    <ul
                        class="dropdown-menu dropdown-menu-end"
                    >


                        <li>

                            <a
                                class="dropdown-item"
                                href="profile.php"
                            >

                                👤 Tài khoản của tôi

                            </a>

                        </li>


                        <li>

                            <a
                                class="dropdown-item"
                                href="orders.php"
                            >

                                📦 Đơn hàng của tôi

                            </a>

                        </li>


                        <li>

                            <hr
                                class="dropdown-divider"
                            >

                        </li>


                        <li>

                            <a
                                class="dropdown-item text-danger"
                                href="logout.php"
                            >

                                🚪 Đăng xuất

                            </a>

                        </li>


                    </ul>

                </div>


            <?php } else { ?>


                <a
                    class="nav-link text-white px-3"
                    href="login.php"
                >

                    👤 Tài khoản của tôi

                </a>


            <?php } ?>


        </div>

    </div>

</nav>


<!-- =================================
     NỘI DUNG GIỚI THIỆU
================================= -->

<div class="container mt-5 mb-5">


    <!-- TIÊU ĐỀ -->

    <div class="text-center mb-5">

        <h1>

            ☕ Giới thiệu Food And Drink

        </h1>

        <p class="text-muted">

            Đồ uống và đồ ăn ngon - phục vụ mỗi ngày

        </p>

    </div>


    <!-- GIỚI THIỆU -->

    <div class="row align-items-center mb-5">


        <div class="col-md-6">

            <img
    src="../assets/img/categories/Logo.png"
    class="img-fluid rounded shadow"
    alt="Food And Drink"
    style="max-height:300px; object-fit:contain;"
>
        </div>


        <div class="col-md-6 mt-4 mt-md-0">

            <h2>

                Về Food And Drink

            </h2>


            <p>

                Food And Drink là cửa hàng chuyên cung cấp
                các loại đồ uống và đồ ăn nhẹ dành cho mọi
                khách hàng.

            </p>


            <p>

                Tại đây, khách hàng có thể dễ dàng tìm kiếm
                sản phẩm, xem thông tin, thêm sản phẩm vào
                giỏ hàng và đặt hàng trực tuyến.

            </p>


            <p>

                Chúng tôi hướng đến sự tiện lợi, nhanh chóng
                và mang đến trải nghiệm mua hàng đơn giản
                cho khách hàng.

            </p>

        </div>

    </div>


    <!-- SẢN PHẨM -->

    <div class="text-center mb-4">

        <h2>

            🍰 Sản phẩm của chúng tôi

        </h2>

    </div>


    <div class="row text-center mb-5">


        <!-- COFFEE -->

        <div class="col-md-3 mb-4">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <h3>
                        ☕
                    </h3>

                    <h5>
                        Coffee
                    </h5>

                    <p>
                        Các loại cà phê thơm ngon,
                        phù hợp cho học tập và làm việc.
                    </p>

                </div>

            </div>

        </div>


        <!-- TRÀ SỮA -->

        <div class="col-md-3 mb-4">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <h3>
                        🧋
                    </h3>

                    <h5>
                        Trà sữa
                    </h5>

                    <p>
                        Nhiều loại trà sữa với
                        hương vị đa dạng.
                    </p>

                </div>

            </div>

        </div>


        <!-- ĐỒ ĂN -->

        <div class="col-md-3 mb-4">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <h3>
                        🍟
                    </h3>

                    <h5>
                        Đồ ăn vặt
                    </h5>

                    <p>
                        Các món ăn vặt thích hợp
                        dùng cùng đồ uống.
                    </p>

                </div>

            </div>

        </div>


        <!-- BÁNH -->

        <div class="col-md-3 mb-4">

            <div class="card h-100 shadow-sm">

                <div class="card-body">

                    <h3>
                        🍰
                    </h3>

                    <h5>
                        Bánh ngọt
                    </h5>

                    <p>
                        Các loại bánh ngọt thơm ngon
                        cho những buổi ăn nhẹ.
                    </p>

                </div>

            </div>

        </div>


    </div>


    <!-- ĐIỂM NỔI BẬT -->

    <div class="card shadow-sm mb-5">

        <div class="card-body">

            <h2 class="text-center mb-4">

                ⭐ Vì sao chọn Food And Drink?

            </h2>


            <div class="row">


                <div class="col-md-4 text-center mb-3">

                    <h3>
                        🛒
                    </h3>

                    <h5>
                        Đặt hàng dễ dàng
                    </h5>

                    <p>
                        Khách hàng có thể đặt hàng
                        trực tuyến nhanh chóng.
                    </p>

                </div>


                <div class="col-md-4 text-center mb-3">

                    <h3>
                        📦
                    </h3>

                    <h5>
                        Theo dõi đơn hàng
                    </h5>

                    <p>
                        Dễ dàng theo dõi trạng thái
                        đơn hàng của mình.
                    </p>

                </div>


                <div class="col-md-4 text-center mb-3">

                    <h3>
                        💬
                    </h3>

                    <h5>
                        Hỗ trợ khách hàng
                    </h5>

                    <p>
                        Luôn sẵn sàng hỗ trợ khi
                        khách hàng cần.
                    </p>

                </div>


            </div>

        </div>

    </div>


    <!-- NÚT -->

    <div class="text-center">

        <a
            href="products.php"
            class="btn btn-warning btn-lg"
        >

            🛒 Xem sản phẩm

        </a>


        <a
            href="contact.php"
            class="btn btn-dark btn-lg ms-2"
        >

            💬 Liên hệ với chúng tôi

        </a>

    </div>


</div>


<!-- =================================
     FOOTER
================================= -->

<footer class="text-center mt-5 mb-4">

    © 2026 Food And Drink

</footer>


<!-- BOOTSTRAP JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>