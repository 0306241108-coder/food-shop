<?php
ob_start();

require_once "header_admin.php";
require_once "../config/database.php";

/* =========================
   THÊM DANH MỤC
========================= */
if (isset($_POST['add'])) {

    $name = trim($_POST['name']);

    if ($name == "") {
        $error = "Vui lòng nhập tên danh mục!";
    } else {

        $sql = "INSERT INTO categories (name) VALUES (?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $name);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: categories.php?success=add");
            exit;
        } else {
            $error = "Không thể thêm danh mục!";
        }
    }
}


/* =========================
   XÓA DANH MỤC
========================= */
if (isset($_GET['delete'])) {

    $id = (int)$_GET['delete'];

    // Kiểm tra danh mục có sản phẩm hay chưa
    $check = mysqli_prepare(
        $conn,
        "SELECT COUNT(*) FROM products WHERE category_id = ?"
    );

    mysqli_stmt_bind_param($check, "i", $id);
    mysqli_stmt_execute($check);
    mysqli_stmt_bind_result($check, $count);
    mysqli_stmt_fetch($check);
    mysqli_stmt_close($check);

    if ($count > 0) {
        $error = "Không thể xóa! Danh mục này đang có sản phẩm.";
    } else {

        $stmt = mysqli_prepare(
            $conn,
            "DELETE FROM categories WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: categories.php?success=delete");
            exit;
        } else {
            $error = "Không thể xóa danh mục!";
        }
    }
}


/* =========================
   CẬP NHẬT DANH MỤC
========================= */
if (isset($_POST['update'])) {

    $id = (int)$_POST['id'];
    $name = trim($_POST['name']);

    if ($name == "") {
        $error = "Vui lòng nhập tên danh mục!";
    } else {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE categories SET name = ? WHERE id = ?"
        );

        mysqli_stmt_bind_param($stmt, "si", $name, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: categories.php?success=update");
            exit;
        } else {
            $error = "Không thể cập nhật danh mục!";
        }
    }
}


/* =========================
   LẤY DANH MỤC ĐANG SỬA
========================= */
$edit_category = null;

if (isset($_GET['edit'])) {

    $id = (int)$_GET['edit'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM categories WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $edit_category = mysqli_fetch_assoc($result);
}


/* =========================
   LẤY DANH SÁCH DANH MỤC
========================= */
$result = mysqli_query(
    $conn,
    "SELECT * FROM categories ORDER BY id DESC"
);

?>

<div class="container mt-4">

    <h2 class="mb-4">🗂️ Quản lý danh mục</h2>


    <!-- THÔNG BÁO -->
    <?php if (isset($_GET['success'])): ?>

        <?php if ($_GET['success'] == 'add'): ?>
            <div class="alert alert-success">
                Thêm danh mục thành công!
            </div>

        <?php elseif ($_GET['success'] == 'update'): ?>
            <div class="alert alert-success">
                Cập nhật danh mục thành công!
            </div>

        <?php elseif ($_GET['success'] == 'delete'): ?>
            <div class="alert alert-success">
                Xóa danh mục thành công!
            </div>
        <?php endif; ?>

    <?php endif; ?>


    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?= $error ?>
        </div>
    <?php endif; ?>


    <!-- FORM THÊM / SỬA -->
    <div class="card mb-4">

        <div class="card-header">
            <?php if ($edit_category): ?>
                ✏️ Sửa danh mục
            <?php else: ?>
                ➕ Thêm danh mục
            <?php endif; ?>
        </div>

        <div class="card-body">

            <?php if ($edit_category): ?>

                <form method="POST">

                    <input
                        type="hidden"
                        name="id"
                        value="<?= $edit_category['id'] ?>"
                    >

                    <div class="mb-3">

                        <label class="form-label">
                            Tên danh mục
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="<?= htmlspecialchars($edit_category['name']) ?>"
                            required
                        >

                    </div>

                    <button
                        type="submit"
                        name="update"
                        class="btn btn-primary"
                    >
                        💾 Cập nhật
                    </button>

                    <a
                        href="categories.php"
                        class="btn btn-secondary"
                    >
                        Hủy
                    </a>

                </form>

            <?php else: ?>

                <form method="POST">

                    <div class="mb-3">

                        <label class="form-label">
                            Tên danh mục
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Ví dụ: Coffee"
                            required
                        >

                    </div>

                    <button
                        type="submit"
                        name="add"
                        class="btn btn-success"
                    >
                        ➕ Thêm danh mục
                    </button>

                </form>

            <?php endif; ?>

        </div>

    </div>


    <!-- DANH SÁCH DANH MỤC -->
    <div class="card">

        <div class="card-header">
            📋 Danh sách danh mục
        </div>

        <div class="card-body p-0">

            <table class="table table-bordered table-hover mb-0">

                <thead class="table-dark">

                    <tr>
                        <th width="100">ID</th>
                        <th>Tên danh mục</th>
                        <th width="220">Thao tác</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($result) > 0): ?>

                        <?php while ($category = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <td>
                                    <?= $category['id'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($category['name']) ?>
                                </td>

                                <td>

                                    <a
                                        href="categories.php?edit=<?= $category['id'] ?>"
                                        class="btn btn-warning btn-sm"
                                    >
                                        ✏️ Sửa
                                    </a>

                                    <a
                                        href="categories.php?delete=<?= $category['id'] ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn xóa danh mục này không?')"
                                    >
                                        🗑️ Xóa
                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td
                                colspan="3"
                                class="text-center py-4"
                            >
                                Chưa có danh mục nào.
                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>


<?php
ob_end_flush();
?>