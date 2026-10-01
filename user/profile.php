<?php

session_start();

require_once "../config/database.php";


// ======================================
// KIỂM TRA ĐĂNG NHẬP
// ======================================

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit;

}


$user_id = $_SESSION['user_id'];

$error = "";
$success = "";


// ======================================
// LẤY THÔNG TIN TÀI KHOẢN
// ======================================

$sql = "
    SELECT *
    FROM users
    WHERE id = '$user_id'
    LIMIT 1
";

$result = mysqli_query($conn, $sql);


if ($result && mysqli_num_rows($result) > 0) {

    $user = mysqli_fetch_assoc($result);

} else {

    die("Không tìm thấy tài khoản.");

}


// ======================================
// CẬP NHẬT THÔNG TIN
// ======================================

if (isset($_POST['update_profile'])) {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);


    if ($fullname == "" || $email == "") {

        $error = "Vui lòng nhập đầy đủ thông tin.";

    } else {


        $fullname_safe = mysqli_real_escape_string(
            $conn,
            $fullname
        );

        $email_safe = mysqli_real_escape_string(
            $conn,
            $email
        );


        // Kiểm tra email đã được tài khoản khác sử dụng chưa

        $check_sql = "
            SELECT id
            FROM users
            WHERE email = '$email_safe'
            AND id != '$user_id'
            LIMIT 1
        ";

        $check_result = mysqli_query(
            $conn,
            $check_sql
        );


        if (
            $check_result &&
            mysqli_num_rows($check_result) > 0
        ) {

            $error = "Email này đã được sử dụng.";

        } else {


            // Cập nhật thông tin

            $update_sql = "
                UPDATE users
                SET
                    fullname = '$fullname_safe',
                    email = '$email_safe'
                WHERE id = '$user_id'
            ";


            if (mysqli_query($conn, $update_sql)) {

                // Cập nhật session

                $_SESSION['user_name'] = $fullname;

                $_SESSION['user_email'] = $email;


                $success = "Cập nhật thông tin thành công.";


                // Lấy lại dữ liệu

                $result = mysqli_query(
                    $conn,
                    $sql
                );

                $user = mysqli_fetch_assoc($result);

            } else {

                $error = "Không thể cập nhật tài khoản: "
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
        Tài khoản của tôi - Food And Drink
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


        <!-- LOGO -->

        <a
            class="navbar-brand"
            href="../index.php"
        >

            ☕ Food And Drink

        </a>


        <!-- MENU -->

        <div>

            <a
                class="nav-link d-inline-block text-white px-3"
                href="../index.php"
            >

                Trang chủ

            </a>


            <a
                class="nav-link d-inline-block text-white px-3"
                href="products.php"
            >

                Sản phẩm

            </a>


            <a
                class="nav-link d-inline-block text-white px-3"
                href="contact.php"
            >

                Liên hệ

            </a>


            <a
                class="nav-link d-inline-block text-white px-3"
                href="logout.php"
            >

                Đăng xuất

            </a>

        </div>

    </div>

</nav>


<!-- ======================================
     TÀI KHOẢN
====================================== -->

<div class="container">

    <div class="row justify-content-center mt-5 mb-5">

        <div class="col-md-6">


            <div class="card shadow-sm">

                <div class="card-body p-4">


                    <h2 class="text-center mb-4">

                        👤 Tài khoản của tôi

                    </h2>


                    <!-- THÔNG BÁO LỖI -->

                    <?php if ($error != "") { ?>

                        <div class="alert alert-danger">

                            <?php echo $error; ?>

                        </div>

                    <?php } ?>


                    <!-- THÔNG BÁO THÀNH CÔNG -->

                    <?php if ($success != "") { ?>

                        <div class="alert alert-success">

                            <?php echo $success; ?>

                        </div>

                    <?php } ?>


                    <!-- FORM -->

                    <form
                        method="POST"
                        action=""
                    >


                        <!-- HỌ VÀ TÊN -->

                        <div class="mb-3">

                            <label class="form-label">

                                Họ và tên

                            </label>


                            <input
                                type="text"
                                name="fullname"
                                class="form-control"
                                value="<?php echo htmlspecialchars($user['fullname']); ?>"
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
                                value="<?php echo htmlspecialchars($user['email']); ?>"
                                required
                            >

                        </div>


                        <!-- NÚT LƯU -->

                        <button
                            type="submit"
                            name="update_profile"
                            class="btn btn-warning w-100"
                        >

                            Lưu thay đổi

                        </button>


                    </form>


                    <!-- ĐƠN HÀNG -->

                    <div class="text-center mt-4">

                        <a
                            href="orders.php"
                            class="btn btn-outline-secondary"
                        >

                            📦 Đơn hàng của tôi

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