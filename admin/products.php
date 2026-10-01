<?php
require_once "header_admin.php";
require_once "../config/database.php";

// ===============================
// XÓA SẢN PHẨM
// ===============================
if (isset($_GET['delete'])) {

    $id = (int)$_GET['delete'];

    mysqli_query(
        $conn,
        "DELETE FROM products WHERE id = $id"
    );

    header("Location: products.php");
    exit;
}

// ===============================
// THÊM SẢN PHẨM
// ===============================
if (isset($_POST['add'])) {

    $name = mysqli_real_escape_string(
        $conn,
        $_POST['name']
    );

    $description = mysqli_real_escape_string(
        $conn,
        $_POST['description']
    );

    $price = (float)$_POST['price'];
    $quantity = (int)$_POST['quantity'];
    $category_id = (int)$_POST['category_id'];

    $image = "";

    // Upload ảnh
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
    }

    mysqli_query(
        $conn,
        "INSERT INTO products
        (name, description, price, quantity, category_id, image)
        VALUES
        ('$name', '$description', $price, $quantity, $category_id, '$image')"
    );

    header("Location: products.php");
    exit;
}

// ===============================
// TÌM KIẾM
// ===============================
$search = isset($_GET['search'])
    ? trim($_GET['search'])
    : "";

$sql = "
    SELECT
        p.*,
        c.name AS category_name
    FROM products p
    LEFT JOIN categories c
        ON p.category_id = c.id
    WHERE 1
";

if ($search != "") {

    $search = mysqli_real_escape_string(
        $conn,
        $search
    );

    $sql .= "
        AND p.name LIKE '%$search%'
    ";
}

$sql .= "
    ORDER BY p.id DESC
";

$result = mysqli_query($conn, $sql);

// Lấy danh mục
$categories = mysqli_query(
    $conn,
    "SELECT * FROM categories ORDER BY id ASC"
);
?>

<div class="container mt-5 mb-5">

    <!-- TIÊU ĐỀ -->
    <div class="d-flex justify-content-between mb-4">

        <h2>
            📦 Quản lý sản phẩm
        </h2>

        <button
            class="btn btn-success"
            data-bs-toggle="collapse"
            data-bs-target="#addProduct"
        >
            ➕ Thêm sản phẩm
        </button>

    </div>

    <!-- ===============================
         THÊM SẢN PHẨM
    ================================ -->

    <div
        id="addProduct"
        class="collapse mb-4"
    >

        <div class="card">

            <div class="card-header bg-dark text-white">
                ➕ Thêm sản phẩm mới
            </div>

            <div class="card-body">

                <form
                    method="POST"
                    enctype="multipart/form-data"
                >

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Tên sản phẩm
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                required
                            >

                        </div>

                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Giá
                            </label>

                            <input
                                type="number"
                                name="price"
                                class="form-control"
                                min="0"
                                required
                            >

                        </div>

                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Số lượng
                            </label>

                            <input
                                type="number"
                                name="quantity"
                                class="form-control"
                                min="0"
                                required
                            >

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Danh mục
                            </label>

                            <select
                                name="category_id"
                                class="form-select"
                                required
                            >

                                <option value="">
                                    -- Chọn danh mục --
                                </option>

                                <?php while (
                                    $category =
                                    mysqli_fetch_assoc($categories)
                                ) { ?>

                                    <option
                                        value="<?php echo $category['id']; ?>"
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

                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Hình ảnh
                            </label>

                            <input
                                type="file"
                                name="image"
                                class="form-control"
                            >

                        </div>

                        <div class="col-12 mb-3">

                            <label class="form-label">
                                Mô tả
                            </label>

                            <textarea
                                name="description"
                                class="form-control"
                                rows="3"
                            ></textarea>

                        </div>

                    </div>

                    <button
                        type="submit"
                        name="add"
                        class="btn btn-success"
                    >
                        💾 Thêm sản phẩm
                    </button>

                </form>

            </div>

        </div>

    </div>

    <!-- ===============================
         TÌM KIẾM
    ================================ -->

    <form
        method="GET"
        class="mb-4"
    >

        <div class="input-group">

            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="🔍 Tìm sản phẩm..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button
                class="btn btn-primary"
            >
                Tìm
            </button>

        </div>

    </form>

    <!-- ===============================
         DANH SÁCH SẢN PHẨM
    ================================ -->

    <div class="table-responsive">

        <table
            class="table table-bordered table-hover align-middle"
        >

            <thead class="table-dark">

                <tr>
                    <th>ID</th>
                    <th>Ảnh</th>
                    <th>Tên</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Thao tác</th>
                </tr>

            </thead>

            <tbody>

            <?php if (
                $result &&
                mysqli_num_rows($result) > 0
            ) { ?>

                <?php while (
                    $product =
                    mysqli_fetch_assoc($result)
                ) { ?>

                    <tr>

                        <td>
                            <?php echo $product['id']; ?>
                        </td>

                        <td>

                            <?php if (
                                !empty($product['image'])
                            ) { ?>

                                <img
                                    src="../assets/img/products/<?php echo htmlspecialchars($product['image']); ?>"
                                    alt="<?php echo htmlspecialchars($product['name']); ?>"
                                    style="
                                        width:60px;
                                        height:60px;
                                        object-fit:cover;
                                        border-radius:8px;
                                    "
                                >

                            <?php } else { ?>

                                Không có ảnh

                            <?php } ?>

                        </td>

                        <td>
                            <strong>
                                <?php
                                echo htmlspecialchars(
                                    $product['name']
                                );
                                ?>
                            </strong>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $product['category_name']
                                ?? 'Chưa có'
                            );
                            ?>
                        </td>

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

                        <td>
                            <?php echo $product['quantity']; ?>
                        </td>

                        <td>

                            <a
                                href="product_edit.php?id=<?php echo $product['id']; ?>"
                                class="btn btn-warning btn-sm"
                            >
                                ✏️ Sửa
                            </a>

                            <a
                                href="products.php?delete=<?php echo $product['id']; ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này không?')"
                            >
                                🗑️ Xóa
                            </a>

                        </td>

                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>

                    <td
                        colspan="7"
                        class="text-center"
                    >
                        📦 Không có sản phẩm.
                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<footer class="text-center mt-5 mb-4">
    © 2026 Food And Drink - Admin
</footer>

</body>
</html>