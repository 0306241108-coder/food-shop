<?php
require_once "header_admin.php";
require_once "../config/database.php";

// ===============================
// KIỂM TRA ID
// ===============================

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = (int)$_GET['id'];

// ===============================
// LẤY SẢN PHẨM
// ===============================

$result = mysqli_query(
    $conn,
    "SELECT * FROM products WHERE id = $id LIMIT 1"
);

$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("Không tìm thấy sản phẩm.");
}

// ===============================
// LẤY DANH MỤC
// ===============================

$categories = mysqli_query(
    $conn,
    "SELECT * FROM categories ORDER BY id ASC"
);

// ===============================
// CẬP NHẬT
// ===============================

if (isset($_POST['update'])) {

    $name = mysqli_real_escape_string(
        $conn,
        trim($_POST['name'])
    );

    $description = mysqli_real_escape_string(
        $conn,
        trim($_POST['description'])
    );

    $price = (float)$_POST['price'];
    $quantity = (int)$_POST['quantity'];
    $category_id = (int)$_POST['category_id'];

    if ($quantity < 0) {
        $quantity = 0;
    }

    // Có ảnh mới
    if (
        isset($_FILES['image']) &&
        $_FILES['image']['name'] != ''
    ) {

        $image = basename(
            $_FILES['image']['name']
        );

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../assets/img/products/" . $image
        );

        $sql = "
            UPDATE products SET
                name = '$name',
                description = '$description',
                price = $price,
                quantity = $quantity,
                category_id = $category_id,
                image = '$image'
            WHERE id = $id
        ";

    } else {

        // Không đổi ảnh
        $sql = "
            UPDATE products SET
                name = '$name',
                description = '$description',
                price = $price,
                quantity = $quantity,
                category_id = $category_id
            WHERE id = $id
        ";
    }

    mysqli_query($conn, $sql);

    header("Location: products.php");
    exit;
}

?>

<div class="container mt-5 mb-5">

    <h2 class="mb-4">
        ✏️ Sửa sản phẩm
    </h2>

    <div class="card">

        <div class="card-body">

            <form
                method="POST"
                enctype="multipart/form-data"
            >

                <!-- TÊN -->

                <div class="mb-3">

                    <label class="form-label">
                        Tên sản phẩm
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="<?php echo htmlspecialchars($product['name']); ?>"
                        required
                    >

                </div>

                <!-- GIÁ + SỐ LƯỢNG -->

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Giá
                        </label>

                        <input
                            type="number"
                            name="price"
                            class="form-control"
                            min="0"
                            value="<?php echo $product['price']; ?>"
                            required
                        >

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Số lượng
                        </label>

                        <input
                            type="number"
                            name="quantity"
                            class="form-control"
                            min="0"
                            value="<?php echo $product['quantity']; ?>"
                            required
                        >

                    </div>

                </div>

                <!-- DANH MỤC -->

                <div class="mb-3">

                    <label class="form-label">
                        Danh mục
                    </label>

                    <select
                        name="category_id"
                        class="form-select"
                        required
                    >

                        <?php while (
                            $category =
                            mysqli_fetch_assoc($categories)
                        ) { ?>

                            <option
                                value="<?php echo $category['id']; ?>"
                                <?php
                                if (
                                    $category['id'] ==
                                    $product['category_id']
                                ) {
                                    echo "selected";
                                }
                                ?>
                            >

                                <?php
                                echo htmlspecialchars(
                                    $category['name']
                                );
                                ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>

                <!-- MÔ TẢ -->

                <div class="mb-3">

                    <label class="form-label">
                        Mô tả
                    </label>

                    <textarea
                        name="description"
                        class="form-control"
                        rows="4"
                    ><?php
                    echo htmlspecialchars(
                        $product['description']
                    );
                    ?></textarea>

                </div>

                <!-- ẢNH HIỆN TẠI -->

                <div class="mb-3">

                    <label class="form-label">
                        Hình ảnh hiện tại
                    </label>

                    <br>

                    <?php if (
                        !empty($product['image'])
                    ) { ?>

                        <img
                            src="../assets/img/products/<?php echo htmlspecialchars($product['image']); ?>"
                            alt="<?php echo htmlspecialchars($product['name']); ?>"
                            style="
                                width:120px;
                                height:120px;
                                object-fit:cover;
                                border-radius:10px;
                                margin-bottom:10px;
                            "
                        >

                    <?php } else { ?>

                        <p>
                            Chưa có hình ảnh.
                        </p>

                    <?php } ?>

                </div>

                <!-- ẢNH MỚI -->

                <div class="mb-4">

                    <label class="form-label">
                        Chọn hình ảnh mới
                    </label>

                    <input
                        type="file"
                        name="image"
                        class="form-control"
                    >

                    <small class="text-muted">
                        Không chọn ảnh nếu muốn giữ ảnh hiện tại.
                    </small>

                </div>

                <!-- NÚT -->

                <button
                    type="submit"
                    name="update"
                    class="btn btn-primary"
                >
                    💾 Lưu thay đổi
                </button>

                <a
                    href="products.php"
                    class="btn btn-secondary"
                >
                    ← Quay lại
                </a>

            </form>

        </div>

    </div>

</div>

<footer class="text-center mt-5 mb-4">
    © 2026 Food And Drink - Admin
</footer>

</body>
</html>