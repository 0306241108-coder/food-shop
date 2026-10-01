<?php
ob_start();

require_once "header_admin.php";
require_once "../config/database.php";


/* =========================
   KIỂM TRA ID ĐƠN HÀNG
========================= */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$order_id = (int)$_GET['id'];


/* =========================
   DUYỆT ĐƠN
   - Trừ kho
   - Ghi lịch sử kho
   - Đổi trạng thái
========================= */

if (isset($_POST['approve_order'])) {

    mysqli_begin_transaction($conn);

    try {

        /* Lấy đơn hàng và khóa dòng */
        $sql = "
            SELECT *
            FROM orders
            WHERE id = $order_id
            LIMIT 1
            FOR UPDATE
        ";

        $result_order = mysqli_query($conn, $sql);

        if (
            !$result_order ||
            mysqli_num_rows($result_order) == 0
        ) {
            throw new Exception("Không tìm thấy đơn hàng.");
        }

        $order_check = mysqli_fetch_assoc($result_order);


        /* Chỉ cho duyệt đơn đang chờ */
        if ($order_check['status'] != 'pending') {
            throw new Exception(
                "Đơn hàng này đã được xử lý trước đó."
            );
        }


        /* =========================
           LẤY SẢN PHẨM TRONG ĐƠN
        ========================= */

        $sql_items = "
            SELECT
                od.product_id,
                od.quantity,
                p.name,
                p.quantity AS stock
            FROM order_details od
            JOIN products p
                ON od.product_id = p.id
            WHERE od.order_id = $order_id
            FOR UPDATE
        ";

        $result_items = mysqli_query($conn, $sql_items);

        if (
            !$result_items ||
            mysqli_num_rows($result_items) == 0
        ) {
            throw new Exception(
                "Đơn hàng không có sản phẩm."
            );
        }


        /* =========================
           KIỂM TRA TỒN KHO
        ========================= */

        $items = [];

        while ($item = mysqli_fetch_assoc($result_items)) {

            if ($item['quantity'] > $item['stock']) {

                throw new Exception(
                    "Sản phẩm \""
                    . $item['name']
                    . "\" không đủ hàng. Kho còn "
                    . $item['stock']
                    . ", khách đặt "
                    . $item['quantity']
                    . "."
                );
            }

            $items[] = $item;
        }


        /* =========================
           TRỪ KHO + LƯU LỊCH SỬ
        ========================= */

        foreach ($items as $item) {

            $product_id = (int)$item['product_id'];
            $quantity = (int)$item['quantity'];

            $product_name = mysqli_real_escape_string(
                $conn,
                $item['name']
            );


            /* Trừ kho */
            $update_stock = mysqli_query(
                $conn,
                "
                UPDATE products
                SET quantity = quantity - $quantity
                WHERE id = $product_id
                AND quantity >= $quantity
                "
            );

            if (!$update_stock) {
                throw new Exception(
                    "Không thể cập nhật kho."
                );
            }


            /* Kiểm tra trừ kho thành công */
            if (mysqli_affected_rows($conn) == 0) {
                throw new Exception(
                    "Không đủ hàng trong kho."
                );
            }


            /* =========================
               LƯU LỊCH SỬ XUẤT KHO
            ========================= */

            $note = "Xuất hàng - Đơn #" . $order_id;

            $note = mysqli_real_escape_string(
                $conn,
                $note
            );

            $insert_history = mysqli_query(
                $conn,
                "
                INSERT INTO inventory_history
                (
                    product_id,
                    type,
                    quantity,
                    note
                )
                VALUES
                (
                    $product_id,
                    'export',
                    $quantity,
                    '$note'
                )
                "
            );

            if (!$insert_history) {
                throw new Exception(
                    "Không thể lưu lịch sử kho cho sản phẩm "
                    . $product_name
                );
            }
        }


        /* =========================
           ĐỔI TRẠNG THÁI ĐƠN
        ========================= */

        $update_order = mysqli_query(
            $conn,
            "
            UPDATE orders
            SET status = 'confirmed'
            WHERE id = $order_id
            AND status = 'pending'
            "
        );

        if (!$update_order) {
            throw new Exception(
                "Không thể cập nhật trạng thái đơn."
            );
        }

        if (mysqli_affected_rows($conn) == 0) {
            throw new Exception(
                "Đơn hàng đã được xử lý."
            );
        }


        /* Hoàn tất */
        mysqli_commit($conn);

        header(
            "Location: order_detail.php?id=$order_id"
        );

        exit;

    } catch (Exception $e) {

        mysqli_rollback($conn);

        $error = $e->getMessage();
    }
}


/* =========================
   LẤY ĐƠN HÀNG
========================= */

$sql = "
    SELECT *
    FROM orders
    WHERE id = $order_id
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


/* =========================
   CẬP NHẬT TRẠNG THÁI
========================= */

if (isset($_POST['update_status'])) {

    $status = $_POST['status'];

    $allowed = [
        'shipping',
        'completed'
    ];

    if (in_array($status, $allowed)) {

        $status = mysqli_real_escape_string(
            $conn,
            $status
        );

        mysqli_query(
            $conn,
            "
            UPDATE orders
            SET status = '$status'
            WHERE id = $order_id
            "
        );

        header(
            "Location: order_detail.php?id=$order_id"
        );

        exit;
    }
}


