<?php

session_start();

require_once "../config/database.php";

// ==========================
// KIỂM TRA ĐĂNG NHẬP
// ==========================

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];


// ==========================
// LẤY GIỎ HÀNG
// ==========================

$sql = "
    SELECT id
    FROM cart
    WHERE user_id = $user_id
    LIMIT 1
";

$result = mysqli_query($conn, $sql);
$cart = mysqli_fetch_assoc($result);


// Không có giỏ hàng
if (!$cart) {
    echo "Giỏ hàng đang trống.";
    exit;
}

$cart_id = (int)$cart['id'];


// ==========================
// LẤY SẢN PHẨM TRONG GIỎ
// ==========================

$sql = "
    SELECT
        ci.product_id,
        ci.quantity,
        p.name,
        p.price,
        p.quantity AS stock
    FROM cart_items ci
    JOIN products p
        ON ci.product_id = p.id
    WHERE ci.cart_id = $cart_id
";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {

    echo "Giỏ hàng đang trống.";
    exit;
}


// ==========================
// TÍNH TỔNG TIỀN
// ==========================

$total = 0;
$items = [];

while ($item = mysqli_fetch_assoc($result)) {

    // Kiểm tra tồn kho
    if ($item['quantity'] > $item['stock']) {

        echo "
        <div style='text-align:center;margin-top:50px;'>
            <h3>Sản phẩm <b>{$item['name']}</b> không đủ số lượng.</h3>
            <a href='cart.php'>Quay lại giỏ hàng</a>
        </div>
        ";

        exit;
    }

    $subtotal =
        $item['price'] *
        $item['quantity'];

    $total += $subtotal;

    $items[] = $item;
}


// ==========================
// XỬ LÝ ĐẶT HÀNG
// ==========================

$error = "";

