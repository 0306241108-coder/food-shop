<?php

session_start();
require_once "../config/database.php";


// ===============================
// KIỂM TRA ĐĂNG NHẬP
// ===============================

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit;
}


$user_id = (int)$_SESSION['user_id'];


// ===============================
// KIỂM TRA ID ĐƠN
// ===============================

if (
    !isset($_GET['id']) ||
    !is_numeric($_GET['id'])
) {

    header("Location: orders.php");
    exit;
}


$order_id = (int)$_GET['id'];


// ===============================
// HỦY ĐƠN
// ===============================

if (isset($_POST['cancel_order'])) {

    $sql = "
        UPDATE orders
        SET status = 'cancelled'
        WHERE id = $order_id
        AND user_id = $user_id
        AND status = 'pending'
    ";

    mysqli_query($conn, $sql);

    header(
        "Location: order_detail.php?id=$order_id"
    );

    exit;
}


// ===============================
// LẤY ĐƠN HÀNG
// ===============================

$sql = "
    SELECT *
    FROM orders
    WHERE id = $order_id
    AND user_id = $user_id
    LIMIT 1
";

$result = mysqli_query($conn, $sql);


if (
    !$result ||
    mysqli_num_rows($result) == 0
) {

    die("Không tìm thấy đơn hàng.");

}


$order = mysqli_fetch_assoc($result);


// ===============================
// LẤY CHI TIẾT ĐƠN
// ===============================

$sql_items = "
    SELECT
        od.quantity,
        od.price,
        p.name,
        p.image

    FROM order_details od

    JOIN products p
        ON od.product_id = p.id

    WHERE od.order_id = $order_id

    ORDER BY od.id ASC
";

$result_items = mysqli_query(
    $conn,
    $sql_items
);

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
        Chi tiết đơn hàng #<?php echo $order['id']; ?>
    </title>

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

        📦 Chi tiết đơn hàng
        #<?php echo $order['id']; ?>

    </h2>


    <!-- ===============================
         THÔNG TIN ĐƠN
    ================================ -->

    <div class="card mb-4">

        <div class="card-header bg-dark text-white">

            📋 Thông tin đơn hàng

        </div>


        <div class="card-body">


            <p>

                <strong>
                    Mã đơn:
                </strong>

                #<?php echo $order['id']; ?>

            </p>


            <p>

                <strong>
                    Ngày đặt:
                </strong>

                <?php

                echo date(
                    "d/m/Y H:i",
                    strtotime(
                        $order['created_at']
                    )
                );

                ?>

            </p>


            <p>

                <strong>
                    Trạng thái:
                </strong>


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

            </p>


        </div>

    </div>


    <!-- ===============================
         THÔNG TIN GIAO HÀNG
    ================================ -->

    <div class="card mb-4">

        <div class="card-header bg-dark text-white">

            🚚 Thông tin giao hàng

        </div>


        <div class="card-body">


            <p>

                <strong>
                    Người nhận:
                </strong>

                <?php

                echo htmlspecialchars(
                    $order['receiver_name']
                );

                ?>

            </p>


            <p>

                <strong>
                    Số điện thoại:
                </strong>

                <?php

                echo htmlspecialchars(
                    $order['phone']
                );

                ?>

            </p>


            <p>

                <strong>
                    Địa chỉ:
                </strong>

                <br>

                <?php

                echo nl2br(
                    htmlspecialchars(
                        $order['address']
                    )
                );

                ?>

            </p>


            <p>

                <strong>
                    Ghi chú:
                </strong>

                <br>

                <?php

                if (!empty($order['note'])) {

                    echo nl2br(
                        htmlspecialchars(
                            $order['note']
                        )
                    );

                } else {

                    echo "Không có ghi chú.";

                }

                ?>

            </p>


        </div>

    </div>


    <!-- ===============================
         SẢN PHẨM
    ================================ -->

    <div class="card mb-4">

        <div class="card-header bg-dark text-white">

            🛒 Sản phẩm đã đặt

        </div>


        <div class="card-body p-0">


            <div class="table-responsive">

                <table
                    class="table table-bordered align-middle mb-0"
                >

                    <thead class="table-dark">

                        <tr>

                            <th>
                                Sản phẩm
                            </th>

                            <th class="text-center">
                                Giá
                            </th>

                            <th class="text-center">
                                Số lượng
                            </th>

                            <th class="text-center">
                                Thành tiền
                            </th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php

                    if (
                        $result_items &&
                        mysqli_num_rows(
                            $result_items
                        ) > 0
                    ) {

                        while (
                            $item =
                            mysqli_fetch_assoc(
                                $result_items
                            )
                        ) {

                            $subtotal =
                                $item['price']
                                *
                                $item['quantity'];

                    ?>


                        <tr>


                            <td>

                                <div
                                    class="d-flex align-items-center"
                                >

                                    <?php if (
                                        !empty(
                                            $item['image']
                                        )
                                    ) { ?>

                                        <img
                                            src="../assets/img/products/<?php echo htmlspecialchars($item['image']); ?>"
                                            style="
                                                width:70px;
                                                height:70px;
                                                object-fit:cover;
                                                border-radius:8px;
                                                margin-right:15px;
                                            "
                                        >

                                    <?php } ?>


                                    <strong>

                                        <?php

                                        echo htmlspecialchars(
                                            $item['name']
                                        );

                                        ?>

                                    </strong>

                                </div>

                            </td>


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


                            <td class="text-center">

                                <?php

                                echo $item['quantity'];

                                ?>

                            </td>


                            <td
                                class="text-center text-danger"
                            >

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


                        </tr>


                    <?php

                        }

                    } else {

                    ?>

                        <tr>

                            <td
                                colspan="4"
                                class="text-center"
                            >

                                Không có sản phẩm.

                            </td>

                        </tr>

                    <?php } ?>


                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- ===============================
         TỔNG TIỀN
    ================================ -->

    <div class="text-end mb-4">

        <h3>

            Tổng tiền:

            <span class="text-danger">

                <?php

                echo number_format(
                    $order['total'],
                    0,
                    ',',
                    '.'
                );

                ?>đ

            </span>

        </h3>

    </div>


    <!-- ===============================
         NÚT HỦY ĐƠN
    ================================ -->

    <?php if (
        $order['status'] == 'pending'
    ) { ?>


        <form
            method="POST"
            class="d-inline"
            onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này không?');"
        >

            <button
                type="submit"
                name="cancel_order"
                class="btn btn-danger"
            >

                ❌ Hủy đơn hàng

            </button>

        </form>


    <?php } ?>


    <a
        href="orders.php"
        class="btn btn-secondary"
    >

        ← Quay lại đơn hàng

    </a>


</div>


<footer class="text-center mt-5 mb-4">

    © 2026 Food And Drink

</footer>


</body>

</html>