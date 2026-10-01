<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Food & Coffee</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="/food&shop/assets/css/style.css?v=10"
    >

    <style>

        .my-account {
            position: relative;
        }

        .my-account-button {
            border: none;
            background: transparent;
            color: white;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 16px;
        }

        .my-account-button:hover {
            color: #ffc107;
        }

        .my-account-box {
            display: none;
            position: absolute;
            right: 0;
            top: 45px;
            width: 220px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.25);
            z-index: 99999;
        }

        .my-account-box.show {
            display: block;
        }

        .my-account-box a {
            display: block;
            padding: 12px 15px;
            color: #333;
            text-decoration: none;
        }

        .my-account-box a:hover {
            background: #f2f2f2;
        }

        .my-account-box .logout {
            color: red;
        }

    </style>

</head>


<body>


<nav class="navbar navbar-dark bg-dark">

    <div class="container">


        <!-- LOGO -->

        <a
            class="navbar-brand"
            href="/food&shop/index.php"
        >
            ☕ Food And Drink
        </a>


        <!-- MENU -->

        <div class="d-flex align-items-center">


            <!-- SẢN PHẨM -->

            <a
                class="nav-link text-white px-3"
                href="/food&shop/user/products.php"
            >
                Sản phẩm
            </a>


            <!-- GIỚI THIỆU -->

            <a
                class="nav-link text-white px-3"
                href="/food&shop/user/about.php"
            >
                Giới thiệu
            </a>


            <!-- LIÊN HỆ -->

            <a
                class="nav-link text-white px-3"
                href="/food&shop/user/contact.php"
            >
                Liên hệ - Hỗ trợ
            </a>


            <?php if (isset($_SESSION['user_id'])) { ?>


                <!-- TÀI KHOẢN -->

                <div class="my-account">


                    <button
                        type="button"
                        class="my-account-button"
                        id="accountButton"
                    >

                        👤

                        <?php
                        echo htmlspecialchars($_SESSION['user_name']);
                        ?>

                        ▼

                    </button>


                    <!-- MENU TÀI KHOẢN -->

                    <div
                        class="my-account-box"
                        id="accountBox"
                    >

                        <a
                            href="/food&shop/user/profile.php"
                        >
                            👤 Tài khoản của tôi
                        </a>


                        <a
                            href="/food&shop/user/orders.php"
                        >
                            📦 Đơn hàng của tôi
                        </a>
                        <a
                            href="/food&shop/user/cart.php"
>
                               🛒 Giỏ hàng của tôi
                        </a>


                        <hr style="margin: 5px 0;">


                        <a
                            href="/food&shop/user/logout.php"
                            class="logout"
                        >
                            🚪 Đăng xuất
                        </a>

                    </div>

                </div>


            <?php } else { ?>


                <!-- CHƯA ĐĂNG NHẬP -->

                <a
                    class="nav-link text-white px-3"
                    href="/food&shop/user/login.php"
                >
                    👤 Tài khoản của tôi
                </a>


            <?php } ?>


        </div>

    </div>

</nav>


<script>

document.addEventListener("DOMContentLoaded", function () {

    const button = document.getElementById("accountButton");
    const box = document.getElementById("accountBox");

    if (button && box) {

        button.addEventListener("click", function (event) {

            event.preventDefault();
            event.stopPropagation();

            box.classList.toggle("show");

        });


        document.addEventListener("click", function (event) {

            if (!box.contains(event.target) &&
                !button.contains(event.target)) {

                box.classList.remove("show");

            }

        });

    }

});

</script>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>