/* =========================
   LẤY SẢN PHẨM TRONG ĐƠN
========================= */

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

<div class="container mt-5 mb-5">

    <h2 class="mb-4">
        📦 Chi tiết đơn hàng #<?php echo $order['id']; ?>
    </h2>


    <!-- THÔNG BÁO LỖI -->

    <?php if (isset($error)) { ?>

        <div class="alert alert-danger">

            ❌ <?php echo htmlspecialchars($error); ?>

        </div>

    <?php } ?>


    <!-- =========================
         THÔNG TIN KHÁCH HÀNG
    ========================= -->

    <div class="card mb-4">

        <div class="card-header bg-dark text-white">
            👤 Thông tin khách hàng
        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <strong>Tên người nhận:</strong>

                    <br>

                    <?php
                    echo htmlspecialchars(
                        $order['receiver_name']
                    );
                    ?>

                </div>


                <div class="col-md-6 mb-3">

                    <strong>Số điện thoại:</strong>

                    <br>

                    <?php
                    echo htmlspecialchars(
                        $order['phone']
                    );
                    ?>

                </div>


                <div class="col-md-12 mb-3">

                    <strong>Địa chỉ giao hàng:</strong>

                    <br>

                    <?php
                    echo nl2br(
                        htmlspecialchars(
                            $order['address']
                        )
                    );
                    ?>

                </div>


                <div class="col-md-12">

                    <strong>Ghi chú:</strong>

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

                </div>

            </div>

        </div>

    </div>


    <!-- =========================
         THÔNG TIN ĐƠN HÀNG
    ========================= -->

    <div class="card mb-4">

        <div class="card-header bg-dark text-white">
            📋 Thông tin đơn hàng
        </div>

        <div class="card-body">

            <p>

                <strong>Mã đơn:</strong>

                #<?php echo $order['id']; ?>

            </p>


            <p>

                <strong>Ngày đặt:</strong>

                <?php

                echo date(
                    "d/m/Y H:i",
                    strtotime($order['created_at'])
                );

                ?>

            </p>


            <p>

                <strong>Trạng thái:</strong>

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
                            🚚 Đang giao
                        </span>';

                        break;


                    case 'completed':

                        echo '
                        <span class="badge bg-success">
                            🎉 Hoàn thành
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


            <!-- =========================
                 DUYỆT ĐƠN
            ========================= -->

            <?php if ($order['status'] == 'pending') { ?>

                <form method="POST">

                    <button
                        type="submit"
                        name="approve_order"
                        class="btn btn-success"
                        onclick="
                            return confirm(
                                'Duyệt đơn sẽ trừ kho. Bạn có chắc chắn không?'
                            )
                        "
                    >
                        ✅ Duyệt đơn
                    </button>

                </form>

            <?php } ?>


            <!-- =========================
                 BẮT ĐẦU GIAO
            ========================= -->

            <?php if ($order['status'] == 'confirmed') { ?>

                <form
                    method="POST"
                    class="mt-3"
                >

                    <input
                        type="hidden"
                        name="status"
                        value="shipping"
                    >

                    <button
                        type="submit"
                        name="update_status"
                        class="btn btn-info"
                    >
                        🚚 Bắt đầu giao hàng
                    </button>

                </form>

            <?php } ?>


            <!-- =========================
                 HOÀN THÀNH
            ========================= -->

            <?php if ($order['status'] == 'shipping') { ?>

                <form
                    method="POST"
                    class="mt-3"
                >

                    <input
                        type="hidden"
                        name="status"
                        value="completed"
                    >

                    <button
                        type="submit"
                        name="update_status"
                        class="btn btn-success"
                    >
                        🎉 Hoàn thành đơn
                    </button>

                </form>

            <?php } ?>

        </div>

    </div>


    <!-- =========================
         SẢN PHẨM TRONG ĐƠN
    ========================= -->

    <div class="card mb-4">

        <div class="card-header bg-dark text-white">
            🛒 Sản phẩm trong đơn hàng
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered align-middle mb-0">

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
                        mysqli_num_rows($result_items) > 0
                    ) {

                        while (
                            $item =
                            mysqli_fetch_assoc($result_items)
                        ) {

                            $subtotal =
                                $item['price']
                                *
                                $item['quantity'];

                    ?>

                        <tr>

                            <td>

                                <div class="d-flex align-items-center">

                                    <?php if (!empty($item['image'])) { ?>

                                        <img
                                            src="../assets/img/products/<?php echo htmlspecialchars($item['image']); ?>"
                                            style="
                                                width:60px;
                                                height:60px;
                                                object-fit:cover;
                                                border-radius:8px;
                                                margin-right:12px;
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


    <!-- =========================
         TỔNG TIỀN
    ========================= -->

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


    <a
        href="orders.php"
        class="btn btn-secondary"
    >
        ← Quay lại danh sách đơn
    </a>

</div>


<footer class="text-center mt-5 mb-4">

    © 2026 Food And Drink - Admin

</footer>

<?php
ob_end_flush();
?>