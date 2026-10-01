<?php

session_start();

require_once "../config/database.php";

$error = "";
$success = "";


// ================================
// XỬ LÝ ĐĂNG KÝ
// ================================

if (isset($_POST['register'])) {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    // Kiểm tra dữ liệu

    if (
        $fullname == "" ||
        $email == "" ||
        $password == "" ||
        $confirm_password == ""
    ) {

        $error = "Vui lòng nhập đầy đủ thông tin.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Email không hợp lệ.";

    } elseif (strlen($password) < 6) {

        $error = "Mật khẩu phải có ít nhất 6 ký tự.";

    } elseif ($password != $confirm_password) {

        $error = "Mật khẩu xác nhận không giống nhau.";

    } else {


        // ================================
        // KIỂM TRA EMAIL
        // ================================

        $email_safe = mysqli_real_escape_string(
            $conn,
            $email
        );


        $sql_check = "
            SELECT id
            FROM users
            WHERE email = '$email_safe'
            LIMIT 1
        ";


        $result_check = mysqli_query(
            $conn,
            $sql_check
        );


        if (
            $result_check &&
            mysqli_num_rows($result_check) > 0
        ) {

            $error = "Email này đã được sử dụng.";

        } else {


            // ================================
            // MÃ HÓA MẬT KHẨU
            // ================================

            $password_hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );


            $fullname_safe = mysqli_real_escape_string(
                $conn,
                $fullname
            );


            // ================================
            // THÊM TÀI KHOẢN
            // ================================

            $sql_insert = "
                INSERT INTO users
                (
                    fullname,
                    email,
                    password
                )
                VALUES
                (
                    '$fullname_safe',
                    '$email_safe',
                    '$password_hash'
                )
            ";


            if (mysqli_query($conn, $sql_insert)) {

                $success = "Tạo tài khoản thành công! Bạn có thể đăng nhập.";

                $fullname = "";
                $email = "";

            } else {

                $error = "Không thể tạo tài khoản: "
                    . mysqli_error($conn);

            }

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
        Đăng ký - Food And Drink
    </title>


    <!-- Bootstrap -->

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


<!-- ================================
     MENU
================================ -->

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



<!-- ================================
     ĐĂNG KÝ
================================ -->

<div class="container">

    <div class="row justify-content-center mt-5 mb-5">

        <div class="col-md-5">


            <div class="card shadow-sm">

                <div class="card-body p-4">


                    <h2 class="text-center mb-4">

                        📝 Tạo tài khoản

                    </h2>


                    <!-- LỖI -->

                    <?php if ($error != "") { ?>

                        <div class="alert alert-danger">

                            <?php echo htmlspecialchars($error); ?>

                        </div>

                    <?php } ?>


                    <!-- THÀNH CÔNG -->

                    <?php if ($success != "") { ?>

                        <div class="alert alert-success">

                            <?php echo htmlspecialchars($success); ?>

                        </div>

                    <?php } ?>


                    <!-- FORM -->

                    <form
                        method="POST"
                        action=""
                    >


                        <!-- HỌ TÊN -->

                        <div class="mb-3">

                            <label class="form-label">

                                Họ và tên

                            </label>


                            <input
                                type="text"
                                name="fullname"
                                class="form-control"
                                placeholder="Nhập họ và tên"
                                value="<?php
                                    echo isset($fullname)
                                        ? htmlspecialchars($fullname)
                                        : '';
                                ?>"
                                required
                            >

                        </div>



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
                                value="<?php
                                    echo isset($email)
                                        ? htmlspecialchars($email)
                                        : '';
                                ?>"
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
                                placeholder="Ít nhất 6 ký tự"
                                required
                            >

                        </div>



                        <!-- NHẬP LẠI -->

                        <div class="mb-3">

                            <label class="form-label">

                                Nhập lại mật khẩu

                            </label>


                            <input
                                type="password"
                                name="confirm_password"
                                class="form-control"
                                placeholder="Nhập lại mật khẩu"
                                required
                            >

                        </div>



                        <!-- NÚT ĐĂNG KÝ -->

                        <button
                            type="submit"
                            name="register"
                            class="btn btn-warning w-100"
                        >

                            Tạo tài khoản

                        </button>


                    </form>



                    <!-- ĐĂNG NHẬP -->

                    <div class="text-center mt-4">

                        <p>

                            Đã có tài khoản?

                        </p>


                        <a
                            href="login.php"
                            class="btn btn-outline-secondary"
                        >

                            Đăng nhập

                        </a>

                    </div>



                    <!-- TRANG CHỦ -->

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



<!-- FOOTER -->

<footer>

    © 2026 Food And Drink

</footer>


</body>

</html>