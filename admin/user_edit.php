<?php
ob_start();

require_once "header_admin.php";
require_once "../config/database.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: users.php");
    exit;
}

// Lấy thông tin tài khoản
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: users.php");
    exit;
}

$error = "";

// Khi bấm cập nhật
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $password = trim($_POST['password']);

    // Kiểm tra dữ liệu
    if ($fullname == "" || $email == "") {
        $error = "Vui lòng nhập đầy đủ họ tên và email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ.";
    } elseif ($role != "user" && $role != "admin") {
        $error = "Quyền tài khoản không hợp lệ.";
    } else {

        // Kiểm tra email đã được tài khoản khác sử dụng chưa
        $check = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt = mysqli_prepare($conn, $check);
        mysqli_stmt_bind_param($stmt, "si", $email, $id);
        mysqli_stmt_execute($stmt);

        $check_result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($check_result) > 0) {

            $error = "Email này đã được sử dụng.";

        } else {

            // Nếu có nhập mật khẩu mới
            if ($password != "") {

                // Hash mật khẩu
                $hashed_password = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                $sql = "UPDATE users 
                        SET fullname = ?, email = ?, password = ?, role = ?
                        WHERE id = ?";

                $stmt = mysqli_prepare($conn, $sql);

                mysqli_stmt_bind_param(
                    $stmt,
                    "ssssi",
                    $fullname,
                    $email,
                    $hashed_password,
                    $role,
                    $id
                );

            } else {

                // Không nhập mật khẩu -> giữ mật khẩu cũ
                $sql = "UPDATE users 
                        SET fullname = ?, email = ?, role = ?
                        WHERE id = ?";

                $stmt = mysqli_prepare($conn, $sql);

                mysqli_stmt_bind_param(
                    $stmt,
                    "sssi",
                    $fullname,
                    $email,
                    $role,
                    $id
                );
            }

            if (mysqli_stmt_execute($stmt)) {

                header("Location: users.php");
                exit;

            } else {

                $error = "Có lỗi xảy ra khi cập nhật.";
            }
        }
    }
}
?>

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>✏️ Chỉnh sửa tài khoản</h2>

        <a href="users.php" class="btn btn-secondary">
            ← Quay lại
        </a>
    </div>

    <?php if ($error != ""): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST">

                <!-- Họ tên -->
                <div class="mb-3">
                    <label class="form-label">
                        Họ và tên
                    </label>

                    <input
                        type="text"
                        name="fullname"
                        class="form-control"
                        value="<?= htmlspecialchars($user['fullname']) ?>"
                        required
                    >
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($user['email']) ?>"
                        required
                    >
                </div>

                <!-- Quyền -->
                <div class="mb-3">
                    <label class="form-label">
                        Quyền tài khoản
                    </label>

                    <select name="role" class="form-select">

                        <option value="user"
                            <?= $user['role'] == 'user' ? 'selected' : '' ?>>
                            Người dùng
                        </option>

                        <option value="admin"
                            <?= $user['role'] == 'admin' ? 'selected' : '' ?>>
                            Admin
                        </option>

                    </select>
                </div>

                <!-- Mật khẩu -->
                <div class="mb-3">
                    <label class="form-label">
                        Mật khẩu mới
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Nhập mật khẩu mới nếu muốn đổi"
                    >

                    <small class="text-muted">
                        Để trống nếu không muốn thay đổi mật khẩu.
                    </small>
                </div>

                <button type="submit" class="btn btn-primary">
                    💾 Cập nhật
                </button>

                <a href="users.php" class="btn btn-secondary">
                    Hủy
                </a>

            </form>

        </div>
    </div>

</div>

<?php ob_end_flush(); ?>