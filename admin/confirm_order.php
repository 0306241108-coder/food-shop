<?php

session_start();
require_once "../config/database.php";

// Lấy ID đơn hàng
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("Đơn hàng không hợp lệ.");
}

// Lấy đơn hàng
$sql = "SELECT * FROM orders WHERE id = $id";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Không tìm thấy đơn hàng.");
}

$order = mysqli_fetch_assoc($result);

// Chỉ được trừ kho khi đơn đang chờ duyệt
if ($order['status'] != 'pending') {
    die("Đơn hàng này đã được xử lý.");
}


// ================================
// LẤY CHI TIẾT ĐƠN
// ================================

$sql_detail = "
    SELECT product_id, quantity
    FROM order_details
    WHERE order_id = $id
";

$result_detail = mysqli_query($conn, $sql_detail);


// ================================
// KIỂM TRA KHO
// ================================

while ($item = mysqli_fetch_assoc($result_detail)) {

    $product_id = (int)$item['product_id'];
    $quantity = (int)$item['quantity'];

    $sql_product = "
        SELECT quantity, name
        FROM products
        WHERE id = $product_id
    ";

    $result_product = mysqli_query($conn, $sql_product);
    $product = mysqli_fetch_assoc($result_product);

    if (!$product) {
        die("Không tìm thấy sản phẩm.");
    }

    if ($product['quantity'] < $quantity) {
        die(
            "Sản phẩm " . $product['name'] .
            " không đủ hàng."
        );
    }
}


// ================================
// TRỪ KHO
// ================================

// Lấy lại chi tiết đơn
$result_detail = mysqli_query($conn, $sql_detail);

while ($item = mysqli_fetch_assoc($result_detail)) {

    $product_id = (int)$item['product_id'];
    $quantity = (int)$item['quantity'];

    mysqli_query(
        $conn,
        "UPDATE products
         SET quantity = quantity - $quantity
         WHERE id = $product_id"
    );
}


// ================================
// ĐỔI TRẠNG THÁI
// ================================

mysqli_query(
    $conn,
    "UPDATE orders
     SET status = 'confirmed'
     WHERE id = $id"
);


// ================================
// QUAY LẠI
// ================================

header("Location: orders.php");
exit;

?>