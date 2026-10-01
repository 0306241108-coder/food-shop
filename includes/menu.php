<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<style>
.account-menu {
    position: relative;
}

.account-button {
    background: none;
    border: none;
    color: white;
    padding: 8px 15px;
    cursor: pointer;
    font-size: 16px;
}

.account-dropdown {
    display: none;
    position: absolute;
    right: 0;
    top: 45px;
    min-width: 210px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,.2);
    z-index: 9999;
}

.account-dropdown.show {
    display: block;
}

.account-dropdown a {
    display: block;
    padding: 10px 15px;
    color: #333;
    text-decoration: none;
}

.account-dropdown a:hover {
    background: #f1f1f1;
}

.account-dropdown .logout {
    color: red;
}
</style>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">

    <div class="container">

        <!-- LOGO -->
        <a
            class="navbar-brand"
            href="/food&shop/index.php"
        >
            ☕ Food And Drink
        </a>


        <!-- MOBILE -->
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#menu"
        >
            <span class="navbar-toggler-icon"></span>
        </button>


        <!-- MENU -->
        <div
            class="collapse navbar-collapse"
            id="menu"
        >

            <ul class="navbar-nav ms-auto align-items-center">

                <!-- TRANG CHỦ -->
                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="/food&shop/index.php"
                    >
                        Trang chủ
                    </a>
                </li>


                <!-- SẢN PHẨM -->
                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="/food&shop/user/products.php"
                    >
                        Sản phẩm
                    </a>
                </li>


                <!-- GIỚI THIỆU -->
                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="/food&shop/user/about.php"
                    >
                        Giới thiệu
                    </a>
                </li>


                <!-- LIÊN HỆ -->
                <li class="nav-item">
                    <a
                        class="nav-link"
                        href="/food&shop/user/contact.php"
                    >
                        Liên hệ - Hỗ trợ
                    </a>
                </li>


                <?php if (isset($_SESSION['user_id'])) { ?>

                    <!-- TÀI KHOẢN -->
                    <li class="nav-item account-menu">

                        <button
                            type="button"
                            class="account-button"
                            onclick="toggleAccount()"
                        >
                            👤
                            <?php
                            echo htmlspecialchars($_SESSION['user_name']);
                            ?>
                            ▼
                        </button>


                        <!-- MENU TÀI KHOẢN -->
                        <div
                            id="accountDropdown"
                            class="account-dropdown"
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


                            <hr style="margin:5px 0;">


                            <a
                                href="/food&shop/user/logout.php"
                                class="logout"
                            >
                                🚪 Đăng xuất
                            </a>

                        </div>

                    </li>


                <?php } else { ?>

                    <!-- CHƯA ĐĂNG NHẬP -->
                    <li class="nav-item">

                        <a
                            class="nav-link"
                            href="/food&shop/user/login.php"
                        >
                            👤 Đăng nhập
                        </a>

                    </li>

                <?php } ?>

            </ul>

        </div>

    </div>

</nav>


<script>

function toggleAccount() {

    const menu = document.getElementById("accountDropdown");

    menu.classList.toggle("show");

}


// Bấm ra ngoài thì đóng menu
document.addEventListener("click", function(event) {

    const account = document.querySelector(".account-menu");

    const menu = document.getElementById("accountDropdown");

    if (
        account &&
        menu &&
        !account.contains(event.target)
    ) {

        menu.classList.remove("show");

    }

});

</script>