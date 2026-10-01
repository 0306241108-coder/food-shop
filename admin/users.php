<?php
ob_start();

require_once "header_admin.php";
require_once "../config/database.php";


/* =========================
   TÌM KIẾM
========================= */

$keyword = "";

if (isset($_GET['keyword'])) {
    $keyword = trim($_GET['keyword']);
}


/* =========================
   LẤY DANH SÁCH TÀI KHOẢN
========================= */

if ($keyword != "") {

    $keyword_safe = mysqli_real_escape_string(
        $conn,
        $keyword
    );

    $sql = "
        SELECT
            id,
            fullname,
            email,
            role,
            created_at
        FROM users
        WHERE fullname LIKE '%$keyword_safe%'
        OR email LIKE '%$keyword_safe%'
        ORDER BY id DESC
    ";

} else {

    $sql = "
        SELECT
            id,
            fullname,
            email,
            role,
            created_at
        FROM users
        ORDER BY id DESC
    ";
}


$result = mysqli_query($conn, $sql);

?>

<div class="container mt-5 mb-5">


    <!-- =========================
         TIÊU ĐỀ
    ========================= -->

    <h2 class="mb-4">
        👥 Quản lý tài khoản
    </h2>


    <!-- =========================
         TÌM KIẾM
    ========================= -->

    <form
        method="GET"
        class="row mb-4"
    >

        <div class="col-md-10">

            <input
                type="text"
                name="keyword"
                class="form-control"
                placeholder="Tìm theo họ tên hoặc email..."
                value="<?php echo htmlspecialchars($keyword); ?>"
            >

        </div>


        <div class="col-md-2">

            <button
                type="submit"
                class="btn btn-primary w-100"
            >
                🔍 Tìm kiếm
            </button>

        </div>

    </form>


    <!-- =========================
         BẢNG TÀI KHOẢN
    ========================= -->

    <div class="card">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table
                    class="table table-bordered table-hover align-middle mb-0"
                >

                    <thead class="table-dark">

                        <tr>

                            <th class="text-center">
                                ID
                            </th>

                            <th>
                                Họ tên
                            </th>

                            <th>
                                Email
                            </th>

                            <th class="text-center">
                                Vai trò
                            </th>

                            <th>
                                Ngày đăng ký
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
                            $user =
                            mysqli_fetch_assoc($result)
                        ) {

                    ?>

                        <tr>


                            <!-- ID -->

                            <td class="text-center">

                                <?php
                                echo $user['id'];
                                ?>

                            </td>


                            <!-- HỌ TÊN -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $user['fullname']
                                );

                                ?>

                            </td>


                            <!-- EMAIL -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $user['email']
                                );

                                ?>

                            </td>


                            <!-- VAI TRÒ -->

                            <td class="text-center">

                                <?php

                                if (
                                    $user['role'] == 'admin'
                                ) {

                                    echo '
                                    <span class="badge bg-danger">
                                        👑 Admin
                                    </span>';

                                } else {

                                    echo '
                                    <span class="badge bg-primary">
                                        👤 Người dùng
                                    </span>';

                                }

                                ?>

                            </td>


                            <!-- NGÀY ĐĂNG KÝ -->

                            <td>

                                <?php

                                echo date(
                                    "d/m/Y H:i",
                                    strtotime(
                                        $user['created_at']
                                    )
                                );

                                ?>

                            </td>


                            <!-- THAO TÁC -->

                            <td class="text-center">

                                <a
                                    href="user_detail.php?id=<?php echo $user['id']; ?>"
                                    class="btn btn-info btn-sm"
                                >
                                    👁️ Xem
                                </a>


                                <a
                                    href="user_edit.php?id=<?php echo $user['id']; ?>"
                                    class="btn btn-warning btn-sm"
                                >
                                    ✏️ Sửa
                                </a>


                                <?php

                                /* Không cho Admin tự xóa mình */

                                if (
                                    $user['id']
                                    !=
                                    $_SESSION['admin_id']
                                ) {

                                ?>

                                    <a
                                        href="user_delete.php?id=<?php echo $user['id']; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="
                                            return confirm(
                                                'Bạn có chắc muốn xóa tài khoản này không?'
                                            )
                                        "
                                    >
                                        🗑️ Xóa
                                    </a>

                                <?php } ?>

                            </td>

                        </tr>

                    <?php

                        }

                    } else {

                    ?>

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-4"
                            >

                                📭 Không tìm thấy tài khoản nào.

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