<?php

session_start();

/* Xóa thông tin đăng nhập */
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_email']);

/* Xóa toàn bộ session */
session_destroy();

/* Quay về trang chủ */
header("Location: ../index.php");
exit;

?>