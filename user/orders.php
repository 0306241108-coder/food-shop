<?php

session_start();
require_once "../config/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$message = "";
$error = "";


// ===============================
// HỦY ĐƠN
// ===============================

if (isset($_POST['cancel_order'])) {

    $order_id = (int)$_POST['order_id'];

    // Chỉ được hủy đơn của chính mình
    $sql = "
        SELECT status
        FROM orders
        WHERE id = $order_id
        AND user_id = $user_id
        LIMIT 1
    ";

    $result = mysqli_query($conn, $sql);
    $order = mysqli_fetch_assoc($result);

    if (!$order) {

        $error = "Không tìm thấy đơn hàng.";

    } elseif ($order['status'] != 'pending') {

        $error = "Đơn hàng này không thể hủy.";

    } else {

        mysqli_query(
            $conn,
            "UPDATE orders
             SET status = 'cancelled'
             WHERE id = $order_id
             AND user_id = $user_id
             AND status = 'pending'"
        );

        $message = "Đã hủy đơn hàng thành công.";
    }
}


// ===============================
// LẤY TẤT CẢ ĐƠN HÀNG
// ===============================

$sql = "
    SELECT *
    FROM orders
    WHERE user_id = $user_id
    ORDER BY id DESC
";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Đơn hàng của tôi - Food And Drink</title>

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


<div class="container mt-5 mb-5">

    <h2 class="mb-4">
        📦 Đơn hàng của tôi
    </h2>


    <!-- THÔNG BÁO -->

    <?php if ($message != "") { ?>

        <div class="alert alert-success">
            ✅ <?php echo $message; ?>
        </div>

    <?php } ?>


    <?php if ($error != "") { ?>

        <div class="alert alert-danger">
            ❌ <?php echo $error; ?>
        </div>

    <?php } ?>


    <?php if ($result && mysqli_num_rows($result) > 0) { ?>

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">

                    <tr>

                        <th>Mã đơn</th>

                        <th>Người nhận</th>

                        <th>Tổng tiền</th>

                        <th>Ngày đặt</th>

                        <th>Trạng thái</th>

                        <th>Thao tác</th>

                    </tr>

                </thead>


                <tbody>

                <?php while ($order = mysqli_fetch_assoc($result)) { ?>

                    <tr>

                        <!-- MÃ ĐƠN -->

                        <td>

                            <strong>
                                #<?php echo $order['id']; ?>
                            </strong>

                        </td>


                        <!-- NGƯỜI NHẬN -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $order['receiver_name']
                            );

                            ?>

                        </td>


                        <!-- TỔNG TIỀN -->

                        <td class="text-danger">

                            <strong>

                                <?php

                                echo number_format(
                                    $order['total'],
                                    0,
                                    ',',
                                    '.'
                                );

                                ?>đ

                            </strong>

                        </td>


                        <!-- NGÀY -->

                        <td>

                            <?php

                            echo date(
                                "d/m/Y H:i",
                                strtotime(
                                    $order['created_at']
                                )
                            );

                            ?>

                        </td>


                        <!-- TRẠNG THÁI -->

                        <td>

                            <?php

                            switch ($order['status']) {

                                case 'pending':

                                    echo '
                                    <span class="badge bg-warning text-dark">
                                        ⏳ Chờ duyệt
                                    </span>';

                                    break;


                                case 'confirmed':

                                    echo '
                                    <span class="badge bg-primary">
                                        ✅ Đã xác nhận
                                    </span>';

                                    break;


                                case 'shipping':

                                    echo '
                                    <span class="badge bg-info text-dark">
                                        🚚 Đang giao hàng
                                    </span>';

                                    break;


                                case 'completed':

                                    echo '
                                    <span class="badge bg-success">
                                        🎉 Hoàn thành đơn
                                    </span>';

                                    break;


                                case 'cancelled':

                                    echo '
                                    <span class="badge bg-danger">
                                        ❌ Đã hủy
                                    </span>';

                                    break;


                                default:

                                    echo '
                                    <span class="badge bg-secondary">
                                        '
                                        . htmlspecialchars(
                                            $order['status']
                                        )
                                        . '
                                    </span>';
                            }

                            ?>

                        </td>


                        <!-- THAO TÁC -->

                        <td>

                            <a
                                href="order_detail.php?id=<?php echo $order['id']; ?>"
                                class="btn btn-warning btn-sm mb-1"
                            >

                                👁️ Chi tiết

                            </a>


                            <?php if ($order['status'] == 'pending') { ?>

                                <form
                                    method="POST"
                                    style="display:inline;"
                                    onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này không?');"
                                >

                                    <input
                                        type="hidden"
                                        name="order_id"
                                        value="<?php echo $order['id']; ?>"
                                    >

                                    <button
                                        type="submit"
                                        name="cancel_order"
                                        class="btn btn-danger btn-sm"
                                    >

                                        ❌ Hủy đơn

                                    </button>

                                </form>

                            <?php } ?>


                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>


    <?php } else { ?>


        <div class="text-center mt-5">

            <h4>
                📦 Bạn chưa có đơn hàng nào.
            </h4>

            <p>
                Hãy chọn sản phẩm và bắt đầu mua sắm.
            </p>

            <a
                href="products.php"
                class="btn btn-warning"
            >

                🛒 Xem sản phẩm

            </a>

        </div>


    <?php } ?>

</div>


<footer class="text-center mt-5 mb-4">

    © 2026 Food And Drink

</footer>


</body>

</html>