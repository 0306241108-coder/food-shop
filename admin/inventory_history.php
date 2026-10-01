<?php
ob_start();

require_once "header_admin.php";
require_once "../config/database.php";


/* =========================
   LẤY LỊCH SỬ KHO
========================= */

$sql = "
    SELECT
        h.*,
        p.name AS product_name
    FROM inventory_history h
    LEFT JOIN products p
        ON h.product_id = p.id
    ORDER BY h.id DESC
";

$result = mysqli_query($conn, $sql);

?>

<div class="container mt-5 mb-5">


    <!-- =========================
         TIÊU ĐỀ
    ========================= -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            📜 Lịch sử kho
        </h2>


        <a
            href="inventory.php"
            class="btn btn-secondary"
        >
            ← Quay lại kho
        </a>

    </div>


    <!-- =========================
         BẢNG LỊCH SỬ
    ========================= -->

    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover align-middle"
                >

                    <thead class="table-dark">

                        <tr>

                            <th>
                                #
                            </th>

                            <th>
                                Thời gian
                            </th>

                            <th>
                                Sản phẩm
                            </th>

                            <th>
                                Loại
                            </th>

                            <th>
                                Số lượng
                            </th>

                            <th>
                                Ghi chú
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                    <?php

                    if (
                        $result &&
                        mysqli_num_rows($result) > 0
                    ) {

                        while (
                            $history =
                            mysqli_fetch_assoc($result)
                        ) {

                    ?>

                        <tr>


                            <!-- ID -->

                            <td>

                                <?php
                                echo $history['id'];
                                ?>

                            </td>


                            <!-- THỜI GIAN -->

                            <td>

                                <?php

                                echo date(
                                    'd/m/Y H:i',
                                    strtotime(
                                        $history['created_at']
                                    )
                                );

                                ?>

                            </td>


                            <!-- SẢN PHẨM -->

                            <td>

                                <strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $history['product_name']
                                        ?? 'Sản phẩm đã xóa'
                                    );

                                    ?>

                                </strong>

                            </td>


                            <!-- LOẠI -->

                            <td>

                                <?php

                                if (
                                    $history['type']
                                    == 'import'
                                ) {

                                    echo '
                                    <span class="badge bg-success">
                                        📥 Nhập hàng
                                    </span>';

                                } elseif (
                                    $history['type']
                                    == 'export'
                                ) {

                                    echo '
                                    <span class="badge bg-danger">
                                        📤 Xuất hàng
                                    </span>';

                                } elseif (
                                    $history['type']
                                    == 'adjustment'
                                ) {

                                    echo '
                                    <span class="badge bg-warning text-dark">
                                        ✏️ Điều chỉnh
                                    </span>';

                                } else {

                                    echo '
                                    <span class="badge bg-secondary">
                                        Khác
                                    </span>';
                                }

                                ?>

                            </td>


                            <!-- SỐ LƯỢNG -->

                            <td>

                                <?php

                                if (
                                    $history['type']
                                    == 'import'
                                ) {

                                    echo '
                                    <span class="text-success fw-bold">
                                        +'
                                        . $history['quantity'] .
                                    '</span>';

                                } elseif (
                                    $history['type']
                                    == 'export'
                                ) {

                                    echo '
                                    <span class="text-danger fw-bold">
                                        -'
                                        . $history['quantity'] .
                                    '</span>';

                                } else {

                                    echo '
                                    <span class="fw-bold">
                                        '
                                        . $history['quantity'] .
                                    '</span>';
                                }

                                ?>

                            </td>


                            <!-- GHI CHÚ -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $history['note']
                                    ?? ''
                                );

                                ?>

                            </td>

                        </tr>


                    <?php

                        }

                    } else {

                    ?>

                        <tr>

                            <td
                                colspan="6"
                                class="text-center"
                            >

                                📭 Chưa có lịch sử kho.

                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<footer class="text-center mt-5 mb-4">

    © 2026 Food And Drink - Admin

</footer>

<?php
ob_end_flush();
?>