if (isset($_POST['checkout'])) {

    $receiver_name = trim($_POST['receiver_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $note = trim($_POST['note']);


    // ==========================
    // KIỂM TRA THÔNG TIN
    // ==========================

    if ($receiver_name == "") {

        $error = "Vui lòng nhập tên người nhận.";

    } elseif ($phone == "") {

        $error = "Vui lòng nhập số điện thoại.";

    } elseif ($address == "") {

        $error = "Vui lòng nhập địa chỉ giao hàng.";

    } else {

        // ==========================
        // TẠO ĐƠN HÀNG
        // ==========================

        $sql_order = "
            INSERT INTO orders
            (
                user_id,
                total,
                status,
                receiver_name,
                phone,
                address,
                note
            )
            VALUES
            (
                $user_id,
                $total,
                'pending',
                '" . mysqli_real_escape_string($conn, $receiver_name) . "',
                '" . mysqli_real_escape_string($conn, $phone) . "',
                '" . mysqli_real_escape_string($conn, $address) . "',
                '" . mysqli_real_escape_string($conn, $note) . "'
            )
        ";

        if (mysqli_query($conn, $sql_order)) {

            // Lấy mã đơn vừa tạo
            $order_id = mysqli_insert_id($conn);


            // ==========================
            // LƯU CHI TIẾT ĐƠN HÀNG
            // ==========================

            foreach ($items as $item) {

                $product_id = (int)$item['product_id'];
                $quantity = (int)$item['quantity'];
                $price = (float)$item['price'];

                $sql_detail = "
                    INSERT INTO order_details
                    (
                        order_id,
                        product_id,
                        quantity,
                        price
                    )
                    VALUES
                    (
                        $order_id,
                        $product_id,
                        $quantity,
                        $price
                    )
                ";

                mysqli_query($conn, $sql_detail);
            }


            // ==========================
            // XÓA GIỎ HÀNG
            // ==========================

            mysqli_query(
                $conn,
                "DELETE FROM cart_items
                 WHERE cart_id = $cart_id"
            );


            // ==========================
            // CHUYỂN SANG CHI TIẾT ĐƠN
            // ==========================

            header(
                "Location: order_detail.php?id=$order_id"
            );

            exit;

        } else {

            $error = "Có lỗi khi tạo đơn hàng: " . mysqli_error($conn);
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

    <title>Thanh toán - Food And Drink</title>


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


<?php include "../includes/header.php"; ?>


<div class="container mt-5 mb-5">

    <h2 class="text-center mb-4">

        🛒 Thông tin đặt hàng

    </h2>


    <?php if ($error != "") { ?>

        <div class="alert alert-danger">

            <?php echo $error; ?>

        </div>

    <?php } ?>


    <div class="row">


        <!-- ==========================
             THÔNG TIN GIAO HÀNG
        =========================== -->

        <div class="col-md-7">

            <div class="card">

                <div class="card-header bg-dark text-white">

                    📦 Thông tin giao hàng

                </div>


                <div class="card-body">


                    <form method="POST">


                        <!-- TÊN -->

                        <div class="mb-3">

                            <label class="form-label">

                                Tên người nhận

                            </label>

                            <input
                                type="text"
                                name="receiver_name"
                                class="form-control"
                                placeholder="Nhập tên người nhận"
                                value="<?php
                                    echo isset($_POST['receiver_name'])
                                        ? htmlspecialchars($_POST['receiver_name'])
                                        : htmlspecialchars($_SESSION['user_name'] ?? '');
                                ?>"
                            >

                        </div>


                        <!-- SỐ ĐIỆN THOẠI -->

                        <div class="mb-3">

                            <label class="form-label">

                                Số điện thoại

                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                placeholder="Nhập số điện thoại"
                                value="<?php
                                    echo isset($_POST['phone'])
                                        ? htmlspecialchars($_POST['phone'])
                                        : '';
                                ?>"
                            >

                        </div>


                        <!-- ĐỊA CHỈ -->

                        <div class="mb-3">

                            <label class="form-label">

                                Địa chỉ giao hàng

                            </label>

                            <textarea
                                name="address"
                                class="form-control"
                                rows="3"
                                placeholder="Nhập địa chỉ nhận hàng"
                            ><?php
                                echo isset($_POST['address'])
                                    ? htmlspecialchars($_POST['address'])
                                    : '';
                            ?></textarea>

                        </div>


                        <!-- GHI CHÚ -->

                        <div class="mb-3">

                            <label class="form-label">

                                Ghi chú

                            </label>

                            <textarea
                                name="note"
                                class="form-control"
                                rows="3"
                                placeholder="Ví dụ: Giao buổi chiều..."
                            ><?php
                                echo isset($_POST['note'])
                                    ? htmlspecialchars($_POST['note'])
                                    : '';
                            ?></textarea>

                        </div>


                        <!-- NÚT -->

                        <button
                            type="submit"
                            name="checkout"
                            class="btn btn-warning w-100"
                        >

                            ✅ Xác nhận đặt hàng

                        </button>


                    </form>

                </div>

            </div>

        </div>



        <!-- ==========================
             TÓM TẮT ĐƠN HÀNG
        =========================== -->

        <div class="col-md-5">

            <div class="card">

                <div class="card-header bg-dark text-white">

                    🛍️ Đơn hàng của bạn

                </div>


                <div class="card-body">


                    <?php foreach ($items as $item) { ?>

                        <div
                            class="d-flex justify-content-between mb-3"
                        >

                            <div>

                                <strong>

                                    <?php
                                    echo htmlspecialchars(
                                        $item['name']
                                    );
                                    ?>

                                </strong>

                                <br>

                                <small>

                                    Số lượng:
                                    <?php echo $item['quantity']; ?>

                                </small>

                            </div>


                            <div>

                                <?php

                                echo number_format(
                                    $item['price'] *
                                    $item['quantity'],
                                    0,
                                    ',',
                                    '.'
                                );

                                ?>đ

                            </div>

                        </div>

                    <?php } ?>


                    <hr>


                    <div class="text-end">

                        <h4>

                            Tổng tiền:

                            <span class="text-danger">

                                <?php

                                echo number_format(
                                    $total,
                                    0,
                                    ',',
                                    '.'
                                );

                                ?>đ

                            </span>

                        </h4>

                    </div>


                </div>

            </div>


            <a
                href="cart.php"
                class="btn btn-secondary mt-3"
            >

                ← Quay lại giỏ hàng

            </a>

        </div>


    </div>

</div>


<footer class="text-center mt-5">

    © 2026 Food And Drink

</footer>


</body>

</html>