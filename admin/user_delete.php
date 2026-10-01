<?php
ob_start();

require_once "header_admin.php";
require_once "../config/database.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Kiểm tra ID
if ($id <= 0) {
    header("Location: users.php");
    exit;
}

// Không cho Admin tự xóa tài khoản của mình
if ($id == $_SESSION['admin_id']) {
    header("Location: users.php");
    exit;
}

// Kiểm tra tài khoản có tồn tại không
$sql = "SELECT id FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header("Location: users.php");
    exit;
}

// Xóa tài khoản
$sql = "DELETE FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: users.php");
    exit;
} else {
    echo "Không thể xóa tài khoản.";
}

ob_end_flush();
?>