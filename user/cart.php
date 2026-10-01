<?php
session_start();

require_once "../config/database.php";

// Chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = "";

// ==========================
// LẤY GIỎ HÀNG CỦA USER
// ==========================
$sql = "SELECT * FROM cart WHERE user_id = $user_id LIMIT 1";
$result = mysqli_query($conn, $sql);

$cart = mysqli_fetch_assoc($result);

// Nếu chưa có giỏ hàng thì tạo
if (!$cart) {
    mysqli_query(
        $conn,
        "INSERT INTO cart (user_id) VALUES ($user_id)"
    );

    $cart_id = mysqli_insert_id($conn);
} else {
    $cart_id = $cart['id'];
}


// ==========================
// CẬP NHẬT SỐ LƯỢNG
// ==========================
if (isset($_POST['update_cart'])) {

    foreach ($_POST['quantity'] as $item_id => $quantity) {

        $item_id = (int)$item_id;
        $quantity = (int)$quantity;

        if ($quantity < 1) {
            $quantity = 1;
        }

        // Lấy sản phẩm để kiểm tra tồn kho
        $sql = "
            SELECT p.quantity
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.id
            WHERE ci.id = $item_id
            AND ci.cart_id = $cart_id
        ";

        $result = mysqli_query($conn, $sql);
        $product = mysqli_fetch_assoc($result);

        if ($product) {

            // Không cho mua quá số lượng tồn
            if ($quantity > $product['quantity']) {
                $quantity = $product['quantity'];
            }

            mysqli_query(
                $conn,
                "UPDATE cart_items
                 SET quantity = $quantity
                 WHERE id = $item_id
                 AND cart_id = $cart_id"
            );
        }
    }

    $message = "Đã cập nhật giỏ hàng.";
}


// ==========================
// XÓA SẢN PHẨM
// ==========================
if (isset($_GET['delete'])) {

    $item_id = (int)$_GET['delete'];

    mysqli_query(
        $conn,
        "DELETE FROM cart_items
         WHERE id = $item_id
         AND cart_id = $cart_id"
    );

    header("Location: cart.php");
    exit;
}


// ==========================
// LẤY SẢN PHẨM TRONG GIỎ
// ==========================
$sql = "
    SELECT
        ci.id AS cart_item_id,
        ci.quantity AS cart_quantity,
        p.name,
        p.price,
        p.image,
        p.quantity AS stock
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.cart_id = $cart_id
    ORDER BY ci.id DESC
";

$result = mysqli_query($conn, $sql);

$total = 0;

?>


<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Giỏ hàng - Food And Drink</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>


<body>

<?php include "../includes/header.php"; ?>


<div class="container mt-5">

    <h2 class="mb-4">
        🛒 Giỏ hàng của bạn
    </h2>


    <?php if ($message != "") { ?>

        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>

    <?php } ?>


    <?php if ($result && mysqli_num_rows($result) > 0) { ?>


        <form method="POST">


            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-dark">

                        <tr>

                            <th>Sản phẩm</th>

                            <th class="text-center">
                                Giá
                            </th>

                            <th class="text-center">
                                Số lượng
                            </th>

                            <th class="text-center">
                                Thành tiền
                            </th>

                            <th class="text-center">
                                Xóa
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php while ($item = mysqli_fetch_assoc($result)) { ?>


                        <?php

                        $subtotal =
                            $item['price'] *
                            $item['cart_quantity'];

                        $total += $subtotal;

                        ?>


                        <tr>

                            <!-- SẢN PHẨM -->

                            <td>

                                <div class="d-flex align-items-center">

                                    <img
                                        src="../assets/img/products/<?php echo $item['image']; ?>"
                                        style="
                                            width:70px;
                                            height:70px;
                                            object-fit:cover;
                                            border-radius:8px;
                                            margin-right:15px;
                                        "
                                    >

                                    <strong>
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </strong>

                                </div>

                            </td>


                            <!-- GIÁ -->

                            <td class="text-center">

                                <?php
                                echo number_format(
                                    $item['price'],
                                    0,
                                    ',',
                                    '.'
                                );
                                ?>đ

                            </td>


                            <!-- SỐ LƯỢNG -->

                            <td class="text-center">

                                <input
                                    type="number"
                                    name="quantity[<?php echo $item['cart_item_id']; ?>]"
                                    value="<?php echo $item['cart_quantity']; ?>"
                                    min="1"
                                    max="<?php echo $item['stock']; ?>"
                                    class="form-control"
                                    style="width:90px;margin:auto;"
                                >

                            </td>


                            <!-- THÀNH TIỀN -->

                            <td class="text-center text-danger">

                                <strong>

                                    <?php
                                    echo number_format(
                                        $subtotal,
                                        0,
                                        ',',
                                        '.'
                                    );
                                    ?>đ

                                </strong>

                            </td>


                            <!-- XÓA -->

                            <td class="text-center">

                                <a
                                    href="cart.php?delete=<?php echo $item['cart_item_id']; ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có muốn xóa sản phẩm này không?')"
                                >
                                    Xóa
                                </a>

                            </td>

                        </tr>


                    <?php } ?>


                    </tbody>

                </table>

            </div>


            <!-- CẬP NHẬT -->

            <button
                type="submit"
                name="update_cart"
                class="btn btn-primary"
            >
                🔄 Cập nhật số lượng
            </button>


        </form>


        <!-- TỔNG TIỀN -->

        <div class="text-end mt-4">

            <h3>
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
            </h3>


            <a
                href="products.php"
                class="btn btn-secondary"
            >
                ← Tiếp tục mua hàng
            </a>


            <a
                href="checkout.php"
                class="btn btn-warning"
            >
                Đặt hàng →
            </a>

        </div>


    <?php } else { ?>


        <!-- GIỎ TRỐNG -->

        <div class="text-center mt-5">

            <h4>
                🛒 Giỏ hàng đang trống
            </h4>

            <p>
                Bạn chưa có sản phẩm nào.
            </p>

            <a
                href="products.php"
                class="btn btn-warning"
            >
                Xem sản phẩm
            </a>

        </div>


    <?php } ?>


</div>


<footer class="text-center mt-5">

    © 2026 Food And Drink

</footer>


</body>

</html>