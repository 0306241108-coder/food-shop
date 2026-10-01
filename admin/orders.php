<?php
ob_start();

require_once "header_admin.php";
require_once "../config/database.php";


/* =========================
   LẤY DANH SÁCH ĐƠN HÀNG
========================= */

$sql = "
    SELECT *
    FROM orders
    ORDER BY id DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Lỗi SQL: " . mysqli_error($conn));
}


/* =========================
   HIỂN THỊ TRẠNG THÁI
========================= */

function statusText($status)
{
    if ($status == "pending") {
        return "⏳ Chờ duyệt";
    }

    if ($status == "confirmed") {
        return "✅ Đã duyệt";
    }

    if ($status == "shipping") {
        return "🚚 Đang giao";
    }

    if ($status == "complete") {
        return "🎉 Hoàn thành";
    }

    return $status;
}

?>

<div class="container mt-5">

    <h2 class="text-center mb-4">
        📦 Quản lý đơn hàng
    </h2>


    <?php if (mysqli_num_rows($result) > 0) { ?>

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-dark">

                    <tr>

                        <th>Mã đơn</th>

                        <th>Mã khách hàng</th>

                        <th>Ngày đặt</th>

                        <th>Tổng tiền</th>

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


                        <!-- KHÁCH HÀNG -->
                        <td>
                            <?php echo $order['user_id']; ?>
                        </td>


                        <!-- NGÀY ĐẶT -->
                        <td>

                            <?php
                            echo date(
                                "d/m/Y H:i",
                                strtotime($order['created_at'])
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


                        <!-- TRẠNG THÁI -->
                        <td>

                            <?php
                            echo statusText($order['status']);
                            ?>

                        </td>


                        <!-- THAO TÁC -->
                        <td>

                            <a
                                href="order_detail.php?id=<?php echo $order['id']; ?>"
                                class="btn btn-warning btn-sm"
                            >
                                👁 Xem
                            </a>

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    <?php } else { ?>

        <div class="alert alert-info text-center">

            Chưa có đơn hàng nào.

        </div>

    <?php } ?>

</div>

<?php
ob_end_flush();
?>