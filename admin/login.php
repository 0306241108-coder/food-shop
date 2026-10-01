<?php

session_start();

require_once "../config/database.php";

// Nếu đã đăng nhập Admin
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

// Đăng nhập
if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $username = mysqli_real_escape_string(
        $conn,
        $username
    );

    $sql = "
        SELECT *
        FROM admin_accounts
        WHERE username = '$username'
        LIMIT 1
    ";

    $result = mysqli_query($conn, $sql);

    $admin = mysqli_fetch_assoc($result);

    // Kiểm tra tài khoản và mật khẩu
    if (
        $admin &&
        $password == $admin['password']
    ) {

        $_SESSION['admin_id'] = $admin['id'];

        $_SESSION['admin_username'] =
            $admin['username'];

        // Đăng nhập thành công -> Dashboard
        header("Location: dashboard.php");
        exit;

    } else {

        $error = "❌ Tên đăng nhập hoặc mật khẩu không đúng.";

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

    <title>Đăng nhập Admin</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f2f2f2;
        }

        .login-box {
            max-width: 400px;
            margin: 100px auto;
        }

    </style>

</head>

<body>

<div class="login-box">

    <div class="card shadow">

        <div class="card-header bg-dark text-white text-center">

            <h4 class="mb-0">
                🔐 Đăng nhập Admin
            </h4>

        </div>

        <div class="card-body">

            <?php if ($error != "") { ?>

                <div class="alert alert-danger">

                    <?php echo $error; ?>

                </div>

            <?php } ?>

            <form method="POST">

                <div class="mb-3">

                    <label class="form-label">
                        Tên đăng nhập
                    </label>

                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Mật khẩu
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        required
                    >

                </div>

                <button
                    type="submit"
                    name="login"
                    class="btn btn-dark w-100"
                >
                    🔐 Đăng nhập
                </button>

            </form>

            <div class="text-center mt-3">

                <a href="../index.php">
                    ← Về trang khách hàng
                </a>

            </div>

        </div>

    </div>

</div>

</body>

</html>