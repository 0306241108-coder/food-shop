<?php

session_start();

require_once "../config/database.php";


// ======================================
// NẾU ĐÃ ĐĂNG NHẬP
// ======================================

if (isset($_SESSION['user_id'])) {

    header("Location: ../index.php");
    exit;

}


$error = "";


// ======================================
// XỬ LÝ ĐĂNG NHẬP
// ======================================

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];


    // KIỂM TRA TRỐNG

    if ($email == "" || $password == "") {

        $error = "Vui lòng nhập đầy đủ email và mật khẩu.";

    } else {


        // TÌM TÀI KHOẢN

        $email_safe = mysqli_real_escape_string($conn, $email);

        $sql = "
            SELECT *
            FROM users
            WHERE email = '$email_safe'
            LIMIT 1
        ";

        $result = mysqli_query($conn, $sql);


        if ($result && mysqli_num_rows($result) > 0) {

            $user = mysqli_fetch_assoc($result);


            // ======================================
            // KIỂM TRA MẬT KHẨU
            // ======================================

            if (password_verify($password, $user['password'])) {


                // TẠO SESSION

                $_SESSION['user_id'] = $user['id'];

                $_SESSION['user_name'] = $user['fullname'];

                $_SESSION['user_email'] = $user['email'];


                // ĐĂNG NHẬP THÀNH CÔNG

                header("Location: ../index.php");
                exit;


            } else {

                $error = "Mật khẩu không đúng.";

            }


        } else {

            $error = "Email không tồn tại.";

        }

    }

}

?>


<!DOCTYPE html>

<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Đăng nhập - Food And Drink
    </title>


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


<!-- ======================================
     MENU
====================================== -->

<nav class="navbar navbar-dark bg-dark">

    <div class="container">


        <a
            class="navbar-brand"
            href="../index.php"
        >

            ☕ Food And Drink

        </a>


        <div>

            <a
                class="nav-link d-inline-block text-white"
                href="../index.php"
            >

                Trang chủ

            </a>


            <a
                class="nav-link d-inline-block text-white"
                href="products.php"
            >

                Sản phẩm

            </a>

        </div>


    </div>

</nav>



<!-- ======================================
     FORM ĐĂNG NHẬP
====================================== -->

<div class="container">

    <div class="row justify-content-center mt-5 mb-5">

        <div class="col-md-5">


            <div class="card shadow-sm">

                <div class="card-body p-4">


                    <h2 class="text-center mb-4">

                        🔐 Đăng nhập

                    </h2>


                    <!-- THÔNG BÁO LỖI -->

                    <?php if ($error != "") { ?>

                        <div class="alert alert-danger">

                            <?php echo $error; ?>

                        </div>

                    <?php } ?>


                    <!-- FORM -->

                    <form
                        method="POST"
                        action=""
                    >


                        <!-- EMAIL -->

                        <div class="mb-3">

                            <label class="form-label">

                                Email

                            </label>


                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Nhập email"
                                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                required
                            >

                        </div>



                        <!-- MẬT KHẨU -->

                        <div class="mb-3">

                            <label class="form-label">

                                Mật khẩu

                            </label>


                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Nhập mật khẩu"
                                required
                            >

                        </div>



                        <!-- NÚT ĐĂNG NHẬP -->

                        <button
                            type="submit"
                            name="login"
                            class="btn btn-warning w-100"
                        >

                            Đăng nhập

                        </button>


                    </form>



                    <!-- ĐĂNG KÝ -->

                    <div class="text-center mt-4">

                        <p class="mb-2">

                            Chưa có tài khoản?

                        </p>


                        <a
                            href="register.php"
                            class="btn btn-outline-secondary"
                        >

                            Đăng ký tài khoản

                        </a>

                    </div>



                    <!-- QUAY LẠI -->

                    <div class="text-center mt-3">

                        <a
                            href="../index.php"
                            class="text-decoration-none"
                        >

                            ← Quay lại trang chủ

                        </a>

                    </div>


                </div>

            </div>


        </div>

    </div>

</div>



<!-- ======================================
     FOOTER
====================================== -->

<footer>

    © 2026 Food And Drink

</footer>


</body>

</html>