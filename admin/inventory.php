<?php
ob_start();

require_once "header_admin.php";
require_once "../config/database.php";


/* =========================
   TÌM KIẾM + LỌC
========================= */

$search = isset($_GET['search'])
    ? trim($_GET['search'])
    : '';

$filter = isset($_GET['filter'])
    ? $_GET['filter']
    : 'all';


/* =========================
   SQL
========================= */

$sql = "
    SELECT
        p.id,
        p.name,
        p.price,
        p.quantity,
        c.name AS category_name
    FROM products p
    LEFT JOIN categories c
        ON p.category_id = c.id
    WHERE 1
";


/* =========================
   TÌM KIẾM
========================= */

if ($search != '') {

    $search_safe = mysqli_real_escape_string(
        $conn,
        $search
    );

    $sql .= "
        AND p.name LIKE '%$search_safe%'
    ";
}


/* =========================
   LỌC KHO
========================= */

if ($filter == 'out') {

    $sql .= "
        AND p.quantity = 0
    ";

} elseif ($filter == 'low') {

    $sql .= "
        AND p.quantity BETWEEN 1 AND 5
    ";

} elseif ($filter == 'available') {

    $sql .= "
        AND p.quantity > 5
    ";
}


/* =========================
   SẮP XẾP
========================= */

$sql .= "
    ORDER BY p.id DESC
";


$result = mysqli_query($conn, $sql);

?>

<div class="container mt-5 mb-5">


    <!-- =========================
         TIÊU ĐỀ
    ========================= -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            📦 Quản lý kho
        </h2>

        <div class="d-flex gap-2">

            <a
                href="inventory_add.php"
                class="btn btn-success"
            >
                ➕ Nhập hàng
            </a>

            <a
                href="inventory_history.php"
                class="btn btn-info"
            >
                📜 Lịch sử kho
            </a>

            <a
                href="products.php"
                class="btn btn-secondary"
            >
                ← Sản phẩm
            </a>

        </div>

    </div>


    <!-- =========================
         TÌM KIẾM + LỌC
    ========================= -->

    <div class="card mb-4">

        <div class="card-body">

            <form
                method="GET"
                class="row g-2"
            >


                <!-- TÌM KIẾM -->

                <div class="col-md-6">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="🔍 Tìm tên sản phẩm..."
                        value="<?php echo htmlspecialchars($search); ?>"
                    >

                </div>


                <!-- LỌC -->

                <div class="col-md-4">

                    <select
                        name="filter"
                        class="form-select"
                    >

                        <option
                            value="all"
                            <?php
                            if ($filter == 'all') {
                                echo 'selected';
                            }
                            ?>
                        >
                            📦 Tất cả sản phẩm
                        </option>


                        <option
                            value="available"
                            <?php
                            if ($filter == 'available') {
                                echo 'selected';
                            }
                            ?>
                        >
                            🟢 Còn hàng
                        </option>


                        <option
                            value="low"
                            <?php
                            if ($filter == 'low') {
                                echo 'selected';
                            }
                            ?>
                        >
                            🟠 Sắp hết
                        </option>


                        <option
                            value="out"
                            <?php
                            if ($filter == 'out') {
                                echo 'selected';
                            }
                            ?>
                        >
                            🔴 Hết hàng
                        </option>

                    </select>

                </div>


                <!-- NÚT LỌC -->

                <div class="col-md-2">

                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >
                        🔍 Lọc
                    </button>

                </div>

            </form>

        </div>

    </div>


    <!-- =========================
         BẢNG KHO
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
                                ID
                            </th>

                            <th>
                                Tên sản phẩm
                            </th>

                            <th>
                                Danh mục
                            </th>

                            <th>
                                Giá
                            </th>

                            <th class="text-center">
                                Tồn kho
                            </th>

                            <th class="text-center">
                                Trạng thái
                            </th>

                            <th class="text-center">
                                Thao tác
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
                            $product =
                            mysqli_fetch_assoc($result)
                        ) {

                    ?>

                        <tr>


                            <!-- ID -->

                            <td>

                                <?php
                                echo $product['id'];
                                ?>

                            </td>


                            <!-- TÊN -->

                            <td>

                                <strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $product['name']
                                    );

                                    ?>

                                </strong>

                            </td>


                            <!-- DANH MỤC -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $product['category_name']
                                    ?? 'Chưa có'
                                );

                                ?>

                            </td>


                            <!-- GIÁ -->

                            <td>

                                <?php

                                echo number_format(
                                    $product['price'],
                                    0,
                                    ',',
                                    '.'
                                );

                                ?>đ

                            </td>


                            <!-- TỒN KHO -->

                            <td class="text-center">

                                <strong>

                                    <?php
                                    echo $product['quantity'];
                                    ?>

                                </strong>

                            </td>


                            <!-- TRẠNG THÁI -->

                            <td class="text-center">

                                <?php

                                if (
                                    $product['quantity'] <= 0
                                ) {

                                    echo '
                                    <span class="badge bg-danger">
                                        🔴 Hết hàng
                                    </span>';

                                } elseif (
                                    $product['quantity'] <= 5
                                ) {

                                    echo '
                                    <span class="badge bg-warning text-dark">
                                        🟠 Sắp hết
                                    </span>';

                                } else {

                                    echo '
                                    <span class="badge bg-success">
                                        🟢 Còn hàng
                                    </span>';

                                }

                                ?>

                            </td>


                            <!-- THAO TÁC -->

                            <td class="text-center">

                                <a
                                    href="inventory_edit.php?id=<?php echo $product['id']; ?>"
                                    class="btn btn-warning btn-sm"
                                >
                                    ✏️ Điều chỉnh
                                </a>

                            </td>

                        </tr>


                    <?php

                        }

                    } else {

                    ?>

                        <tr>

                            <td
                                colspan="7"
                                class="text-center"
                            >
                                📦 Không tìm thấy sản phẩm.
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