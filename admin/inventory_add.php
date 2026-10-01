<?php
ob_start();

require_once "header_admin.php";
require_once "../config/database.php";


$message = "";


/* =========================
   NHẬP HÀNG
========================= */

if (isset($_POST['add_stock'])) {

    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    if ($quantity <= 0) {

        $message = "❌ Số lượng nhập phải lớn hơn 0.";

    } else {

        /* Lấy sản phẩm */
        $result_product = mysqli_query(
            $conn,
            "
            SELECT quantity
            FROM products
            WHERE id = $product_id
            LIMIT 1
            "
        );

        $product = mysqli_fetch_assoc($result_product);

        if (!$product) {

            $message = "❌ Không tìm thấy sản phẩm.";

        } else {

            /* Cộng thêm hàng */
            $new_quantity =
                $product['quantity'] + $quantity;


            $update = mysqli_query(
                $conn,
                "
                UPDATE products
                SET quantity = $new_quantity
                WHERE id = $product_id
                "
            );


            if (!$update) {

                $message = "❌ Không thể cập nhật kho.";

            } else {

                /* =========================
                   LƯU LỊCH SỬ NHẬP HÀNG
                ========================= */

                $history = mysqli_query(
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
                        'import',
                        $quantity,
                        'Nhập hàng'
                    )
                    "
                );


                if (!$history) {

                    $message =
                        "❌ Cập nhật kho thành công nhưng không thể lưu lịch sử.";

                } else {

                    header("Location: inventory.php");
                    exit;
                }
            }
        }
    }
}


/* =========================
   LẤY DANH SÁCH SẢN PHẨM
========================= */

$result = mysqli_query(
    $conn,
    "
    SELECT
        id,
        name,
        quantity
    FROM products
    ORDER BY name ASC
    "
);

?>

<div class="container mt-5 mb-5">

    <h2 class="mb-4">
        ➕ Nhập hàng
    </h2>


    <!-- THÔNG BÁO -->

    <?php if ($message != "") { ?>

        <div class="alert alert-danger">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php } ?>


    <!-- FORM NHẬP HÀNG -->

    <div class="card">

        <div class="card-header bg-dark text-white">

            📦 Nhập thêm sản phẩm vào kho

        </div>


        <div class="card-body">

            <form method="POST">


                <!-- SẢN PHẨM -->

                <div class="mb-3">

                    <label class="form-label">

                        Chọn sản phẩm

                    </label>


                    <select
                        name="product_id"
                        class="form-select"
                        required
                    >

                        <option value="">

                            -- Chọn sản phẩm --

                        </option>


                        <?php while (
                            $product =
                            mysqli_fetch_assoc($result)
                        ) { ?>

                            <option
                                value="<?php echo $product['id']; ?>"
                            >

                                <?php

                                echo htmlspecialchars(
                                    $product['name']
                                );

                                ?>

                                -
                                Tồn:
                                <?php
                                echo $product['quantity'];
                                ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <!-- SỐ LƯỢNG -->

                <div class="mb-3">

                    <label class="form-label">

                        Số lượng nhập thêm

                    </label>


                    <input
                        type="number"
                        name="quantity"
                        class="form-control"
                        min="1"
                        placeholder="Nhập số lượng..."
                        required
                    >

                </div>


                <!-- NÚT -->

                <button
                    type="submit"
                    name="add_stock"
                    class="btn btn-success"
                >

                    ➕ Nhập hàng

                </button>


                <a
                    href="inventory.php"
                    class="btn btn-secondary"
                >

                    ← Quay lại kho

                </a>

            </form>

        </div>

    </div>

</div>


<footer class="text-center mt-5 mb-4">

    © 2026 Food And Drink - Admin

</footer>

<?php
ob_end_flush();
?>